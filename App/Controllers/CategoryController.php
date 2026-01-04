<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Category;

if (!isset($_SESSION['user']) || $_SESSION['user']->role !== 'Admin') {
    header('Location: ../Views/auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? $_GET['action'] ?? '';

    if ($action == 'addBulk') {
        $categories = $_POST['category'] ?? [];

        $validCategories = array_filter($categories, function ($cat) {
            return !empty(trim($cat['nom'] ?? ''));
        });

        if (empty($validCategories)) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        Category::addBulkCategory($validCategories);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
        
    } elseif ($action == 'update') {
        $id = intval($_POST['id_category']) ?? 0;
        $nom = trim($_POST['nom']) ?? '';
        $description = trim($_POST['description'] ?? '');

        if ($id > 0 && !empty($nom)) {
            $category = new Category($nom, $description);
            $category->updateCategory($id, $nom, $description);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } elseif ($action == 'delete') {
        $id = intval($_POST['id_category'] ?? 0);

        if ($id > 0) {
            Category::deleteCategory($id);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
