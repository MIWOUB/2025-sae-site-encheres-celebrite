<?php
$pdo = DatabaseConnection::getConnection();
$favoriteRepository = new FavoriteRepository($pdo);

$userId = $_SESSION['user']['id_user'];

$favorites = $favoriteRepository->getUserFavorites($userId);
?>

<h1>Mes favoris</h1>

<?php if (empty($favorites)): ?>
    <p>Aucun favori</p>
<?php else: ?>
    <?php foreach ($favorites as $f): ?>
        <div>
            <h3><?= htmlspecialchars($f['title']) ?></h3>
        </div>
    <?php endforeach; ?>
<?php endif; ?>