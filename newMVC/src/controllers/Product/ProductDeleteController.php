<?php

require_once(__DIR__ . '/../../lib/database.php');
require_once(__DIR__ . '/../../model/product.php');
require_once(__DIR__ . '/../../model/celebrity.php');

class ProductDeleteController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function deleteProductAsAdmin(int $id_product)
    {
        $pdo = \DatabaseConnection::getConnection();
        $productRepository = new \ProductRepository($pdo);
        $celebrityRepositiory = new \CelebrityRepository($pdo);

        //Recupération avant suppression de l'annonce
        $categoryName = $productRepository->getCategoryFromAnnouncement($id_product);
        $celebrityName = $celebrityRepositiory->getCelebrityFromAnnouncement($id_product);

        //Annonce
        $productRepository->deleteProduct($id_product);

        //Cateogrie
        $productRepository->deleteCategory($id_product, $categoryName['name']);

        //Celebrity
        $celebrityRepositiory->deleteCelebrity($id_product, $celebrityName['name']);
    }

    public function deleteOwnProduct(int $id_product)
    {
        if (!isset($_SESSION['user'])) {
            throw new Exception('Vous devez être connecté pour supprimer une annonce.');
        }

        $pdo = \DatabaseConnection::getConnection();
        $productRepository = new \ProductRepository($pdo);

        $id_user = (int) $_SESSION['user']['id_user'];
        $id_product = (int) $id_product;

        if ($id_product <= 0) {
            throw new Exception('ID de produit invalide.');
        }

        if (!$productRepository->isProductOwnedByUser($id_product, $id_user)) {
            throw new Exception('Vous n\'avez pas le droit de supprimer cette annonce.');
        }

        //Annonce
        $success = $productRepository->deleteProduct($id_product);

        if (!$success) {
            throw new Exception('Impossible de supprimer cette annonce.');
        }
    }
}
