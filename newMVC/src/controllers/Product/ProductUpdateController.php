<?php

require_once(__DIR__ . '/../../lib/database.php');
require_once(__DIR__ . '/../../model/product.php');
require_once(__DIR__ . '/../../model/celebrity.php');

class ProductUpdateController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function updateProduct(int $id_product, array $input = []): array
    {
        $pdo = \DatabaseConnection::getConnection();
        $productRepository = new \ProductRepository($pdo);
        $celebrityRepository = new \CelebrityRepository($pdo);

        if (!isset($_SESSION['user'])) {
            throw new Exception('Vous devez être connecté pour modifier une annonce.');
        }

        $product = $productRepository->getProduct($id_product);
        if (!$product) {
            throw new Exception('Annonce introuvable.');
        }

        $id_user = (int) $_SESSION['user']['id_user'];
        if (!$productRepository->isProductOwnedByUser($id_product, $id_user)) {
            throw new Exception('Vous n\'avez pas le droit de modifier cette annonce.');
        }

        $title = trim((string) ($input['nom_annonce_vente'] ?? $product['title']));
        $description = trim((string) ($input['description_produit'] ?? $product['description']));
        $start_date = trim((string) ($input['date_debut'] ?? $product['start_date']));
        $end_date = trim((string) ($input['date_fin'] ?? $product['end_date']));
        $reserve_price = isset($input['valeur_reserve']) && $input['valeur_reserve'] !== ''
            ? trim((string) $input['valeur_reserve'])
            : $product['reserve_price'];

        if ($title === '' || $description === '' || $start_date === '' || $end_date === '') {
            throw new Exception('Les champs de modification sont invalides.');
        }

        $startDateObject = new DateTime($start_date);
        $endDateObject = new DateTime($end_date);
        if ($startDateObject >= $endDateObject) {
            throw new Exception('La date de fin doit être postérieure à la date de début.');
        }

        $success = $productRepository->updateProduct(
            $id_product,
            $title,
            $description,
            $startDateObject->format('Y-m-d H:i:s'),
            $endDateObject->format('Y-m-d H:i:s'),
            $reserve_price
        );

        if (!$success) {
            throw new Exception('Impossible de modifier cette annonce.');
        }

        return [
            'success' => true,
            'id_product' => $id_product,
        ];
    }
}
