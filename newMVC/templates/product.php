<?php
$title = "Page du produit";
$style = "templates/style/product.css";
$script = "templates/JS/favorite.js";
?>

<?php ob_start(); ?>

<?php include('preset/header.php'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="templates/style/stylePopup.css" rel="stylesheet" />

<div id="popup"></div>
<div id="toastBox"></div>

<script src="https://kit.fontawesome.com/645d3e5fd2.js" crossorigin="anonymous"></script>

<?php
$lastPrice = $productRepository->getLastPrice($p['id_product']);
$current_price = $lastPrice ?? (int) $p['reserve_price'];
?>

<main>
    <div style="text-align: center;">
        <h1><?= mb_convert_encoding(html_entity_decode($p['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), 'UTF-8', 'UTF-8') ?>
        </h1>
    </div>

    <div class="product-header">

        <div class="timer-box">

            <h3 class="header-title">Temps restant :</h3>

            <p class="timer" data-end="<?= htmlspecialchars($p['end_date']) ?>">
            </p>

            <p class="end-date">
                Fini le
                <time class="local-date" data-local-datetime="<?= htmlspecialchars($p['end_date']) ?>"></time>
            </p>

        </div>

        <div class="product-price">

            <?php if ($current_price === null)
                $current_price = $p['start_price']; ?>

            <h3 class="header-title">Offre actuelle :</h3>

            <span>
                <?= htmlspecialchars(number_format($current_price, 0, ',', ' ')) ?> €
            </span>

        </div>

        <button class="main_btn" id="bid_button" type="button" onclick="ouvrirPopup('BidForm')">
            Enchérir
        </button>

    </div>

    <input id="currentPrice" type="hidden" value="<?= (int) $current_price ?>">
    <input id="idProduct" type="hidden" value="<?= (int) $p['id_product'] ?>">

    <div class="product-layout">

        <!-- IMAGES -->
        <div class="container">
            <?php if (empty($images)) { ?>
                <p>Aucune image disponible pour cette annonce.</p>
            <?php } else { ?>
                <div class="swiper mySwiper2">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) { ?>
                            <div class="swiper-slide">
                                <img src="<?= $image['url_image'] ?>">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <div thumbsSlider="" class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) { ?>
                            <div class="swiper-slide">
                                <img src="<?= $image['url_image'] ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- DESCRIPTION -->
        <section id="product-description">
            <h2>Description</h2>
            <p><?= mb_convert_encoding(html_entity_decode(strip_tags($p['description']), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 'UTF-8', 'UTF-8') ?>
            </p>
        </section>
    </div>

    <!-- COMMENTAIRES -->
    <section class="product-title">
        <hr>
        <h2>Commentaires</h2>
        <hr>
    </section>

    <section id="product-comment">

        <?php $currentUserId = $_SESSION['user']['id_user'] ?? null; ?>

        <?php foreach ($comments as $comment) { ?>
            <div class="comment-item" data-comment-id="<?= $comment['id_comment'] ?>">

                <h3>
                    <a href="index.php?action=user&id=<?= $comment['id_user'] ?>">
                        <?= htmlspecialchars($comment['full_name']) ?>
                    </a>
                    <time data-local-datetime="<?= $comment['comment_date'] ?>"></time>
                </h3>

                <p class="comment-text"><?= htmlspecialchars(strip_tags($comment['comment'])) ?></p>

                <?php if ($currentUserId && (int) $currentUserId === (int) $comment['id_user']) { ?>
                    <div class="comment-actions">
                        <button class="btn-edit" onclick="enableEdit(this)">Modifier</button>
                        <form method="POST" action="index.php?action=deleteComment">
                            <input type="hidden" name="id" value="<?= $p['id_product'] ?>">
                            <input type="hidden" name="id_comment" value="<?= $comment['id_comment'] ?>">
                            <button class="btn-delete" type="submit">Supprimer</button>
                        </form>
                    </div>

                    <form class="edit-form" method="POST" action="index.php?action=updateComment">
                        <input type="hidden" name="id" value="<?= $p['id_product'] ?>">
                        <input type="hidden" name="id_comment" value="<?= $comment['id_comment'] ?>">
                        <textarea name="comment" required><?= htmlspecialchars($comment['comment']) ?></textarea>
                        <div class="edit-buttons">
                            <button type="submit" class="btn-save">Enregistrer</button>
                            <button type="button" class="btn-cancel" onclick="cancelEdit(this)">Annuler</button>
                        </div>
                    </form>
                <?php } ?>

            </div>
        <?php } ?>

        <form id="comment-form" method="POST" action="index.php?action=addComment">
            <input type="hidden" name="id" value="<?= $p['id_product'] ?>">
            <textarea name="comment" placeholder="Laissez un commentaire !" required></textarea>
            <button type="submit">Publier</button>
        </form>

    </section>

</main>

<?php include('preset/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });

    var swiper2 = new Swiper(".mySwiper2", {
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
    });
</script>

<script src="templates/JS/OuverturePopUp.js"></script>
<script src="templates/JS/timer.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.timer').forEach(el => {
            const endDate = el.getAttribute('data-end');
            startCountdown(endDate, el);
        });
    });

    const toastBox = document.querySelector('#toastBox');

    function showToast(type, msg) {
        if (!toastBox) return;

        const toast = document.createElement('div');

        // container styles
        toast.style.position = "fixed";
        toast.style.top = "25px";
        toast.style.right = "25px";
        toast.style.minWidth = "320px";
        toast.style.padding = "16px 22px";
        toast.style.borderRadius = "14px";
        toast.style.zIndex = "999999";
        toast.style.fontWeight = "500";
        toast.style.fontSize = "15px";
        toast.style.fontFamily = "Poppins, sans-serif";
        toast.style.boxShadow = "0 10px 25px rgba(0,0,0,0.15)";
        toast.style.backdropFilter = "blur(10px)";
        toast.style.display = "flex";
        toast.style.alignItems = "center";
        toast.style.gap = "12px";
        toast.style.animation = "toastSlide 0.3s ease";

        if (type === 3) {
            toast.style.background = "#fff8e6";
            toast.style.borderLeft = "5px solid #f0b429";
            toast.style.color = "#7a5a00";
            toast.innerHTML = `
                <div style="width:10px;height:10px;border-radius:50%;background:#f0b429;flex-shrink:0;"></div>
                <div><strong>Information</strong><br>${msg}</div>
            `;
        } else if (type === 1) {
            toast.style.background = "#eef6ff";
            toast.style.borderLeft = "5px solid #3498db";
            toast.style.color = "#1f4f7a";
            toast.innerHTML = `
                <div style="width:10px;height:10px;border-radius:50%;background:#3498db;flex-shrink:0;"></div>
                <div>${msg}</div>
            `;
        } else if (type > 1) {
            toast.style.background = "#fff1f0";
            toast.style.borderLeft = "5px solid #e74c3c";
            toast.style.color = "#7a1f1f";
            toast.innerHTML = `
                <div style="width:10px;height:10px;border-radius:50%;background:#e74c3c;flex-shrink:0;"></div>
                <div>${msg}</div>
            `;
        } else {
            toast.style.background = "#edfdf3";
            toast.style.borderLeft = "5px solid #2ecc71";
            toast.style.color = "#17663a";
            toast.innerHTML = `
                <div style="width:10px;height:10px;border-radius:50%;background:#2ecc71;flex-shrink:0;"></div>
                <div>${msg}</div>
            `;
        }

        toastBox.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translateX(40px)";
            setTimeout(() => toast.remove(), 300);
        }, 4500);
    }

    // expose globally for other scripts that call showToast
    window.showToast = showToast;

    function enableEdit(btn) {
        const item = btn.closest('.comment-item');
        item.classList.add('edit-mode');
        item.querySelector('.comment-text').style.display = "none";
        item.querySelector('.comment-actions').style.display = "none";
        item.querySelector('.edit-form').style.display = "flex";
    }

    function cancelEdit(btn) {
        const item = btn.closest('.comment-item');
        item.classList.remove('edit-mode');
        item.querySelector('.comment-text').style.display = "";
        item.querySelector('.comment-actions').style.display = "flex";
        item.querySelector('.edit-form').style.display = "none";
    }
</script>

<?php $content = ob_get_clean(); ?>
<?php require('preset/layout.php'); ?>