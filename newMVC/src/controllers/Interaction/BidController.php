<?php

require_once("src/model/bid.php");
require_once("src/lib/database.php");
require_once("src/model/product.php");

class BidController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function bid()
    {
        if (!isset($_GET['id']) || $_GET['id'] < 0) {
            echo "invalid";
            exit;
        }

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo "not_logged";
            exit;
        }

        $id_product = (int) $_GET['id'];
        $id_user = (int) $_SESSION['user']['id_user'];
        $newPrice = (int) $_POST['newPrice'];

        $pdo = \DatabaseConnection::getConnection();

        $bidRepository = new \BidRepository($pdo);
        $productRepository = new \ProductRepository($pdo);

        // ❌ utilisateur propriétaire
        if ($bidRepository->sameUser($id_product, $id_user)) {
            echo "same";
            exit;
        }

        // ❌ fin enchère
        $productDate = $bidRepository->getProductDate($id_product);
        $endTimestamp = strtotime($productDate);
        $remaining = $endTimestamp - time();

        if ($remaining <= 0) {
            echo "finished";
            exit;
        }

        // ❌ dernier enchérisseur
        $id_last_bidder = $bidRepository->getLastBidder($id_product);

        if ((int)$id_user === (int)$id_last_bidder) {
            echo "user_not_accepted";
            exit;
        }

        // 🔥 PRIX ACTUEL PROPRE
        $lastPriceData = $productRepository->getLastPrice($id_product);
        $currentPrice = (int) ($lastPriceData['last_price'] ?? 0);

        // si aucun bid → prix de départ
        if ($currentPrice <= 0) {
            $product = $productRepository->getProduct($id_product);
            $currentPrice = (int) $product['reserve_price'];
        }

        // 🔥 DEBUG PROPRE
        error_log("DEBUG BID => new:$newPrice current:$currentPrice");

        // ❌ prix invalide
        if ($newPrice <= $currentPrice) {
            echo "price_not_accepted";
            exit;
        }

        // 🔥 INSERT BID
        $success = $bidRepository->bidProduct($id_product, $id_user, $newPrice);

        if (!$success) {
            echo "not_available";
            exit;
        }

        // 🔥 extension temps si fin proche
        if ($remaining <= 30) {
            $bidRepository->addTime($id_product);
            echo "time_extended";
            exit;
        }

        echo "success";
        exit;
    }
}