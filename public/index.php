<?php
// Affichage des erreurs (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Session
session_start();

// Chargement de la config
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/routes.php';
require_once __DIR__ . '/../config/init_page.php';

// Gestion des erreurs fatales
set_exception_handler(function ($e) {
    error_log($e);
    http_response_code(500);
    require_once __DIR__ . '/../app/views/errors/500.php';
    exit;
});

// Vérifie le rôle de l'utilisateur
function checkRoles(string ...$roles): void {
    if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles, true)) {
        http_response_code(401);
        require_once __DIR__ . '/../app/views/errors/401.php';
        exit;
    }
}

// Page demandée
$page = $_GET['page'] ?? 'home';

// Routeur
switch ($page) {
    // --- Pages publiques ---
    case 'home':
    case 'contact':
    case 'cgu':
        require_once __DIR__ . "/../app/views/pages/{$page}.php";
        break;

    // --- Authentification ---
    case 'login':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        $controller = new LoginController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->login($_POST)
            : require_once __DIR__ . '/../app/views/pages/login.php';
        break;

    case 'logout':
        require_once __DIR__ . '/../app/controllers/LoginController.php';
        (new LoginController())->logout();
        break;

    case 'register':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->register($_POST)
            : require_once __DIR__ . '/../app/views/pages/register.php';
        break;

    // --- Dashboards ---
    case 'dashboard':
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }
        $role = $_SESSION['user']['role'];
        header('Location: /?page=dashboard_' . $role);
        exit;

    case 'dashboard_user':
        checkRoles('user');
        require_once __DIR__ . '/../app/views/pages/dashboard_user.php';
        break;

    case 'dashboard_admin':
        checkRoles('admin');
        require_once __DIR__ . '/../app/views/admin/dashboard_admin.php';
        break;

    // --- Admin : Utilisateurs ---
    case 'admin_users':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        (new AdminUserController())->listUsers();
        break;

    case 'edit_user':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateUser($_POST);
        } else {
            $id = $_GET['id'] ?? null;
            if (!is_numeric($id)) {
                header('Location: /?page=admin_users');
                exit;
            }
            $controller->editUserForm((int)$id);
        }
        break;

    case 'create_user':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->createUser($_POST)
            : $controller->createUserForm();
        break;

    case 'admin_delete_user':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        (new AdminUserController())->delete();
        break;

    // --- Admin : Parkings, Réservations, Tarifs ---
    case 'admin_parkings':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminParkingController.php';
        (new AdminParkingController())->listParkings();
        break;

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

    case 'admin_tarifs':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminTarifController.php';
        $controller = new AdminTarifController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->update($_POST)
            : $controller->show();
        break;

    // --- Utilisateur : Réservations ---
    case 'nouvelle_reservation':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        $controller = new ReservationController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->create($_POST)
            : $controller->form();
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
        $id = $_GET['id'] ?? null;
        if (!is_numeric($id)) {
            http_response_code(400);
            echo "ID invalide.";
            exit;
        }
        (new ReservationController())->editForm((int)$id);
        break;

    case 'update_reservation':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        (new ReservationController())->update($_POST);
        break;

    // --- Utilisateur : Voiture & Compte ---
    case 'ma_voiture':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/CarController.php';
        $controller = new CarController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->save($_POST)
            : $controller->form();
        break;

    case 'mon_compte':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->updateCurrentUser($_POST)
            : require_once __DIR__ . '/../app/views/pages/mon_compte.php';
        break;

// Dans votre switch de routage
case 'download_invoice':
    checkRoles('user');
    require_once __DIR__ . '/../app/controllers/InvoiceController.php';
    (new InvoiceController())->downloadInvoice();
    break;


    case 'download_invoice':
    checkRoles('user');
    require_once __DIR__ . '/../app/controllers/InvoiceController.php';
    (new InvoiceController())->downloadInvoice();
    break;
    // --- Paiement (CORRIGÉ) ---
    case 'paiement':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/PaiementController.php';
        $controller = new PaiementController();
        $controller->handleRequest();
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