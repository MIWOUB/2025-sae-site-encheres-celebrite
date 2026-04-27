<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../model/product.php';

function get_all_annoncement($id_user)
{
    $pdo = \DatabaseConnection::getConnection();
    $productRepository = new \ProductRepository($pdo);
    $tab_annoncements = $productRepository->get_Annonce_User($id_user);
    return $tab_annoncements;
}
