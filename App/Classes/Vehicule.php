<?php

namespace App\Classes;

use App\Config\Database;
use PDO;
use PDOException;

class Vehicule
{
    private $id_car;
    private $marque;
    private $model;
    private $prix;
    private $image;
    private $status;
    private $id_category;
    private $description;

    public function __construct(string $marque, string $model, float $prix, string $image, string $status, int $id_category, string $description)
    {
        $this->marque = $marque;
        $this->model = $model;
        $this->prix = $prix;
        $this->image = $image;
        $this->status = $status;
        $this->id_category = $id_category;
        $this->description = $description;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
    public function addCar()
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "INSERT INTO car (marque, model, prix, image, status, id_category, description) 
                VALUES (:marque, :model, :prix, :image, :status, :id_category, :description)";

        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([
            ':marque' => $this->marque,
            ':model' => $this->model,
            ':prix' => $this->prix,
            ':image' => $this->image,
            ':status' => $this->status,
            ':id_category' => $this->id_category,
            ':description' => $this->description
        ])) {
            $this->id_car = $pdo->lastInsertId();
            return true;
        }
        return false;
    }

    public static function addMultipleCars(array $cars): bool
    {
        if (empty($cars)) {
            return false;
        }
        $pdo = Database::getInstance()->getConnection();
        $sql = "INSERT INTO car (marque, model, prix, image, id_category, description) 
                VALUES (:marque, :model, :prix, :image, :id_category, :description)";
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare($sql);
            foreach ($cars as $car) {
                $stmt->execute([
                    ':marque' => $car['marque'],
                    ':model' => $car['model'],
                    ':prix' => $car['prix'],
                    ':image' => $car['image'],
                    ':id_category' => $car['id_category'],
                    ':description' => $car['description']
                ]);
            }
            $pdo->commit();
            return true;
        } catch (PDOException) {
            $pdo->rollBack();
            return false;
        }
    }


    public function searchCarByModel(string $model): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT c.*, cat.nom FROM car c LEFT JOIN categroy cat ON c.id_category = cat.id_category 
                WHERE c.model = :mod';
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([':mod' => $model])) {
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return [];
    }

    public static function filterByCategory(string $category): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT c.*, cat.nom FROM car c LEFT JOIN categroy cat ON c.id_category = cat.id_category 
                WHERE cat.nom = :cnom';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':cnom' => $category]) ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
    }

    public static function getCarById(int $id_car): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT c.*, cat.nom FROM car c LEFT JOIN category cat ON c.id_category = cat.id_category WHERE id_car = :idc';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':idc' => $id_car]) ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
    }
    public static function getAllCars(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT c.*, cat.nom FROM car c LEFT JOIN category cat ON c.id_category = cat.id_category';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
    }
    public function updateCar(): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = "UPDATE car SET marque = :marque, model = :model, prix = :prix, 
                image = :image, status = :status, id_category = :id_category, 
                description = :description WHERE id_car = :id_car";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':marque' => $this->marque,
            ':model' => $this->model,
            ':prix' => $this->prix,
            ':image' => $this->image,
            ':status' => $this->status,
            ':id_category' => $this->id_category,
            ':description' => $this->description,
            ':id_car' => $this->id_car
        ]);
    }

    public static function deleteCar(int $id_car): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'DELETE FROM car WHERE id_car = :idc';
        $pdo->prepare($sql)->execute([':idc' => $id_car]);
    }
}
