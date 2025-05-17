<?php
// backend/index.php

// 👉 Charger manuellement les bases (indispensable)
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';

// 👉 Charger l'autoloader après les classes critiques
require_once __DIR__ . '/core/autoload.php';

// 👉 Puis démarrer l'application
require_once __DIR__ . '/core/App.php';

$app = new App();
