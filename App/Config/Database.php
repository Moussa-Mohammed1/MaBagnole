<?php

namespace App\Config;

use PDO;

class Database
{
    private static ?Database $instance = null;
    private PDO $connexion;
    private function __construct()
    {
        $this->connexion = new PDO('mysql:host=' . Config::HOST . ';dbname=' . Config::DB_NAME, Config::USER, Config::PASSWORD);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connexion;
    }
    private function __clone() {}
}
