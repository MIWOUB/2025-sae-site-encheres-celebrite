<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../model/product.php';

function getAllAnnouncements(int $id_user)
{
    $pdo = \DatabaseConnection::getConnection();
    $productRepository = new \ProductRepository($pdo);
    $tab_annoncements = $productRepository->getUserAnnouncements($id_user);
    return $tab_annoncements;
}
