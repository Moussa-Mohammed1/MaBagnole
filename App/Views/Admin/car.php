<?php
session_start();

if (!isset($_SESSION['logged']) || $_SESSION['logged']->role !== 'admin') {
    header('Location: ./../auth/login.php');
    exit();
}

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Classes\Vehicule;
use App\Classes\Category;

$cars = Vehicule::getAllCars();
$categories = Category::getAllCategories();

function getStatusBadge($status)
{
    $status = strtolower($status);
    $badges = [
        'available' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50"><span class="size-1.5 rounded-full bg-emerald-500"></span>Available</span>',
        'rented' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50"><span class="size-1.5 rounded-full bg-blue-500"></span>Rented</span>',
        'maintenance' => '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800/50"><span class="size-1.5 rounded-full bg-amber-500"></span>Maintenance</span>',
    ];
    return $badges[$status] ?? $badges['available'];
}
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Vehicle Management - MaBagnole</title>
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
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="#">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">dashboard</span>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-400 transition-colors" href="#">
                <span class="material-symbols-outlined fill-1">directions_car</span>
                <span class="text-sm font-bold">Vehicles</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="#">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">category</span>
                <span class="text-sm font-medium">Categories</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="#">
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
                        <a class="text-slate-500 dark:text-slate-400 hover:text-primary" href="#">Vehicles</a>
                        <span class="text-slate-400 dark:text-slate-600">/</span>
                        <span class="text-slate-900 dark:text-white font-medium">Manage</span>
                    </div>
                    <div class="flex flex-wrap justify-between items-end gap-4 mt-2">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Vehicle Management</h1>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Manage your fleet, check availability, and update vehicle details.</p>
                        </div>
                        <div class="flex gap-3">
                            <button class="px-4 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold text-sm shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">upload_file</span>
                                Import
                            </button>
                            <button id="openModalBtn" class="px-4 py-2 rounded-lg bg-primary text-white font-bold text-sm shadow-sm hover:bg-blue-600 transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                Add New Vehicle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark custom-scrollbar p-6">
            <div class="max-w-7xl mx-auto flex flex-col gap-6 pb-12">
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-4">
                    <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                        <div class="relative w-full md:w-96 group">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </span>
                            <input class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm focus:border-primary focus:ring-primary dark:text-white transition-all" placeholder="Search by model, plate, or category..." type="text" />
                        </div>
                        <div class="flex w-full md:w-auto gap-3 overflow-x-auto pb-2 md:pb-0">
                            <select class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-primary py-2.5 pl-3 pr-8 min-w-[140px]">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category->nom) ?>"><?= htmlspecialchars($category->nom) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-700 dark:text-slate-300 focus:border-primary focus:ring-primary py-2.5 pl-3 pr-8 min-w-[140px]">
                                <option value="">All Statuses</option>
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            <button class="p-2.5 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition-colors">
                                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-bold uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4">Vehicle Info</th>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4">License Plate</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Daily Rate</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <?php if (empty($cars)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-5xl">directions_car_filled</span>
                                                <p class="text-slate-500 dark:text-slate-400 text-sm">No vehicles found. Start by adding your first vehicle.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($cars as $car): ?>
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <?php if (!empty($car->image)): ?>
                                                        <img src="<?= htmlspecialchars($car->image) ?>"
                                                            alt="<?= htmlspecialchars($car->marque . ' ' . $car->model) ?>"
                                                            class="size-10 rounded-lg object-cover"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <?php endif; ?>
                                                    <div class="size-10 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 <?= !empty($car->image) ? 'hidden' : '' ?>">
                                                        <span class="material-symbols-outlined">directions_car</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($car->marque . ' ' . $car->model) ?></p>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($car->nom ?? 'Vehicle') ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400"><?= htmlspecialchars($car->nom ?? 'N/A') ?></td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-mono"><?= htmlspecialchars($car->id_car ?? 'N/A') ?></td>
                                            <td class="px-6 py-4">
                                                <?= getStatusBadge($car->status ?? 'available') ?>
                                            </td>
                                            <td class="px-6 py-4 text-slate-900 dark:text-white font-bold">$<?= number_format($car->prix ?? 0, 2) ?></td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button class="view-btn size-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-primary transition-colors"
                                                        title="View Details" data-id="<?= $car->id_car ?>">
                                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                                    </button>
                                                    <button class="edit-btn size-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-primary transition-colors"
                                                        title="Edit Vehicle" data-id="<?= $car->id_car ?>">
                                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                                    </button>
                                                    <button class="delete-btn size-8 rounded-lg flex items-center justify-center text-slate-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-500 transition-colors"
                                                        title="Delete" data-id="<?= $car->id_car ?>">
                                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Showing <span class="font-bold text-slate-900 dark:text-white"><?= count($cars) > 0 ? 1 : 0 ?></span> to <span class="font-bold text-slate-900 dark:text-white"><?= count($cars) ?></span> of <span class="font-bold text-slate-900 dark:text-white"><?= count($cars) ?></span> results
                        </p>
                        <?php if (count($cars) > 10): ?>
                            <div class="flex gap-2">
                                <button class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 disabled:opacity-50 transition-colors" disabled="">
                                    Previous
                                </button>
                                <div class="hidden sm:flex gap-1">
                                    <button class="size-8 rounded-lg bg-primary text-white text-sm font-bold shadow-sm">1</button>
                                </div>
                                <button class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-sm hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">
                                    Next
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Vehicle Modal -->
    <div id="vehicleModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-slate-900/75 dark:bg-slate-950/90 backdrop-blur-sm" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-slate-200 dark:border-slate-800">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-primary to-blue-600 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <span class="material-symbols-outlined">directions_car</span>
                        Add New Vehicle(s)
                    </h3>
                    <button id="closeModalBtn" class="text-white/80 hover:text-white transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="vehicleForm" method="POST" class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <input type="hidden" name="action" value="add">
                    <div id="vehiclesContainer" class="divide-y divide-slate-200 dark:divide-slate-800">
                        <!-- Vehicle Entry Template (First one) -->
                        <div class="vehicle-entry p-6 bg-slate-50 dark:bg-slate-800/50">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white">Vehicle #<span class="vehicle-number">1</span></h4>
                                <button type="button" class="remove-vehicle hidden text-slate-400 hover:text-red-500 transition-colors">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Brand -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">
                                        Brand <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="vehicles[0][marque]" required
                                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                        placeholder="e.g., Tesla, Toyota, BMW">
                                </div>

                                <!-- Model -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">
                                        Model <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="vehicles[0][model]" required
                                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                        placeholder="e.g., Model 3, Camry, X5">
                                </div>

                                <!-- Category -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select name="vehicles[0][id_category]" required
                                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category->id_category ?>"><?= htmlspecialchars($category->nom) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Daily Rate -->
                                <div class="space-y-2">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">
                                        Daily Rate ($) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="vehicles[0][prix]" required min="0" step="0.01"
                                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                        placeholder="85.00">
                                </div>

                                <!-- Image URL -->
                                <div class="space-y-2 md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">
                                        Image URL
                                    </label>
                                    <input type="url" name="vehicles[0][image]"
                                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                        placeholder="https://example.com/car-image.jpg">
                                </div>

                                <!-- Description -->
                                <div class="space-y-2 md:col-span-2">
                                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">
                                        Description
                                    </label>
                                    <textarea name="vehicles[0][description]" rows="3"
                                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none"
                                        placeholder="Enter vehicle description..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Another Vehicle Button -->
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/30">
                        <button type="button" id="addVehicleBtn"
                            class="w-full px-4 py-3 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-primary hover:text-primary hover:bg-primary/5 dark:hover:bg-primary/10 transition-all flex items-center justify-center gap-2 font-medium">
                            <span class="material-symbols-outlined">add_circle</span>
                            Add Another Vehicle
                        </button>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-slate-100 dark:bg-slate-800 px-6 py-4 flex flex-col-reverse sm:flex-row gap-3 sm:justify-between sm:items-center">
                        <button type="button" id="cancelBtn"
                            class="px-6 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            Cancel
                        </button>
                        <div class="flex gap-3">
                            <span class="text-sm text-slate-500 dark:text-slate-400 flex items-center">
                                <span id="vehicleCount">1</span> vehicle(s) to add
                            </span>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-lg bg-primary text-white font-bold text-sm shadow-lg shadow-blue-500/30 hover:bg-blue-600 transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Save Vehicle(s)
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const categories = <?= json_encode($categories) ?>;
        const modal = document.getElementById('vehicleModal');
        const vehiclesContainer = document.getElementById('vehiclesContainer');
        const vehicleCount = document.getElementById('vehicleCount');
        let vehicleIndex = 1;

        document.getElementById('openModalBtn').onclick = () => modal.classList.remove('hidden');

        const closeModal = () => {
            modal.classList.add('hidden');
            document.getElementById('vehicleForm').reset();
            vehiclesContainer.querySelectorAll('.vehicle-entry').forEach((e, i) => i > 0 && e.remove());
            vehicleIndex = 1;
            vehicleCount.textContent = 1;
            vehiclesContainer.querySelector('.remove-vehicle').classList.add('hidden');
        };

        document.getElementById('closeModalBtn').onclick = closeModal;
        document.getElementById('cancelBtn').onclick = closeModal;


        document.getElementById('addVehicleBtn').onclick = () => {
            const entry = document.createElement('div');
            entry.className = 'vehicle-entry p-6 bg-white dark:bg-slate-900';
            entry.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-slate-900 dark:text-white">Vehicle #<span class="vehicle-number">${vehicleIndex + 1}</span></h4>
                    <button type="button" class="remove-vehicle text-slate-400 hover:text-red-500">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Brand <span class="text-red-500">*</span></label>
                        <input type="text" name="vehicles[${vehicleIndex}][marque]" required class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm" placeholder="e.g., Tesla">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Model <span class="text-red-500">*</span></label>
                        <input type="text" name="vehicles[${vehicleIndex}][model]" required class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm" placeholder="e.g., Model 3">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Category <span class="text-red-500">*</span></label>
                        <select name="vehicles[${vehicleIndex}][id_category]" required class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm">
                            <option value="">Select Category</option>
                            ${categories.map(c => `<option value="${c.id_category}">${c.nom}</option>`).join('')}
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Daily Rate ($) <span class="text-red-500">*</span></label>
                        <input type="number" name="vehicles[${vehicleIndex}][prix]" required min="0" step="0.01" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm" placeholder="85.00">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Image URL</label>
                        <input type="url" name="vehicles[${vehicleIndex}][image]" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm" placeholder="https://example.com/car.jpg">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Description</label>
                        <textarea name="vehicles[${vehicleIndex}][description]" rows="3" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-sm" placeholder="Description..."></textarea>
                    </div>
                </div>`;

            entry.querySelector('.remove-vehicle').onclick = () => {
                entry.remove();
                const allEntries = vehiclesContainer.querySelectorAll('.vehicle-entry');
                const total = allEntries.length;

                allEntries.forEach((e, i) => {
                    e.querySelector('.vehicle-number').textContent = i + 1;
                    e.querySelectorAll('input, select, textarea').forEach(inp => {
                        const oldName = inp.name;
                        if (oldName) inp.name = oldName.replace(/\[\d+\]/, `[${i}]`);
                    });
                    e.querySelector('.remove-vehicle').classList.toggle('hidden', total === 1);
                });

                vehicleCount.textContent = total;
            };

            vehiclesContainer.appendChild(entry);
            vehicleIndex++;
            vehicleCount.textContent = vehiclesContainer.querySelectorAll('.vehicle-entry').length;
            vehiclesContainer.querySelectorAll('.remove-vehicle').forEach(btn => btn.classList.remove('hidden'));
        };

        document.getElementById('vehicleForm').onsubmit = (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true;

            fetch('../../Controllers/VehicleController.php', {
                    method: 'POST',
                    body: new FormData(e.target)
                })
                .then(r => r.json())
                .then(d => {
                    btn.disabled = false;
                    if (d.success) {
                        closeModal();
                        location.reload();
                    }
                })
        };

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = function() {
                const fd = new FormData();
                fd.append('action', 'delete');
                fd.append('id_car', this.dataset.id);
                fetch('../../Controllers/VehicleController.php', {
                        method: 'POST',
                        body: fd
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) location.reload();
                    });

            };
        });
    </script>

</body>

</html>