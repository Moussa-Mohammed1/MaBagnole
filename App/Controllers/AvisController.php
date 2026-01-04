<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Avis;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    $role = trim($_POST['role_user']) ?? null;
    $id_client = $_POST['id_client'];
    $id_car = $_POST['id_car'];
    if ($role == 'client') {
        switch ($action) {
            case 'add':
                if (isset($_POST['note'], $_POST['texte'], $_POST['id_reservation'])) {
                    $note = intval($_POST['note']);
                    $texte = trim($_POST['texte']);
                    $id_reservation = intval($_POST['id_reservation']);

                    if ($note < 1 || $note > 5 || empty($texte)) {
                        header("Location: " . $_SERVER['HTTP_REFERER']);
                        exit();
                    }

                    $avis = new Avis($id_client, $id_car, $note, $texte, $id_reservation);
                    $avis->addAvis();
                }
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();

            case 'update':
                if (isset($_POST['id_avis'], $_POST['note'], $_POST['texte'])) {
                    $id_avis = intval($_POST['id_avis']);
                    $note = intval($_POST['note']);
                    $texte = trim($_POST['texte']);

                    if ($note < 1 || $note > 5 || empty($texte)) {
                       header("Location: " . $_SERVER['HTTP_REFERER']);
                        exit();
                    }

                    Avis::updateAvis($id_avis, $note, $texte);
                }
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();

            case 'delete':
                if (isset($_POST['id_avis'])) {
                    Avis::softDeleteAvis(intval($_POST['id_avis']));
                }
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();

            default:
                break;
        }
    } else {
        if (isset($_POST['id_avis'])) {
            Avis::softDeleteAvis(intval($_POST['id_avis']));
        }
        header('Location: ./../Views/Admin/dashboard.php');
        exit();
    }
}
