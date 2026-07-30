<?php

require_once '../app/core/Auth.php';
require_once '../app/models/Utilisateur.php';

class ProfilController
{
    private $utilisateurModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        Auth::verifierConnexion();

        $this->utilisateurModel = new Utilisateur();
    }

    public function index()
    {
        require_once '../app/views/profil/index.php';
    }

    public function parametres()
    {
        /*
         * Affichage normal de la page.
         */
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once '../app/views/profil/parametres.php';
            return;
        }

        $action = $_POST['action'] ?? '';

        if ($action !== 'changer_mot_de_passe') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=parametres-compte'
            );
            exit;
        }

        $motDePasseActuel =
            $_POST['mot_de_passe_actuel'] ?? '';

        $nouveauMotDePasse =
            $_POST['nouveau_mot_de_passe'] ?? '';

        $confirmationMotDePasse =
            $_POST['confirmation_mot_de_passe'] ?? '';

        /*
         * Vérification des champs.
         */
        if (
            $motDePasseActuel === '' ||
            $nouveauMotDePasse === '' ||
            $confirmationMotDePasse === ''
        ) {
            $_SESSION['error'] =
                'Veuillez remplir tous les champs du formulaire.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=parametres-compte'
            );
            exit;
        }

        /*
         * Longueur minimale.
         */
        if (strlen($nouveauMotDePasse) < 8) {
            $_SESSION['error'] =
                'Le nouveau mot de passe doit contenir au moins 8 caractères.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=parametres-compte'
            );
            exit;
        }

        /*
         * Vérification de la confirmation.
         */
        if ($nouveauMotDePasse !== $confirmationMotDePasse) {
            $_SESSION['error'] =
                'La confirmation ne correspond pas au nouveau mot de passe.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=parametres-compte'
            );
            exit;
        }

        $idUtilisateur =
            (int)($_SESSION['id_utilisateur'] ?? 0);

        $utilisateur =
            $this->utilisateurModel->getById($idUtilisateur);

        if (!$utilisateur) {
            $_SESSION['error'] =
                'Utilisateur introuvable. Veuillez vous reconnecter.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=logout'
            );
            exit;
        }

        $motDePasseBDD =
            $utilisateur['MOT_DE_PASSE']
            ?? $utilisateur['mot_de_passe']
            ?? '';

        /*
         * Vérification du mot de passe actuel.
         *
         * La deuxième condition permet temporairement
         * de gérer les anciens mots de passe en clair.
         */
        $motDePasseActuelValide =
            password_verify(
                $motDePasseActuel,
                $motDePasseBDD
            )
            ||
            $motDePasseActuel === $motDePasseBDD;

        if (!$motDePasseActuelValide) {
            $_SESSION['error'] =
                'Le mot de passe actuel est incorrect.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=parametres-compte'
            );
            exit;
        }

        /*
         * Le nouveau mot de passe doit être différent
         * du mot de passe actuel.
         */
        $memeMotDePasse =
            password_verify(
                $nouveauMotDePasse,
                $motDePasseBDD
            )
            ||
            $nouveauMotDePasse === $motDePasseBDD;

        if ($memeMotDePasse) {
            $_SESSION['error'] =
                'Le nouveau mot de passe doit être différent du mot de passe actuel.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=parametres-compte'
            );
            exit;
        }

        $resultat =
            $this->utilisateurModel->changerMotDePasse(
                $idUtilisateur,
                $nouveauMotDePasse
            );

        if (!$resultat) {
            $_SESSION['error'] =
                'Une erreur est survenue lors du changement du mot de passe.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=parametres-compte'
            );
            exit;
        }

        /*
         * L’utilisateur n’a plus besoin de passer
         * par le changement obligatoire.
         */
        $_SESSION['doit_changer_mdp'] = 0;

        $_SESSION['success'] =
            'Votre mot de passe a été modifié avec succès.';

        header(
            'Location: ' .
            BASE_URL .
            '?page=parametres-compte'
        );
        exit;
    }
}