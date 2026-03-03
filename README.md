# Pistes d'améliorations 
- Refactoriser le code pour passer sur une architecture MVC
- Harmoniser les connexions BDD et Meilisearch avec des singleton et des variables d'environnement
- Améliorer la gestion des erreurs
- Améliorer le CSS
- Corriger le problème d'encodage

# Comment lancer le projet 
- Créer une base MySQL/MariaDB appelée `auction_site`
- Importer la BDD `BDD_constructor.sql`
- Modifier les connexions BDD pour s'y connecter 
- Lancer meilisearch avec comme clé `CLE_TEST_SAE_SITE`
- Harmoniser les connexions MeiliSearch dans le projet en cherchant `use Meilisearch\Client;`
- Synchroniser l'index de recherche MeiliSearch lors des opérations de mise à jour de BDD au lieu de le faire à chaque rechargement