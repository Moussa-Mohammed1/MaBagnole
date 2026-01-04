<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged']->role !== 'admin') {
    header('Location: ./../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Classes\Reservation;

$reservations = Reservation::getAllReservations();

function getStatusBadge($status) {
    $status = strtolower($status ?? 'pending');
    $badges = [
        'confirmed' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50">Confirmed</span>',
        'accepted' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50">Accepted</span>',
        'pending' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800/50">Pending</span>',
        'active' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>Active</span>',
        'cancelled' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800/50">Cancelled</span>',
        'rejected' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800/50">Rejected</span>',
        'completed' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">Completed</span>',
    ];
    return $badges[$status] ?? $badges['pending'];
}
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Reservation Management - MaBagnole</title>
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
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="dashboard.php">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">dashboard</span>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="car.php">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">directions_car</span>
                <span class="text-sm font-medium">Vehicles</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="category.php">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">category</span>
                <span class="text-sm font-medium">Categories</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-400 transition-colors" href="reservations.php">
                <span class="material-symbols-outlined fill-1">calendar_today</span>
                <span class="text-sm font-bold">Reservations</span>
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
                        <a class="text-slate-500 dark:text-slate-400 hover:text-primary" href="#">Reservations</a>
                        <span class="text-slate-400 dark:text-slate-600">/</span>
                        <span class="text-slate-900 dark:text-white font-medium">Manage</span>
                    </div>
                    <div class="flex flex-wrap justify-between items-end gap-4 mt-2">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Reservation Management</h1>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">View, manage, and update all car rental bookings in real-time.</p>
                        </div>
                        <div class="flex gap-3">
                            <button class="flex items-center gap-2 px-4 py-2 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-bold shadow-sm text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">file_download</span>
                                Export CSV
                            </button>
                            <button class="flex items-center gap-2 px-4 py-2 rounded-md bg-primary text-white font-bold shadow-sm text-sm hover:bg-blue-600 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                New Reservation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark custom-scrollbar">
            <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col gap-6 pb-20">
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
                    <div class="flex flex-col md:flex-row gap-4 justify-between">
                        <div class="relative flex-1 max-w-md">
                            <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400">search</span>
                            <input class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary dark:text-white" placeholder="Search by customer, vehicle, or reservation ID..." type="text" />
                        </div>
                        <div class="flex gap-3 overflow-x-auto pb-2 md:pb-0">
                            <div class="relative min-w-[140px]">
                                <select class="w-full pl-3 pr-10 py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm appearance-none focus:ring-primary focus:border-primary dark:text-white cursor-pointer font-medium text-slate-600 dark:text-slate-300">
                                    <option>All Statuses</option>
                                    <option>Confirmed</option>
                                    <option>Pending</option>
                                    <option>Active</option>
                                    <option>Completed</option>
                                    <option>Cancelled</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-2.5 text-slate-400 pointer-events-none text-[20px]">expand_more</span>
                            </div>
                            <div class="relative min-w-[160px]">
                                <select class="w-full pl-3 pr-10 py-2 bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm appearance-none focus:ring-primary focus:border-primary dark:text-white cursor-pointer font-medium text-slate-600 dark:text-slate-300">
                                    <option>Any Pickup Location</option>
                                    <option>Airport (LAX)</option>
                                    <option>Downtown Office</option>
                                    <option>Westside Branch</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-2.5 text-slate-400 pointer-events-none text-[20px]">location_on</span>
                            </div>
                            <button class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors border border-transparent hover:border-slate-300 dark:hover:border-slate-600">
                                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col h-full">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-bold uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4">Reservation ID</th>
                                    <th class="px-6 py-4">Customer</th>
                                    <th class="px-6 py-4">Vehicle</th>
                                    <th class="px-6 py-4">Pickup</th>
                                    <th class="px-6 py-4">Return</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <?php if (empty($reservations)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-5xl">event_busy</span>
                                                <p class="text-slate-500 dark:text-slate-400 text-sm">No reservations found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($reservations as $reservation): 
                                        $isPending = strtolower($reservation->status ?? '') === 'pending';
                                        $isCancelled = in_array(strtolower($reservation->status ?? ''), ['cancelled', 'rejected']);
                                    ?>
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group <?= $isCancelled ? 'opacity-75' : '' ?>">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-900 dark:text-white">#RES-<?= str_pad($reservation->id_reservation, 4, '0', STR_PAD_LEFT) ?></div>
                                                <div class="text-xs text-slate-500 mt-0.5">Booked <?= date('M d, Y', strtotime($reservation->date_reservation)) ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="size-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 font-bold text-xs">
                                                        <?= strtoupper(substr($reservation->id_client ?? 'C', 0, 2)) ?>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-slate-900 dark:text-white">Client #<?= $reservation->id_client ?></div>
                                                        <div class="text-xs text-slate-500">ID: <?= $reservation->id_client ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-900 dark:text-white">Car #<?= $reservation->id_car ?></div>
                                                <div class="text-xs text-slate-500 font-mono">ID: <?= $reservation->id_car ?></div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-700 dark:text-slate-300"><?= date('M d, h:i A', strtotime($reservation->startDate)) ?></div>
                                                <div class="text-xs text-slate-500 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px]">location_on</span> <?= htmlspecialchars($reservation->pickupLocation ?? 'N/A') ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-slate-700 dark:text-slate-300"><?= date('M d, h:i A', strtotime($reservation->endDate)) ?></div>
                                                <div class="text-xs text-slate-500 flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[12px]">location_on</span> <?= htmlspecialchars($reservation->retournLocation ?? 'N/A') ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?= getStatusBadge($reservation->status) ?>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <?php if ($isPending): ?>
                                                        <form method="POST" action="../../Controllers/ReservationController.php" class="inline">
                                                            <input type="hidden" name="action" value="accept">
                                                            <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>">
                                                            <button type="submit" class="p-1.5 rounded-md hover:bg-emerald-50 dark:hover:bg-emerald-900/30 text-emerald-500 hover:text-emerald-600 transition-colors" title="Accept">
                                                                <span class="material-symbols-outlined text-[20px]">check</span>
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="../../Controllers/ReservationController.php" class="inline">
                                                            <input type="hidden" name="action" value="reject">
                                                            <input type="hidden" name="id_reservation" value="<?= $reservation->id_reservation ?>">
                                                            <button type="submit" class="p-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 text-red-500 hover:text-red-600 transition-colors" title="Reject">
                                                                <span class="material-symbols-outlined text-[20px]">close</span>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button class="p-1.5 rounded-md hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-500 hover:text-primary transition-colors" title="View Details">
                                                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-800 p-4 bg-slate-50 dark:bg-slate-900/50 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm mt-auto">
                        <span class="text-slate-500 dark:text-slate-400">
                            Total: <span class="font-bold text-slate-700 dark:text-white"><?= count($reservations) ?></span> reservation<?= count($reservations) !== 1 ? 's' : '' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>