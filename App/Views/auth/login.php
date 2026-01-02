<?php
session_start();
$error = $_SESSION['error'] ?? '';
$old = $_SESSION['old'] ?? [];
$empty = $_SESSION['empty'] ?? '';
session_unset();
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - MaBagnole</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
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
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-[#0d131c] dark:text-white flex flex-col h-screen overflow-hidden">
    <!-- Top Navigation -->
    <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-[#e7ecf3] dark:border-gray-800 bg-white dark:bg-[#1a202c] px-4 lg:px-6 py-2 sticky top-0 z-50 shrink-0">
        <div class="flex items-center gap-3 text-[#0d131c] dark:text-white">

            <h2 class="text-base font-bold leading-tight tracking-[-0.015em]">MaBagnole <span class="text-red-500 text-lg">.</span></h2>
        </div>
        <div class="flex flex-1 justify-end gap-8">
            
            <div class="flex gap-2">
                <a href="./register.php" class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-3 bg-primary text-white text-xs font-bold leading-normal tracking-[0.015em] hover:bg-blue-600 transition-colors">
                    <span class="truncate">Sign Up</span>
                </a>
            </div>
        </div>
    </header>
    <!-- Main Content Split Layout -->
    <main class="flex-1 flex w-full overflow-hidden">
        <!-- Left Side: Image/Brand -->
        <div class="hidden lg:block w-1/2 relative bg-gray-100 overflow-hidden">
            <img alt="Modern silver sports car parked on a scenic mountain road at sunset" class="absolute inset-0 w-full h-full object-cover" data-alt="Modern silver sports car parked on a scenic mountain road at sunset" src="https://i.pinimg.com/736x/fe/d1/b3/fed1b3844d38e8b87fd8958f406fff70.jpg" />
            <blockquote class="max-w-md">
                <p class="text-2xl font-bold mb-4">"The best way to predict the future is to create it. Start your journey with MaBagnole."</p>
                <footer class="text-white/80 font-medium">— The MaBagnole Team</footer>
            </blockquote>
        </div>
        </div>
        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white dark:bg-[#1a202c] p-4 sm:p-6 overflow-hidden">
            <div class="w-full max-w-[440px] flex flex-col gap-4">
                <!-- Heading -->
                <div class="flex flex-col gap-1">
                    <h1 class="text-[#0d131c] dark:text-white tracking-light text-2xl font-bold leading-tight">Welcome back</h1>
                    <p class="text-[#4b6c9b] dark:text-gray-400 text-xs font-normal leading-normal">Please enter your details to access your rentals.</p>
                </div>
                <!-- Form -->
                <form class="flex flex-col gap-3 mt-1" action="./../../Controllers/AuthController.php?action=login" method="POST">
                    <!-- Email Field -->
                    <p class="text-red-500 text-xs"><?= isset($errors) ? $errors : '' ?></p>
                    <p class="text-red-500 text-xs"><?= isset($empty) ? $empty : '' ?></p>

                    <label class="flex flex-col w-full">
                        <p class="text-[#0d131c] dark:text-gray-200 text-xs font-medium leading-normal pb-1">Email Address</p>
                        <input required class="form-input flex w-full resize-none overflow-hidden rounded-lg text-[#0d131c] dark:text-white dark:bg-[#2d3748] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#cfd9e8] dark:border-gray-600 focus:border-primary h-9 placeholder:text-[#4b6c9b] dark:placeholder:text-gray-500 px-3 py-2 text-sm font-normal leading-normal transition-all" placeholder="name@example.com" type="email" value="<?= !empty($old) ? $old['email'] : '' ?>" name="email" />
                    </label>
                    <!-- Password Field -->
                    <label class="flex flex-col w-full">
                        <div class="flex justify-between items-center pb-1">
                            <p class="text-[#0d131c] dark:text-gray-200 text-xs font-medium leading-normal">Password</p>
                        </div>
                        <div class="flex w-full items-stretch rounded-lg relative">
                            <input
                                class="form-input flex w-full resize-none overflow-hidden rounded-lg text-[#0d131c] dark:text-white dark:bg-[#2d3748] focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#cfd9e8] dark:border-gray-600 focus:border-primary h-9 placeholder:text-[#4b6c9b] dark:placeholder:text-gray-500 px-3 py-2 pr-10 text-sm font-normal leading-normal transition-all" placeholder="Enter your password"
                                type="password" value="" name="password" />
                            <button class="absolute right-0 top-0 bottom-0 px-3 flex items-center justify-center text-[#4b6c9b] hover:text-primary transition-colors cursor-pointer" type="button">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </label>
                    <!-- Forgot Password Link -->
                    <div class="flex justify-end">
                        <a class="text-primary text-xs font-medium leading-normal hover:underline" href="#">Forgot Password?</a>
                    </div>
                    <!-- Submit Button -->
                    <button class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-9 px-4 bg-primary hover:bg-blue-600 text-white text-sm font-bold leading-normal tracking-[0.015em] transition-all shadow-md hover:shadow-lg mt-1">
                        Log In
                    </button>
                </form>
                <!-- Divider -->
                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                    <span class="flex-shrink-0 mx-3 text-gray-400 text-xs">Not in a race yet</span>
                    <div class="flex-grow border-t border-gray-200 dark:border-gray-700"></div>
                </div>
                <!-- Social Login -->

                <!-- Footer Sign Up -->
                <div class="text-center mt-2">
                    <p class="text-[#4b6c9b] dark:text-gray-400 text-xs font-normal">
                        Don't have an account?
                        <a class="text-primary font-bold hover:underline" href="./register.php">Sign up for free</a>
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>

</html>