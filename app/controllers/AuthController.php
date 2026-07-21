<?php

require_once '../app/models/Utilisateur.php';

class AuthController extends Controller
{
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('auth/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $motDePasseSaisi = $_POST['mot_de_passe'] ?? '';

        if ($email === '' || $motDePasseSaisi === '') {
            $this->view('auth/login', [
                'erreur' => 'Veuillez remplir tous les champs.'
            ]);
            return;
        }

        $utilisateurModel = new Utilisateur();
        $utilisateur = $utilisateurModel->trouverParEmail($email);

        if (!$utilisateur) {
            $this->view('auth/login', [
                'erreur' => 'Email ou mot de passe incorrect.'
            ]);
            return;
        }

        $motDePasseBDD =
            $utilisateur['MOT_DE_PASSE']
            ?? $utilisateur['mot_de_passe']
            ?? '';

        $statut =
            $utilisateur['STATUT']
            ?? $utilisateur['statut']
            ?? 'Inactif';

        if (strtolower($statut) !== 'actif') {
            $this->view('auth/login', [
                'erreur' => 'Ce compte est inactif. Contactez l’administrateur.'
            ]);
            return;
        }

        $motDePasseValide = false;

        if (password_verify($motDePasseSaisi, $motDePasseBDD)) {
            $motDePasseValide = true;
        } elseif ($motDePasseSaisi === $motDePasseBDD) {
            // Compatibilité temporaire avec les anciens mots de passe en clair
            $motDePasseValide = true;
        }

        if (!$motDePasseValide) {
            $this->view('auth/login', [
                'erreur' => 'Email ou mot de passe incorrect.'
            ]);
            return;
        }

        session_regenerate_id(true);

        $_SESSION['id_utilisateur'] =
            $utilisateur['ID_UTILISATEUR']
            ?? $utilisateur['id_utilisateur'];

        $_SESSION['nom'] =
            $utilisateur['NOM']
            ?? $utilisateur['nom']
            ?? '';

        $_SESSION['prenom'] =
            $utilisateur['PRENOM']
            ?? $utilisateur['prenom']
            ?? '';

        $_SESSION['email'] =
            $utilisateur['EMAIL']
            ?? $utilisateur['email']
            ?? '';

        $_SESSION['tel'] =
            $utilisateur['TEL']
            ?? $utilisateur['TELEPHONE']
            ?? $utilisateur['tel']
            ?? $utilisateur['telephone']
            ?? '';

        $_SESSION['statut'] = $statut;

        $_SESSION['id_role'] =
            $utilisateur['ID_ROLE']
            ?? $utilisateur['id_role']
            ?? null;

        $_SESSION['nom_role'] =
            $utilisateur['NOM_ROLE']
            ?? $utilisateur['nom_role']
            ?? '';

        header('Location: ' . BASE_URL . '?page=dashboard');
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        header('Location: ' . BASE_URL . '?page=login');
        exit;
    }
}