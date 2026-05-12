<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../model/product.php';
require_once __DIR__ . '/../../model/celebrity.php';

class AnnouncementModerationController
{
    public function validateAnnouncement(int $idProduct): array
    {
        if ($idProduct < 0) {
            throw new Exception('Impossible to update product statut !');
        }

        $pdo = \DatabaseConnection::getConnection();
        $productRepository = new \ProductRepository($pdo);
        $celebrityRepository = new \CelebrityRepository($pdo);

        $productRepository->updateStatus($idProduct);
        $productRepository->updateCategoryStatus($idProduct);
        $celebrityRepository->updateCelebrityStatus($idProduct);

        return ['success' => true];
    }
}
