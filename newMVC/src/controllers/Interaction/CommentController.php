<?php

require_once('src/model/comment.php');
require_once('src/lib/database.php');

function addComment()
{
    // Vérifie si utilisateur connecté
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?action=connection');
        exit();
    }

    // Vérifie les données POST
    if (
        !isset($_POST['id']) ||
        !isset($_POST['comment']) ||
        empty(trim($_POST['comment']))
    ) {
        die("Erreur : données du commentaire invalides.");
    }

    $id_product = (int) $_POST['id'];
    $comment = trim($_POST['comment']);
    $id_user = $_SESSION['user']['id_user'];

    try {

        $pdo = \DatabaseConnection::getConnection();
        $commentRepository = new \CommentRepository($pdo);

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