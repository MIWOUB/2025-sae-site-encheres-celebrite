<?php

require_once('src/model/comment.php');
require_once('src/lib/database.php');

class CommentController
{
    public function addComment()
    {
        //  Redirection si non connecté
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }

        if (
            !isset($_POST['id']) ||
            !isset($_POST['comment']) ||
            empty(trim($_POST['comment']))
        ) {
            die("Erreur : données du commentaire invalides.");
        }

        $id_product = (int) $_POST['id'];
        $comment = trim($_POST['comment']);
        $id_user = (int) $_SESSION['user']['id_user'];

        try {
            $pdo = DatabaseConnection::getConnection();
            $commentRepository = new CommentRepository($pdo);

            $commentRepository->addCommentToProduct(
                $id_product,
                $id_user,
                $comment
            );

            header("Location: index.php?action=product&id=" . $id_product);
            exit();
        } catch (Exception $e) {
            die("Erreur ajout commentaire : " . $e->getMessage());
        }
    }

    public function updateComment()
    {
        //  Redirection si non connecté
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }

        if (
            !isset($_POST['id']) ||
            !isset($_POST['id_comment']) ||
            !isset($_POST['comment']) ||
            empty(trim($_POST['comment']))
        ) {
            die("Erreur : données du commentaire invalides.");
        }

        $id_product = (int) $_POST['id'];
        $id_comment = (int) $_POST['id_comment'];
        $comment = trim($_POST['comment']);
        $id_user = (int) $_SESSION['user']['id_user'];

        try {
            $pdo = DatabaseConnection::getConnection();
            $commentRepository = new CommentRepository($pdo);

            if (!$commentRepository->isCommentOwnedByUser($id_comment, $id_user)) {
                die("Erreur : vous ne pouvez modifier que vos propres commentaires.");
            }

            $commentRepository->updateComment($id_comment, $id_user, $comment);

            header("Location: index.php?action=product&id=" . $id_product);
            exit();
        } catch (Exception $e) {
            die("Erreur modification commentaire : " . $e->getMessage());
        }
    }

    public function deleteComment()
    {
        //  Redirection si non connecté
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit();
        }

        if (!isset($_POST['id']) || !isset($_POST['id_comment'])) {
            die("Erreur : données du commentaire invalides.");
        }

        $id_product = (int) $_POST['id'];
        $id_comment = (int) $_POST['id_comment'];
        $id_user = (int) $_SESSION['user']['id_user'];

        try {
            $pdo = DatabaseConnection::getConnection();
            $commentRepository = new CommentRepository($pdo);

            if (!$commentRepository->isCommentOwnedByUser($id_comment, $id_user)) {
                die("Erreur : vous ne pouvez supprimer que vos propres commentaires.");
            }

            $commentRepository->deleteComment($id_comment, $id_user);

            header("Location: index.php?action=product&id=" . $id_product);
            exit();
        } catch (Exception $e) {
            die("Erreur suppression commentaire : " . $e->getMessage());
        }
    }
}