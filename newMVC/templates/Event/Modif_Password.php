<style>
    /* Overlay */
    #popupPassword.modal-overlay {
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
    #popupPassword .modal-content {
        background: var(--color-background);
        width: 420px;
        padding: 30px 28px;
        border-radius: var(--radius-sm);
        position: relative;
        font-family: var(--font-body);
    }

    /* Titre */
    #popupPassword .modal-content h3 {
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
    #popupPassword .close-btn {
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

    #popupPassword .close-btn:hover {
        color: var(--color-gold);
        transform: scale(1.3);
    }

    /* Labels */
    #popupPassword .field label {
        display: block;
        text-align: start;
        font-family: var(--font-title);
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--color-blue);
        margin-bottom: 0.4rem;
    }

    #popupPassword .field {
        margin-bottom: 15px;
    }

    /* Inputs */
    #popupPassword .field input[type="password"] {
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
    #popupPassword .field input[type="password"]:focus {
        border-color: var(--color-blue-light);
        outline: none;
    }

    /* Boutons */
    #popupPassword .actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 30px;
    }

    #popupPassword .btn-submit {
        background: var(--color-blue);
        color: var(--color-white);
        padding: 12px;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #popupPassword .btn-submit:hover {
        background: var(--color-blue-light);
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }

    #popupPassword .btn-secondary {
        background: var(--color-white);
        border: 2px solid var(--color-blue);
        color: var(--color-blue);
        padding: 10px;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    #popupPassword .btn-secondary:hover {
        background: var(--color-blue);
        color: var(--color-white);
        box-shadow: var(--shadow-sm);
    }
</style>

<div id="popupPassword" class="modal-overlay">
    <div class="modal-content">

        <button class="close-btn" onclick="fermerPopupPassword()" aria-label="Fermer">&#x2715;</button>

        <h3>Modifier votre mot </br>de passe</h3>

        <div class="div-erreur"></div>

        <form id="form_modif_password" action="index.php?action=update_password" method="POST"
            onsubmit="checkupNewPWD(event)">
            <input type="hidden" name="action" value="update_password">

            <div class="field">
                <label for="new_password_1">Nouveau mot de passe</label>
                <input class="new-password-1" type="password" id="new_password_1" name="new_password_1"
                    placeholder="Votre nouveau mot de passe">
            </div>

            <div class="field">
                <label for="new_password_2">Confirmer le mot de passe</label>
                <input class="new-password-2" type="password" id="new_password_2" name="new_password_2"
                    placeholder="Répéter le mot de passe">
            </div>

            <div class="actions">
                <button type="submit" class="btn-submit">Valider</button>
                <button type="button" class="btn-secondary" onclick="fermerPopupPassword()">Annuler</button>
            </div>
        </form>

    </div>
</div>

<script src="templates/JS/OuverturePopUp.js"></script>