<style>
    /* Overlay */
    #popupBidValidation.modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: none;

        justify-content: center;
        align-items: flex-start;

        padding-top: calc(var(--navbar-height) + 50px);

        z-index: 9999;
    }

    /* Conteneur */
    #popupBidValidation .modal-content {
        background: var(--color-background);
        width: 420px;
        padding: 30px 28px;
        border-radius: var(--radius-sm);
        position: relative;
        font-family: var(--font-body);
        animation: fadeIn 0.25s ease-out;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Titre */
    #popupBidValidation .modal-content h3 {
        text-align: center;
        font-size: var(--text-h3);
        font-family: var(--font-title);
        color: var(--color-blue) !important;
        font-weight: bold;

        display: inline-block;
        border-bottom: 2px solid var(--color-gold);
        padding: 0 50px 5px;
        margin-bottom: 25px;
    }

    /* Texte */
    #popupBidValidation p {
        text-align: center;
        font-size: 1.1rem;
        font-family: var(--font-body);
        color: var(--color-blue);
        margin-bottom: 20px;
        font-weight: 600;
    }

    /* Bouton fermer */
    #popupBidValidation .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: transparent;
        border: none;
        padding: 0;
        font-size: 30px;
        color: var(--color-blue);

        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        box-shadow: none;
        outline: none;
        transition: all 0.2s ease;
    }

    #popupBidValidation .close-btn:hover {
        color: var(--color-gold);
        transform: scale(1.3);
    }

    /* Boutons */
    #popupBidValidation .actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }

    #popupBidValidation .btn-submit {
        background: var(--color-green);
        color: var(--color-white);
        font-weight: 500;
        padding: 12px;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #popupBidValidation .btn-submit:hover {
        opacity: 0.95;
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }

    #popupBidValidation .btn-secondary {
        background: var(--color-white);
        border: 2px solid var(--color-red);
        color: var(--color-red);
        font-weight: 500;
        padding: 10px;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #popupBidValidation .btn-secondary:hover {
        background: var(--color-red);
        color: var(--color-white);
        box-shadow: var(--shadow-sm);
    }
</style>

<div id="popupBidValidation" class="modal-overlay">
    <div class="modal-content">

        <button class="close-btn" onclick="fermerPopupBidValidation()">✕</button>

        <h3>Confirmer votre enchère</h3>

        <p id="bidValidationText"></p>

        <div class="actions">
            <button class="btn-submit" onclick="envoyerEnchere()">Confirmer</button>
            <button class="btn-secondary" onclick="fermerPopupBidValidation()">Annuler</button>
        </div>

    </div>
</div>