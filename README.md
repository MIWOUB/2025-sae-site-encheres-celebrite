# Tâches à faire

- Refactoriser le code pour passer sur une architecture MVC
- Harmoniser les connexions BDD et Meilisearch avec des singleton et des variables d'environnement
- Améliorer la gestion des erreurs
- Améliorer le CSS

# Prérequis

- Docker Desktop (ou Docker Engine + Docker Compose)
- Ports disponibles: `80`, `3306`, `7700`

# Comment lancer le projet

1. Se placer a la racine du projet.
2. Construire et demarrer les conteneurs:
   ```bash
   docker compose up -d --build
   ```
3. Installer les dependances PHP dans `newMVC` (genere `vendor/autoload.php`):
   ```bash
   docker run --rm -v "${PWD}/newMVC:/app" -w /app composer:2 install --no-interaction
   ```
4. Ouvrir l'application dans le navigateur:
   - `http://localhost`

# Services

- Application PHP/Apache: `http://localhost`
- MariaDB: `localhost:3306`
- Meilisearch: `localhost:7700`

# Base de donnees

- La base `auction_site` est creee automatiquement par le conteneur MariaDB.
- Le script `BDD_constructor.sql` est importe automatiquement au premier demarrage.
- En cas de reinitialisation, supprimer le conteneur/volume de base puis relancer `docker compose up -d --build`.

# Meilisearch

- Cle attendue: `CLE_TEST_SAE_SITE`
- Verifier que les connexions utilisent la bonne cle dans le code.

# Depannage rapide

- Erreur `Failed opening required vendor/autoload.php`:
  relancer la commande d'installation Composer.
- Site inaccessible:
  verifier l'etat des services avec `docker compose ps`.
