<?php
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Category;

if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'Admin') {
    header('Location: ../auth/login.php');
    exit;
}

$logged = $_SESSION['user'];
$categories = Category::getAllCategories();

$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Category Management - MaBagnole</title>
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
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors group" href="#">
                <span class="material-symbols-outlined text-slate-500 dark:text-slate-400 group-hover:text-primary">directions_car</span>
                <span class="text-sm font-medium">Vehicles</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-400 transition-colors" href="#">
                <span class="material-symbols-outlined fill-1">category</span>
                <span class="text-sm font-bold">Categories</span>
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
                        <a class="text-slate-500 dark:text-slate-400 hover:text-primary" href="#">Management</a>
                        <span class="text-slate-400 dark:text-slate-600">/</span>
                        <span class="text-slate-900 dark:text-white font-medium">Categories</span>
                    </div>
                    <div class="flex flex-wrap justify-between items-end gap-4 mt-2">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Category Management</h1>
                            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Create, update, or remove vehicle categories to organize your fleet.</p>
                        </div>
                        <button class="px-4 py-2 rounded-lg bg-primary text-white font-bold text-sm shadow-sm hover:bg-blue-600 transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                            New Category
                        </button>
                    </div>
                </div>
            </div>
        </header>
        <?php if ($successMessage): ?>
            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 mx-6 mt-4 rounded" role="alert">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">check_circle</span>
                    <p class="font-medium"><?= htmlspecialchars($successMessage) ?></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 mx-6 mt-4 rounded" role="alert">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">error</span>
                    <p class="font-medium"><?= htmlspecialchars($errorMessage) ?></p>
                </div>
            </div>
        <?php endif; ?>
        <div class="flex-1 overflow-y-auto bg-background-light dark:bg-background-dark custom-scrollbar">
            <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col gap-8 pb-32">
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
                    <div class="flex items-center justify-between mb-5 border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-primary/10 rounded-lg">
                                <span class="material-symbols-outlined text-primary">edit_square</span>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold">Add New Category</h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Define a new vehicle classification.</p>
                            </div>
                        </div>
                        <button class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                            <span class="material-symbols-outlined">expand_less</span>
                        </button>
                    </div>
                    <form method="POST" action="../../Controllers/CategoryController.php?action=addBulk">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="categoryForms">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Category Name</label>
                                <input name="category[0][nom]" required class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-primary focus:ring-primary dark:text-white" placeholder="e.g. Premium SUV" type="text" />
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                                <textarea name="category[0][description]" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-primary focus:ring-primary dark:text-white resize-none" placeholder="Brief description of the vehicle category..." rows="1"></textarea>
                            </div>
                        </div>
                        <div id="additionalCategories"></div>
                        <div class="mt-4 flex gap-3">
                            <button type="button" id="plusCategory" class="px-4 py-2 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                Add Another Category
                            </button>
                            <button class="flex-1 px-4 py-2 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-sm hover:bg-slate-800 dark:hover:bg-slate-200 transition-colors shadow-sm" type="submit">
                                Create Categories
                            </button>
                        </div>
                    </form>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden flex-1">
                    <div class="p-4 md:p-6 border-b border-slate-200 dark:border-slate-800 flex flex-wrap gap-4 justify-between items-center bg-white dark:bg-slate-900 sticky top-0 z-10">
                        <div>
                            <h3 class="text-lg font-bold">Existing Categories</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Manage your active fleet classifications.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                                <input class="pl-9 pr-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm focus:ring-primary focus:border-primary w-64" placeholder="Search categories..." type="text" />
                            </div>
                            <button class="p-2 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                <span class="material-symbols-outlined">filter_list</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 font-bold uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-4">Category Name</th>
                                    <th class="px-6 py-4">Code</th>
                                    <th class="px-6 py-4">Vehicles</th>
                                    <th class="px-6 py-4">Avg. Rate</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-5xl">inbox</span>
                                                <p class="text-slate-500 dark:text-slate-400">No categories found. Create your first category above.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php
                                    $iconColors = [
                                        ['bg' => 'bg-indigo-100 dark:bg-indigo-900/30', 'text' => 'text-indigo-600 dark:text-indigo-400', 'icon' => 'directions_car'],
                                        ['bg' => 'bg-sky-100 dark:bg-sky-900/30', 'text' => 'text-sky-600 dark:text-sky-400', 'icon' => 'commute'],
                                        ['bg' => 'bg-orange-100 dark:bg-orange-900/30', 'text' => 'text-orange-600 dark:text-orange-400', 'icon' => 'sports_motorsports'],
                                        ['bg' => 'bg-purple-100 dark:bg-purple-900/30', 'text' => 'text-purple-600 dark:text-purple-400', 'icon' => 'electric_car'],
                                        ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-600 dark:text-green-400', 'icon' => 'airport_shuttle'],
                                    ];
                                    foreach ($categories as $index => $category):
                                        $colorScheme = $iconColors[$index % count($iconColors)];
                                    ?>
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-lg <?= $colorScheme['bg'] ?> <?= $colorScheme['text'] ?> flex items-center justify-center">
                                                        <span class="material-symbols-outlined"><?= $colorScheme['icon'] ?></span>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($category->nom) ?></p>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($category->description ?? 'No description') ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-mono text-xs">CAT-<?= str_pad($category->id_category, 3, '0', STR_PAD_LEFT) ?></td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                                                    - Units
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-medium">-</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button onclick="openEditModal(<?= $category->id_category ?>, '<?= htmlspecialchars(addslashes($category->nom)) ?>', '<?= htmlspecialchars(addslashes($category->description ?? '')) ?>')"
                                                    class="text-slate-400 hover:text-primary transition-colors p-1">
                                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                                </button>
                                                <form method="POST" action="../../Controllers/CategoryController.php?action=delete" class="inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                    <input type="hidden" name="id_category" value="<?= $category->id_category ?>">
                                                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors p-1 ml-1">
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-800 p-4 flex items-center justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Total: <?= count($categories) ?> categor<?= count($categories) !== 1 ? 'ies' : 'y' ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-2xl max-w-md w-full mx-4 border border-slate-200 dark:border-slate-800">
            <div class="p-6 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Edit Category</h3>
                    <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
            </div>
            <form id="editForm" method="POST" action="../../Controllers/CategoryController.php?action=update" class="p-6">
                <input type="hidden" name="id_category" id="editId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Category Name</label>
                        <input name="nom" id="editNom" required class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-primary focus:ring-primary dark:text-white" type="text" />
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                        <textarea name="description" id="editDescription" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-primary focus:ring-primary dark:text-white resize-none" rows="3"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-primary text-white font-bold text-sm hover:bg-blue-600 transition-colors">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let categoryIndex = 1;
        const plusButton = document.getElementById('plusCategory');
        const additionalCategories = document.getElementById('additionalCategories');

        plusButton.addEventListener('click', function() {
            const newCategoryForm = document.createElement('div');
            newCategoryForm.className = 'grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg relative';
            newCategoryForm.innerHTML = `
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Category Name</label>
                    <input name="category[${categoryIndex}][nom]" required class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-primary focus:ring-primary dark:text-white" placeholder="e.g. Compact Car" type="text" />
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                    <textarea name="category[${categoryIndex}][description]" class="w-full rounded-lg border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:border-primary focus:ring-primary dark:text-white resize-none" placeholder="Brief description..." rows="1"></textarea>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                </button>
            `;
            additionalCategories.appendChild(newCategoryForm);
            categoryIndex++;
        });

        function openEditModal(id, nom, description) {
            document.getElementById('editId').value = id;
            document.getElementById('editNom').value = nom;
            document.getElementById('editDescription').value = description;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }


        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</body>

</html>