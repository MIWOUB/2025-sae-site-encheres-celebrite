<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../model/product.php';
require_once __DIR__ . '/../../model/celebrity.php';

class BuyController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showBuyPage(): void
    {
        $pdo = \DatabaseConnection::getConnection();
        $productRepository = new \ProductRepository($pdo);
        $celebrityRepository = new \CelebrityRepository($pdo);

        $activeProducts = array_values(array_filter(
            $productRepository->getAllProduct(),
            static fn(array $product): bool => new DateTime($product['end_date']) > new DateTime()
        ));

        $products = array_map(static function (array $product) use ($productRepository, $celebrityRepository): array {
            $images = $productRepository->getImages((int) $product['id_product']);
            $lastPrice = $productRepository->getLastPrice((int) $product['id_product']);
            $celebrity = $celebrityRepository->getCelebrityFromAnnouncement((int) $product['id_product']);

            $product['image_url'] = !empty($images) ? $images[0]['url_image'] : null;
            $product['current_price'] = $lastPrice ?? $product['start_price'];
            $product['celebrity_name'] = $celebrity['name'] ?? 'Non spécifiée';

            return $product;
        }, $activeProducts);

        renderView('templates/buy.php', [
            'products' => $products,
        ]);
    }
}
