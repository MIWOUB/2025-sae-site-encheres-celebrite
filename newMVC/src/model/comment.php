<?php

require_once('src/lib/database.php');

class CommentRepository
{
    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public function getCommentsFromProduct($id_product)
    {
        $sql = "
            SELECT 
                CONCAT(u.firstname, ' ', u.name) AS full_name,
                c.comment,
                c.comment_date,
                c.id_user
            FROM comment c
            JOIN users u ON u.id_user = c.id_user
            WHERE c.id_product = :id_product
            ORDER BY c.comment_date DESC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'id_product' => $id_product
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCommentToProduct($id_product, $id_user, $comment)
    {
        $sql = "
            INSERT INTO comment (id_product, id_user, comment, comment_date)
            VALUES (:id_product, :id_user, :comment, NOW())
        ";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            'id_product' => $id_product,
            'id_user' => $id_user,
            'comment' => $comment
        ]);
    }
}