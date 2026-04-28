<?php
require(dirname(__DIR__, 2) . '/src/script/verif_online_annoncement.php');
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="templates/Style/header.css">

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-custom">
        <div class="container-fluid">

            <a class="navbar-brand" href="index.php">
                <img src="templates/images/logo.png" alt="Logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a id="btn_achat" class="nav-link" href="index.php?action=buy">Acheter</a>
                    </li>

                    <?php if (isset($_SESSION['user'])) { ?>
                        <li class="nav-item">
                            <a id="btn_vente" class="nav-link" href="index.php?action=sell">Vendre</a>
                        </li>
                    <?php } ?>

                    <?php if (isset($_SESSION['user']['admin']) && $_SESSION['user']['admin'] != 0) { ?>
                        <li class="nav-item">
                            <a id="btn_admin" class="nav-link" href="index.php?action=admin">Panneau Admin</a>
                        </li>
                    <?php } ?>
                </ul>

                <ul class="navbar-nav">

                    <?php if (isset($_SESSION['user'])) { ?>

                        <li class="nav-item">
                            <a id="btn_historique" class="nav-link" href="index.php?action=historique_annonces_publiees">
                                <img src="templates/images/historique.png" style="width:30px;height:30px;">
                            </a>
                        </li>

                        <!-- FAVORIS -->
                        <li class="nav-item">
                            <a id="btn_Favoris" class="nav-link" href="index.php?action=favorites">
                                <img src="templates/images/coeur.png" style="width:30px;height:30px;">
                            </a>
                        </li>

                        <li class="nav-item">
                            <a id="btn_client" class="nav-link" href="index.php?action=user">
                                <img src="templates/images/compte.png" style="width:30px;height:30px;">
                            </a>
                        </li>

                        <li class="nav-item">
                            <a id="btn_deconnexion" class="nav-link" href="index.php?action=logout">
                                Déconnexion
                            </a>
                        </li>

                    <?php } else { ?>

                        <li class="nav-item">
                            <a id="btn_connexion" class="nav-link" href="index.php?action=login">
                                Connexion
                            </a>
                        </li>

                        <li class="nav-item">
                            <a id="btn_inscription" class="nav-link" href="index.php?action=register">
                                Inscription
                            </a>
                        </li>

                    <?php } ?>

                </ul>

            </div>
        </div>
    </nav>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>