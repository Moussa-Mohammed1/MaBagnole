<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Reservation;

if (!isset($_SESSION['logged']) || $_SESSION['logged']->role !== 'admin') {
    header('Location: ../Views/auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'accept') {
        $id = intval($_POST['id_reservation'] ?? 0);

        if ($id > 0) {
            Reservation::accepterReservation($id);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    } elseif ($action == 'reject') {
        $id = intval($_POST['id_reservation'] ?? 0);

        if ($id > 0) {
            Reservation::refuserReservation($id);
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
