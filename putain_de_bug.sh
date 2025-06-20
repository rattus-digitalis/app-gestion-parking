#!/bin/bash

# Script de débogage pour Zenpark
echo "🔍 Vérification des fichiers JavaScript..."

# Rechercher tous les imports de notify
echo "📁 Recherche des imports 'notify' dans les fichiers JS :"
find public/js -name "*.js" -exec grep -l "import.*notify" {} \; 2>/dev/null || echo "Aucun import trouvé"

# Afficher le contenu des premières lignes de register.js
echo ""
echo "📄 Premières lignes de register.js :"
head -10 public/js/modules/register.js 2>/dev/null || echo "Fichier register.js non trouvé"

# Vérifier si notify.js existe et ses exports
echo ""
echo "📄 Exports dans notify.js :"
tail -20 public/js/modules/utils/notify.js 2>/dev/null || echo "Fichier notify.js non trouvé"

# Vérifier le manifest.json
echo ""
echo "📄 Contenu de manifest.json :"
cat public/manifest.json 2>/dev/null || echo "Fichier manifest.json non trouvé"

# Vérifier les erreurs dans les logs Docker
echo ""
echo "📋 Dernières erreurs PHP (si disponibles) :"
docker-compose logs php --tail=10 2>/dev/null || echo "Logs Docker non disponibles"

echo ""
echo "✅ Vérification terminée"
