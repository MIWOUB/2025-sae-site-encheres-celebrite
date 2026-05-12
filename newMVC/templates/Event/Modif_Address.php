<style>
    /* Overlay */
    #popupAdresse.modal-overlay {
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
    #popupAdresse .modal-content {
        background: var(--color-background);
        width: 420px;
        padding: 30px 28px;
        border-radius: var(--radius-sm);
        position: relative;
        font-family: var(--font-body);
    }

    /* Titre */
    #popupAdresse .modal-content h3 {
        text-align: center;
        font-size: var(--text-h3);
        font-family: var(--font-title);
        color: var(--color-blue) !important;
        font-weight: bold;

        display: inline-block;
        border-bottom: 2px solid var(--color-gold);
        padding: 0 50px 5px;
        margin-bottom: 30px;
    }

    /* Bouton fermer */
    #popupAdresse .close-btn {
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

    #popupAdresse .close-btn:hover {
        color: var(--color-gold);
        transform: scale(1.3);
    }

    /* Labels */
    #popupAdresse .field label {
        display: block;
        text-align: start;
        font-family: var(--font-title);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--color-blue);
        margin-bottom: 0.4rem;
    }

    #popupAdresse .field {
        margin-bottom: 15px;
    }

    /* Inputs */
    #popupAdresse .field input[type="text"] {
        width: 100%;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-sm);
        border: 2px solid var(--color-gold);
        background: var(--color-white);
        font-family: var(--font-body);
        font-size: var(--text-body);
        color: var(--color-blue);
        transition: border-color 0.2s ease;
    }

    /* Focus inputs */
    #popupAdresse .field input[type="text"]:focus {
        border-color: var(--color-blue-light);
        outline: none;
    }

    /* Boutons */
    #popupAdresse .actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 30px;
    }

    #popupAdresse .btn-submit {
        background: var(--color-blue);
        color: var(--color-white);
        padding: 12px;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #popupAdresse .btn-submit:hover {
        background: var(--color-blue-light);
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }

    #popupAdresse .btn-secondary {
        background: var(--color-white);
        border: 2px solid var(--color-blue);
        color: var(--color-blue);
        padding: 10px;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #popupAdresse .btn-secondary:hover {
        background: var(--color-blue);
        color: var(--color-white);
        box-shadow: var(--shadow-sm);
    }
</style>

<div id="popupAdresse" class="modal-overlay">
    <div class="modal-content">

        <button type="button" class="close-btn" onclick="fermerPopupAdresse()" aria-label="Fermer">&#x2715;</button>

        <h3>Modifier votre adresse</h3>

        <div class="div-erreur"></div>

        <form id="form_modif_adresse" action="index.php?action=update_address" method="POST">
            <input type="hidden" name="action" value="update_address">

            <div class="field">
                <label for="adresse">Nouvelle adresse</label>
                <input type="text" id="adresse" name="addresse" placeholder="Votre nouvelle adresse">
            </div>

            <div class="field">
                <label for="code_postal">Nouveau code postal</label>
                <input type="text" id="code_postal" name="code_postal" placeholder="Votre nouveau code postal">
            </div>

            <div class="field">
                <label for="ville">Nouvelle ville</label>
                <input type="text" id="ville" name="ville" placeholder="Votre nouvelle ville">
            </div>

            <div class="actions">
                <button type="submit" class="btn-submit">Valider</button>
                <button type="button" class="btn-secondary" onclick="fermerPopupAdresse()">Annuler</button>
            </div>
        </form>

    </div>
</div>

<script src="templates/JS/OuverturePopUp.js"></script>