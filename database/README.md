# Installation de la base de données

Suivez ces étapes pour installer la base de données du projet LenSi :

1. Démarrez XAMPP et assurez-vous que les services Apache et MySQL sont actifs
2. Ouvrez phpMyAdmin en allant sur http://localhost/phpmyadmin
3. Cliquez sur "Importer" dans le menu supérieur
4. Cliquez sur "Choisir un fichier" et sélectionnez le fichier `lensi_db.sql`
5. Vérifiez que l'encodage est bien réglé sur `utf8mb4`
6. Cliquez sur "Exécuter" en bas de la page

Une fois l'importation terminée, la base de données sera prête à être utilisée avec :

- Un compte administrateur :
  - Nom d'utilisateur : admin
  - Mot de passe : admin123

- Des événements et participants de démonstration

## Structure de la base de données

### Table `events`
- id (INT, Auto-increment)
- title (VARCHAR)
- date (DATE)
- location (VARCHAR)
- description (TEXT)
- image (VARCHAR)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

### Table `participants`
- id (INT, Auto-increment)
- name (VARCHAR)
- email (VARCHAR)
- phone (VARCHAR)
- event_id (INT, Foreign Key)
- registration_date (TIMESTAMP)

### Table `admins`
- id (INT, Auto-increment)
- username (VARCHAR)
- password (VARCHAR)
- email (VARCHAR)
- created_at (TIMESTAMP)