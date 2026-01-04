<?php
session_start();

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Classes\Vehicule;
use App\Classes\Category;

$cars = Vehicule::getAllCars();

?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MaBagnole - Browse Fleet</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css" />
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
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "body": ["Noto Sans", "sans-serif"],
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
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background-color: #334155;
        }

        /* DataTables Custom Styling */
        #carsTable_wrapper {
            padding: 1rem;
        }

        #carsTable_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin-left: 0.5rem;
        }

        .dark #carsTable_filter input {
            background-color: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }

        #carsTable_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin: 0 0.5rem;
        }

        .dark #carsTable_length select {
            background-color: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-50 font-display min-h-screen flex flex-col antialiased">
    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800">
        <div class="px-4 md:px-10 py-3 flex items-center justify-between whitespace-nowrap">
            <div class="flex items-center gap-8">
                <a class="flex items-center gap-3 group" href="cars.php">

                    <h2 class="text-slate-900 dark:text-white text-xl font-bold tracking-tight">MaBagnole</h2>
                </a>
                <nav class="hidden md:flex items-center gap-6">
                    <a class="text-primary text-sm font-bold" href="cars.php">Fleet</a>
                    <a class="text-slate-600 dark:text-slate-300 hover:text-primary dark:hover:text-primary text-sm font-medium transition-colors" href="dashboard.php">My Bookings</a>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <?php if (isset($_SESSION['logged'])): ?>
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex flex-col items-end">
                            <span class="text-sm font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($_SESSION['logged']->username ?? 'User') ?></span>
                            <span class="text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($_SESSION['logged']->email ?? '') ?></span>
                        </div>
                        <div class="size-10 rounded-full bg-gradient-to-br from-primary to-blue-600 flex items-center justify-center text-white font-bold">
                            <?= strtoupper(substr($_SESSION['logged']->username ?? 'U', 0, 1)) ?>
                        </div>
                    </div>
                    <a href="./../auth/login.php" class="flex items-center gap-2 px-4 py-2 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        <span class="hidden sm:inline text-sm font-medium">Logout</span>
                    </a>
                <?php else: ?>
                    <a href="./../auth/login.php" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary hover:bg-blue-600 text-white font-bold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                        Sign In
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <!-- Hero Section -->
    <div class="relative bg-slate-900 text-white">
        <div class="absolute inset-0 z-0">
            <div class="w-full h-full bg-cover bg-center opacity-60" data-alt="Modern black luxury car driving on a coastal road at sunset" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCicflpU91W_hBT63Jmv11iAeUdut5-PUpxvMlLMKyLeGmNUMQ_CVq9AOAdVIrLewLhuEd7TZl1PE-E4-5b6WdcVsjeM2JlmqnXjZQoTkGXlIk0X_rW1_qnUSOWd3Ppg2wl16wfmAIyEG_9XXbi12oyo0pcaYeTBYRS9x3WYCtbzjP1RUsaibZckMJNk7fyXbv_LdbKGVerfkSiNmqFNE8WpqrP4_YWb_lbvqb_BgaX4mHYdlOEt7iRbdKDKhwSUv3c72YGo1OP7cY');"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-background-light dark:from-background-dark via-transparent to-transparent"></div>
        </div>
        <div class="relative z-10 layout-container px-4 md:px-10 lg:px-40 py-16 md:py-24 flex flex-col items-center text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight mb-4 drop-shadow-lg">
                Find the perfect ride
            </h1>
            <p class="text-lg md:text-xl text-slate-200 max-w-2xl font-light mb-10 drop-shadow-md">
                Choose from our premium fleet at affordable daily rates. From economy to luxury, we have the keys to your next journey.
            </p>
        </div>
    </div>
    <!-- Main Content Area with overlapping Search -->
    <main class="layout-container px-4 md:px-10 lg:px-20 -mt-12 md:-mt-16 relative z-20 pb-20">
        <!-- Search Bar Component -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 p-4 mb-12">
            <form class="flex flex-col lg:flex-row gap-4 items-end lg:items-center">
                <!-- Search Text -->
                <div class="flex-1 w-full">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Search Model</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <input class="block w-full pl-10 pr-3 py-3 rounded-lg border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="E.g. Tesla Model 3..." type="text" />
                    </div>
                </div>
                <!-- Dates -->
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Pick-up</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                            </div>
                            <input class="block w-full pl-10 pr-3 py-3 rounded-lg border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary focus:border-transparent transition-all" onblur="(this.type='text')" onfocus="(this.type='date')" placeholder="Select Date" type="text" />
                        </div>
                    </div>
                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Drop-off</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                            </div>
                            <input class="block w-full pl-10 pr-3 py-3 rounded-lg border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-primary focus:border-transparent transition-all" onblur="(this.type='text')" onfocus="(this.type='date')" placeholder="Select Date" type="text" />
                        </div>
                    </div>
                </div>
                <!-- Submit -->
                <div class="w-full lg:w-auto mt-2 lg:mt-6">
                    <button class="w-full lg:w-auto h-[50px] bg-primary hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2" type="button">
                        <span class="material-symbols-outlined">search</span>
                        <span>Search</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->

            <!-- Vehicle Grid -->
            <div class="flex-1">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Available Vehicles <span class="text-slate-400 font-normal text-lg ml-2">(<?= count($cars) ?>)</span></h2>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500 hidden sm:inline">Filter by category:</span>
                        <select id="cattt" class="form-select bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-lg text-sm font-medium py-2 px-3 focus:ring-primary focus:border-primary">
                            <option value="">All Categories</option>
                            <?php $categories = Category::getAllCategories();
                            foreach ($categories as $cat): ?>
                                <option name="<?= $cat->nom ?>"><?= $cat->nom ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- DataTable Container -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <table id="carsTable" class="w-full display">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Image</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Vehicle</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Price/Day</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <?php
                            function getCategoryColor($categoryName)
                            {
                                $colors = [
                                    'Luxury' => 'bg-primary/10 text-primary',
                                    'SUV' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                    'Economy' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
                                    'Off-Road' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
                                    'Convertible' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400',
                                    'Sport' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                    'Sedan' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                ];
                                return $colors[$categoryName] ?? 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300';
                            }

                            if (!empty($cars)) {
                                foreach ($cars as $car) {
                                    $categoryColor = getCategoryColor($car->nom ?? 'Other');
                            ?>
                                    <tr class="hover:bg-slate-50 car dark:hover:bg-slate-700/50 transition-colors">
                                        <td class="px-4 py-3">
                                            <img src="<?= htmlspecialchars($car->image ?? 'https://via.placeholder.com/150') ?>"
                                                alt="<?= htmlspecialchars($car->marque . ' ' . $car->model) ?>"
                                                class="w-24 h-16 object-cover rounded-lg"
                                                onerror="this.src='https://via.placeholder.com/150'">
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-bold text-slate-900 dark:text-white">
                                                <?= htmlspecialchars($car->marque . ' ' . $car->model) ?>
                                            </div>
                                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                                <?= htmlspecialchars($car->description ?? 'No description') ?>
                                            </div>
                                        </td>
                                        <td class="px-4 category py-3">
                                            <span class="px-2 py-1 <?= $categoryColor ?> rounded-md text-xs font-bold">
                                                <?= htmlspecialchars($car->nom ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-col gap-1 text-xs text-slate-600 dark:text-slate-400">
                                                <div class="flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[16px]">
                                                        <?= strtoupper($car->status ?? 'AVAILABLE') === 'AVAILABLE' ? 'check_circle' : 'cancel' ?>
                                                    </span>
                                                    <span><?= htmlspecialchars($car->status ?? 'AVAILABLE') ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-lg font-black text-primary">
                                                $<?= number_format($car->prix ?? 0, 2) ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button class="px-3 py-1.5 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-lg text-xs font-bold transition-all"
                                                onclick="viewCarDetails(<?= $car->id_car ?>)">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                                        No vehicles available at the moment. Please check back later.
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="mt-auto bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
        <div class="layout-container px-4 md:px-10 lg:px-40 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="size-6 text-primary">
                            <svg class="w-full h-full" fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_6_319_footer)">
                                    <path d="M8.57829 8.57829C5.52816 11.6284 3.451 15.5145 2.60947 19.7452C1.76794 23.9758 2.19984 28.361 3.85056 32.3462C5.50128 36.3314 8.29667 39.7376 11.8832 42.134C15.4698 44.5305 19.6865 45.8096 24 45.8096C28.3135 45.8096 32.5302 44.5305 36.1168 42.134C39.7033 39.7375 42.4987 36.3314 44.1494 32.3462C45.8002 28.361 46.2321 23.9758 45.3905 19.7452C44.549 15.5145 42.4718 11.6284 39.4217 8.57829L24 24L8.57829 8.57829Z" fill="currentColor"></path>
                                </g>
                                <defs>
                                    <clippath id="clip0_6_319_footer">
                                        <rect fill="white" height="48" width="48"></rect>
                                    </clippath>
                                </defs>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">MaBagnole</h3>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Premium car rental services for your business and leisure needs. Quality fleet, affordable prices.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">Company</h4>
                    <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                        <li><a class="hover:text-primary transition-colors" href="#">About Us</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Careers</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                        <li><a class="hover:text-primary transition-colors" href="#">Help Center</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Terms of Service</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">mail</span>
                            info@mabagnole.com
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">call</span>
                            +1 (555) 123-4567
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-800 text-center text-xs text-slate-400">
                © 2023 MaBagnole Inc. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#carsTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 3, 5]
                    },
                    {
                        "searchable": false,
                        "targets": [0, 5]
                    }
                ],
                "language": {
                    "search": "Search vehicles:",
                    "lengthMenu": "Show _MENU_ vehicles",
                    "info": "Showing _START_ to _END_ of _TOTAL_ vehicles",
                    "infoEmpty": "No vehicles available",
                    "infoFiltered": "(filtered from _MAX_ total vehicles)",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                },
                "responsive": true
            });

            $('#cattt').on('change', function() {
                let selectedCategory = $(this).val();
                table.column(2).search(selectedCategory).draw();
            });
        });

        function viewCarDetails(carId) {
            window.location.href = 'info.php?id=' + carId;
        }
    </script>
</body>

</html>