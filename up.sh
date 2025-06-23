#!/bin/bash

# Chargement des variables d'environnement
if [ -f .env ]; then
  source .env
else
  echo "Fichier .env introuvable !"
  exit 1
fi

echo "Vérification des dépendances Docker..."
if ! command -v docker &> /dev/null || ! command -v docker-compose &> /dev/null; then
  echo "Docker et/ou Docker Compose ne sont pas installés."
  exit 1
fi

echo "Construction des conteneurs si nécessaire..."
docker-compose build

echo "Lancement de l'environnement Zenpark..."
docker-compose up -d

echo ""
echo "Environnement démarré avec succès !"
echo "Accès à l'application :    http://${DOMAIN_NAME}"
echo "Accès à phpMyAdmin :     http://localhost:${PMA_PORT}"
echo ""
echo "Assure-toi d'avoir cette ligne dans ton fichier /etc/hosts :"
echo "   127.0.0.1 ${DOMAIN_NAME}"

