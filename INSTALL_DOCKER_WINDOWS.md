# Guide d'installation de Docker Desktop sur Windows

Ce guide vous accompagne pas à pas pour installer Docker Desktop sur une machine Windows.

## Étape 1 : Télécharger Docker Desktop

- Ouvrez votre navigateur web.
- Rendez-vous sur le site officiel : https://www.docker.com/get-started
- Cliquez sur "Download for Windows" pour télécharger l'installateur.

## Étape 2 : Installer Docker Desktop

- Une fois le fichier téléchargé, double-cliquez dessus pour lancer l'installation.
- Suivez les instructions à l'écran.
- Si la virtualisation n'est pas activée sur votre machine, l'installateur vous le signalera. Vous devrez alors activer la virtualisation dans le BIOS de votre ordinateur.
- Une fois l'installation terminée, redémarrez votre ordinateur si demandé.

## Étape 3 : Vérifier l'installation

- Ouvrez PowerShell ou l'invite de commandes.
- Tapez la commande suivante et validez :
  ```
  docker --version
  ```
- Vous devriez voir la version de Docker installée s'afficher.

## Étape 4 : Construire votre image Docker

- Placez-vous dans le répertoire de votre projet (exemple : `c:/Users/HP/tournoi_foot`).
- Exécutez la commande suivante pour construire l'image Docker :
  ```
  docker build -t tournoi_foot_image .
  ```

## Étape 5 : Lancer le conteneur (optionnel)

- Pour lancer un conteneur à partir de l'image créée :
  ```
  docker run -p 80:80 tournoi_foot_image
  ```

---

Si vous avez besoin d'aide à n'importe quelle étape, n'hésitez pas à me demander.
