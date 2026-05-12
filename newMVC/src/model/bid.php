<?php

require_once('src/lib/database.php');
require_once('src/model/product.php');

class BidRepository
{
    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    // =========================
    // INSERT BID (SAFE)
    // =========================
    function bidProduct(int $id_product, int $id_user, int $newPrice, ?int $currentPrice = null)
    {
        $pdo = $this->connection;

        // 🔥 récupération safe du prix actuel
        if ($currentPrice === null) {

            $productRepository = new \ProductRepository($this->connection);

            // produit
            $product = $productRepository->getProduct($id_product);

            if (!$product) {
                return false;
            }

            $currentPrice = (int) $product['reserve_price'];

            // dernier bid
            $last = $productRepository->getLastPrice($id_product);

            if (is_array($last) && isset($last['last_price']) && $last['last_price'] !== null) {
                $currentPrice = (int) $last['last_price'];
            }
        }

        $request = "
            INSERT INTO bid
            (id_product, id_user, current_price, new_price, bid_date)
            VALUES (:id_product, :id_user, :current_price, :new_price, NOW())
        ";

        $stmt = $pdo->prepare($request);

        return $stmt->execute([
            ':id_product' => $id_product,
            ':id_user' => $id_user,
            ':current_price' => $currentPrice,
            ':new_price' => $newPrice
        ]);
    }

    // =========================
    // LAST BIDDER SAFE
    // =========================
    function getLastBidder(int $id_product)
    {
        $pdo = $this->connection;

        $request = "
            SELECT id_user
            FROM bid
            WHERE new_price = (
                SELECT MAX(new_price)
                FROM bid
                WHERE id_product = ?
            )
            LIMIT 1
        ";

        $stmt = $pdo->prepare($request);
        $stmt->execute([$id_product]);

        $result = $stmt->fetchColumn();

        return $result ?: null;
    }

    // =========================
    // PRODUCT END DATE
    // =========================
    function getProductDate(int $id_product)
    {
        $pdo = $this->connection;

        $request = "SELECT end_date FROM product WHERE id_product = ?";

        $stmt = $pdo->prepare($request);
        $stmt->execute([$id_product]);

        $result = $stmt->fetchColumn();

        return $result ?: null;
    }

    // =========================
    // EXTEND TIME
    // =========================
    function addTime(int $id_product)
    {
        $pdo = $this->connection;

        $request = "
            UPDATE product
            SET end_date = DATE_ADD(end_date, INTERVAL 30 SECOND)
            WHERE id_product = ?
        ";

        $stmt = $pdo->prepare($request);

        return $stmt->execute([$id_product]);
    }

    // =========================
    // CHECK OWNER
    // =========================
    function sameUser(int $id_product, int $id_user)
    {
        $pdo = $this->connection;

        $request = "
            SELECT id_user
            FROM published
            WHERE id_product = ?
            LIMIT 1
        ";

        $stmt = $pdo->prepare($request);
        $stmt->execute([$id_product]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || !isset($result['id_user'])) {
            return false;
        }

        return (int)$id_user === (int)$result['id_user'];
    }
}
