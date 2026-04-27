<?php

require_once("src/model/favorite.php");
require_once("src/lib/database.php");

function unfavorite()
{
    if (!isset($_GET['id']) || $_GET['id'] <= 0) {
        echo "error";
        exit;
    }

    if (!isset($_SESSION['user'])) {
        echo "not_logged";
        exit;
    }

    $id_product = $_GET['id'];
    $id_user = $_SESSION['user']['id_user'];

    $pdo = DatabaseConnection::getConnection();
    $favoriteRepository = new FavoriteRepository($pdo);

    if (!$favoriteRepository->isProductFavorite($id_product, $id_user)) {
        echo "not_favorite";
        exit;
    }

    $success = $favoriteRepository->unsetProductFavorite($id_product, $id_user);

    echo $success ? "ok" : "error";
    exit;
}