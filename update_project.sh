#!/bin/bash

# Arrêter l'exécution en cas d'erreur
set -e

# Naviguer vers le répertoire de votre projet (modifiez le chemin selon votre projet)
# cd /chemin/vers/votre/projet

# Vérifier les modifications locales
#if [[ $(git status --porcelain) ]]; then
#  echo "Vous avez des modifications locales non enregistrées. Veuillez les committer ou les stasher avant de continuer."
#  exit 1
#fi

# Mettre à jour le dépôt git
echo "Mise à jour du dépôt git..."
git pull

# Mettre à jour le schéma de la base de données
echo "Mise à jour du schéma de la base de données..."
php bin/console doctrine:schema:update --force

# Vider le cache de Symfony
echo "Nettoyage du cache de Symfony..."
php bin/console cache:clear

echo "Mise à jour terminée avec succès !"

# Rendre le script exécutable
# chmod +x update_project.sh


#git fetch --all
#git reset --hard origin/main