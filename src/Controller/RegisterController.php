<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Mns\Buggy\Core\AbstractController;

class RegisterController extends AbstractController
{

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        $errors = [];

        if(!empty($_POST['user'])) {

            $user = $_POST['user'];
            
            if(empty($user['lastname']))
                $errors['lastname'] = 'Le Nom est obligatoire';

            if(empty($user['firstname']))
                $errors['firstname'] = 'Le Prénom est obligatoire';

            if(empty($user['email']))
                $errors['email'] = 'L\'email est obligatoire';

            if(empty($user['password']))
                $errors['password'] = 'Le mot de passe est obligatoire';

            if(empty($user['password'])) {
                $errors['password'] = 'Le mot de passe est obligatoire';
                // Correction : Mot de passe fort obligatoire
            } elseif(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};:\'",.<>\/?]).{8,}$/', $user['password'])) {
                $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un caractère spécial autorisé (!@#$%^&*()_+-=[]{},.;:\'"<>?/).';
            }

            if(count($errors) == 0) {
                // Par défaut l'utilisateur n'est pas admin
                $user['isadmin'] = 0;

                // Correction : Il faut hasher le mot de passe
                $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

                // On persite les informations en BDD
                $id = $this->userRepository->insert($user);

                // On authentifie l'utilsateur directement
                $_SESSION['user'] = [
                    'id' => $id,
                    'username' => $user['firstname']
                ];
                // Correction : Par défaut l'utilisateur n'est pas admin
                $_SESSION['admin'] = 0;

                // On redirige vers son dashboard
                // Correction : La route /user/ticket n'existe pas, je l'ai remplacé par /dashboard
                header("Location: /dashboard");
                exit;
            }
        }

        return $this->render('register/index.html.php', [
            'title' => 'Inscription',
            'errors' => $errors
        ]);
    }


}