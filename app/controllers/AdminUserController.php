<?php
require_once __DIR__ . '/../models/User.php';

class AdminUserController
{
    public function listUsers()
    {
        $userModel = new User();
        $users = $userModel->getAllUsers();

        require_once __DIR__ . '/../views/admin/users_list.php';
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur
     */
    public function createUserForm()
    {
        require_once __DIR__ . '/../views/admin/create_user.php';
    }

    /**
     * Traite la création d'un nouvel utilisateur
     */
    public function createUser($postData)
    {
        // Activer le débogage temporairement
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        
        error_log("=== AdminUserController::createUser() DÉBUT ===");
        error_log("POST data: " . print_r($postData, true));
        
        try {
            // Validation des données requises
            $requiredFields = ['last_name', 'first_name', 'email', 'phone', 'password', 'role'];
            $missingFields = [];
            
            foreach ($requiredFields as $field) {
                if (!isset($postData[$field]) || trim($postData[$field]) === '') {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                $errorMessage = 'Champs manquants : ' . implode(', ', $missingFields);
                error_log("❌ Validation échouée: " . $errorMessage);
                
                if ($this->isAjaxRequest()) {
                    $this->jsonError($errorMessage, 400);
                } else {
                    header('Location: /?page=create_user&error=' . urlencode($errorMessage));
                    exit;
                }
            }
            
            // Nettoyer et sécuriser les données
            $lastName = htmlspecialchars(trim($postData['last_name']));
            $firstName = htmlspecialchars(trim($postData['first_name']));
            $email = filter_var(trim($postData['email']), FILTER_VALIDATE_EMAIL);
            $phone = htmlspecialchars(trim($postData['phone']));
            $password = $postData['password'];
            $role = in_array($postData['role'], ['user', 'admin', 'moderator']) ? $postData['role'] : 'user';
            
            // Validation de l'email
            if (!$email) {
                $errorMessage = 'Adresse email invalide';
                error_log("❌ Email invalide: " . $postData['email']);
                
                if ($this->isAjaxRequest()) {
                    $this->jsonError($errorMessage, 400);
                } else {
                    header('Location: /?page=create_user&error=' . urlencode($errorMessage));
                    exit;
                }
            }
            
            $userModel = new User();
            
            // Vérifier si l'email existe déjà
            $existingUser = $userModel->getUserByEmail($email);
            if ($existingUser) {
                $errorMessage = 'Un utilisateur avec cet email existe déjà';
                error_log("❌ Email déjà existant: " . $email);
                
                if ($this->isAjaxRequest()) {
                    $this->jsonError($errorMessage, 409);
                } else {
                    header('Location: /?page=create_user&error=' . urlencode($errorMessage));
                    exit;
                }
            }
            
            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            error_log("🔐 Mot de passe hashé");
            
            // Créer l'utilisateur
            $success = $userModel->createUser($lastName, $firstName, $email, $phone, $hashedPassword);
            error_log("Résultat création: " . ($success ? 'SUCCESS' : 'FAILURE'));
            
            if ($success) {
                $successMessage = "Utilisateur créé avec succès : $firstName $lastName";
                error_log("✅ " . $successMessage);
                
                if ($this->isAjaxRequest()) {
                    $this->jsonSuccess([
                        'message' => $successMessage,
                        'user' => [
                            'email' => $email,
                            'name' => "$firstName $lastName",
                            'role' => $role
                        ]
                    ]);
                } else {
                    header('Location: /?page=admin_users&success=' . urlencode($successMessage));
                    exit;
                }
            } else {
                throw new Exception('Échec de la création de l\'utilisateur');
            }
            
        } catch (Exception $e) {
            error_log("💥 EXCEPTION dans createUser(): " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            if ($this->isAjaxRequest()) {
                $this->jsonError('Erreur interne du serveur: ' . $e->getMessage(), 500);
            } else {
                header('Location: /?page=create_user&error=' . urlencode('Erreur interne du serveur'));
                exit;
            }
        }
        
        error_log("=== AdminUserController::createUser() FIN ===");
    }

    public function delete()
    {
        // Votre code de suppression existant...
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        error_log("=== AdminUserController::delete() DÉBUT ===");
        error_log("REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'undefined'));
        error_log("POST data: " . print_r($_POST, true));
        error_log("SESSION: " . print_r($_SESSION ?? [], true));
        
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
                error_log("✅ Validation POST OK");
                
                $userModel = new User();
                $userId = (int) $_POST['delete_user_id'];
                error_log("User ID à supprimer: " . $userId);
                
                // Vérifier que l'utilisateur ne se supprime pas lui-même
                if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $userId) {
                    error_log("❌ Tentative d'auto-suppression détectée");
                    if ($this->isAjaxRequest()) {
                        $this->jsonError('Vous ne pouvez pas vous supprimer vous-même', 403);
                    } else {
                        header('Location: /?page=admin_users&error=self_delete');
                        exit;
                    }
                }

                error_log("🔄 Appel de deleteUserById...");
                $success = $userModel->deleteUserById($userId);
                error_log("Résultat deleteUserById: " . ($success ? 'SUCCESS' : 'FAILURE'));
                
                // Si c'est une requête AJAX, retourner du JSON
                if ($this->isAjaxRequest()) {
                    error_log("📡 Requête AJAX détectée, envoi JSON...");
                    if ($success) {
                        $this->jsonSuccess(['message' => 'Utilisateur supprimé avec succès']);
                    } else {
                        $this->jsonError('Échec de la suppression', 500);
                    }
                } else {
                    error_log("🔄 Requête normale, redirection...");
                    if ($success) {
                        header('Location: /?page=admin_users&success=deleted');
                    } else {
                        header('Location: /?page=admin_users&error=delete_failed');
                    }
                    exit;
                }
                
            } else {
                error_log("❌ Validation POST échouée");
                
                if ($this->isAjaxRequest()) {
                    $this->jsonError('Requête invalide - données manquantes', 400);
                } else {
                    http_response_code(404);
                    require_once __DIR__ . '/../views/errors/404.php';
                    exit;
                }
            }
            
        } catch (Exception $e) {
            error_log("💥 EXCEPTION CAPTURÉE: " . $e->getMessage());
            
            if ($this->isAjaxRequest()) {
                $this->jsonError('Erreur interne du serveur: ' . $e->getMessage(), 500);
            } else {
                header('Location: /?page=admin_users&error=server_error');
                exit;
            }
        }
    }

    // ... autres méthodes existantes ...

    public function handlePost($postData)
    {
        $userModel = new User();

        if (isset($postData['delete_user_id'])) {
            $userModel->deleteUserById($postData['delete_user_id']);
        }

        if (isset($postData['edit_user_id'])) {
            // Ici tu peux gérer la modification (à implémenter)
        }

        header('Location: /?page=admin_users');
        exit;
    }

    public function editUserForm($id)
    {
        $userModel = new User();
        $user = $userModel->getUserById($id);

        if (!$user) {
            header('Location: /?page=admin_users');
            exit;
        }

        require_once __DIR__ . '/../views/admin/edit_user.php';
    }

    public function updateUser($postData)
    {
        if (!isset($postData['id'])) {
            header('Location: /?page=admin_users');
            exit;
        }

        $id = $postData['id'];
        $lastName = htmlspecialchars($postData['last_name'] ?? '');
        $firstName = htmlspecialchars($postData['first_name'] ?? '');
        $email = htmlspecialchars($postData['email'] ?? '');
        $phone = htmlspecialchars($postData['phone'] ?? '');
        $role = htmlspecialchars($postData['role'] ?? 'user');
        $status = htmlspecialchars($postData['status'] ?? 'offline');

        $userModel = new User();
        
        try {
            $success = $userModel->updateUser($id, $lastName, $firstName, $email, $phone, $role, $status);
            
            if ($this->isAjaxRequest()) {
                if ($success) {
                    $this->jsonSuccess(['message' => 'Utilisateur modifié avec succès']);
                } else {
                    $this->jsonError('Échec de la modification', 500);
                }
            } else {
                header('Location: /?page=admin_users' . ($success ? '&success=updated' : '&error=update_failed'));
                exit;
            }
            
        } catch (Exception $e) {
            error_log("Erreur modification utilisateur: " . $e->getMessage());
            
            if ($this->isAjaxRequest()) {
                $this->jsonError('Erreur interne du serveur', 500);
            } else {
                header('Location: /?page=admin_users&error=server_error');
                exit;
            }
        }
    }

    /**
     * Vérifie si la requête est une requête AJAX
     */
    private function isAjaxRequest(): bool
    {
        $result = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        error_log("isAjaxRequest: " . ($result ? 'TRUE' : 'FALSE'));
        return $result;
    }

    /**
     * Retourne une réponse JSON d'erreur
     */
    private function jsonError(string $message, int $code = 400): void
    {
        error_log("📤 Envoi jsonError: $message (code: $code)");
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $message,
            'code' => $code
        ]);
        exit;
    }

    /**
     * Retourne une réponse JSON de succès
     */
    private function jsonSuccess(array $data = []): void
    {
        error_log("📤 Envoi jsonSuccess: " . print_r($data, true));
        header('Content-Type: application/json');
        echo json_encode(array_merge([
            'success' => true
        ], $data));
        exit;
    }
}