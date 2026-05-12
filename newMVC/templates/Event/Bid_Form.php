<div class="popup-overlay" id="popup_bid_form">

    <div class="popup-card">

        <button class="close-btn" onclick="fermerPopupBidForm()">
            ✕
        </button>

        <h2>Faire une enchère</h2>
        <p class="subtitle">Propose un montant supérieur au prix actuel</p>

        <form class="popup-form" id="bid-form">

            <input type="hidden" id="idProduct_form">
            <input type="hidden" id="currentPrice_form">

            <label id="bid-label-form">Montant de votre enchère</label>

            <div class="input-wrapper">
                <input id="bid_input_form" type="number" required>
                <span class="currency">€</span>
            </div>

            <button class="btn-bid" type="button"
                onclick="event.preventDefault(); ouvrirPopup('Bid')">
                Enchérir
            </button>

            <p class="hint">💡 Doit être supérieur au prix actuel</p>

        </form>

    </div>

</div>