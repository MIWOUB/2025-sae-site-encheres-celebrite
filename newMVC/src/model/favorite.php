<?php

require_once('src/lib/database.php');

class FavoriteRepository
{
    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public function setProductFavorite($id_product, $id_user)
    {
        $sql = "INSERT INTO interest (id_product, id_user)
                VALUES (:id_product, :id_user)";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);
    }

    public function unsetProductFavorite($id_product, $id_user)
    {
        $sql = "DELETE FROM interest
                WHERE id_product = :id_product
                AND id_user = :id_user";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);
    }

    public function isProductFavorite($id_product, $id_user)
    {
        $sql = "SELECT COUNT(*) 
                FROM interest
                WHERE id_product = :id_product
                AND id_user = :id_user";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function getLikes($id_product)
    {
        $sql = "SELECT COUNT(*) AS nbLike
                FROM interest
                WHERE id_product = :id";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':id' => $id_product
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserFavorites($id_user)
    {
        $sql = "SELECT p.*
                FROM interest i
                JOIN product p ON p.id_product = i.id_product
                WHERE i.id_user = :id_user";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':id_user' => $id_user
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}