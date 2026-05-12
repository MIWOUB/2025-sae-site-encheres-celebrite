<?php

date_default_timezone_set('Europe/Paris');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/src/controllers/Product/ProductCreateController.php';
require_once __DIR__ . '/src/controllers/Auth/LoginController.php';
require_once __DIR__ . '/src/controllers/Auth/LogoutController.php';
require_once __DIR__ . '/src/controllers/Auth/RegisterController.php';
require_once __DIR__ . '/src/controllers/User/ProfileUpdateController.php';
require_once __DIR__ . '/src/controllers/User/UserController.php';
require_once __DIR__ . '/src/controllers/Interaction/FavoriteController.php';
require_once __DIR__ . '/src/controllers/Interaction/BidController.php';
require_once __DIR__ . '/src/controllers/Interaction/CommentController.php';
require_once __DIR__ . '/src/controllers/Product/ProductUpdateController.php';
require_once __DIR__ . '/src/controllers/Product/ProductDeleteController.php';
require_once __DIR__ . '/src/controllers/Product/ProductRepublishController.php';
require_once __DIR__ . '/src/controllers/Product/ProductController.php';
require_once __DIR__ . '/src/controllers/Admin/NewsletterController.php';
require_once __DIR__ . '/src/controllers/Admin/AdminPanelController.php';
require_once __DIR__ . '/src/controllers/Admin/AnnouncementModerationController.php';
require_once __DIR__ . '/src/controllers/Page/HomeController.php';
require_once __DIR__ . '/src/controllers/EmailingController.php';
require_once __DIR__ . '/src/controllers/ViewCounterController.php';

require_once __DIR__ . '/src/lib/auth.php';
require_once __DIR__ . '/src/lib/database.php';
require_once __DIR__ . '/src/model/pdo.php';
require_once __DIR__ . '/src/model/user.php';
require_once __DIR__ . '/src/model/product.php';
require_once __DIR__ . '/src/model/celebrity.php';
require_once __DIR__ . '/src/model/favorite.php';

function renderView(string $templatePath, array $variables = []): void
{
    extract($variables, EXTR_SKIP);
    require __DIR__ . '/' . $templatePath;
}

function redirectTo(string $url): void
{
    header('Location: ' . $url);
    exit();
}

function jsonResponse(mixed $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit();
}

function renderErrorPage(string $message, int $statusCode = 404): void
{
    http_response_code($statusCode);
    $errorMessage = $message;
    require __DIR__ . '/templates/preset/error.php';
    exit();
}

function ensureAdminAccess(bool $expectsJson = false): void
{
    if (isAdmin()) {
        return;
    }

    if ($expectsJson) {
        jsonResponse([
            'success' => false,
            'error' => 'Acces administrateur requis.',
        ], 403);
    }

    requireLogin();

    renderErrorPage('<i class="fa-solid fa-lock"></i> <span>Erreur 403 :</span> Acces administrateur requis.', 403);
}

try {
    $action = $_GET['action'] ?? 'home';

    $routes = [
        'home' => function (): void {
            home();
        },
        'login' => function (): void {
            $controller = new \LoginController();
            if (!empty($_SESSION['user']['DateConnexion'])) {
                $controller->checkconnection($_SESSION['user']['DateConnexion']);
            }
            $_SESSION['show_login_modal'] = true;
            redirectTo('index.php');
        },
        'logout' => function (): void {
            $controller = new \LogoutController();
            $controller->logout();
        },
        'register' => function (): void {
            $_SESSION['show_register_modal'] = true;
            redirectTo('index.php');
        },
        'user' => function (): void {
            if (isset($_GET['id']) && (int) $_GET['id'] >= 0) {
                $pdo = \DatabaseConnection::getConnection();
                $userRepository = new \UserRepository($pdo);
                $productRepository = new \ProductRepository($pdo);

                $userId = (int) $_GET['id'];
                $u = $userRepository->getUser($userId);
                $products = $productRepository->getUserAnnouncements($userId);
                $score = $userRepository->getRatingUser($userId);
                $score = $score ?? 0;

                renderView('templates/user_profile.php', [
                    'u' => $u,
                    'products' => $products,
                    'score' => $score,
                ]);
                return;
            }

            renderView('templates/user.php');
        },
        'sell' => function (): void {
            renderView('templates/sell_product.php');
        },
        'buy' => function (): void {
            renderView('templates/buy.php');
        },
        'favorites' => function (): void {
            renderView('templates/favorites.php');
        },
        'historique_annonces_publiees' => function (): void {
            renderView('templates/historique_annonces_publiees.php');
        },
        'userLogin' => function (): void {
            $controller = new \LoginController();
            $controller->connect($_POST);
        },
        'userRegister' => function (): void {
            $controller = new \RegisterController();
            $controller->register($_POST);
        },
        'update_email' => function (): void {
            updateEmail($_POST['email'] ?? '');
        },
        'update_address' => function (): void {
            updateAddress($_POST);
        },
        'update_password' => function (): void {
            updatePassword($_POST['new_password_2'] ?? '');
        },
        'newsletter' => function (): void {
            $_SESSION['show_newsletter_modal'] = true;
            redirectTo('index.php');
        },
        'subscribeNewsletter' => function (): void {
            subscribeNewsletter($_POST);
        },
        'addProduct' => function (): void {
            requireLogin();

            $controller = new \ProductCreateController();
            $controller->createProduct($_SESSION['user'], $_POST);
        },
        'deleteProduct' => function (): void {
            $idProduct = $_GET['id_product'] ?? $_POST['id_product'] ?? null;

            if ($idProduct !== null && (int) $idProduct > 0) {
                try {
                    $controller = new \ProductDeleteController();
                    $controller->deleteOwnProduct((int) $idProduct);

                    jsonResponse(['success' => true]);
                } catch (Throwable $throwable) {
                    jsonResponse([
                        'success' => false,
                        'error' => $throwable->getMessage(),
                    ], 403);
                }
            }

            throw new Exception('Impossible to delete this product !');
        },
        'validateAnnouncement' => function (): void {
            ensureAdminAccess(true);

            if (!isset($_POST['id_product']) || (int) $_POST['id_product'] < 0) {
                jsonResponse(['success' => false, 'error' => 'Impossible to update product statut !'], 400);
            }

            $controller = new \AnnouncementModerationController();
            $result = $controller->validateAnnouncement((int) $_POST['id_product']);
            jsonResponse($result);
        },
        'updateProduct' => function (): void {
            if (isset($_POST['id_product']) && (int) $_POST['id_product'] >= 0) {
                $controller = new \ProductUpdateController();
                $result = $controller->updateProduct((int) $_POST['id_product'], $_POST);
                jsonResponse($result);
                return;
            }

            throw new Exception('Impossible to update the product !');
        },
        'addComment' => function (): void {
            $controller = new \CommentController();
            $controller->addComment();
        },
        'updateComment' => function (): void {
            $controller = new \CommentController();
            $controller->updateComment();
        },
        'deleteComment' => function (): void {
            $controller = new \CommentController();
            $controller->deleteComment();
        },
        'favorite' => function (): void {
            $controller = new \FavoriteController();
            $controller->favorite();
        },
        'unfavorite' => function (): void {
            $controller = new \FavoriteController();
            $controller->unfavorite();
        },
        'bid' => function (): void {
            $controller = new \BidController();
            $controller->bid();
        },
        'product' => function (): void {
            if (!isset($_GET['id']) || (int) $_GET['id'] <= 0) {
                throw new Exception('ID de produit invalide.');
            }

            $controller = new \ProductController();
            $controller->showProduct((int) $_GET['id']);
        },
        'getLastPrice' => function (): void {
            if (!isset($_GET['id_product']) || (int) $_GET['id_product'] < 0) {
                throw new Exception('ID de produit invalide pour récupérer le dernier prix.');
            }

            $idProduct = (int) $_GET['id_product'];
            $pdo = \DatabaseConnection::getConnection();
            $productRepository = new \ProductRepository($pdo);

            if (!empty($_GET['option'])) {
                jsonResponse($productRepository->getPriceWithOption($idProduct, $_GET['option']));
            }

            $lastPrice = $productRepository->getLastPrice($idProduct);
            if ($lastPrice === false) {
                throw new Exception('Impossible de récupérer le dernier prix pour ce produit.');
            }

            jsonResponse($lastPrice);
        },
        'getGlobalViews' => function (): void {
            if (!isset($_GET['id_product']) || (int) $_GET['id_product'] < 0) {
                throw new Exception('ID de produit invalide pour récupérer les vues globales.');
            }

            $idProduct = (int) $_GET['id_product'];

            if (!empty($_GET['option'])) {
                $pdo = \DatabaseConnection::getConnection();
                $productRepository = new \ProductRepository($pdo);
                jsonResponse($productRepository->getViewsWithOption($idProduct, $_GET['option']));
            }

            $globalViews = getGlobalViews($idProduct);
            if ($globalViews === false) {
                throw new Exception('Impossible de récupérer les vues globales pour ce produit.');
            }

            jsonResponse($globalViews);
        },
        'getLikes' => function (): void {
            if (!isset($_GET['id_product']) || !ctype_digit((string) $_GET['id_product'])) {
                jsonResponse(['error' => 'ID de produit invalide pour récupérer les likes'], 400);
            }

            $pdo = \DatabaseConnection::getConnection();
            $favoriteRepository = new \FavoriteRepository($pdo);

            try {
                jsonResponse($favoriteRepository->getLikes((int) $_GET['id_product']));
            } catch (Throwable $throwable) {
                jsonResponse(['error' => 'Impossible de récupérer les likes'], 500);
            }
        },
        'getImage' => function (): void {
            if (!isset($_GET['id_product']) || (int) $_GET['id_product'] < 0) {
                throw new Exception('ID de produit invalide pour récupérer l\'image.');
            }

            $image = getImage((int) $_GET['id_product']);
            if ($image === false) {
                throw new Exception('Impossible de récupérer l\'image pour ce produit.');
            }

            jsonResponse($image);
        },
        'reservedAnnoncement' => function (): void {
            if (!isset($_GET['id_user']) || (int) $_GET['id_user'] < 0) {
                throw new Exception('Impossible de récupéré l\'indice utilisateur');
            }

            $annoncements = getAnnoncementEndWithReservedPrice((int) $_GET['id_user']);
            if ($annoncements === false) {
                throw new Exception("Impossible d'extraire des annonce finis avec un prix de réserve.");
            }

            jsonResponse($annoncements);
        },
        'LisAnnoncementEnd' => function (): void {
            if (!isset($_GET['id_user']) || (int) $_GET['id_user'] < 0) {
                throw new Exception('Impossible de récupéré l\'indice utilisateur');
            }

            $annoncements = getListFinishedAnnoncements((int) $_GET['id_user']);
            if ($annoncements === false) {
                throw new Exception("Impossible d'extraire des annonce finis.");
            }

            jsonResponse($annoncements);
        },
        'republish' => function (): void {
            $idProduct = $_GET['id_product'] ?? $_POST['id_product'] ?? null;

            if ($idProduct === null || (int) $idProduct <= 0) {
                throw new Exception('Impossible de re-publée l\'annonce');
            }

            try {
                $controller = new \ProductRepublishController();
                $result = $controller->republishProduct((int) $idProduct);
                jsonResponse($result);
            } catch (Throwable $throwable) {
                jsonResponse([
                    'success' => false,
                    'error' => $throwable->getMessage(),
                ], 403);
            }
        },
        'searchCategories' => function (): void {
            if (!isset($_GET['writting'])) {
                jsonResponse([]);
            }

            $pdo = \DatabaseConnection::getConnection();
            $productRepository = new \ProductRepository($pdo);
            jsonResponse($productRepository->searchCategories($_GET['writting']));
        },
        'searchCelebrities' => function (): void {
            if (!isset($_GET['writting'])) {
                jsonResponse([]);
            }

            $pdo = \DatabaseConnection::getConnection();
            $celebrityRepository = new \CelebrityRepository($pdo);
            jsonResponse($celebrityRepository->searchCelebrities($_GET['writting']));
        },
        'admin' => function (): void {
            ensureAdminAccess();
            $controller = new \AdminPanelController();
            $controller->showPanel();
        },
        'sendNewsletter' => function (): void {
            ensureAdminAccess();
            $controller = new \NewsletterController();
            $controller->postNewsletter($_POST);
        },
        'deleteProductAsAdmin' => function (): void {
            ensureAdminAccess(true);

            if (!isset($_POST['id_product']) || (int) $_POST['id_product'] < 0) {
                jsonResponse(['success' => false, 'error' => 'Impossible de supprimer l\'annonce depuis l\'admin.'], 400);
            }

            $controller = new \ProductDeleteController();
            $controller->deleteProductAsAdmin((int) $_POST['id_product']);
            jsonResponse(['success' => true]);
        },
    ];

    if (!isset($routes[$action])) {
        throw new Exception("La page que vous recherchez n'existe pas.");
    }

    $routes[$action]();
} catch (Throwable $throwable) {
    renderErrorPage('<i class="fa-solid fa-bug"></i> <span>Erreur 404 :</span> Page non trouvé !');
}
