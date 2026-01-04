<?php

namespace App\Classes;

use App\Config\Database;
use DateTime;
use PDO;

class Reservation
{
    private $id_reservation;
    private $id_client;
    private $id_car;
    private $date_reservation;
    private $pickupLocation;
    private $retournLocation;
    private $status;
    private $startDate;
    private $endDate;

    public function __construct(
        int $id_client,
        int $id_car,
        DateTime $date_reservation,
        string $pickupLocation,
        string $retournLocation,
        DateTime $startDate,
        DateTime $endDate
    ) {
        $this->id_client = $id_client;
        $this->id_car = $id_car;
        $this->date_reservation = $date_reservation;
        $this->pickupLocation = $pickupLocation;
        $this->retournLocation = $retournLocation;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function reserver(): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'CALL create_reservation(:idcl, :idcr, :dtr, :pl, :rl, :srtd, :endd)';
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([
            ':idcl' => $this->id_client,
            ':idcr' => $this->id_car,
            ':dtr' => $this->date_reservation->format('Y-m-d H:i:s'),
            ':pl' => $this->pickupLocation,
            ':rl' => $this->retournLocation,
            ':srtd' => $this->startDate->format('Y-m-d H:i:s'),
            ':endd' => $this->endDate->format('Y-m-d H:i:s'),
        ])) {
            return true;
        }
        return false;
    }

    public static function accepterReservation(int $id_reservation): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'UPDATE reservation SET `status` = :st  WHERE id_reservation = :idr';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':st' => 'ACCEPTED', ':idr' => $id_reservation]);
    }

    public static function refuserReservation(int $id_reservation): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'UPDATE reservation SET `status` = :st  WHERE id_reservation = :idr';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':st' => 'REJECTED', ':idr' => $id_reservation]);
    }

    public static function getAllReservations(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT * FROM reservation';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
    }
    public static function checkReservation(int $id_client): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT 
                    r.id_reservation,
                    r.id_client,
                    r.id_car,
                    r.date_reservation,
                    r.pickupLocation,
                    r.retournLocation,
                    r.status,
                    r.startDate,
                    r.endDate,
                    c.marque,
                    c.model,
                    c.prix,
                    c.image,
                    cat.nom as category_name,
                    a.id_avis,
                    a.note,
                    a.texte as review_text,
                    a.created_at as review_date
                FROM reservation r
                LEFT JOIN car c ON r.id_car = c.id_car
                LEFT JOIN category cat ON c.id_category = cat.id_category
                LEFT JOIN avis a ON r.id_reservation = a.id_reservation
                WHERE r.id_client = :id_client
                ORDER BY r.date_reservation DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_client' => $id_client]);

        return $stmt->fetchAll(PDO::FETCH_OBJ) ?? null;
    }
    
}
