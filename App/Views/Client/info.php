<?php
session_start();
$logged = $_SESSION['logged'];
if (empty($logged)) {
    header('Location: ./../auth/login.php');
    exit();
}
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Classes\Avis;
use App\Classes\Vehicule;
use App\Classes\Category;
use App\Classes\Reservation;

$carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$check = Reservation::checkReservation($logged->id_user);
$carData = [];
$car = null;

if ($carId > 0) {
    $carData = Vehicule::getCarById($carId);
    if (!empty($carData) && isset($carData[0])) {
        $car = $carData[0];
    }
}

if (!$car) {
    header('Location: cars.php');
    exit;
}

function getCategoryBadgeClass($categoryName)
{
    $colors = [
        'Luxury' => 'text-blue-600 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300',
        'SUV' => 'text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-300',
        'Economy' => 'text-yellow-600 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300',
        'Off-Road' => 'text-orange-600 bg-orange-100 dark:bg-orange-900/30 dark:text-orange-300',
        'Convertible' => 'text-purple-600 bg-purple-100 dark:bg-purple-900/30 dark:text-purple-300',
        'Sport' => 'text-red-600 bg-red-100 dark:bg-red-900/30 dark:text-red-300',
        'Sedan' => 'text-indigo-600 bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300',
    ];
    return $colors[$categoryName] ?? 'text-blue-600 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300';
}

$badgeClass = getCategoryBadgeClass($car->nom ?? 'Other');
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Vehicle Details - MaBagnole</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
        /* Custom scrollbar for better aesthetics */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white antialiased min-h-screen flex flex-col">
    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-8">
                    <a class="flex items-center gap-3 group" href="cars.php">
                        <div class="size-8 text-primary transition-transform group-hover:scale-110">
                            <svg class="w-full h-full" fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_6_319)">
                                    <path d="M8.57829 8.57829C5.52816 11.6284 3.451 15.5145 2.60947 19.7452C1.76794 23.9758 2.19984 28.361 3.85056 32.3462C5.50128 36.3314 8.29667 39.7376 11.8832 42.134C15.4698 44.5305 19.6865 45.8096 24 45.8096C28.3135 45.8096 32.5302 44.5305 36.1168 42.134C39.7033 39.7375 42.4987 36.3314 44.1494 32.3462C45.8002 28.361 46.2321 23.9758 45.3905 19.7452C44.549 15.5145 42.4718 11.6284 39.4217 8.57829L24 24L8.57829 8.57829Z" fill="currentColor"></path>
                                </g>
                                <defs>
                                    <clippath id="clip0_6_319">
                                        <rect fill="white" height="48" width="48"></rect>
                                    </clippath>
                                </defs>
                            </svg>
                        </div>
                        <h2 class="text-slate-900 dark:text-white text-xl font-bold tracking-tight">MaBagnole</h2>
                    </a>
                    <!-- Desktop Nav Links -->
                    <nav class="hidden md:flex items-center gap-8">
                        <a class="text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium transition-colors" href="cars.php">Fleet</a>
                        <a class="text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium transition-colors" href="cars.php">Locations</a>
                        <a class="text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium transition-colors" href="cars.php">Deals</a>
                        <a class="text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium transition-colors" href="cars.php">Support</a>
                    </nav>
                </div>
                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center relative">
                        <span class="material-symbols-outlined absolute left-3 text-slate-400">search</span>
                        <input class="pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-primary w-64" placeholder="Search vehicles..." type="text" />
                    </div>
                    <button class="bg-primary hover:bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                        Sign In
                    </button>
                </div>
            </div>
        </div>
    </header>
    <main class="flex-grow w-full max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex mb-6">
            <ol class="flex items-center space-x-2">
                <li><a class="text-slate-500 dark:text-slate-400 hover:text-primary text-sm font-medium" href="cars.php">Home</a></li>
                <li><span class="text-slate-400 text-sm">/</span></li>
                <li><a class="text-slate-500 dark:text-slate-400 hover:text-primary text-sm font-medium" href="cars.php">Fleet</a></li>
                <li><span class="text-slate-400 text-sm">/</span></li>
                <li><a class="text-slate-500 dark:text-slate-400 hover:text-primary text-sm font-medium" href="cars.php"><?= htmlspecialchars($car->nom ?? 'Category') ?></a></li>
                <li><span class="text-slate-400 text-sm">/</span></li>
                <li><span class="text-slate-900 dark:text-slate-100 text-sm font-medium"><?= htmlspecialchars($car->marque . ' ' . $car->model) ?></span></li>
            </ol>
        </nav>
        <!-- Main Layout Grid -->
        <div class="grid grid-cols-1 <?= !empty($check) ? '' : 'lg:grid-cols-12' ?> gap-10">
            <!-- Left Column: Content (Images, Specs, Description, Reviews) -->
            <div class="lg:col-span-8 space-y-10">
                <!-- Page Heading & Title -->
                <div class="space-y-4">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <span class="inline-block px-3 py-1 mb-3 text-xs font-bold tracking-wide uppercase rounded-full <?= $badgeClass ?>">
                                <?= htmlspecialchars($car->nom ?? 'Vehicle') ?>
                            </span>
                            <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                                <?= htmlspecialchars($car->marque . ' ' . $car->model) ?>
                            </h1>
                            <p class="mt-2 text-lg text-slate-500 dark:text-slate-400 font-medium">
                                <?= htmlspecialchars($car->nom ?? 'Vehicle') ?> • <?= htmlspecialchars(ucfirst(strtolower($car->status ?? 'Available'))) ?>
                            </p>
                        </div>
                        <div class="flex items-center gap-2 text-yellow-500">
                            <span class="text-slate-500 dark:text-slate-400 text-sm font-medium underline cursor-pointer hover:text-primary"><?= count(Avis::getAvisBycar($carId)) ?> reviews</span>
                        </div>
                    </div>
                </div>
                <!-- Hero Image Gallery -->
                <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-3 h-[480px] rounded-2xl overflow-hidden">

                    <div class="col-span-1 md:col-span-3 row-span-2 relative group cursor-pointer overflow-hidden">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105"
                            data-alt="<?= htmlspecialchars($car->marque . ' ' . $car->model) ?>"
                            style="background-image: url('<?= htmlspecialchars($car->image ?? 'https://via.placeholder.com/800x600?text=Car+Image') ?>');"></div>
                        <div class="absolute bottom-4 left-4 bg-black/60 backdrop-blur-sm px-3 py-1 rounded text-white text-xs font-bold">Main View</div>
                    </div>

                </div>

                <!-- Description -->
                <div class="prose dark:prose-invert max-w-none">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">About this car</h3>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                        <?= nl2br(htmlspecialchars($car->description ?? 'No detailed description available for this vehicle. Contact us for more information.')) ?>
                    </p>
                    <?php if ($car->status && strtoupper($car->status) === 'AVAILABLE'): ?>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed mt-4">
                            <strong class="text-green-600 dark:text-green-400">Available Now</strong> - This vehicle is ready for immediate booking. Reserve it today to secure your rental dates.
                        </p>
                    <?php else: ?>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed mt-4">
                            <strong class="text-orange-600 dark:text-orange-400">Note:</strong> This vehicle is currently <?= htmlspecialchars(strtolower($car->status)) ?>. Please contact us for availability updates.
                        </p>
                    <?php endif; ?>
                </div>
                <hr class="border-slate-200 dark:border-slate-700" />
                <!-- Reviews Section -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Customer Reviews</h3>
                    </div>

                    <?php if (isset($_SESSION['review_success'])): ?>
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span><?= htmlspecialchars($_SESSION['review_success']) ?></span>
                        </div>
                        <?php unset($_SESSION['review_success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['review_errors'])): ?>
                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg mb-6">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined">error</span>
                                <strong>Please fix the following errors:</strong>
                            </div>
                            <ul class="list-disc list-inside ml-6">
                                <?php foreach ($_SESSION['review_errors'] as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['review_errors']); ?>
                    <?php endif; ?>

                    <!-- Review Form (Collapsed/Simple view) -->
                    <?php

                    if (!empty($check)) {

                    ?>
                        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 mb-8">
                            <h4 class="text-sm font-bold uppercase text-slate-500 mb-3 tracking-wider">Share your experience</h4>

                            <form id="reviewForm" method="POST" action="./../../Controllers/AvisController.php">
                                <input type="hidden" name="car_id" value="<?= htmlspecialchars($car->id_car) ?>">
                                <input type="hidden" name="rating" id="ratingInput" value="0">
                                <input type="hidden" name="role_user" value="<?= $logged->role ?>">
                                <div class="flex gap-4">
                                    <div class="size-10 rounded-full bg-slate-200 dark:bg-slate-700 shrink-0 flex items-center justify-center text-slate-500">
                                        <span class="material-symbols-outlined">person</span>
                                    </div>
                                    <div class="flex-grow">
                                        <textarea name="review_text" id="reviewText" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg p-3 text-sm focus:ring-2 focus:ring-primary focus:border-transparent resize-none" placeholder="Tell us about your trip..." rows="2" required></textarea>
                                        <div class="flex justify-between items-center mt-3">
                                            <div class="flex text-slate-300" id="starRating">
                                                <span class="material-symbols-outlined cursor-pointer hover:text-yellow-400 transition-colors" data-rating="1">star</span>
                                                <span class="material-symbols-outlined cursor-pointer hover:text-yellow-400 transition-colors" data-rating="2">star</span>
                                                <span class="material-symbols-outlined cursor-pointer hover:text-yellow-400 transition-colors" data-rating="3">star</span>
                                                <span class="material-symbols-outlined cursor-pointer hover:text-yellow-400 transition-colors" data-rating="4">star</span>
                                                <span class="material-symbols-outlined cursor-pointer hover:text-yellow-400 transition-colors" data-rating="5">star</span>
                                            </div>
                                            <button type="submit" class="bg-slate-900 dark:bg-slate-700 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-800">Post Review</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else {

                        echo '';
                    } ?>

                    <!-- Existing Reviews List -->
                    <div class="space-y-6">
                        <?php
                        $reviews = Avis::getAvisBycar($carId);

                        if (empty($reviews)) {
                            echo '<div class="text-center py-12 bg-slate-50 dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-5xl mb-4">rate_review</span>
                                    <h4 class="text-lg font-bold text-slate-600 dark:text-slate-400 mb-2">No reviews yet</h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-500">Be the first to share your experience with this vehicle!</p>
                                  </div>';
                        } else {
                            foreach ($reviews as $review) {
                                $timeAgo = '';
                                if ($review->created_at) {
                                    $reviewDate = new DateTime($review->created_at);
                                    $now = new DateTime();
                                    $diff = $now->diff($reviewDate);

                                    if ($diff->y > 0) {
                                        $timeAgo = $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
                                    } elseif ($diff->m > 0) {
                                        $timeAgo = $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
                                    } elseif ($diff->d > 0) {
                                        $timeAgo = $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
                                    } else {
                                        $timeAgo = 'Today';
                                    }
                                }

                                $clientInitial = !empty($review->client_name) ? strtoupper($review->client_name[0]) : 'U';
                                $isOwnReview = ($review->client_email === $logged->email);
                        ?>
                                <div class="flex gap-4 relative group" id="review-<?= $review->id_avis ?>">
                                    <div class="size-12 rounded-full overflow-hidden bg-gradient-to-br from-primary to-blue-600 shrink-0 flex items-center justify-center text-white font-bold text-lg">
                                        <?= htmlspecialchars($clientInitial) ?>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <div class="flex items-center gap-2">
                                                <h5 class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($review->client_name ?? 'Anonymous') ?></h5>
                                                <?php if ($timeAgo): ?>
                                                    <span class="text-xs text-slate-400">• <?= htmlspecialchars($timeAgo) ?></span>
                                                <?php endif; ?>
                                                <?php if ($isOwnReview): ?>
                                                    <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded font-bold">Your Review</span>
                                                <?php endif; ?>
                                            </div>

                                            <?php if ($isOwnReview): ?>
                                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button onclick="editReview(<?= $review->id_avis ?>, <?= $review->note ?>, '<?= htmlspecialchars(addslashes($review->texte), ENT_QUOTES) ?>')"
                                                        class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 rounded text-slate-400 hover:text-primary transition-colors"
                                                        title="Edit">
                                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                                    </button>
                                                    <form method="POST" action="../../Controllers/AvisController.php" class="inline">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="id_avis" value="<?= $review->id_avis ?>">
                                                        <button type="submit"
                                                            class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded text-slate-400 hover:text-red-500 transition-colors"
                                                            title="Delete">
                                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="review-display">
                                            <div class="flex text-yellow-500 text-sm mb-2">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <?php if ($i <= $review->note): ?>
                                                        <span class="material-symbols-outlined text-[18px] fill-1" style="font-variation-settings: 'FILL' 1;">star</span>
                                                    <?php else: ?>
                                                        <span class="material-symbols-outlined text-[18px] text-slate-300">star</span>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>
                                            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
                                                <?= nl2br(htmlspecialchars($review->texte ?? 'No review text provided.')) ?>
                                            </p>
                                        </div>

                                        <?php if ($isOwnReview): ?>
                                            <form method="POST" action="../../Controllers/AvisController.php" class="review-edit hidden mt-3">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="id_avis" value="<?= $review->id_avis ?>">
                                                <input type="hidden" name="note" class="edit-rating-input" value="<?= $review->note ?>">

                                                <div class="flex text-yellow-500 text-sm mb-2 edit-star-rating">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <span class="material-symbols-outlined cursor-pointer text-[18px] <?= $i <= $review->note ? 'text-yellow-400' : 'text-slate-300' ?>"
                                                            data-rating="<?= $i ?>"
                                                            style="font-variation-settings: 'FILL' <?= $i <= $review->note ? 1 : 0 ?>;">star</span>
                                                    <?php endfor; ?>
                                                </div>

                                                <textarea name="texte" required
                                                    class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg p-3 text-sm focus:ring-2 focus:ring-primary focus:border-transparent resize-none mb-2"
                                                    rows="3"><?= htmlspecialchars($review->texte) ?></textarea>

                                                <div class="flex gap-2">
                                                    <button type="submit" class="bg-primary text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-600">
                                                        Save Changes
                                                    </button>
                                                    <button type="button" onclick="cancelEdit(<?= $review->id_avis ?>)"
                                                        class="bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-slate-300 dark:hover:bg-slate-600">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <?php if (!empty($reviews) && count($reviews) > 5): ?>
                        <button class="w-full mt-6 py-3 text-sm font-bold text-slate-500 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">Load More Reviews</button>
                    <?php endif; ?>
                </section>
            </div>
            <?php
            if (empty($check)) {


            ?>
                <!-- Right Column: Sticky Booking Card -->
                <div class="lg:col-span-4 relative">
                    <div class="sticky top-24">
                        <form action="./../../Controllers/ReserverController.php" method="POST" class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 p-6 overflow-hidden">
                            <!-- Price Header -->
                            <input type="hidden" name="id_client" value="<?= htmlspecialchars($logged->id_user) ?>">
                            <div class="flex items-end gap-2 mb-6 border-b border-slate-100 dark:border-slate-700 pb-6">
                                <span class="text-3xl font-black text-slate-900 dark:text-white">$<?= number_format($car->prix ?? 0, 2) ?></span>
                                <span class="text-slate-500 dark:text-slate-400 font-medium mb-1">/ day</span>
                                <div class="ml-auto">
                                    <?php if (strtoupper($car->status ?? '') === 'AVAILABLE'): ?>
                                        <span class="bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold px-2 py-1 rounded">Available Now</span>
                                    <?php else: ?>
                                        <span class="bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 text-xs font-bold px-2 py-1 rounded"><?= htmlspecialchars(ucfirst(strtolower($car->status))) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Date Selection Form -->
                            <div class="space-y-4 mb-6">
                                <input type="hidden" id="car_id" name="id_car" value="<?= htmlspecialchars($car->id_car) ?>" />
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Start-Date</label>
                                        <div class="relative">
                                            <input name="startDate" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-3 text-sm text-slate-900 dark:text-white focus:ring-primary focus:border-primary" type="date" />
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">End-Date</label>
                                        <div class="relative">
                                            <input name="endDate" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-3 text-sm text-slate-900 dark:text-white focus:ring-primary focus:border-primary" type="date" />
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">return Location</label>
                                    <input class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-3 text-sm text-slate-900 dark:text-white focus:ring-primary focus:border-primary"
                                        type="text" placeholder="return location" name="pickupLocation">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Pick-up Location</label>
                                    <input class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg py-2.5 px-3 text-sm text-slate-900 dark:text-white focus:ring-primary focus:border-primary"
                                        type="text" placeholder="pick-up location" name="retournLocation">
                                </div>
                            </div>
                            <!-- Cost Summary -->
                            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4 mb-6 space-y-2">
                                <div class="dark:border-slate-600 my-2  flex justify-between font-bold text-slate-900 dark:text-white text-lg">
                                    <span>Total</span>
                                    <span>$<?= number_format($car->prix) ?></span>
                                </div>
                            </div>
                            <!-- Main Action -->
                            <button type="submit" id="reserver" data-carId="<?= $carId ?>" data-clientId="<?= $logged->id_user ?>" class="w-full bg-primary hover:bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                                <span>Reserve Now</span>
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </button>
                            <p class="text-center text-xs text-slate-400 mt-4">You won't be charged yet</p>
                            <!-- Trust Signals -->
                            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 space-y-3">
                                <div class="flex items-center gap-3 text-slate-600 dark:text-slate-400 text-sm">
                                    <span class="material-symbols-outlined text-green-500 text-xl">check_circle</span>
                                    <span>Free Cancellation</span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-600 dark:text-slate-400 text-sm">
                                    <span class="material-symbols-outlined text-green-500 text-xl">check_circle</span>
                                    <span>Instant Confirmation</span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-600 dark:text-slate-400 text-sm">
                                    <span class="material-symbols-outlined text-green-500 text-xl">check_circle</span>
                                    <span>Best Price Guarantee</span>
                                </div>
                            </div>
                        </form>
                        <!-- Help Card -->
                        <div class="mt-6 bg-slate-100 dark:bg-slate-800 rounded-xl p-4 flex items-center gap-4 border border-slate-200 dark:border-slate-700">
                            <div class="size-10 rounded-full bg-white dark:bg-slate-700 flex items-center justify-center shadow-sm text-primary">
                                <span class="material-symbols-outlined">support_agent</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Need help?</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Call our support team 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
    <!-- Footer Simple -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 mt-12 py-10">
        <div class="max-w-[1280px] mx-auto px-4 text-center">
            <p class="text-slate-500 text-sm">© 2024 MaBagnole Inc. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const starContainer = document.getElementById('starRating');
        const stars = starContainer.querySelectorAll('span');
        const ratingInput = document.getElementById('ratingInput');
        const ratingText = document.getElementById('ratingText');
        let currentRating = 0;

        stars.forEach((star, index) => {
            star.addEventListener('click', function() {
                currentRating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = currentRating;
                updateStars(currentRating);
                updateRatingText(currentRating);
            });

            star.addEventListener('mouseenter', function() {
                const hoverRating = parseInt(this.getAttribute('data-rating'));
                updateStars(hoverRating);
            });
        });

        starContainer.addEventListener('mouseleave', function() {
            updateStars(currentRating);
        });

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-slate-300');
                    star.style.fontVariationSettings = "'FILL' 1";
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-slate-300');
                    star.style.fontVariationSettings = "'FILL' 0";
                }
            });
        }

        function editReview(reviewId, currentNote, currentText) {
            const reviewElement = document.getElementById('review-' + reviewId);
            const displayElement = reviewElement.querySelector('.review-display');
            const editForm = reviewElement.querySelector('.review-edit');

            displayElement.classList.add('hidden');
            editForm.classList.remove('hidden');

            setupEditStarRating(reviewId, currentNote);
        }

        function cancelEdit(reviewId) {
            const reviewElement = document.getElementById('review-' + reviewId);
            const displayElement = reviewElement.querySelector('.review-display');
            const editForm = reviewElement.querySelector('.review-edit');

            displayElement.classList.remove('hidden');
            editForm.classList.add('hidden');
        }

        function setupEditStarRating(reviewId, currentNote) {
            const reviewElement = document.getElementById('review-' + reviewId);
            const editStarContainer = reviewElement.querySelector('.edit-star-rating');
            const editStars = editStarContainer.querySelectorAll('span');
            const editRatingInput = reviewElement.querySelector('.edit-rating-input');
            let editCurrentRating = currentNote;

            editStars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    editCurrentRating = parseInt(this.getAttribute('data-rating'));
                    editRatingInput.value = editCurrentRating;
                    updateEditStars(editStars, editCurrentRating);
                });

                star.addEventListener('mouseenter', function() {
                    const hoverRating = parseInt(this.getAttribute('data-rating'));
                    updateEditStars(editStars, hoverRating);
                });
            });

            editStarContainer.addEventListener('mouseleave', function() {
                updateEditStars(editStars, editCurrentRating);
            });
        }

        function updateEditStars(stars, rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-slate-300');
                    star.style.fontVariationSettings = "'FILL' 1";
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-slate-300');
                    star.style.fontVariationSettings = "'FILL' 0";
                }
            });
        }
    </script>
</body>

</html>