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

    case 'dashboard_user':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
            header('Location: /?page=login');
            exit;
        }
        require_once __DIR__ . '/../app/views/pages/dashboard_user.php';
        break;

    case 'dashboard_admin':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /?page=login');
            exit;
        }
        require_once __DIR__ . '/../app/views/admin/dashboard_admin.php';
        break;

    case 'admin_users':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /?page=login');
            exit;
        }
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handlePost($_POST);
        } else {
            $controller->listUsers();
        }
        break;

    case 'edit_user':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /?page=login');
            exit;
        }
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateUser($_POST);
        } else {
            $userId = $_GET['id'] ?? null;
            if ($userId === null) {
                header('Location: /?page=admin_users');
                exit;
            }
            $controller->editUserForm($userId);
        }
        break;

    case 'admin_parkings':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /?page=login');
            exit;
        }
        require_once __DIR__ . '/../app/controllers/AdminParkingController.php';
        $controller = new AdminParkingController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateStatus($_POST);
        } else {
            $controller->listParkings();
        }
        break;

    case 'reservations_list':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /?page=login');
            exit;
        }
        require_once __DIR__ . '/../app/controllers/AdminReservationController.php';
        $controller = new AdminReservationController();
        $controller->listReservations();
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée";
        break;
}
