<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged']->role !== 'admin') {
    header('Location: ./../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Classes\Vehicule;
use App\Classes\Category;
use App\Classes\Reservation;
use App\Classes\Avis;

$totalCars = count(Vehicule::getAllCars());
$totalCategories = count(Category::getAllCategories());
$totalReservations = count(Reservation::getAllReservations());
$allReviews = Avis::getAllAvis();
$totalReviews = count($allReviews);

// Calculate average rating
$avgRating = 0;
if ($totalReviews > 0) {
    $totalRating = array_sum(array_map(fn($review) => $review->note, $allReviews));
    $avgRating = round($totalRating / $totalReviews, 1);
}

// Get recent reservations (latest 5)
$recentReservations = array_slice(Reservation::getAllReservations(), 0, 5);
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Admin Dashboard - MaBagnole</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
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
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .material-symbols-outlined.filled {
            font-variation-settings: 'FILL' 1;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-white overflow-hidden h-screen flex">
    <aside class="w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex-col hidden lg:flex h-full z-10 relative">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800">
            <div class="flex gap-3 items-center">
                <div class="bg-center bg-no-repeat bg-cover rounded-full size-10 border border-slate-200 dark:border-slate-700" data-alt="User profile picture showing a smiling man" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCC3MqzxP3uuCVk6C4sk4fTJsCxAatwAedQrnFNEE81XukbhdewT1yiGX4mS4WCnkXevgNeM-02I1X66JUz42TMMcQ68dq_nAP77phDxymeLjN1tRlGVXQO2efmhDZcD8uZffnh8cN5wZEG7zwql5gr_Bg4IfGiJNwT8KjwJPHUwD-skAppLlzm5TWeD-fG_QWO70n3ryJtDcXk0NAZuahQ7YMC4mEynIfrX5waaxKjWI5n9Rf2p3oBw16vLBEZEwl4jp9SC0DRv5g");'></div>
                <div class="flex flex-col">
                    <h1 class="text-slate-900 dark:text-white text-base font-bold leading-normal">MaBagnole Admin</h1>
                    <p class="text-primary text-sm font-medium leading-normal">Administrator</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto p-4 flex flex-col gap-2">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-400 transition-colors" href="dashboard.php">
                <span class="material-symbols-outlined filled">dashboard</span>
                <span class="text-sm font-bold">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="car.php">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">directions_car</span>
                <span class="text-sm font-medium">Vehicles</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="category.php">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">category</span>
                <span class="text-sm font-medium">Categories</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="reservations.php">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">calendar_today</span>
                <span class="text-sm font-medium">Reservations</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="#">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">star</span>
                <span class="text-sm font-medium">Reviews</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group mt-auto" href="#">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-red-500">logout</span>
                <span class="text-sm font-medium group-hover:text-red-500">Log Out</span>
            </a>
        </nav>
    </aside>
    <main class="flex-1 flex flex-col h-full relative overflow-hidden">
        <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex-none z-10">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center gap-2 text-sm">
                        <a class="text-slate-500 dark:text-slate-400 hover:text-primary" href="#">Home</a>
                        <span class="text-slate-400 dark:text-slate-600">/</span>
                        <span class="text-slate-900 dark:text-white font-medium">Dashboard</span>
                    </div>
                    <div class="flex flex-wrap justify-between items-end gap-4 mt-2">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Dashboard Overview</h1>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Welcome back! Here's what's happening with your car rental business.</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark custom-scrollbar">
            <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col gap-6 pb-32">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Vehicles</p>
                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white mt-1"><?= $totalCars ?></h3>
                            <p class="text-slate-400 text-xs mt-1">In fleet</p>
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="material-symbols-outlined text-primary text-3xl">directions_car</span>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Reservations</p>
                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white mt-1"><?= $totalReservations ?></h3>
                            <p class="text-slate-400 text-xs mt-1">Total bookings</p>
                        </div>
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-3xl">calendar_today</span>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Categories</p>
                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white mt-1"><?= $totalCategories ?></h3>
                            <p class="text-slate-400 text-xs mt-1">Vehicle types</p>
                        </div>
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-3xl">category</span>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Avg Rating</p>
                            <h3 class="text-3xl font-bold text-slate-900 dark:text-white mt-1"><?= number_format($avgRating, 1) ?></h3>
                            <div class="flex items-center gap-0.5 mt-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="material-symbols-outlined filled text-yellow-400 text-[14px]"><?= $i <= floor($avgRating) ? 'star' : ($i - 0.5 <= $avgRating ? 'star_half' : 'star') ?></span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400 text-3xl">star</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Recent Reservations</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Latest bookings and their status</p>
                            </div>
                            <a href="reservations.php" class="text-primary hover:text-blue-600 text-sm font-bold flex items-center gap-1">
                                View All
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-bold uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4">Client</th>
                                    <th class="px-6 py-4">Vehicle</th>
                                    <th class="px-6 py-4">Start Date</th>
                                    <th class="px-6 py-4">End Date</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <?php if (empty($recentReservations)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-5xl">event_busy</span>
                                                <p class="text-slate-500 dark:text-slate-400 text-sm">No reservations yet.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentReservations as $reservation): ?>
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="size-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 font-bold text-sm">
                                                        <?= strtoupper(substr($reservation->client_name ?? 'C', 0, 1)) ?>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($reservation->client_name ?? 'Client') ?></span>
                                                        <span class="text-xs text-slate-500"><?= htmlspecialchars($reservation->client_email ?? '') ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span class="font-medium text-slate-900 dark:text-white"><?= htmlspecialchars($reservation->car_model ?? 'Vehicle') ?></span>
                                                    <span class="text-xs text-slate-500"><?= htmlspecialchars($reservation->category_name ?? '') ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                                <?= date('M d, Y', strtotime($reservation->startDate)) ?>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                                <?= date('M d, Y', strtotime($reservation->endDate)) ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php
                                                $statusClass = match (strtolower($reservation->status ?? 'pending')) {
                                                    'confirmed' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800/50',
                                                    'cancelled' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100 dark:border-red-800/50',
                                                    default => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-800/50'
                                                };
                                                ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border <?= $statusClass ?>">
                                                    <span class="size-1.5 rounded-full bg-current"></span>
                                                    <?= ucfirst($reservation->status ?? 'Pending') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="reservations.php?id=<?= $reservation->id_reservation ?>" class="text-slate-400 hover:text-primary transition-colors p-1 inline-block">
                                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>