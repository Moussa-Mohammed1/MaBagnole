<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Vehicule;
use App\Classes\Category;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';

    switch ($action) {
        case 'add':
            if (isset($_POST['vehicles']) && is_array($_POST['vehicles'])) {
                $vehicles = $_POST['vehicles'];
                $carsData = [];
                foreach ($vehicles as $vehicle) {
                    if (
                        empty($vehicle['marque']) || empty($vehicle['model']) ||
                        empty($vehicle['prix']) || empty($vehicle['id_category'])
                    ) {
                        exit();
                    }

                    $carsData[] = [
                        'marque' => trim($vehicle['marque']),
                        'model' => trim($vehicle['model']),
                        'prix' => floatval($vehicle['prix']),
                        'image' => trim($vehicle['image']),
                        'id_category' => intval($vehicle['id_category']),
                        'description' => trim($vehicle['description'] ?? '')
                    ];
                }
                Vehicule::addMultipleCars($carsData);
            }
            break;

        case 'update':

            if (isset($_POST['id_car'])) {
                $vehicle = new Vehicule(
                    trim($_POST['marque']),
                    trim($_POST['model']),
                    floatval($_POST['prix']),
                    trim($_POST['image']),
                    trim($_POST['status']),
                    intval($_POST['id_category']),
                    trim($_POST['description'] ?? '')
                );
                $vehicle->id_car = intval($_POST['id_car']);
                $vehicle->updateCar();
            }
            break;

        case 'delete':
            if (isset($_POST['id_car'])) {
                Vehicule::deleteCar(intval($_POST['id_car']));
            }
            break;
        default:
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'getAll':
            $cars = Vehicule::getAllCars();
            break;

        case 'getById':
            if (isset($_GET['id'])) {
                $car = Vehicule::getCarById(intval($_GET['id']));
            }
            break;

        case 'searchByModel':
            if (isset($_GET['model'])) {
                $vehicle = new Vehicule('', '', 0, '', '', 0, '');
                $cars = $vehicle->searchCarByModel(trim($_GET['model']));
            }
            break;

        case 'filterByCategory':
            if (isset($_GET['category'])) {
                Vehicule::filterByCategory(trim($_GET['category']));
            }
            break;

        default:
            break;
    }
}
