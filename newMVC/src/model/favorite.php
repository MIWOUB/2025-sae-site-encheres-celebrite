<?php

require_once('src/lib/database.php');

class FavoriteRepository
{
    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    /* =========================
       AJOUT FAVORI
    ========================= */
    public function setProductFavorite($id_product, $id_user)
    {
        $request = "INSERT INTO interest (id_product, id_user)
                    VALUES (:id_product, :id_user)";

        $stmt = $this->connection->prepare($request);

        return $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);
    }

    /* =========================
       SUPPRIMER FAVORI
    ========================= */
    public function unsetProductFavorite($id_product, $id_user)
    {
        $request = "DELETE FROM interest
                    WHERE id_product = :id_product
                    AND id_user = :id_user";

        $stmt = $this->connection->prepare($request);

        return $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);
    }

    /* =========================
       CHECK FAVORI
    ========================= */
    public function isProductFavorite($id_product, $id_user)
    {
        $request = "SELECT COUNT(*) 
                    FROM interest
                    WHERE id_product = :id_product
                    AND id_user = :id_user";

        $stmt = $this->connection->prepare($request);

        $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);

        return $stmt->fetchColumn() > 0;
    }

    /* =========================
       COMPTER LES LIKES
    ========================= */
    public function getLikes($id_product)
    {
        $request = "SELECT COUNT(*) AS nbLike
                    FROM interest
                    WHERE id_product = :id";

        $stmt = $this->connection->prepare($request);

        $stmt->execute([
            ':id' => $id_product
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================
       FAVORIS USER
    ========================= */
    public function getUserFavorites($id_user)
    {
        $request = "
            SELECT p.*
            FROM interest i
            JOIN product p ON p.id_product = i.id_product
            WHERE i.id_user = :id_user
        ";

        $stmt = $this->connection->prepare($request);

        $stmt->execute([
            ':id_user' => $id_user
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}