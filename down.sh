#!/bin/bash

echo "Arrêt des conteneurs..."
docker-compose down

echo ""
read -p "Supprimer les volumes Docker (base de données, etc.) ? [y/N] " confirm
if [[ "$confirm" =~ ^[Yy]$ ]]; then
  echo "Vérification des volumes existants..."
  
  # Liste des volumes Docker non utilisés
  volumes=$(docker volume ls -qf dangling=false)
  
  if [ -n "$volumes" ]; then
    echo "Suppression des volumes..."
    docker volume rm $volumes
  else
    echo "Aucun volume à supprimer."
  fi
else
  echo "Volumes conservés."
fi

echo ""
echo "Environnement arrêté."
