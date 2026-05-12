<?php

require_once('src/lib/database.php');

class ProductRepository
{

    private PDO $connection;

    public function __construct(PDO $pdo)
    {
        $this->connection = $pdo;
    }

    function getCategory()
    {
        $pdo = $this->connection;
        $requete = "SELECT * FROM category";
        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute();
        } catch (PDOException $e) {
            die("Error retrieving categories, try again !\nError : " . $e->getMessage());
        }
        return $tmp->fetchAll(PDO::FETCH_ASSOC);
    }


    function getAllProduct()
    {
        $pdo = $this->connection;
        $requete = "SELECT * FROM product";
        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute();
        } catch (PDOException $e) {
            die("Error retrieving products, try again !\nError : " . $e->getMessage());
        }
        return $tmp->fetchAll(PDO::FETCH_ASSOC);
    }

    function getProduct(int $id_product)
    {
        $pdo = $this->connection;

        $requete = "
            SELECT p.*,
                COALESCE(MAX(b.new_price), p.reserve_price) AS current_price
            FROM product p
            LEFT JOIN bid b ON b.id_product = p.id_product
            WHERE p.id_product = ?
            GROUP BY p.id_product
        ";

        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute([$id_product]);
        } catch (PDOException $e) {
            die("Error retrieving product: " . $e->getMessage());
        }

        return $tmp->fetch(PDO::FETCH_ASSOC);
    }

    function getFinishedAnnouncementsByClient(int $id_client)
    {
        $pdo = $this->connection;
        $requete = "
        SELECT
            p.id_product,
            p.title AS titre,
            p.description,
            p.end_date,
            p.reserve_price,
            COALESCE(MAX(b.new_price), p.reserve_price) AS prix_en_cours
        FROM product p
        JOIN published pb ON pb.id_product = p.id_product
        LEFT JOIN bid b ON b.id_product = p.id_product
        WHERE pb.id_user = :id_client
        AND p.end_date < NOW()
        GROUP BY p.id_product, p.title, p.description, p.end_date, p.reserve_price
        ORDER BY p.end_date DESC
    ";
        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute([
                ':id_client' => $id_client
            ]);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des annonces terminées pour le client : " . $e->getMessage());
        }
        return $tmp->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_actual_annonces_by_client(int $id_client)
    {
        $pdo = $this->connection;
        $requete = "
        SELECT
            p.id_product,
            p.title AS titre,
            p.description,
            p.end_date,
            p.reserve_price,
            COALESCE(MAX(b.new_price), p.reserve_price) AS prix_en_cours
        FROM product p
        JOIN published pb ON pb.id_product = p.id_product
        LEFT JOIN bid b ON b.id_product = p.id_product
        WHERE pb.id_user = :id_client
        AND p.end_date >= NOW()
        GROUP BY p.id_product, p.title, p.description, p.end_date, p.reserve_price
        ORDER BY p.end_date ASC
    ";
        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute([
                ':id_client' => $id_client
            ]);
        } catch (PDOException $e) {
            die("Erreur lors de la récupération des annonces en cours pour le client : " . $e->getMessage());
        }
        return $tmp->fetchAll(PDO::FETCH_ASSOC);
    }

    function createProduct(string $title, string $description, string $start_date, string $end_date, ?string $reserve_price, int $id_user, int $status)
    {
        $pdo = $this->connection;

        $requete1 = "INSERT INTO product 
            (title, description, start_date, end_date, reserve_price, status)
            VALUES (:title, :description, :start_date, :end_date, :reserve_price, :status)";

        $requete2 = "INSERT INTO published (id_product, id_user) VALUES (:id_product, :id_user)";

        try {
            $stmt = $pdo->prepare($requete1);
            $stmt->execute([
                ":title" => $title,
                ":description" => $description,
                ":start_date" => $start_date,
                ":end_date" => $end_date,
                ":reserve_price" => $reserve_price,
                ":status" => $status,
            ]);

            $id_product = $pdo->lastInsertId();

            $stmt = $pdo->prepare($requete2);
            $stmt->execute([
                ":id_product" => $id_product,
                ":id_user" => $id_user
            ]);

            return $id_product;
        } catch (PDOException $e) {
            die("Error inserting product: " . $e->getMessage());
        }
    }

    function deleteProduct(int $id_product)
    {
        $pdo = $this->connection;
        $request = "DELETE FROM product WHERE id_product = ?";
        $temp = $pdo->prepare($request);
        $success = $temp->execute([$id_product]);

        return $success;
    }

    function isProductOwnedByUser(int $id_product, int $id_user)
    {
        $pdo = $this->connection;
        $request = "SELECT 1 FROM published WHERE id_product = :id_product AND id_user = :id_user LIMIT 1";

        try {
            $temp = $pdo->prepare($request);
            $temp->execute([
                ':id_product' => $id_product,
                ':id_user' => $id_user,
            ]);
        } catch (PDOException $e) {
            die("Error checking product ownership, try again !\nError : " . $e->getMessage());
        }

        return (bool) $temp->fetchColumn();
    }

    function republishProduct(int $id_product, string $start_date, string $end_date)
    {
        $pdo = $this->connection;
        $request = "UPDATE product
                    SET start_date = :start_date,
                        end_date = :end_date,
                        mailIsSent = 0
                    WHERE id_product = :id_product";

        try {
            $temp = $pdo->prepare($request);
            return $temp->execute([
                ':id_product' => $id_product,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
            ]);
        } catch (PDOException $e) {
            die("Error republishing product, try again !\nError : " . $e->getMessage());
        }
    }

    function updateProduct(int $id_product, string $title, string $description, string $start_date, string $end_date, ?string $reserve_price)
    {
        $pdo = $this->connection;
        $request = "UPDATE product
                    SET title = :title,
                        description = :description,
                        start_date = :start_date,
                        end_date = :end_date,
                        reserve_price = :reserve_price
                    WHERE id_product = :id_product";

        try {
            $temp = $pdo->prepare($request);
            return $temp->execute([
                ':id_product' => $id_product,
                ':title' => $title,
                ':description' => $description,
                ':start_date' => $start_date,
                ':end_date' => $end_date,
                ':reserve_price' => $reserve_price,
            ]);
        } catch (PDOException $e) {
            die("Error updating product, try again !\nError : " . $e->getMessage());
        }
    }

    function addImage(int $id_product, string $path_image, string $name_image)
    {
        $pdo = $this->connection;
        try {
            $requete2 = "INSERT INTO image (id_product, path_image, alt) VALUES (:id_product, :path_image, :name_image)";

            $temp = $pdo->prepare($requete2);
            $temp->execute([
                ":id_product" => $id_product,
                ":path_image" => $path_image,
                ":name_image" => $name_image
            ]);

            return true;
        } catch (PDOException $e) {
            die("Error inserting your image into the database, try again !\nError : " . $e->getMessage());
        }
    }

    function getUserAnnouncements(int $id_client)
    {
        $pdo = $this->connection;
        $request = "SELECT * 
                from product as p 
                join published as pb on pb.id_product = p.id_product
                where pb.id_user = :id_client and p.end_date > date(now())
                ";
        try {
            $temp = $pdo->prepare($request);
            $temp->execute([
                "id_client" => $id_client
            ]);
        } catch (PDOException $e) {
            die("Error on extraction of your announcement" . $e->getMessage());
        }

        return $temp->fetchAll(PDO::FETCH_ASSOC);
    }

    function getLastPrice(int $id_product)
    {
        $pdo = $this->connection;

        $req = $pdo->prepare("
            SELECT MAX(new_price) AS last_price
            FROM bid
            WHERE id_product = :id
        ");

        $req->execute([':id' => $id_product]);

        $data = $req->fetch(PDO::FETCH_ASSOC);

        if (!$data || $data['last_price'] === null) {
            return null;
        }

        return (int)$data['last_price'];
    }
    function getViewsWithOption(int $id_product, string $option)
    {
        $pdo = $this->connection;
        switch ($option) {
            case 'M':
                $requete = "SELECT COUNT(view_number) as value, DATE_FORMAT(view_date, '%Y-%m') AS date FROM productview
                            WHERE id_product = :id
                            GROUP BY MONTH(view_date), YEAR(view_date)
                            ORDER BY view_date ASC;
                    ";
                $temp = $pdo->prepare($requete);
                $temp->execute([
                    ":id" => $id_product
                ]);
                return $temp->fetchall(PDO::FETCH_ASSOC);
            case 'Y':
                $requete = "SELECT COUNT(view_number) as value, YEAR(view_date) as date FROM productview
                            WHERE id_product = :id
                            GROUP BY YEAR(view_date)
                            ORDER BY view_date ASC;
                    ";
                $temp = $pdo->prepare($requete);
                $temp->execute([
                    ":id" => $id_product
                ]);
                return $temp->fetchall(PDO::FETCH_ASSOC);
            default:
                $requete = "SELECT COUNT(view_number) as value, DATE(view_date) as date FROM productview
                            WHERE id_product = :id
                            GROUP BY DATE(view_date), YEAR(view_date)
                            ORDER BY view_date ASC;
                    ";
                $temp = $pdo->prepare($requete);
                $temp->execute([
                    ":id" => $id_product
                ]);
                return $temp->fetchall(PDO::FETCH_ASSOC);
        }
    }

    function getPriceWithOption(int $id_product, string $option)
    {
        $pdo = $this->connection;
        switch ($option) {
            case 'M':
                $requete = "SELECT MAX(new_price) as value, DATE(bid_date) as date FROM bid
                            WHERE id_product = :id 
                            AND MONTH(bid_date) = MONTH(NOW()) 
                            AND YEAR(bid_date) = YEAR(NOW())
                            GROUP BY DATE(bid_date)
                            ORDER BY bid_date ASC;
                    ";
                $temp = $pdo->prepare($requete);
                $temp->execute([
                    ":id" => $id_product
                ]);
                return $temp->fetchall(PDO::FETCH_ASSOC);
            case 'Y':
                $requete = "SELECT MAX(new_price) as value, DATE_FORMAT(bid_date, '%Y-%m') as date FROM bid
                            WHERE id_product = :id
                            GROUP BY MONTH(bid_date), YEAR(bid_date)
                            ORDER BY bid_date ASC;
                    ";
                $temp = $pdo->prepare($requete);
                $temp->execute([
                    ":id" => $id_product
                ]);
                return $temp->fetchall(PDO::FETCH_ASSOC);
            default:
                $requete = "SELECT new_price as value, DATE_FORMAT(bid_date, '%H:%i') as date FROM bid
                            WHERE id_product = :id
                            AND DAY(bid_date) = DAY(NOW()) 
                            AND MONTH(bid_date) = MONTH(NOW()) 
                            AND YEAR(bid_date) = YEAR(NOW())
                            ORDER BY bid_date ASC;
                    ";
                $temp = $pdo->prepare($requete);
                $temp->execute([
                    ":id" => $id_product
                ]);
                return $temp->fetchall(PDO::FETCH_ASSOC);
        }
    }

    /// used for admin
    function getCategoryFromAnnouncement(int $id_product)
    {
        $pdo = $this->connection;
        $requete = "SELECT name 
                    from category as c
                    where c.id_category = (
                        select id_category 
                        from belongsto as b 
                        where b.id_product = :id 
                        LIMIT 1);";
        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute([
                ":id" => $id_product,
            ]);
        } catch (PDOException $e) {
            die("Error on get categorie from a annoncement : " . $e->getMessage());
        }
        return $tmp->fetch(PDO::FETCH_ASSOC);
    }


    // Recherche autonome categorie 
    function searchCategories(string $writting)
    {
        $pdo = connection();
        $requete = "SELECT * from category where name like :writting and statut = 1";
        $tmp = $pdo->prepare($requete);
        $tmp->execute([
            ":writting" => $search = '%' . $writting . '%'
        ]);

        return $tmp->fetchAll(PDO::FETCH_ASSOC);
    }

    function insertCategory(string $name, int $statut)
    {
        $pdo = $this->connection;
        $requete = "insert into category (name, statut) VALUES (:name, :statut);";
        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute([
                ':name' => $name,
                ':statut' => $statut
            ]);
        } catch (PDOException $e) {
            die("Error on insert catégorie from your annoncement :" . $e->getMessage());
        }
    }

    function linkCategoryProduct(int $id_annoncement, string $name)
    {
        $pdo = $this->connection;
        $requete = "INSERT INTO belongsto (id_product, id_category) Values (:id_annoncement, (SELECT id_category from category where name like :name Limit 1));";
        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute([
                ':id_annoncement' => $id_annoncement,
                ":name" => $name
            ]);
        } catch (PDOException $e) {
            die("Error on linking your category to your annoncement :" . $e->getMessage());
        }
    }

    function updateStatus(int $id_product)
    {
        $pdo = $this->connection;
        $requete = "UPDATE product SET status = 1 where id_product = :id";
        try {
            $tmp = $pdo->prepare($requete);
            $succes = $tmp->execute([':id' => $id_product]);
            return $succes;
        } catch (PDOException $e) {
            die("Error on linking your categorie to your annonce : " . $e->getMessage());
        }
    }

    function updateCategoryStatus(int $id_product)
    {
        $pdo = $this->connection;
        $requete = "UPDATE category SET statut = 1 where id_category = (SELECT id_category from belongsto where id_product = :id)";
        try {
            $tmp = $pdo->prepare($requete);
            $succes = $tmp->execute([':id' => $id_product]);
            return $succes;
        } catch (PDOException $e) {
            die("Error on updating your category statut : " . $e->getMessage());
        }
    }

    function deleteCategory(int $id_product, string $nameCategory)
    {
        $pdo = $this->connection;
        $requete2 = "DELETE from category where name = :nameC";

        try {
            $tmp2 = $pdo->prepare($requete2);
            $tmp2->execute([
                ':nameC' => $nameCategory
            ]);
        } catch (PDOException $e) {
            die("Error on deleting Category and his link to annoncement : " . $e->getMessage());
        }
    }

    public function getImages(int $id_product): array
    {
        $pdo = $this->connection;
        $requete = "SELECT path_image as url_image, alt from image where id_product = :id";
        try {
            $tmp = $pdo->prepare($requete);
            $tmp->execute([
                ":id" => $id_product
            ]);
        } catch (PDOException $e) {
            die("Error retrieving images: " . $e->getMessage());
        }
        return $tmp->fetchAll(PDO::FETCH_ASSOC);
    }
}
