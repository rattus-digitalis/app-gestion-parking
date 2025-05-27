<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/routes.php';

/**
 * Gestionnaire d'exception global : log et affichage page 500
 */
set_exception_handler(function ($e) {
    error_log($e);
    http_response_code(500);
    require_once __DIR__ . '/../app/views/errors/500.php';
    exit;
});

/**
 * Vérifie que l'utilisateur a un des rôles spécifiés
 * @param string ...$roles
 */
function checkRoles(string ...$roles)
{
    if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles, true)) {
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
        (new LoginController())->logout();
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
            exit;
        }
        if ($role === 'user') {
            header('Location: /?page=dashboard_user');
            exit;
        }
        // Rôle inconnu
        http_response_code(401);
        require_once __DIR__ . '/../app/views/errors/401.php';
        exit;

    case 'dashboard_user':
        checkRoles('user');
        require_once __DIR__ . '/../app/views/pages/dashboard_user.php';
        break;

    case 'dashboard_admin':
        checkRoles('admin');
        require_once __DIR__ . '/../app/views/admin/dashboard_admin.php';
        break;

    // --- Admin Utilisateurs ---
    case 'admin_users':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->handlePost($_POST);
        } else {
            $controller->listUsers();
        }
        break;

    case 'edit_user':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateUser($_POST);
        } else {
            $userId = $_GET['id'] ?? null;
            if (empty($userId) || !is_numeric($userId)) {
                header('Location: /?page=admin_users');
                exit;
            }
            $controller->editUserForm((int)$userId);
        }
        break;

    // --- Admin Parkings ---
    case 'admin_parkings':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminParkingController.php';
        $controller = new AdminParkingController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateStatus($_POST);
        } else {
            $controller->listParkings();
        }
        break;

case 'admin_parkings':
    checkRole('admin');
    require_once __DIR__ . '/../app/controllers/AdminParkingController.php';
    $controller = new AdminParkingController();
    $controller->listParkings();
    break;



    // --- Admin Réservations ---
    case 'reservations_list':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminReservationController.php';
        (new AdminReservationController())->listReservations();
        break;

    case 'admin_edit_reservation':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminReservationController.php';
        (new AdminReservationController())->editReservation();
        break;

    case 'admin_delete_reservation':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminReservationController.php';
        (new AdminReservationController())->deleteReservation();
        break;

    // --- User Réservations ---
    case 'nouvelle_reservation':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        $controller = new ReservationController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create($_POST);
        } else {
            $controller->form();
        }
        break;

    case 'mes_reservations':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        (new ReservationController())->mesReservations();
        break;

    case 'annuler_reservation':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        (new ReservationController())->cancelReservation();
        break;

    case 'modifier_reservation':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        $controller = new ReservationController();
        $id = $_GET['id'] ?? null;
        if (empty($id) || !is_numeric($id)) {
            http_response_code(400);
            echo "ID manquant ou invalide.";
            exit;
        }
        $controller->editForm((int)$id);
        break;

    case 'update_reservation':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        (new ReservationController())->update($_POST);
        break;

    // --- Véhicules ---
    case 'ma_voiture':
        checkRoles('user');
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
        checkRoles('admin');
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
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/PaiementController.php';
        $controller = new PaiementController();
        $reservationId = $_GET['id'] ?? null;
        if (!empty($reservationId) && is_numeric($reservationId)) {
            $controller->payer((int)$reservationId);
        } else {
            http_response_code(400);
            echo "ID de réservation manquant ou invalide.";
            exit;
        }
        break;

        case 'valider_paiement':
    checkRoles('user');
    require_once __DIR__ . '/../app/views/pages/valider_paiement.php';
    break;


    // --- 404 ---
    default:
        http_response_code(404);
        require_once __DIR__ . '/../app/views/errors/404.php';
        break;
}
