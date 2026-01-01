<?php
class Category
{
    private $id_category;
    private $nom;
    private $description;

    public function __construct(string $nom, string $description)
    {
        $this->nom = $nom;
        $this->description = $description;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function addCategory(): void
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'INSERT INTO category(nom,descripion)
                VALUES (:nom, :`description`)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $this->nom,
            ':description' => $this->description
        ]);
    }

    public function updateCategory(int $id_category, string $nom, string $description)
    {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'UPDATE category SET nom = :nom, `description` = :descr
                WHERE id_category = :idc';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':descr' => $description,
            ':idc' => $id_category
        ]);
    }

    public function deleteCategory(int $id_category): void {
        $pdo = Database::getInstance()->getConnection();
        $sql = 'DELETE FROM category WHERE id_category = :idc';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':idc' => $id_category]);
    }
}
