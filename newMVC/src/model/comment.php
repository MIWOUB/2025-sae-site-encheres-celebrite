<?php

require_once('src/lib/database.php');

class CommentRepository
{
    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    public function getCommentsFromProduct(int $id_product)
    {
        $sql = "
            SELECT 
                c.id_comment,
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

    public function isCommentOwnedByUser(int $id_comment, int $id_user): bool
    {
        $sql = "
            SELECT 1
            FROM comment
            WHERE id_comment = :id_comment
              AND id_user = :id_user
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'id_comment' => $id_comment,
            'id_user' => $id_user,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function addCommentToProduct(int $id_product, int $id_user, string $comment)
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

    public function updateComment(int $id_comment, int $id_user, string $comment): bool
    {
        $sql = "
            UPDATE comment
            SET comment = :comment
            WHERE id_comment = :id_comment
              AND id_user = :id_user
        ";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            'id_comment' => $id_comment,
            'id_user' => $id_user,
            'comment' => $comment,
        ]);
    }

    public function deleteComment(int $id_comment, int $id_user): bool
    {
        $sql = "
            DELETE FROM comment
            WHERE id_comment = :id_comment
              AND id_user = :id_user
        ";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            'id_comment' => $id_comment,
            'id_user' => $id_user,
        ]);
    }
}
