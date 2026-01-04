<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged'])) {
    header('Location: ../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Classes\Reservation;
use App\Classes\Avis;

$userId = $_SESSION['logged']->id_user;
$userName = $_SESSION['logged']->nom ?? 'User';

// Get all reservations for the logged-in user
$allReservations = Reservation::checkReservation($userId);

// Calculate stats
$activeCount = 0;
$upcomingCount = 0;
$completedCount = 0;
$today = new DateTime();

$activeRentals = [];
$upcomingRentals = [];
$pastRentals = [];

foreach ($allReservations as $reservation) {
    $startDate = new DateTime($reservation->startDate);
    $endDate = new DateTime($reservation->endDate);
    $status = strtoupper($reservation->status ?? 'PENDING');

    // Active: Currently ongoing (today is between start and end date)
    if ($today >= $startDate && $today <= $endDate && in_array($status, ['ACCEPTED', 'PENDING'])) {
        $activeCount++;
        $activeRentals[] = $reservation;
    }
    // Upcoming: Future reservations
    elseif ($today < $startDate && in_array($status, ['ACCEPTED', 'PENDING'])) {
        $upcomingCount++;
        $upcomingRentals[] = $reservation;
    }
    // Completed: Past rentals or completed status
    elseif ($today > $endDate || $status === 'COMPLETED') {
        $completedCount++;
        $pastRentals[] = $reservation;
    }
}

// Get user's reviews
$userReviews = [];
foreach ($allReservations as $reservation) {
    if ($reservation->id_avis) {
        $userReviews[] = $reservation;
    }
}

// Helper function to format dates
function formatDateRange($start, $end)
{
    $startDate = new DateTime($start);
    $endDate = new DateTime($end);
    return $startDate->format('M d') . ' - ' . $endDate->format('M d, Y');
}

// Helper function to get status badge
function getStatusBadge($status)
{
    $status = strtoupper($status ?? 'PENDING');
    $badges = [
        'ACCEPTED' => '<div class="absolute top-3 left-3 bg-primary text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">Active Now</div>',
        'PENDING' => '<div class="absolute top-3 left-3 bg-blue-500/90 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm backdrop-blur-sm">Pending</div>',
        'COMPLETED' => '<div class="absolute top-3 left-3 bg-green-500/90 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm backdrop-blur-sm">Completed</div>',
        'REJECTED' => '<div class="absolute top-3 left-3 bg-red-500/90 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm backdrop-blur-sm">Cancelled</div>',
    ];
    return $badges[$status] ?? $badges['PENDING'];
}
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>My Rentals &amp; Reviews - MaBagnole</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#277bf1",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101722",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .icon-filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="relative flex h-auto min-h-screen w-full flex-col bg-background-light dark:bg-background-dark font-display text-[#0d131c] dark:text-white overflow-x-hidden">
    <!-- Top Navigation -->
    <header class="bg-white dark:bg-[#1a2332] border-b border-[#e7ecf3] dark:border-[#2a3441] sticky top-0 z-50">
        <div class="px-4 md:px-10 py-3 flex items-center justify-between whitespace-nowrap">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <a class="flex items-center gap-4 text-[#0d131c] dark:text-white group" href="cars.php">
                    <h2 class="text-lg font-bold leading-tight tracking-[-0.015em]">MaBagnole</h2>
                </a>
                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium transition-colors" href="cars.php">Fleet</a>
                    <a class="text-primary text-sm font-bold" href="dashboard.php">My Bookings</a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex flex-col items-end">
                        <span class="text-sm font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($_SESSION['logged']->username ?? 'User') ?></span>
                        <span class="text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($_SESSION['logged']->email ?? '') ?></span>
                    </div>
                    <div class="size-10 rounded-full bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center text-white font-bold">
                        <?= strtoupper(substr($_SESSION['logged']->username ?? 'U', 0, 1)) ?>
                    </div>
                </div>
                <a href="../auth/login.php" class="flex items-center gap-2 px-4 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span class="hidden sm:inline text-sm font-medium">Logout</span>
                </a>
            </div>
        </div>
    </header>
    <main class="flex-1 w-full max-w-[1200px] mx-auto px-4 md:px-10 py-8">
        <!-- Page Heading -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em] text-[#0d131c] dark:text-white">Welcome back, <?= htmlspecialchars($userName) ?>!</h1>
                <p class="text-[#4b6c9b] dark:text-[#94a3b8] text-base">Manage your current reservations and review past trips.</p>
            </div>
            <a href="cars.php" class="hidden md:flex items-center gap-2 bg-primary hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg font-bold text-sm transition-colors shadow-lg shadow-blue-500/20">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Book New Rental
            </a>
        </div>
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
            <div class="flex flex-col gap-3 rounded-xl p-6 bg-white dark:bg-[#1a2332] border border-[#e7ecf3] dark:border-[#2a3441] shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[#4b6c9b] dark:text-[#94a3b8] text-sm font-bold uppercase tracking-wider">Active</p>
                    <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-lg">key</span>
                </div>
                <p class="text-[#0d131c] dark:text-white text-3xl font-bold"><?= $activeCount ?></p>
                <p class="text-xs text-[#4b6c9b] dark:text-[#94a3b8]">Current rental<?= $activeCount !== 1 ? 's' : '' ?> in progress</p>
            </div>
            <div class="flex flex-col gap-3 rounded-xl p-6 bg-white dark:bg-[#1a2332] border border-[#e7ecf3] dark:border-[#2a3441] shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[#4b6c9b] dark:text-[#94a3b8] text-sm font-bold uppercase tracking-wider">Upcoming</p>
                    <span class="material-symbols-outlined text-blue-400 bg-blue-400/10 p-2 rounded-lg">calendar_month</span>
                </div>
                <p class="text-[#0d131c] dark:text-white text-3xl font-bold"><?= $upcomingCount ?></p>
                <p class="text-xs text-[#4b6c9b] dark:text-[#94a3b8]">Scheduled reservation<?= $upcomingCount !== 1 ? 's' : '' ?></p>
            </div>
            <div class="flex flex-col gap-3 rounded-xl p-6 bg-white dark:bg-[#1a2332] border border-[#e7ecf3] dark:border-[#2a3441] shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[#4b6c9b] dark:text-[#94a3b8] text-sm font-bold uppercase tracking-wider">Completed</p>
                    <span class="material-symbols-outlined text-green-500 bg-green-500/10 p-2 rounded-lg">check_circle</span>
                </div>
                <p class="text-[#0d131c] dark:text-white text-3xl font-bold"><?= $completedCount ?></p>
                <p class="text-xs text-[#4b6c9b] dark:text-[#94a3b8]">Total lifetime rentals</p>
            </div>
        </div>
        <!-- Tabs -->
        <div class="mb-8 border-b border-[#e7ecf3] dark:border-[#2a3441]">
            <div class="flex gap-8">
                <a class="flex items-center gap-2 border-b-[3px] border-primary text-[#0d131c] dark:text-white pb-3 px-1" href="#rentals">
                    <span class="material-symbols-outlined text-[20px] icon-filled">directions_car</span>
                    <span class="text-sm font-bold tracking-[0.015em]">My Rentals</span>
                </a>
                <a class="flex items-center gap-2 border-b-[3px] border-transparent text-[#4b6c9b] hover:text-[#0d131c] dark:hover:text-white transition-colors pb-3 px-1" href="#reviews">
                    <span class="material-symbols-outlined text-[20px]">rate_review</span>
                    <span class="text-sm font-bold tracking-[0.015em]">My Reviews</span>
                </a>
            </div>
        </div>
        <!-- Content Section: Rentals -->
        <section class="flex flex-col gap-6" id="rentals">
            <h2 class="text-xl font-bold text-[#0d131c] dark:text-white mb-2">Current &amp; Upcoming</h2>

            <?php
            $currentAndUpcoming = array_merge($activeRentals, $upcomingRentals);

            if (empty($currentAndUpcoming)): ?>
                <div class="text-center py-12 bg-slate-50 dark:bg-[#1a2332] rounded-xl border-2 border-dashed border-[#cfd9e8] dark:border-[#2a3441]">
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-5xl mb-4">directions_car</span>
                    <h4 class="font-bold text-[#0d131c] dark:text-white mb-2">No active rentals</h4>
                    <p class="text-sm text-[#4b6c9b] dark:text-[#94a3b8] mb-4">Ready to hit the road? Browse our fleet!</p>
                    <a href="cars.php" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-blue-600 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        Book New Rental
                    </a>
                </div>
                <?php else:
                foreach ($currentAndUpcoming as $rental):
                    $isActive = in_array($rental, $activeRentals);
                    $borderClass = $isActive ? 'border-primary/30' : 'border-[#e7ecf3] dark:border-[#2a3441]';
                ?>
                    <!-- Rental Card -->
                    <div class="group bg-white dark:bg-[#1a2332] rounded-xl border <?= $borderClass ?> overflow-hidden shadow-md hover:shadow-lg transition-all flex flex-col md:flex-row">
                        <div class="relative md:w-[320px] h-48 md:h-auto shrink-0 bg-gray-100 dark:bg-gray-800">
                            <img alt="<?= htmlspecialchars($rental->marque . ' ' . $rental->model) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= htmlspecialchars($rental->image ?? 'https://via.placeholder.com/320x180?text=Car+Image') ?>" />
                            <?= getStatusBadge($rental->status) ?>
                        </div>
                        <div class="flex-1 p-5 md:p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="text-xl font-bold text-[#0d131c] dark:text-white"><?= htmlspecialchars($rental->marque . ' ' . $rental->model) ?></h3>
                                        <p class="text-sm text-[#4b6c9b] dark:text-[#94a3b8]"><?= htmlspecialchars($rental->category_name ?? 'Vehicle') ?></p>
                                    </div>
                                    <span class="text-lg font-bold text-[#0d131c] dark:text-white">$<?= number_format($rental->prix, 2) ?><span class="text-sm font-normal text-[#4b6c9b]">/day</span></span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6 mt-4 text-sm text-[#0d131c] dark:text-white">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[#4b6c9b] text-[20px]">calendar_month</span>
                                        <span><?= formatDateRange($rental->startDate, $rental->endDate) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[#4b6c9b] text-[20px]">location_on</span>
                                        <span><?= htmlspecialchars($rental->pickupLocation ?? 'Not specified') ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[#4b6c9b] text-[20px]">confirmation_number</span>
                                        <span class="font-mono">#RES-<?= str_pad($rental->id_reservation, 5, '0', STR_PAD_LEFT) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[#4b6c9b] text-[20px]">info</span>
                                        <span class="font-medium <?= strtoupper($rental->status) === 'ACCEPTED' ? 'text-green-600' : 'text-orange-600' ?>">
                                            <?= ucfirst(strtolower($rental->status ?? 'Pending')) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-[#e7ecf3] dark:border-[#2a3441]">
                                <a href="info.php?id=<?= $rental->id_car ?>" class="ml-auto flex items-center gap-1 text-primary text-sm font-bold hover:underline">
                                    View Details <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            endif;
            ?>

            <div class="h-8"></div>
            <h2 class="text-xl font-bold text-[#0d131c] dark:text-white mb-2 flex items-center justify-between">
                Past Rentals
                <?php if (count($pastRentals) > 3): ?>
                    <span class="text-sm text-primary font-bold hover:underline cursor-pointer">View All History</span>
                <?php endif; ?>
            </h2>

            <?php
            if (empty($pastRentals)): ?>
                <div class="text-center py-8 bg-slate-50 dark:bg-[#1a2332] rounded-xl border border-[#e7ecf3] dark:border-[#2a3441]">
                    <p class="text-sm text-[#4b6c9b] dark:text-[#94a3b8]">No past rentals yet</p>
                </div>
                <?php else:
                // Show only first 3 past rentals
                $displayPastRentals = array_slice($pastRentals, 0, 3);
                foreach ($displayPastRentals as $rental):
                    $hasReview = !empty($rental->id_avis);
                ?>
                    <!-- Rental Card: Completed -->
                    <div class="group bg-white dark:bg-[#1a2332] rounded-xl border border-[#e7ecf3] dark:border-[#2a3441] overflow-hidden hover:shadow-md transition-all flex flex-col md:flex-row">
                        <div class="relative md:w-[240px] h-40 md:h-auto shrink-0 bg-gray-100 dark:bg-gray-800">
                            <img alt="<?= htmlspecialchars($rental->marque . ' ' . $rental->model) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 <?= $hasReview ? '' : 'grayscale group-hover:grayscale-0' ?>" src="<?= htmlspecialchars($rental->image ?? 'https://via.placeholder.com/240x140?text=Car') ?>" />
                            <div class="absolute top-3 left-3 bg-green-500/90 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm backdrop-blur-sm">
                                Completed
                            </div>
                        </div>
                        <div class="flex-1 p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-1">
                                    <h3 class="text-lg font-bold text-[#0d131c] dark:text-white"><?= htmlspecialchars($rental->marque . ' ' . $rental->model) ?></h3>
                                    <span class="text-base font-bold text-[#0d131c] dark:text-white">$<?= number_format($rental->prix, 2) ?><span class="text-sm font-normal text-[#4b6c9b]">/day</span></span>
                                </div>
                                <p class="text-xs text-[#4b6c9b] dark:text-[#94a3b8] mb-3"><?= htmlspecialchars($rental->category_name ?? 'Vehicle') ?></p>
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-[#0d131c] dark:text-white">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[#4b6c9b] text-[18px]">calendar_month</span>
                                        <span><?= formatDateRange($rental->startDate, $rental->endDate) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[#4b6c9b] text-[18px]">location_on</span>
                                        <span><?= htmlspecialchars($rental->pickupLocation ?? 'Not specified') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-[#e7ecf3] dark:border-[#2a3441]">
                                <span class="text-sm text-green-600 font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span> Rental Returned
                                </span>
                                <?php if (!$hasReview): ?>
                                    <a href="info.php?id=<?= $rental->id_car ?>" class="flex items-center justify-center gap-2 px-4 py-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white text-sm font-bold rounded-lg transition-all">
                                        <span class="material-symbols-outlined text-[18px]">rate_review</span>
                                        Write Review
                                    </a>
                                <?php else: ?>
                                    <span class="text-sm text-[#4b6c9b] flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px] text-yellow-500">star</span>
                                        Reviewed
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
            <?php
                endforeach;
            endif;
            ?>
        </section>
        <!-- Spacer for Section Break -->
        <div class="my-12 border-t border-[#e7ecf3] dark:border-[#2a3441]"></div>
        <!-- Content Section: Reviews -->
        <section class="flex flex-col gap-6" id="reviews">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-[#0d131c] dark:text-white">My Reviews (<?= count($userReviews) ?>)</h2>
            </div>

            <?php if (empty($userReviews)): ?>
                <div class="border-2 border-dashed border-[#cfd9e8] dark:border-[#2a3441] rounded-xl p-8 flex flex-col items-center justify-center text-center gap-4 bg-[#fcfdfd] dark:bg-[#151c27]">
                    <div class="size-16 rounded-full bg-[#e7ecf3] dark:bg-[#2a3441] flex items-center justify-center text-[#4b6c9b]">
                        <span class="material-symbols-outlined text-4xl">rate_review</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-[#0d131c] dark:text-white text-lg mb-2">No reviews yet</h4>
                        <p class="text-sm text-[#4b6c9b] dark:text-[#94a3b8]">Complete a rental and share your experience!</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $displayedReviews = 0;
                    foreach ($userReviews as $review):
                        if ($displayedReviews >= 6) break; // Show max 6 reviews
                        $displayedReviews++;

                        $reviewDate = new DateTime($review->review_date);
                    ?>
                        <!-- Review Card -->
                        <div class="bg-white dark:bg-[#1a2332] rounded-xl border border-[#e7ecf3] dark:border-[#2a3441] p-6 shadow-sm flex flex-col h-full">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex gap-3">
                                    <div class="size-12 rounded-lg bg-gray-100 dark:bg-gray-800 overflow-hidden shrink-0">
                                        <img alt="<?= htmlspecialchars($review->marque . ' ' . $review->model) ?>" class="w-full h-full object-cover" src="<?= htmlspecialchars($review->image ?? 'https://via.placeholder.com/48?text=Car') ?>" />
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-[#0d131c] dark:text-white"><?= htmlspecialchars($review->marque . ' ' . $review->model) ?></h4>
                                        <p class="text-xs text-[#4b6c9b]">Rented <?= $reviewDate->format('M d, Y') ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 mb-3 text-amber-400">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $review->note): ?>
                                        <span class="material-symbols-outlined text-[20px] icon-filled">star</span>
                                    <?php else: ?>
                                        <span class="material-symbols-outlined text-[20px]">star</span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="ml-2 text-sm font-bold text-[#0d131c] dark:text-white"><?= number_format($review->note, 1) ?></span>
                            </div>
                            <p class="text-sm text-[#0d131c] dark:text-[#cbd5e1] leading-relaxed mb-4 flex-1">
                                "<?= htmlspecialchars($review->review_text ?? 'No review text') ?>"
                            </p>
                            <div class="pt-4 border-t border-[#e7ecf3] dark:border-[#2a3441] flex items-center justify-between">
                                <span class="text-xs text-[#4b6c9b] dark:text-[#94a3b8]">Posted on <?= $reviewDate->format('M d, Y') ?></span>
                                <a href="info.php?id=<?= $review->id_car ?>" class="text-primary text-xs font-bold hover:underline">View Car</a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (count($pastRentals) > count($userReviews)):
                        $unreviewedCount = count($pastRentals) - count($userReviews);
                    ?>
                        <!-- Callout to Write More Reviews -->
                        <div class="border-2 border-dashed border-[#cfd9e8] dark:border-[#2a3441] rounded-xl p-6 flex flex-col items-center justify-center text-center gap-4 bg-[#fcfdfd] dark:bg-[#151c27]">
                            <div class="size-12 rounded-full bg-[#e7ecf3] dark:bg-[#2a3441] flex items-center justify-center text-[#4b6c9b]">
                                <span class="material-symbols-outlined">rate_review</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#0d131c] dark:text-white">Review your recent trip</h4>
                                <p class="text-sm text-[#4b6c9b] dark:text-[#94a3b8] mt-1">You have <?= $unreviewedCount ?> completed rental<?= $unreviewedCount !== 1 ? 's' : '' ?> waiting for feedback.</p>
                            </div>
                            <a href="#rentals" class="px-5 py-2 bg-white dark:bg-[#1a2332] border border-[#cfd9e8] dark:border-[#2a3441] text-[#0d131c] dark:text-white text-sm font-bold rounded-lg shadow-sm hover:shadow-md transition-all">
                                Write a Review
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>