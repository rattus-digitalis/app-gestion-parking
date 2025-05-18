<?php
session_start();

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
            $controller->login($_POST);
        } else {
            require_once __DIR__ . '/../app/views/pages/login.php';
        }
        break;

    case 'register':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register($_POST);
        } else {
            require_once __DIR__ . '/../app/views/pages/register.php';
        }
        break;

    case 'dashboard':
        require_once __DIR__ . '/../app/views/pages/dashboard.php';
        break;

    case 'logout':
        require_once __DIR__ . '/../app/models/User.php';

        if (isset($_SESSION['user'])) {
            $userModel = new User();
            $userModel->setStatus($_SESSION['user']['id'], 'offline');
        }

        session_unset();
        session_destroy();

        header('Location: /?page=login');
        exit;

    default:
        http_response_code(404);
        echo "Page non trouvée";
        break;
}
