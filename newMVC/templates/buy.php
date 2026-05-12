<?php
$title = "Page d'achats";
$style = "templates/Style/buy.css";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<?php ob_start(); ?>

<?php include('preset/header.php'); ?>

<main>

    <?php if (empty($products)): ?>
        <p class="error-message">Aucune annonce disponible pour le moment.</p>
    <?php else: ?>
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <?php foreach ($products as $p): ?>
                    <a href="index.php?action=product&id=<?= htmlspecialchars($p['id_product']) ?>"
                        class="swiper-slide swiper-slide-link">

                        <div class="image-container">
                            <?php if (!empty($p['image_url'])): ?>
                                <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="Image annonce">
                            <?php else: ?>
                                <div class="no-image-placeholder">Aucune image disponible</div>
                            <?php endif; ?>

                            <div class="text-content-overlay">
                                <h3><?= htmlspecialchars($p['title']) ?></h3>
                                <p><?= htmlspecialchars($p['celebrity_name'] ?? 'Non spécifiée') ?></p>
                                <p>Prix actuel : <?= htmlspecialchars((string) $p['current_price']) ?> €</p>
                                <p class="timer" data-end="<?= htmlspecialchars($p['end_date']) ?>"></p>
                            </div>
                        </div>

                    </a>
                <?php endforeach; ?>
            </div>

            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    <?php endif; ?>

    <div class="searchbar">
        <div class="searchbar-input-wrap">
            <input type="text" id="searchInput" placeholder="Rechercher une annonce..." autocomplete="off">
            <div id="suggestions"></div>
        </div>
        <a class="searchbar_btn" id="searchButton">Rechercher</a>
    </div>

    <div class="content">
        <h1>Nos annonces</h1>

        <div class="announces">
            <?php if (empty($products)): ?>
                <p class="error-message">Aucune annonce disponible pour le moment.</p>
            <?php else: ?>

                <?php
                $count_displayed = 0;
                $max_to_display = 100;
                ?>

                <?php foreach ($products as $p): ?>
                    <?php if ($count_displayed >= $max_to_display) break; ?>

                    <div class="announce-card">

                        <div class="card-top">
                            <?php if (!empty($p['image_url'])): ?>
                                <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="Image annonce">
                            <?php else: ?>
                                <div class="no-image">Aucune image disponible</div>
                            <?php endif; ?>
                            <h3><?= htmlspecialchars($p['title']) ?></h3>
                        </div>

                        <div class="card-bottom">

                            <div class="card-meta">
                                <p class="card-celebrity">Célébrité: <?= htmlspecialchars($p['celebrity_name'] ?? 'Non spécifiée') ?></p>
                                <p class="card-price">Prix: <?= htmlspecialchars((string) $p['current_price']) ?> €</p>
                                <p class="timer" data-end="<?= htmlspecialchars($p['end_date']) ?>"></p>
                            </div>

                            <div class="card-actions">
                                <button class="fav-btn" data-id="<?= $p['id_product'] ?>" data-isfav="false">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <a class="main_btn" href="index.php?action=product&id=<?= $p['id_product'] ?>">Enchérir</a>
                            </div>

                        </div>

                    </div>

                    <?php $count_displayed++; ?>
                <?php endforeach; ?>

                <?php if ($count_displayed === 0): ?>
                    <p class="error-message">Aucune annonce active n'est disponible pour le moment.</p>
                <?php endif; ?>

            <?php endif; ?>
        </div>

        <div class="text-newletters">
            <p>Ne ratez aucune annonce !</p>
            <p>Abonnez-vous dès maintenant et gratuitement à nos newsletters !</p>
            <a class="main_btn" href="index.php?action=newsletter">S'abonner</a>
        </div>
    </div>
</main>
<?php include('preset/footer.php'); ?>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="templates/JS/timer.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.swiper-slide-link');

        new window.Swiper('.mySwiper', {
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
            loop: slides.length > 2,
            slidesPerView: 1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        document.querySelectorAll('.timer').forEach(el => {
            startCountdown(el.getAttribute('data-end'), el);
        });
    });
</script>

<script>
    document.getElementById('searchButton').addEventListener('click', async function() {
        const q = document.getElementById('searchInput').value.trim();
        if (q.length < 2) return;

        const response = await fetch("src/controllers/search.php?q=" + encodeURIComponent(q));
        const results = await response.json();

        const container = document.querySelector(".announces");
        container.innerHTML = "";

        if (results.length === 0) {
            container.innerHTML = "<p>Aucun résultat trouvé.</p>";
        } else {
            results.forEach(p => {
                const card = document.createElement("div");
                card.classList.add("announce-card");

                let imageHtml = '<div class="no-image">Aucune image disponible</div>';
                if (p.image_url) imageHtml = `<img src="${p.image_url}" alt="Image annonce">`;

                card.innerHTML = `
                    <div class="card-top">
                        ${imageHtml}
                        <h3>${p.title}</h3>
                    </div>
                    <div class="card-bottom">
                        <p>Prix : ${p.price ?? '-'} €</p>
                        <p class="timer" data-end="${p.end_date}"></p>
                        <p><time class="local-date" data-local-datetime="${p.end_date}"></time></p>
                        <a class="main_btn" href="index.php?action=product&id=${p.id}">Voir</a>
                    </div>
                `;
                container.appendChild(card);
            });

            formatLocalDateTime(container);

            document.querySelectorAll('.timer').forEach(el => {
                startCountdown(el.getAttribute('data-end'), el);
            });
        }

        const box = document.getElementById('suggestions');
        box.style.display = 'none';
        box.innerHTML = '';
    });

    const searchInput = document.getElementById('searchInput');
    const suggestionsBox = document.getElementById('suggestions');

    searchInput.addEventListener('keyup', async function() {
        const q = this.value.trim();

        if (q.length < 2) {
            suggestionsBox.style.display = 'none';
            return;
        }

        try {
            const response = await fetch("src/model/suggestion.php?q=" + encodeURIComponent(q));
            const results = await response.json();

            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'block';

            if (results.length === 0) {
                suggestionsBox.innerHTML = '<div class="suggestion-empty">Aucun résultat</div>';
                return;
            }

            results.forEach(item => {
                const div = document.createElement('div');
                div.classList.add('suggestion-item');

                let typeText = '';
                if (item.type === 'product') typeText = 'produit';
                else if (item.type === 'category') typeText = 'catégorie';
                else if (item.type === 'celebrity') typeText = 'célébrité';

                div.textContent = `${item.title} dans ${typeText}`;

                div.onclick = () => {
                    if (item.type === 'product') {
                        window.location.href = "index.php?action=product&id=" + item.product_id;
                    } else if (item.type === 'category') {
                        loadCategory(item.category_id);
                        suggestionsBox.style.display = 'none';
                        searchInput.value = item.title;
                    } else if (item.type === 'celebrity') {
                        loadCelebrity(item.celebrity_id);
                        suggestionsBox.style.display = 'none';
                        searchInput.value = item.title;
                    }
                };

                suggestionsBox.appendChild(div);
            });

        } catch (err) {
            console.error(err);
            suggestionsBox.innerHTML = '<div class="suggestion-error">Erreur lors de la recherche</div>';
        }
    });

    async function loadCategory(categoryId) {
        const response = await fetch(
            "src/controllers/ProductFilterByCategoryController.php?id=" + categoryId
        );
        const products = await response.json();

        const container = document.querySelector('.announces');
        container.innerHTML = '';

        if (products.length === 0) {
            container.innerHTML = "<p>Aucune annonce.</p>";
            return;
        }

        products.forEach(p => {
            container.innerHTML += renderProductCard(p);
        });

        document.querySelectorAll('.timer').forEach(el => {
            startCountdown(el.getAttribute('data-end'), el);
        });
    }

    async function loadCelebrity(celebrityId) {
        const response = await fetch(
            "src/controllers/ProductFilterByCelebrityController.php?id=" + celebrityId
        );
        const products = await response.json();

        const container = document.querySelector('.announces');
        container.innerHTML = '';

        if (products.length === 0) {
            container.innerHTML = "<p>Aucune annonce.</p>";
            return;
        }

        products.forEach(p => {
            container.innerHTML += renderProductCard(p);
        });

        document.querySelectorAll('.timer').forEach(el => {
            startCountdown(el.getAttribute('data-end'), el);
        });
    }

    function renderProductCard(p) {
        let imageHtml = '<div class="no-image">Aucune image disponible</div>';

        if (p.images && p.images.length > 0) {
            imageHtml = `<img src="${p.images[0].url_image}" alt="Image annonce">`;
        }

        return `
            <div class="announce-card">
                <div class="card-top">
                    ${imageHtml}
                    <h3>${p.title}</h3>
                </div>
                <div class="card-bottom">
                    <p class="timer" data-end="${p.end_date}"></p>
                    <a class="main_btn" href="index.php?action=product&id=${p.id_product ?? p.id}">Voir</a>
                </div>
            </div>
        `;
    }
</script>

<?php
$content = ob_get_clean();
require('preset/layout.php');
?>