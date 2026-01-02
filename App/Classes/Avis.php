<?php

namespace App\Classes;

use App\Config\Database;
use PDO;

class Avis
{
    private $id_avis;
    private $note;
    private $texte;
    private $id_reservation;
    private $created_at;
    private $deleted_at;

    public function __construct(int $note, string $texte, int $id_reservation)
    {
        $this->note = $note;
        $this->texte = $note;
        $this->id_reservation = $note;
    }

    public function __get($name)
    {
        return $this->$name;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function addAvis(): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'INSERT INTO avis(note, texte, id_reservation)
                VALUES (:note, :texte, :idrev)';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':note' => $this->note, ':texte' => $this->texte, ':idrev' => $this->id_reservation])
            ? $pdo->lastInsertId() : false;
    }
    public static function updateAvis(int $id_avis, int $note, string $texte): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'UPDATE avis SET note = :note, texte = :txt WHERE id_avis = :idv';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':note' => $note,
            ':txt' => $texte,
            ':idv' => $id_avis
        ]);
    }
    public static function softDeleteAvis(int $id_avis): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'UPDATE avis SET deleted_at = NOW() WHERE id_avis = :idv';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':idv' => $id_avis]);
    }

    public static function getAllAvis(): array
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT a.*, cl.nom AS client, cr.marque AS car 
                FROM avis a
                INNER JOIN reservation r ON a.id_reservation = r.id_reservation
                LEFT JOIN utilisateur cl ON r.id_client = cl.id_utilisateur
                LEFT JOIN car cr ON r.id_car = cr.id_car
                WHERE a.deleted_at IS NULL
                ORDER BY a.created_at DESC';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute() ? $stmt->fetchAll(PDO::FETCH_OBJ) : [];
    }
}
