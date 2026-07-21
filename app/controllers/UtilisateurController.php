<?php

require_once '../app/models/Utilisateur.php';
require_once '../app/core/Auth.php';

class UtilisateurController extends Controller
{
    private $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    // Liste des utilisateurs
    public function index()
    {
        Auth::autoriser(['Administrateur']);

        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            $utilisateurs = $this->utilisateurModel->search($search);
        } else {
            $utilisateurs = $this->utilisateurModel->getAll();
        }

        $this->view('utilisateurs/index', [
            'utilisateurs' => $utilisateurs
        ]);
    }

    // Formulaire d'ajout
    public function create()
    {
        Auth::autoriser(['Administrateur']);

        $this->view('utilisateurs/create');
    }

    // Enregistrer un utilisateur
    public function store()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?page=utilisateurs');
            exit;
        }

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';
        $tel = trim($_POST['tel'] ?? '');
        $statut = trim($_POST['statut'] ?? 'Actif');
        $idRole = (int)($_POST['id_role'] ?? 0);

        if (
            $nom === '' ||
            $prenom === '' ||
            $email === '' ||
            $motDePasse === '' ||
            $idRole <= 0
        ) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';

            header('Location: ' . BASE_URL . '?page=ajouter-utilisateur');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Adresse email invalide.';

            header('Location: ' . BASE_URL . '?page=ajouter-utilisateur');
            exit;
        }

        // Mot de passe sécurisé
        $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mot_de_passe' => $motDePasseHash,
            'tel' => $tel,
            'statut' => $statut,
            'id_role' => $idRole
        ];

        try {
            $this->utilisateurModel->insert($data);

            $_SESSION['success'] = 'Utilisateur ajouté avec succès.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Impossible d’ajouter cet utilisateur. Vérifiez que l’email n’existe pas déjà.';

            header('Location: ' . BASE_URL . '?page=ajouter-utilisateur');
            exit;
        }

        header('Location: ' . BASE_URL . '?page=utilisateurs');
        exit;
    }

    // Afficher le formulaire de modification
    public function edit()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'Utilisateur invalide.';

            header('Location: ' . BASE_URL . '?page=utilisateurs');
            exit;
        }

        $utilisateur = $this->utilisateurModel->getById($id);

        if (!$utilisateur) {
            $_SESSION['error'] = 'Utilisateur introuvable.';

            header('Location: ' . BASE_URL . '?page=utilisateurs');
            exit;
        }

        $this->view('utilisateurs/edit', [
            'utilisateur' => $utilisateur
        ]);
    }

    // Mettre à jour un utilisateur
    public function update()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?page=utilisateurs');
            exit;
        }

        $id = (int)($_POST['id_utilisateur'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tel = trim($_POST['tel'] ?? '');
        $statut = trim($_POST['statut'] ?? 'Actif');
        $idRole = (int)($_POST['id_role'] ?? 0);
        $nouveauMotDePasse = $_POST['mot_de_passe'] ?? '';

        if (
            $id <= 0 ||
            $nom === '' ||
            $prenom === '' ||
            $email === '' ||
            $idRole <= 0
        ) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';

            header('Location: ' . BASE_URL . '?page=modifier-utilisateur&id=' . $id);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Adresse email invalide.';

            header('Location: ' . BASE_URL . '?page=modifier-utilisateur&id=' . $id);
            exit;
        }

        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'tel' => $tel,
            'statut' => $statut,
            'id_role' => $idRole
        ];

        /*
         * Le mot de passe est ajouté uniquement si l'utilisateur
         * a saisi une nouvelle valeur.
         */
        if ($nouveauMotDePasse !== '') {
            $data['mot_de_passe'] = password_hash(
                $nouveauMotDePasse,
                PASSWORD_DEFAULT
            );
        }

        try {
            $this->utilisateurModel->update($id, $data);

            /*
             * Si l'administrateur modifie son propre compte,
             * on actualise les informations de sa session.
             */
            if ((int)Auth::idUtilisateur() === $id) {
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['email'] = $email;
                $_SESSION['tel'] = $tel;
                $_SESSION['statut'] = $statut;
                $_SESSION['id_role'] = $idRole;
            }

            $_SESSION['success'] = 'Utilisateur modifié avec succès.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Impossible de modifier cet utilisateur. Vérifiez les informations saisies.';

            header('Location: ' . BASE_URL . '?page=modifier-utilisateur&id=' . $id);
            exit;
        }

        header('Location: ' . BASE_URL . '?page=utilisateurs');
        exit;
    }

    // Supprimer un utilisateur
    public function delete()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'Utilisateur invalide.';

            header('Location: ' . BASE_URL . '?page=utilisateurs');
            exit;
        }

        // Empêcher l'administrateur de supprimer son propre compte
        if ((int)Auth::idUtilisateur() === $id) {
            $_SESSION['error'] = 'Vous ne pouvez pas supprimer votre propre compte.';

            header('Location: ' . BASE_URL . '?page=utilisateurs');
            exit;
        }

        try {
            $this->utilisateurModel->delete($id);

            $_SESSION['success'] = 'Utilisateur supprimé avec succès.';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Impossible de supprimer cet utilisateur car il est peut-être lié à d’autres données.';
        }

        header('Location: ' . BASE_URL . '?page=utilisateurs');
        exit;
    }
}