<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Reservation;

session_start();

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_client = $_POST['id_client'];
    $id_car = $_POST['id_car'];
    $pickupLocation = $_POST['pickupLocation'];
    $retournLocation = $_POST['retournLocation'];
    $dateReservation = new DateTime();
    $startDate = new DateTime($_POST['startDate']);
    $endDate = new DateTime($_POST['endDate']);

    $reservation = new Reservation($id_client, $id_car, $dateReservation, $pickupLocation, $retournLocation, $startDate, $endDate);
    $reservation->reserver();
    header('Location: ./../Views/Client/dashboard.php');
    exit();
}
