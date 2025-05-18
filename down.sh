#!/bin/bash

echo "🛑 Arrêt des conteneurs..."
docker-compose down

echo ""
read -p "🧹 Supprimer les volumes Docker (base de données, etc.) ? [y/N] " confirm
if [[ "$confirm" =~ ^[Yy]$ ]]; then
  echo "🚨 Suppression des volumes..."
  docker volume rm $(docker volume ls -qf dangling=false)
else
  echo "✅ Volumes conservés."
fi

echo ""
echo "✅ Environnement arrêté."

