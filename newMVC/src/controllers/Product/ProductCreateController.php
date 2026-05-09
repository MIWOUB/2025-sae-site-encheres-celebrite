<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../model/product.php';
require_once __DIR__ . '/../EmailingController.php';
require_once __DIR__ . '/../../model/pdo.php';
require_once __DIR__ . '/../../model/celebrity.php';

class ProductCreateController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function createProduct(array $user, array $input)
    {
        $pdo = \DatabaseConnection::getConnection();
        $productRepository = new \ProductRepository($pdo);
        $celebrityRepository = new \CelebrityRepository($pdo);

        // var_dump($user);
        $id_user = $user['id_user'];
        // var_dump($input);
        if (!empty($input["nom_annonce_vente"]) && !empty($input["lst_categorie_vente"]) && !empty($input['date_debut']) && !empty($input['date_fin']) && !empty($input['description_produit'])) {
            $title = trim(($input['nom_annonce_vente']));
            $category = trim(($input['lst_categorie_vente']));
            $start_date = trim(($input['date_debut']));
            $end_date = trim(($input['date_fin']));
            $description = trim(($input['description_produit']));
            $celebrite = trim(($input['inputcelebrity']));
            if (isset($input['valeur_reserve'])) {
                $reserve_price = trim(($input['valeur_reserve']));
            } else {
                $reserve_price = null;
            }
        } else {
            throw new Exception("Les données du formulaire sont invalides !");
        }

        // Au cas ou nouvelle categorie ou celebrite
        $categoryStatus = $this->categoryExists($category, $productRepository) ? 1 : 0;
        $celebrityStatus = $this->celebrityExists($celebrite, $celebrityRepository) ? 1 : 0;

        if ($celebrityStatus == 0 || $categoryStatus == 0) {
            $statut = 0;
        } else {
            $statut = 1;
        }

        $id_product = $productRepository->createProduct($title, $description, $start_date, $end_date, $reserve_price, $id_user, $statut);

        //Insert categorie
        if ($categoryStatus == 0) {
            $this->attachCategory($category, $id_product, $productRepository, $categoryStatus);
        } else {
            $productRepository->linkCategoryProduct($id_product, $category);
        }

        //Insert Celebrity
        if ($celebrityStatus == 0) {
            $this->attachCelebrity($celebrite, $id_product, $celebrityRepository, $celebrityStatus);
        } else {
            $celebrityRepository->linkCelebrityProduct($id_product, $celebrite);
        }

        if (!$id_product) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        } else {
            $this->uploadImages($id_product, $productRepository);
            $user_email = $user['email'];
            $user_name = $user['name'];
            $param = [$user_email, $user_name];
            routeurMailing('sendEmailConfirmationPlublish', $param);
            header("Location: index.php?action=user");
            exit();
        }
    }

    private function uploadImages(int $id_product, \ProductRepository $productRepository)
    {
        try {
            //Verification de la présence d'images
            if (!isset($_FILES['image_produit'])) {
                echo ("Erreur : Aucune image sélectionnée.");
                exit();
            }
            // Crée le dossier avec le nom de l'annonce
            $DirAnnonce = __DIR__ . "../../../Annonce/" . $id_product;

            // Vérifie si le dossier existe déjà
            if (!is_dir($DirAnnonce)) {
                //creation du dossier
                mkdir($DirAnnonce, 0777, true);
            } else {
                echo ("Erreur : Le dossier existe déjà.");
                exit();
            }

            // Ajoute les images dans le dossier
            for ($i = 0; $i < count($_FILES["image_produit"]['name']); $i++) {
                $tmpFilePath = $_FILES['image_produit']['tmp_name'][$i];
                if ($tmpFilePath != "") {
                    $newFilePath = $DirAnnonce . "/" . $id_product . "_" . $i . ".jpg";
                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        //Ajouter dans un tableau qui sera inséré en base de données
                        $name_image = $id_product . "_" . $i . ".jpg";
                        $newFilePath = "Annonce/" . $id_product . "/" . $name_image;
                        $productRepository->addImage($id_product, $newFilePath, $name_image);
                    }
                }
            }
            if (isset($_FILES['certificat_autenticite'])) {
                $this->uploadCertificate($id_product, $DirAnnonce);
            }
        } catch (Exception $e) {
            echo ("Erreur lors de l'ajout des images : " . $e->getMessage());
            exit();
        }
    }

    private function uploadCertificate(int $id_annonce, string $DirAnnonce)
    {
        $tmpFilePath = $_FILES['certificat_autenticite']['tmp_name'];
        if ($tmpFilePath != "") {
            $newFilePath = $DirAnnonce . "/" . $id_annonce . "_Certificate" . ".pdf";
            // Fonction native de php pour déplacer les fichier
            move_uploaded_file($tmpFilePath, $newFilePath);
            saveCertificatePath($id_annonce, $newFilePath);
        }
    }

    private function categoryExists(string $saisie, \ProductRepository $productRepository)
    {
        $categories = $productRepository->searchCategories($saisie);
        if ($categories) {
            return true;
        } else {
            return false;
        }
    }

    private function attachCategory(string $categories, int $id_product, \ProductRepository $productRepository, int $categoryStatus)
    {
        try {
            $productRepository->insertCategory($categories, $categoryStatus);
            $productRepository->linkCategoryProduct($id_product, $categories);
        } catch (Exception $e) {
            die("Error en insertion of your categorie" . $e->getMessage());
        }
    }

    private function celebrityExists(string $saisie, \CelebrityRepository $celebrityRepository)
    {
        $celebrite = $celebrityRepository->searchCelebrities($saisie);
        if ($celebrite) {
            return true;
        } else {
            return false;
        }
    }

    private function attachCelebrity(string $celebrite, int $id_product, \CelebrityRepository $celebrityRepository, int $celebrityStatus)
    {
        try {
            $celebrityRepository->insertCelebrity($celebrite, $celebrityStatus);
            $celebrityRepository->linkCelebrityProduct($id_product, $celebrite);
        } catch (Exception $e) {
            die('Error on insertion of your celebrity' . $e->getMessage());
        }
    }
}
