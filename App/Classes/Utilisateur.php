<?php

namespace App\Classes;

use App\Config\Database;
use PDO;

class Utilisateur
{
    protected $id_user;
    protected $nom;
    protected $role;
    protected $email;
    protected $password;

    public function __construct(string $nom, string $email, string $password)
    {
        $this->nom = $nom;
        $this->email = $email;
        $this->password = $password;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public static function validateForm(string $nom, string $email): false
    {
        $errors = [];
        if (empty($nom) || !preg_match('/^[a-zA-Z\s]+$/', $nom)) {
            $errors['nom'] = 'Name must contains letters and spaces and not empty';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        return empty($errors) ? false : $errors;
    }

    public static function login($email, $password): null
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT * FROM utilisateur WHERE email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if (!empty($result) && password_verify($password, $result->password)) {
            return $result;
        } else {
            return null;
        }
    }
    public function signUp(): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'INSERT INTO utilisateur(nom, email, `password`)
                VALUES (:nom, :email, :`password`)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $this->nom,
            ':email' => $this->email,
            ':password' => $this->password
        ]);
    }
}
