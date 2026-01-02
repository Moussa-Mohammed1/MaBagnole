<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Utilisateur;

session_start();

$action = $_GET['action'] ?? '';
$old = [];

if ($action == 'login') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $old = $_POST;
        $email = trim($_POST['email']) ?? '';
        $password = trim($_POST['password']) ?? '';

        if (!empty($email) && !empty($password)) {
            $user = Utilisateur::login($email, $password);

            if ($user !== null) {
                $_SESSION['logged'] = $user;

                if ($user->role == 'admin') {
                    header('Location: ./../Views/Admin/dashboard.php');
                } else {
                    header('Location: ./../Views/Client/cars.php');
                }
                exit();
            } else {
                $_SESSION['old'] = $old;
                $_SESSION['error'] = 'Email or password are incorrect';
                header('Location: ./../Views/auth/login.php');
                exit();
            }
        } elseif (empty($password)) {
            $_SESSION['empty'] = 'Email or password cannot be empty';
            $_SESSION['old'] = $old;

            header('Location: ./../Views/auth/login.php');
            exit();
        }
    }
} elseif ($action == 'register') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $old = $_POST;
        $nom = trim($_POST['nom']) ?? '';
        $email = trim($_POST['email']) ?? '';
        $password = trim($_POST['password']) ?? '';

        $checkValidation = Utilisateur::validateForm($nom, $email);

        if ($checkValidation !== null) {
            $_SESSION['errors'] = $checkValidation;
            $_SESSION['old'] = $old;
            header('Location: ./../Views/auth/register.php');
            exit();
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $newUser = new Utilisateur($nom, $email, $hashedPassword);
            $newUser->signUp();
            header('Location: ./../Views/auth/login.php');
            exit();
        }
    }
}
