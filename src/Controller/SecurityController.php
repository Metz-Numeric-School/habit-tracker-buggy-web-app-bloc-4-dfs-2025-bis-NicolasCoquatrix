<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Mns\Buggy\Core\AbstractController;

class SecurityController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login()
    {

        if(!empty($_SESSION['user']))
        {
            // Correction : la route /user/dashboard n'existe pas et je l'ai remplacé en /dashboard
            $_SESSION['admin'] ? header('Location: /admin/dashboard') : header('Location: /dashboard'); die;
        }

        if(!empty($_POST)) {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userRepository->findByEmail($username);

            if($user) {
                // On vérifie le mot de passe
                if(password_verify($password, $user->getPassword())) {
    
                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'username' => $user->getFirstname(),
                    ];

                    if($user->getIsadmin()) {
                        header('Location: /admin/dashboard');
                        $_SESSION['admin'] = $user->getIsAdmin();
                        exit;
                    }
                    else
                    {
                        header('Location: /dashboard');
                    }
                }
                else
                {
                    // Correction : Traduction en français
                    $error = 'Email ou mot de passe invalide';
                }
            }

            // Correction : Traduction en français
            $error = 'Email ou mot de passe invalide';
        }

        return $this->render('security/login.html.php', [
            'title' => 'Login',
            'error' => $error ?? null,
        ]);
    }

    public function logout()
    {
        unset($_SESSION['user']);
        unset($_SESSION['admin']);
        session_destroy();
        header('Location: /login');
        exit;
    }
}