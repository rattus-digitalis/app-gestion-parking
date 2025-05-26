<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/routes.php';

set_exception_handler(function ($e) {
    error_log($e);
    http_response_code(500);
    require_once __DIR__ . '/../app/views/errors/500.php';
    exit;
});

function checkRole(string $role)
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
        http_response_code(401);
        require_once __DIR__ . '/../app/views/errors/401.php';
        exit;
    }
}

$route = $_GET['page'] ?? 'home';

switch ($route) {
    // --- Pages publiques ---
    case 'home':
        require_once __DIR__ . '/../app/views/pages/home.php';
        break;

    case 'contact':
        require_once __DIR__ . '/../app/views/pages/contact.php';
        break;

    case 'cgu':
        require_once __DIR__ . '/../app/views/pages/cgu.php';
        break;

    // --- Auth ---
    case 'login':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login($_POST);
        } else {
            require_once __DIR__ . '/../app/views/pages/login.php';
        }
        break;

    case 'logout':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController();
        $controller->logout();
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

    // --- Dashboard ---
    case 'dashboard':
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }
        $role = $_SESSION['user']['role'];
        if ($role === 'admin') {
            header('Location: /?page=dashboard_admin');
        } elseif ($role === 'user') {
            header('Location: /?page=dashboard_user');
        } else {
            http_response_code(401);
            require_once __DIR__ . '/../app/views/errors/401.php';
        }
        exit;

    case 'dashboard_user':
        checkRole('user');
        require_once __DIR__ . '/../app/views/pages/dashboard_user.php';
        break;

    case 'dashboard_admin':
        checkRole('admin');
        require_once __DIR__ . '/../app/views/admin/dashboard_admin.php';
        break;

    // --- Admin Utilisateurs ---
    case 'admin_users':
        checkRole('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handlePost($_POST);
        } else {
            $controller->listUsers();
        }
        break;

    case 'edit_user':
        checkRole('admin');
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

    // --- Admin Parkings ---
    case 'admin_parkings':
        checkRole('admin');
        require_once __DIR__ . '/../app/controllers/AdminParkingController.php';
        $controller = new AdminParkingController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateStatus($_POST);
        } else {
            $controller->listParkings();
        }
        break;

    // --- Admin Réservations ---
    case 'reservations_list':
        checkRole('admin');
        require_once __DIR__ . '/../app/controllers/AdminReservationController.php';
        $controller = new AdminReservationController();
        $controller->listReservations();
        break;

    case 'admin_edit_reservation':
        checkRole('admin');
        require_once __DIR__ . '/../app/controllers/AdminReservationController.php';
        $controller = new AdminReservationController();
        $controller->editReservation();
        break;

    case 'admin_delete_reservation':
        checkRole('admin');
        require_once __DIR__ . '/../app/controllers/AdminReservationController.php';
        $controller = new AdminReservationController();
        $controller->deleteReservation();
        break;

    // --- User Réservations ---
    case 'nouvelle_reservation':
        checkRole('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        $controller = new ReservationController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create($_POST);
        } else {
            $controller->form();
        }
        break;

    case 'mes_reservations':
        checkRole('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        $controller = new ReservationController();
        $controller->mesReservations();
        break;

    case 'annuler_reservation':
        checkRole('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        $controller = new ReservationController();
        $controller->cancelReservation();
        break;

    case 'modifier_reservation':
        checkRole('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        $controller = new ReservationController();
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            http_response_code(400);
            echo "ID manquant.";
            exit;
        }
        $controller->editForm((int)$id);
        break;

    case 'update_reservation':
        checkRole('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        $controller = new ReservationController();
        $controller->update($_POST);
        break;

    // --- Véhicules ---
    case 'ma_voiture':
        checkRole('user');
        require_once __DIR__ . '/../app/controllers/CarController.php';
        $controller = new CarController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->save($_POST);
        } else {
            $controller->form();
        }
        break;

    // --- Compte ---
    case 'mon_compte':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateCurrentUser($_POST);
        } else {
            require_once __DIR__ . '/../app/views/pages/mon_compte.php';
        }
        break;

    // --- Tarifs ---
    case 'admin_tarifs':
        checkRole('admin');
        require_once __DIR__ . '/../app/controllers/AdminTarifController.php';
        $controller = new AdminTarifController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update($_POST);
        } else {
            $controller->show();
        }
        break;

    // --- Paiement ---
    case 'paiement':
        checkRole('user');
        require_once __DIR__ . '/../app/controllers/PaiementController.php';
        $controller = new PaiementController();
        $reservationId = $_GET['id'] ?? null;
        if ($reservationId) {
            $controller->payer((int)$reservationId);
        } else {
            http_response_code(400);
            echo "ID de réservation manquant.";
        }
        break;

    // --- 404 ---
    default:
        http_response_code(404);
        require_once __DIR__ . '/../app/views/errors/404.php';
        break;
}
