# Guide pour exécuter votre application Symfony sans Docker

Si vous ne pouvez pas utiliser Docker, voici une méthode simple pour exécuter votre application Symfony localement sur Windows.

## Prérequis

- PHP 8.1 installé sur votre machine (compatible avec votre projet)
- Composer installé globalement
- Serveur web local (par exemple Apache via XAMPP, WAMP, ou PHP built-in server)
- Base de données (MySQL, MariaDB, etc.) configurée et accessible

## Étapes

1. **Cloner ou copier votre projet** sur votre machine locale.

2. **Installer les dépendances PHP**  
   Ouvrez un terminal dans le dossier du projet et lancez :  
   ```bash
   composer install --no-dev --optimize-autoloader --no-interaction
   ```

3. **Configurer la base de données**  
   Modifiez le fichier `.env` ou `.env.local` pour définir la variable `DATABASE_URL` avec les informations de connexion à votre base de données.

4. **Créer la base de données et exécuter les migrations**  
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Lancer le serveur de développement Symfony**  
   ```bash
   php bin/console server:run
   ```  
   ou utilisez le serveur PHP intégré :  
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

6. **Accéder à l'application**  
   Ouvrez votre navigateur à l'adresse : `http://127.0.0.1:8000`

## Notes

- Assurez-vous que les extensions PHP nécessaires sont activées (pdo_mysql, intl, zip, etc.)
- Si vous utilisez Apache ou un autre serveur, configurez le VirtualHost pour pointer vers le dossier `public/`
- Pour les droits d'écriture, assurez-vous que les dossiers `var/` et `vendor/` sont accessibles en écriture par le serveur web

---

Cette méthode vous permet de développer et tester votre application Symfony sans Docker, en utilisant un environnement local classique.

Si vous souhaitez, je peux vous aider à configurer chaque étape en détail.
