<?php

require_once __DIR__ . '/../../model/product.php';
require_once __DIR__ . '/../../lib/database.php';

class ProductRepublishController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function republishProduct(int $id_product): array
    {
        $pdo = \DatabaseConnection::getConnection();
        $productRepository = new \ProductRepository($pdo);

        if (!isConnected()) {
            throw new Exception('Vous devez être connecté pour republier une annonce.');
        }

        $oldAnnouncement = $productRepository->getProduct($id_product);
        if (!$oldAnnouncement) {
            throw new Exception('Annonce introuvable.');
        }

        $id_user = (int) $_SESSION['user']['id_user'];
        if (!$productRepository->isProductOwnedByUser($id_product, $id_user)) {
            throw new Exception('Vous n\'avez pas le droit de republier cette annonce.');
        }

        $endDate = new DateTime($oldAnnouncement['end_date']);
        if ($endDate > new DateTime()) {
            throw new Exception('Cette annonce est toujours en cours.');
        }

        $lastPrice = $productRepository->getLastPrice($id_product);
        if (!empty($lastPrice['last_price']) && (float) $lastPrice['last_price'] > 0) {
            throw new Exception('Cette annonce a déjà reçu des enchères.');
        }

        $originalStart = new DateTime($oldAnnouncement['start_date']);
        $originalEnd = new DateTime($oldAnnouncement['end_date']);
        $duration = $originalStart->diff($originalEnd);

        $newStart = new DateTime();
        $newEnd = clone $newStart;
        $newEnd->add($duration);

        $success = $productRepository->republishProduct(
            $id_product,
            $newStart->format('Y-m-d H:i:s'),
            $newEnd->format('Y-m-d H:i:s')
        );

        if (!$success) {
            throw new Exception('Impossible de republier cette annonce.');
        }

        return [
            'success' => true,
            'id_product' => $id_product,
        ];
    }
}
