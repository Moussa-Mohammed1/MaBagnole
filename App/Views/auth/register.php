<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$error = $_SESSION['error'] ?? '';
$old = $_SESSION['old'] ?? [];

session_unset();
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>MaBagnole - Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;family=Noto+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
                        "display": ["Plus Jakarta Sans", "Noto Sans", "sans-serif"]
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
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#0d131c] dark:text-white font-display h-screen flex flex-col overflow-hidden">
    <!-- Top Navigation Bar -->
    <header class="flex sticky top-0 items-center justify-between whitespace-nowrap border-b border-solid border-b-[#e7ecf3] dark:border-b-slate-800 px-4 lg:px-6 py-2 bg-white dark:bg-[#101722] z-20 shrink-0">
        <div class="flex items-center gap-4 text-[#0d131c] dark:text-white">

            <h2 class="text-[#0d131c] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">MaBagnole.</h2>
        </div>
        <a href="./login.php" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-primary/10 dark:bg-primary/20 hover:bg-primary/20 dark:hover:bg-primary/30 text-primary text-sm font-bold leading-normal tracking-[0.015em] transition-colors">
            <span class="truncate">Log in</span>
        </a>
    </header>
    <!-- Main Content Area: Split Screen -->
    <main class="flex flex-1 w-full overflow-hidden">
        <!-- Left Side: Hero Image -->
        <div class="hidden lg:block lg:w-1/2 xl:w-7/12 relative bg-slate-100 dark:bg-slate-900 overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105" data-alt="Modern sports car driving on a scenic road with mountains in the background" style='background-image: url("https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1983&auto=format&fit=crop");'>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent flex flex-col justify-end p-12 text-white">
                <blockquote class="max-w-lg">
                    <p class="text-2xl font-bold mb-4">"The best way to predict the future is to create it. Start your journey with the perfect ride today."</p>
                    <footer class="flex items-center gap-2 text-white/80">
                        <span class="material-symbols-outlined filled text-[20px]">verified</span>
                        <span class="text-sm font-medium">Verified MaBagnole Quality</span>
                    </footer>
                </blockquote>
            </div>
        </div>
        <!-- Right Side: Registration Form -->
        <div class="w-full lg:w-1/2 xl:w-5/12 bg-white dark:bg-[#101722] flex flex-col overflow-hidden">
            <div class="flex-1 flex flex-col justify-center px-4 py-4 sm:px-8 lg:px-12 max-w-[640px] mx-auto w-full">
                <!-- Header Section -->
                <div class="flex flex-col gap-1 mb-4">
                    <h1 class="text-[#0d131c] dark:text-white tracking-tight text-2xl font-black leading-tight sm:text-3xl">
                        Create your account
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-normal leading-normal">
                        Join MaBagnole and start your journey today.
                    </p>
                </div>
                <div class="relative mb-3">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#e7ecf3] dark:border-slate-800"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="bg-white dark:bg-[#101722] px-2 text-slate-500 dark:text-slate-400">Or continue with email</span>
                    </div>
                </div>
                <!-- Form -->
                <form class="flex flex-col gap-3" action="./../../Controllers/AuthController.php?action=register" method="POST">
                    <!-- Full Name -->
                    <label class="flex flex-col w-full">
                        <p class="text-[#0d131c] dark:text-white text-xs font-medium leading-normal pb-1">Full Name</p>
                        <div class="relative">
                            <input class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#0d131c] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#cfd9e8] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:border-primary h-9 placeholder:text-[#4b6c9b] dark:placeholder:text-slate-500 px-3 py-2 text-sm font-normal leading-normal transition-all" placeholder="e.g. John Doe" type="text" name="nom" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" />
                            <span class="material-symbols-outlined absolute right-3 top-2 text-slate-400 text-[18px] pointer-events-none">person</span>
                        </div>
                        <?php if (isset($errors['nom'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $errors['nom'] ?></p>
                        <?php endif; ?>
                    </label>
                    <!-- Email -->
                    <label class="flex flex-col w-full">
                        <p class="text-[#0d131c] dark:text-white text-xs font-medium leading-normal pb-1">Email Address</p>
                        <div class="relative">
                            <input class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#0d131c] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#cfd9e8] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:border-primary h-9 placeholder:text-[#4b6c9b] dark:placeholder:text-slate-500 px-3 py-2 text-sm font-normal leading-normal transition-all" placeholder="name@example.com" type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" />
                            <span class="material-symbols-outlined absolute right-3 top-2 text-slate-400 text-[18px] pointer-events-none">mail</span>
                        </div>
                        <?php if (isset($errors['email'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </label>
                    <!-- Password Group -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="flex flex-col w-full">
                            <p class="text-[#0d131c] dark:text-white text-xs font-medium leading-normal pb-1">Password</p>
                            <div class="relative group">
                                <input class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#0d131c] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#cfd9e8] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:border-primary h-9 placeholder:text-[#4b6c9b] dark:placeholder:text-slate-500 px-3 py-2 text-sm font-normal leading-normal transition-all pr-9" placeholder="Create password" type="password" name="password" />
                                <button class="absolute right-2 top-2 text-slate-400 hover:text-primary transition-colors cursor-pointer" type="button">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </div>
                            <?php if (isset($errors['password'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $errors['password'] ?></p>
                            <?php endif; ?>
                        </label>
                        <label class="flex flex-col w-full">
                            <p class="text-[#0d131c] dark:text-white text-xs font-medium leading-normal pb-1">Confirm Password</p>
                            <div class="relative group">
                                <input class="form-input flex w-full min-w-0 resize-none overflow-hidden rounded-lg text-[#0d131c] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/20 border border-[#cfd9e8] dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:border-primary h-9 placeholder:text-[#4b6c9b] dark:placeholder:text-slate-500 px-3 py-2 text-sm font-normal leading-normal transition-all pr-9" placeholder="Confirm password" type="password" name="confirm_password" />
                                <button class="absolute right-2 top-2 text-slate-400 hover:text-primary transition-colors cursor-pointer" type="button">
                                    <span class="material-symbols-outlined text-[18px]">visibility_off</span>
                                </button>
                            </div>
                            <?php if (isset($errors['confirm_password'])): ?>
                                <p class="text-red-500 text-xs mt-1"><?= $errors['confirm_password'] ?></p>
                            <?php endif; ?>
                        </label>
                    </div>
                    <!-- Password Strength Meter (Visual only) -->
                    <div class="flex gap-1 h-1 w-full mt-[-4px] px-1">
                        <div class="h-full w-1/4 rounded-full bg-primary"></div>
                        <div class="h-full w-1/4 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                        <div class="h-full w-1/4 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                        <div class="h-full w-1/4 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                    </div>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-[-6px] px-1">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</p>
                    <!-- Terms Checkbox -->
                    <div class="flex items-start gap-2 mt-1">
                        <div class="flex items-center h-4">
                            <input class="w-3.5 h-3.5 border border-slate-300 rounded bg-slate-50 focus:ring-2 focus:ring-primary/20 text-primary cursor-pointer transition-colors dark:bg-slate-800 dark:border-slate-600" id="terms" type="checkbox" />
                        </div>
                        <label class="text-xs font-medium text-slate-600 dark:text-slate-300" for="terms">
                            I agree to the <a class="text-primary hover:text-blue-600 hover:underline" href="#">Terms of Service</a> and <a class="text-primary hover:text-blue-600 hover:underline" href="#">Privacy Policy</a>.
                        </label>
                    </div>
                    <!-- Submit Button -->
                    <button class="mt-2 flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-primary hover:bg-blue-600 text-slate-50 text-sm font-bold leading-normal tracking-[0.015em] transition-all shadow-md hover:shadow-lg" type="submit">
                        <span class="truncate">Create Account</span>
                    </button>
                    <!-- Login Link -->
                    <p class="text-center text-xs font-medium text-slate-600 dark:text-slate-400 mt-1">
                        Already have an account?
                        <a class="text-primary font-bold hover:text-blue-600 transition-colors" href="./login.php">Log in</a>
                    </p>
                </form>
            </div>
            <!-- Simple Footer inside the scrollable area -->
            <footer class="p-2 text-center text-[10px] text-slate-400 dark:text-slate-600">
                © 2024 MaBagnole Inc. All rights reserved.
            </footer>
        </div>
    </main>
</body>

</html>