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
        $request = "INSERT INTO interest (id_product, id_user) VALUES (:id_product, :id_user)";
        $stmt = $this->connection->prepare($request);

        return $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);
    }

    public function isProductFavorite($id_product, $id_user)
    {
        $request = "SELECT COUNT(*) FROM interest WHERE id_product = :id_product AND id_user = :id_user";
        $stmt = $this->connection->prepare($request);

        $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function unsetProductFavorite($id_product, $id_user)
    {
        $request = "DELETE FROM interest WHERE id_product = :id_product AND id_user = :id_user";
        $stmt = $this->connection->prepare($request);

        return $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);
    }

    public function getLikes($id_product)
    {
        if (!is_numeric($id_product)) {
            throw new InvalidArgumentException("ID produit invalide");
        }

        $request = "SELECT COUNT(*) AS nbLike FROM interest WHERE id_product = :id";
        $stmt = $this->connection->prepare($request);

        $stmt->execute([
            ':id' => (int)$id_product
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}