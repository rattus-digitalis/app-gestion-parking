<?php

class AuthController 
{
    public function logout() 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Log de la déconnexion
        if (isset($_SESSION['user'])) {
            error_log("Déconnexion utilisateur: " . $_SESSION['user']['email']);
        }
        
        // Détruire la session
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        // Redirection avec message flash si système implémenté
        header('Location: /?page=home&logout=success');
        exit;
    }
}