<?php
// Affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrage de la session
session_start();
require_once __DIR__ . '/../config/init_page.php';


// Constantes, routes, initialisation de la page
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/routes.php';

// Page actuelle
$page = $_GET['page'] ?? 'home';

// Init CSS/pages (auto-load des styles par page)
require_once __DIR__ . '/../config/init_page.php';

// Gestion globale des erreurs fatales
set_exception_handler(function ($e) {
    error_log($e);
    http_response_code(500);
    require_once __DIR__ . '/../app/views/errors/500.php';
    exit;
});

// Vérifie les rôles autorisés pour une route
function checkRoles(string ...$roles): void {
    if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles, true)) {
        http_response_code(401);
        require_once __DIR__ . '/../app/views/errors/401.php';
        exit;
    }
}

// Routeur principal
switch ($page) {

    case 'home':
        require_once __DIR__ . '/../app/views/pages/home.php';
        break;

    case 'contact':
        require_once __DIR__ . '/../app/views/pages/contact.php';
        break;

    case 'cgu':
        require_once __DIR__ . '/../app/views/pages/cgu.php';
        break;

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
        checkRoles('user');
        require_once __DIR__ . '/../app/views/pages/dashboard_user.php';
        break;

    case 'dashboard_admin':
        checkRoles('admin');
        require_once __DIR__ . '/../app/views/admin/dashboard_admin.php';
        break;

    // --- ADMIN ---
    case 'admin_users':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->handlePost($_POST)
            : $controller->listUsers();
        break;

    case 'edit_user':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminUserController.php';
        $controller = new AdminUserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateUser($_POST);
        } else {
            $userId = $_GET['id'] ?? null;
            if (!is_numeric($userId)) {
                header('Location: /?page=admin_users');
                exit;
            }
            $controller->editUserForm((int)$userId);
        }
        break;

        // Dans votre switch case existant
case 'create_user':
    checkRoles('admin');
require_once __DIR__ . '/../app/controllers/AdminUserController.php';
    $controller = new AdminUserController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->createUser($_POST);
    } else {
        $controller->createUserForm();
    }
    break;


    case 'create_user':
    checkRoles('admin');
    require_once __DIR__ . '/app/controllers/AdminUserController.php';
    $controller = new AdminUserController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->createUser($_POST);
    } else {
        $controller->createUserForm();
    }
    break;
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

        case 'admin_delete_user':
    checkRoles('admin');
    require_once __DIR__ . '/../app/controllers/AdminUserController.php';
    $controller = new AdminUserController();
    $controller->delete();
    break;

    // --- UTILISATEUR ---
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
        $controller = new ReservationController();
        $id = $_GET['id'] ?? null;
        if (!is_numeric($id)) {
            http_response_code(400);
            echo "ID invalide.";
            exit;
        }
        $controller->editForm((int)$id);
        break;

    case 'update_reservation':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/ReservationController.php';
        (new ReservationController())->update($_POST);
        break;

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

    case 'admin_tarifs':
        checkRoles('admin');
        require_once __DIR__ . '/../app/controllers/AdminTarifController.php';
        $controller = new AdminTarifController();
        ($_SERVER['REQUEST_METHOD'] === 'POST')
            ? $controller->update($_POST)
            : $controller->show();
        break;

    case 'paiement':
        checkRoles('user');
        require_once __DIR__ . '/../app/controllers/PaiementController.php';
        $controller = new PaiementController();
        $id = $_GET['id'] ?? null;
        if (!is_numeric($id)) {
            http_response_code(400);
            echo "ID de réservation invalide.";
            exit;
        }
        $controller->payer((int)$id);
        break;

    case 'valider_paiement':
        checkRoles('user');
        require_once __DIR__ . '/../app/views/pages/valider_paiement.php';
        break;

    // --- 404
    default:
        http_response_code(404);
        require_once __DIR__ . '/../app/views/errors/404.php';
        break;
}
