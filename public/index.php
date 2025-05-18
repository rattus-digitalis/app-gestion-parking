<?php
// Chargement des fichiers nécessaires
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/routes.php';

$route = $_GET['page'] ?? 'home';

switch ($route) {
    case 'home':
        require_once __DIR__ . '/../app/views/pages/home.php';
        break;
        case 'login':
            require_once __DIR__ . '/../app/controllers/UserController.php';
            $controller = new UserController();
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->login($_POST);  // Ce bloc appelle ta méthode
            } else {
                require_once __DIR__ . '/../app/views/pages/login.php';
            }
            break;
        
        
    case 'dashboard':
        require_once __DIR__ . '/../app/views/pages/dashboard.php';
        break;
    default:
        http_response_code(404);
        echo "Page non trouvée";
        break;
        case 'login':
            require_once __DIR__ . '/../app/controllers/UserController.php';
            $controller = new UserController();
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->login($_POST); // C’est ici que tout se joue
            } else {
                require_once __DIR__ . '/../app/views/pages/login.php';
            }
            break;
        
        
}

