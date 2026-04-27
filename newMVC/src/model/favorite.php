<?php

require_once('src/lib/database.php');

class FavoriteRepository
{
    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

<<<<<<< HEAD
    /* =========================
       AJOUT FAVORI
    ========================= */
    public function setProductFavorite($id_product, $id_user)
    {
        $request = "INSERT INTO interest (id_product, id_user)
                    VALUES (:id_product, :id_user)";

        $stmt = $this->connection->prepare($request);

        return $stmt->execute([
=======
    function setProductFavorite($id_product, $id_user)
    {
        $pdo = $this->connection;
        $request = "INSERT INTO Interest(id_product, id_user) VALUES (:id_product, :id_user)";
        $temp = $pdo->prepare($request);
        $success = $temp->execute([
>>>>>>> 777ee3b3419f331434f0d8bee093c182a382a947
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);

        return $success;
    }

<<<<<<< HEAD
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
=======
    function isProductFavorite($id_product, $id_user)
    {
        $pdo = $this->connection;
        $request = "SELECT COUNT(*) FROM interest WHERE id_product = :id_product AND id_user = :id_user";
        $temp = $pdo->prepare($request);
        $temp->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);
        $success = $temp->fetchColumn();
>>>>>>> 777ee3b3419f331434f0d8bee093c182a382a947

        return $success > 0;
    }

    function unsetProductFavorite($id_product, $id_user)
    {
        $pdo = $this->connection;
        $request = "DELETE FROM Interest WHERE id_product = :id_product AND id_user = :id_user";
        $temp = $pdo->prepare($request);
        $success = $temp->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user
        ]);

        return $success;
    }

<<<<<<< HEAD
    /* =========================
       COMPTER LES LIKES
    ========================= */
    public function getLikes($id_product)
    {
        if (!is_numeric($id_product)) {
            throw new InvalidArgumentException("ID produit invalide");
        }

        $request = "SELECT COUNT(*) AS nbLike
                    FROM interest
                    WHERE id_product = :id";

        $stmt = $this->connection->prepare($request);

        $stmt->execute([
            ':id' => (int)$id_product
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
=======
    function getLikes($id_product)
    {
        $pdo = connection();
        $requete = " SELECT COUNT(*) as nbLike from interest where id_product = :id ";
        $temp = $pdo->prepare($requete);
        $temp->execute([
            ":id" => $id_product
        ]);
        return $temp->fetch(PDO::FETCH_ASSOC);
>>>>>>> 777ee3b3419f331434f0d8bee093c182a382a947
    }

    /* =========================
       🔥 NOUVEAU : FAVORIS USER
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