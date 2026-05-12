<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../model/pdo.php';
require_once __DIR__ . '/../../model/product.php';
require_once __DIR__ . '/../../model/celebrity.php';

class AdminPanelController
{
    public function showPanel(): void
    {
        $pdo = \DatabaseConnection::getConnection();
        $productRepository = new \ProductRepository($pdo);
        $celebrityRepository = new \CelebrityRepository($pdo);

        $products = getAllProduct_admin();
        $productsWithMeta = [];

        foreach ($products as $product) {
            $images = getImage((int) $product['id_product']);
            $category = $productRepository->getCategoryFromAnnouncement((int) $product['id_product']);
            $celebrity = $celebrityRepository->getCelebrityFromAnnouncement((int) $product['id_product']);

            $product['image_url'] = !empty($images) && isset($images[0]['url_image']) ? $images[0]['url_image'] : null;
            $product['category_name'] = $category['name'] ?? 'Non specifiee';
            $product['celebrity_name'] = $celebrity['name'] ?? 'Non specifiee';

            $productsWithMeta[] = $product;
        }

        renderView('templates/admin_panel.php', [
            'productsWithMeta' => $productsWithMeta,
        ]);
    }
}
