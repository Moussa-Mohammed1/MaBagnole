<?php
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

    public function reserver(): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'INSERT INTO reservation(id_client, id_car, date_reservation, pickupLocation, retournLocation, startDate, endDate)
                VALUES (:idcl, :idcr, :dtr, :pl, :rl, :srtd, :endd)';
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
            $this->id_reservation = $pdo->lastInsertId();
            return true;
        }
        return false;
    }

    public function accepterReservation(int $id_reservation): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'UPDATE reservation SET `status` = :st  WHERE id_reservation = :idr';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':st' => 'ACCEPTED', ':idr' => $id_reservation]);
    }

    public function refuserReservation(int $id_reservation): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'UPDATE reservation SET `status` = :st  WHERE id_reservation = :idr';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':st' => 'REJECTED', ':idr' => $id_reservation]);
    }

    public static function getAllReservatio(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT * FROM reservation';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
    }
    
}
