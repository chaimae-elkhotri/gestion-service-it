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

        if (strtolower(trim($statut)) !== 'actif') {
            $this->view('auth/login', [
                'erreur' =>
                    'Ce compte est inactif. Contactez l’administrateur.'
            ]);
            return;
        }

        $motDePasseValide = false;

        if (password_verify($motDePasseSaisi, $motDePasseBDD)) {
            $motDePasseValide = true;
        } elseif ($motDePasseSaisi === $motDePasseBDD) {
            /*
             * Compatibilité temporaire avec les anciens mots
             * de passe enregistrés en clair.
             */
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

        $_SESSION['doit_changer_mdp'] = (int)(
            $utilisateur['DOIT_CHANGER_MDP']
            ?? $utilisateur['doit_changer_mdp']
            ?? 0
        );

        /*
         * Redirection obligatoire vers le changement du mot de passe.
         */
        if ($_SESSION['doit_changer_mdp'] === 1) {
            header(
                'Location: ' .
                BASE_URL .
                '?page=changer-mot-de-passe-obligatoire'
            );
            exit;
        }

        header('Location: ' . BASE_URL . '?page=dashboard');
        exit;
    }

    public function changerMotDePasseObligatoire()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['id_utilisateur'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        /*
         * Si le mot de passe a déjà été changé,
         * retour au tableau de bord.
         */
        if ((int)($_SESSION['doit_changer_mdp'] ?? 0) !== 1) {
            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('auth/changer-mot-de-passe-obligatoire');
            return;
        }

        $nouveauMotDePasse =
            $_POST['nouveau_mot_de_passe'] ?? '';

        $confirmationMotDePasse =
            $_POST['confirmation_mot_de_passe'] ?? '';

        if (
            $nouveauMotDePasse === '' ||
            $confirmationMotDePasse === ''
        ) {
            $this->view(
                'auth/changer-mot-de-passe-obligatoire',
                [
                    'erreur' =>
                        'Veuillez remplir tous les champs.'
                ]
            );
            return;
        }

        if (strlen($nouveauMotDePasse) < 8) {
            $this->view(
                'auth/changer-mot-de-passe-obligatoire',
                [
                    'erreur' =>
                        'Le mot de passe doit contenir au moins 8 caractères.'
                ]
            );
            return;
        }

        if ($nouveauMotDePasse !== $confirmationMotDePasse) {
            $this->view(
                'auth/changer-mot-de-passe-obligatoire',
                [
                    'erreur' =>
                        'Les deux mots de passe ne correspondent pas.'
                ]
            );
            return;
        }

        $utilisateurModel = new Utilisateur();

        $utilisateur = $utilisateurModel->getById(
            (int)$_SESSION['id_utilisateur']
        );

        if (!$utilisateur) {
            session_destroy();

            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        $ancienMotDePasse =
            $utilisateur['MOT_DE_PASSE']
            ?? $utilisateur['mot_de_passe']
            ?? '';

        /*
         * Empêcher l’utilisateur de réutiliser
         * le mot de passe temporaire.
         */
        $memeMotDePasse =
            password_verify(
                $nouveauMotDePasse,
                $ancienMotDePasse
            )
            || $nouveauMotDePasse === $ancienMotDePasse;

        if ($memeMotDePasse) {
            $this->view(
                'auth/changer-mot-de-passe-obligatoire',
                [
                    'erreur' =>
                        'Le nouveau mot de passe doit être différent du mot de passe temporaire.'
                ]
            );
            return;
        }

        $resultat = $utilisateurModel->changerMotDePasse(
            (int)$_SESSION['id_utilisateur'],
            $nouveauMotDePasse
        );

        if (!$resultat) {
            $this->view(
                'auth/changer-mot-de-passe-obligatoire',
                [
                    'erreur' =>
                        'Une erreur est survenue. Veuillez réessayer.'
                ]
            );
            return;
        }

        $_SESSION['doit_changer_mdp'] = 0;

        $_SESSION['success'] =
            'Votre mot de passe a été modifié avec succès.';

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