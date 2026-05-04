<style>
    <?php include 'templates/Style/popupNewsletter.css'; ?>
</style>

<div id="newsletterPopup" style="display:none;">
    <div class="nl-overlay">

        <div class="nl-modal">

            <button class="nl-close" id="nlClose">&times;</button>
            <h2 class="nl-title">Newsletter</h2>

            <?php if (isset($_SESSION['user'])): ?>

                <p class="nl-text">
                    Voulez-vous recevoir nos actualités à l’adresse suivante ?
                </p>

                <form action="index.php?action=subscribeNewsletter" method="post">
                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($_SESSION['user']['email']) ?>"
                        readonly
                        class="nl-email"
                    >

                    <button type="submit" class="nl-submit">
                        Confirmer l’abonnement
                    </button>
                </form>

            <?php else: ?>

                <p class="nl-text">
                    Vous devez être connecté pour vous abonner à la newsletter.
                </p>

                <button class="nl-submit"
                    onclick="window.location.href='index.php?action=connection'">
                    Se connecter
                </button>

            <?php endif; ?>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const popup = document.getElementById('newsletterPopup');
    const overlay = popup.querySelector('.nl-overlay');
    const closeBtn = document.getElementById('nlClose');

    const openPopup = () => {
        popup.style.display = 'block';
        overlay.style.display = 'flex';
    };

    const closePopup = () => {
        overlay.style.display = 'none';
        popup.style.display = 'none';
    };

    closeBtn.addEventListener('click', closePopup);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closePopup();
    });

    const showNewsletter = <?= isset($_SESSION['show_newsletter_modal']) ? 'true' : 'false' ?>;

    if (showNewsletter) {
        openPopup();
    }
});
</script>

<?php unset($_SESSION['show_newsletter_modal']); ?>