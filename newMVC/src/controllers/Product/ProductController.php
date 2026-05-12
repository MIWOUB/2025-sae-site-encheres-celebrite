<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../lib/auth.php';
require_once __DIR__ . '/../../model/product.php';
require_once __DIR__ . '/../../model/comment.php';
require_once __DIR__ . '/../../model/favorite.php';

class ProductController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function showProduct(int $id_product)
    {
        if (!isset($id_product) || $id_product <= 0) {
            throw new Exception("Produit invalide.");
        }

        $pdo = \DatabaseConnection::getConnection();

        $productRepository = new \ProductRepository($pdo);
        $commentRepository = new \CommentRepository($pdo);
        $favoriteRepository = new \FavoriteRepository($pdo);

        // PRODUIT
        $p = $productRepository->getProduct($id_product);

        if (!$p) {
            throw new Exception("Ce produit n'existe pas.");
        }

        // IMPORTANT : compatibilité vue
        $product = $p;

        // VUE (désactivé temporairement)
        // AddNewView($p);

        // COMMENTAIRES
        $comments = $commentRepository->getCommentsFromProduct($id_product);

        // PRIX ACTUEL
        $current_price = $productRepository->getLastPrice($id_product)
            ?? ($p['start_price'] ?? 0);

        // IMAGES
        $images = getImage($id_product);

        // LIKES
        $likeData = $favoriteRepository->getLikes($id_product);
        $like = $likeData['nbLike'] ?? 0;

        // FAVORI USER
        $isFav = false;
        if (isConnected()) {
            $isFav = $favoriteRepository->isProductFavorite(
                $id_product,
                $_SESSION['user']['id_user']
            );
        }

        // VIEW
        require("templates/product.php");
    }
}
