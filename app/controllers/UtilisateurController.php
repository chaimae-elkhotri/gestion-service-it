<?php

require_once '../app/models/Utilisateur.php';
require_once '../app/core/Auth.php';

class UtilisateurController extends Controller
{
    private $utilisateurModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->utilisateurModel = new Utilisateur();
    }

    /*
     * Liste des utilisateurs.
     */
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

    /*
     * Formulaire d’ajout.
     */
    public function create()
    {
        Auth::autoriser(['Administrateur']);

        try {
            $database = new Database();
            $db = $database->connect();

            $sql = "
                SELECT
                    ID_ROLE AS id_role,
                    NOM_ROLE AS nom_role
                FROM role
                ORDER BY ID_ROLE ASC
            ";

            $stmt = $db->prepare($sql);
            $stmt->execute();

            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->view('utilisateurs/create', [
                'roles' => $roles
            ]);

            return;

        } catch (Throwable $e) {
            $_SESSION['error'] =
                'Impossible de charger le formulaire d’ajout.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );

            exit;
        }
    }

    /*
     * Enregistrer un utilisateur manuellement.
     */
    public function store()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?page=utilisateurs');
            exit;
        }

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');

        $email = mb_strtolower(
            trim($_POST['email'] ?? ''),
            'UTF-8'
        );

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
            $_SESSION['error'] =
                'Veuillez remplir tous les champs obligatoires.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-utilisateur'
            );
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] =
                'L’adresse email saisie est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-utilisateur'
            );
            exit;
        }

        if (strlen($motDePasse) < 8) {
            $_SESSION['error'] =
                'Le mot de passe doit contenir au moins 8 caractères.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-utilisateur'
            );
            exit;
        }

        if (!in_array($idRole, [1, 2, 3], true)) {
            $_SESSION['error'] =
                'Le rôle sélectionné est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-utilisateur'
            );
            exit;
        }

        if (!in_array($statut, ['Actif', 'Inactif'], true)) {
            $_SESSION['error'] =
                'Le statut sélectionné est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-utilisateur'
            );
            exit;
        }

        $utilisateurExistant =
            $this->utilisateurModel->trouverParEmail($email);

        if ($utilisateurExistant) {
            $_SESSION['error'] =
                'Cette adresse email est déjà utilisée.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-utilisateur'
            );
            exit;
        }

        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,

            'mot_de_passe' => password_hash(
                $motDePasse,
                PASSWORD_DEFAULT
            ),

            'tel' => $tel,
            'statut' => $statut,
            'id_role' => $idRole
        ];

        try {
            $resultat = $this->utilisateurModel->insert($data);

            if ($resultat) {
                $_SESSION['success'] =
                    'Utilisateur ajouté avec succès. Il devra modifier son mot de passe lors de sa première connexion.';
            } else {
                $_SESSION['error'] =
                    'Impossible d’ajouter cet utilisateur.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Impossible d’ajouter cet utilisateur. Vérifiez que l’adresse email n’existe pas déjà.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-utilisateur'
            );
            exit;
        }

        header('Location: ' . BASE_URL . '?page=utilisateurs');
        exit;
    }

    /*
     * Formulaire de modification.
     */
    public function edit()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] =
                'Utilisateur invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );

            exit;
        }

        $utilisateur =
            $this->utilisateurModel->getById($id);

        if (!$utilisateur) {
            $_SESSION['error'] =
                'Utilisateur introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );

            exit;
        }

        try {
            $database = new Database();
            $db = $database->connect();

            $sql = "
                SELECT
                    ID_ROLE AS id_role,
                    NOM_ROLE AS nom_role
                FROM role
                ORDER BY ID_ROLE ASC
            ";

            $stmt = $db->prepare($sql);
            $stmt->execute();

            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->view('utilisateurs/edit', [
                'utilisateur' => $utilisateur,
                'roles' => $roles
            ]);

            return;

        } catch (Throwable $e) {
            $_SESSION['error'] =
                'Impossible de charger le formulaire de modification.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );

            exit;
        }
    }

    /*
     * Mettre à jour un utilisateur.
     */
    public function update()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        $id = (int)($_POST['id_utilisateur'] ?? 0);
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');

        $email = mb_strtolower(
            trim($_POST['email'] ?? ''),
            'UTF-8'
        );

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
            $_SESSION['error'] =
                'Veuillez remplir tous les champs obligatoires.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-utilisateur&id=' .
                $id
            );
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] =
                'L’adresse email saisie est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-utilisateur&id=' .
                $id
            );
            exit;
        }

        if (!in_array($idRole, [1, 2, 3], true)) {
            $_SESSION['error'] =
                'Le rôle sélectionné est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-utilisateur&id=' .
                $id
            );
            exit;
        }

        if (!in_array($statut, ['Actif', 'Inactif'], true)) {
            $_SESSION['error'] =
                'Le statut sélectionné est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-utilisateur&id=' .
                $id
            );
            exit;
        }

        if (
            $nouveauMotDePasse !== '' &&
            strlen($nouveauMotDePasse) < 8
        ) {
            $_SESSION['error'] =
                'Le nouveau mot de passe doit contenir au moins 8 caractères.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-utilisateur&id=' .
                $id
            );
            exit;
        }

        /*
         * Vérification de l’unicité de l’adresse email.
         */
        $utilisateurExistant =
            $this->utilisateurModel->trouverParEmail($email);

        if ($utilisateurExistant) {
            $idExistant = (int)(
                $utilisateurExistant['ID_UTILISATEUR']
                ?? $utilisateurExistant['id_utilisateur']
                ?? 0
            );

            if ($idExistant !== $id) {
                $_SESSION['error'] =
                    'Cette adresse email est déjà utilisée par un autre utilisateur.';

                header(
                    'Location: ' .
                    BASE_URL .
                    '?page=modifier-utilisateur&id=' .
                    $id
                );
                exit;
            }
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
         * Si l’administrateur saisit un nouveau mot de passe,
         * il devient temporaire.
         */
        if ($nouveauMotDePasse !== '') {
            $data['mot_de_passe'] = password_hash(
                $nouveauMotDePasse,
                PASSWORD_DEFAULT
            );
        }

        try {
            $resultat =
                $this->utilisateurModel->update(
                    $id,
                    $data
                );

            if (!$resultat) {
                $_SESSION['error'] =
                    'Aucune modification n’a été enregistrée.';

                header(
                    'Location: ' .
                    BASE_URL .
                    '?page=modifier-utilisateur&id=' .
                    $id
                );
                exit;
            }

            /*
             * Actualisation de la session si l’administrateur
             * modifie son propre compte.
             */
            if ((int)Auth::idUtilisateur() === $id) {
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['email'] = $email;
                $_SESSION['tel'] = $tel;
                $_SESSION['telephone'] = $tel;
                $_SESSION['statut'] = $statut;
                $_SESSION['id_role'] = $idRole;

                if ($nouveauMotDePasse !== '') {
                    $_SESSION['doit_changer_mdp'] = 1;
                }
            }

            if ($nouveauMotDePasse !== '') {
                $_SESSION['success'] =
                    'Utilisateur modifié avec succès. Le nouveau mot de passe est temporaire.';
            } else {
                $_SESSION['success'] =
                    'Utilisateur modifié avec succès.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Impossible de modifier cet utilisateur. Vérifiez les informations saisies.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-utilisateur&id=' .
                $id
            );
            exit;
        }

        header('Location: ' . BASE_URL . '?page=utilisateurs');
        exit;
    }

    /*
     * Importer plusieurs utilisateurs depuis un fichier CSV.
     */
    public function importCsv()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        if (
            !isset($_FILES['fichier_csv']) ||
            $_FILES['fichier_csv']['error'] !== UPLOAD_ERR_OK
        ) {
            $_SESSION['error'] =
                'Veuillez sélectionner un fichier CSV valide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        $fichier = $_FILES['fichier_csv'];

        $extension = strtolower(
            pathinfo(
                $fichier['name'],
                PATHINFO_EXTENSION
            )
        );

        if ($extension !== 'csv') {
            $_SESSION['error'] =
                'Le fichier sélectionné doit être au format CSV.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        if ((int)$fichier['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] =
                'Le fichier CSV ne doit pas dépasser 2 Mo.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        $handle = fopen(
            $fichier['tmp_name'],
            'r'
        );

        if ($handle === false) {
            $_SESSION['error'] =
                'Impossible de lire le fichier CSV.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        /*
         * Détection du séparateur utilisé par le fichier :
         * point-virgule ou virgule.
         */
        $premiereLigne = fgets($handle);

        if ($premiereLigne === false) {
            fclose($handle);

            $_SESSION['error'] =
                'Le fichier CSV est vide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        $nombrePointsVirgules =
            substr_count($premiereLigne, ';');

        $nombreVirgules =
            substr_count($premiereLigne, ',');

        $separateur =
            $nombrePointsVirgules >= $nombreVirgules
                ? ';'
                : ',';

        rewind($handle);

        $entetes = fgetcsv(
            $handle,
            0,
            $separateur,
            '"',
            ''
        );

        if ($entetes === false || empty($entetes)) {
            fclose($handle);

            $_SESSION['error'] =
                'Les en-têtes du fichier CSV sont invalides.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        $entetesNormalisees = [];

        foreach ($entetes as $entete) {
            $entetesNormalisees[] =
                $this->normaliserEnteteCsv($entete);
        }

        $colonnesObligatoires = [
            'nom',
            'prenom',
            'email',
            'telephone',
            'statut',
            'id_role',
            'mot_de_passe_temporaire'
        ];

        $colonnesManquantes = array_diff(
            $colonnesObligatoires,
            $entetesNormalisees
        );

        if (!empty($colonnesManquantes)) {
            fclose($handle);

            $_SESSION['error'] =
                'Colonnes manquantes dans le fichier CSV : ' .
                implode(', ', $colonnesManquantes) .
                '.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        $resultatImport = [
            'ajoutes' => 0,
            'doublons' => 0,
            'erreurs' => []
        ];

        $numeroLigne = 1;

        while (
            (
                $ligne = fgetcsv(
                    $handle,
                    0,
                    $separateur,
                    '"',
                    ''
                )
            ) !== false
        ) {
            $numeroLigne++;

            if ($this->ligneCsvVide($ligne)) {
                continue;
            }

            /*
             * Compléter une ligne qui contient des colonnes vides
             * à la fin.
             */
            if (
                count($ligne) <
                count($entetesNormalisees)
            ) {
                $ligne = array_pad(
                    $ligne,
                    count($entetesNormalisees),
                    ''
                );
            }

            /*
             * Retirer seulement les colonnes supplémentaires vides.
             */
            while (
                count($ligne) >
                count($entetesNormalisees) &&
                trim((string)end($ligne)) === ''
            ) {
                array_pop($ligne);
            }

            if (
                count($ligne) !==
                count($entetesNormalisees)
            ) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : le nombre de colonnes est incorrect.';

                continue;
            }

            $donnees = array_combine(
                $entetesNormalisees,
                $ligne
            );

            if ($donnees === false) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : format incorrect.';

                continue;
            }

            $nom = trim($donnees['nom'] ?? '');
            $prenom = trim($donnees['prenom'] ?? '');

            $email = mb_strtolower(
                trim($donnees['email'] ?? ''),
                'UTF-8'
            );

            $telephone =
                trim($donnees['telephone'] ?? '');

            $motDePasseTemporaire =
                trim(
                    $donnees[
                        'mot_de_passe_temporaire'
                    ] ?? ''
                );

            $idRole =
                $this->convertirRoleImport(
                    $donnees['id_role'] ?? ''
                );

            $statut =
                $this->convertirStatutImport(
                    $donnees['statut'] ?? ''
                );

            if (
                $nom === '' ||
                $prenom === '' ||
                $email === ''
            ) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : le nom, le prénom et l’adresse email sont obligatoires.';

                continue;
            }

            if (
                !filter_var(
                    $email,
                    FILTER_VALIDATE_EMAIL
                )
            ) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : adresse email invalide (' .
                    $email .
                    ').';

                continue;
            }

            if ($idRole === null) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : rôle invalide. Utilisez 1, 2 ou 3.';

                continue;
            }

            if ($statut === null) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : statut invalide. Utilisez Actif ou Inactif.';

                continue;
            }

            if (strlen($motDePasseTemporaire) < 8) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : le mot de passe temporaire doit contenir au moins 8 caractères.';

                continue;
            }

            $utilisateurExistant =
                $this->utilisateurModel
                    ->trouverParEmail($email);

            if ($utilisateurExistant) {
                $resultatImport['doublons']++;
                continue;
            }

            $data = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,

                'mot_de_passe' => password_hash(
                    $motDePasseTemporaire,
                    PASSWORD_DEFAULT
                ),

                'tel' => $telephone,
                'statut' => $statut,
                'id_role' => $idRole
            ];

            try {
                $resultat =
                    $this->utilisateurModel->insert($data);

                if ($resultat) {
                    $resultatImport['ajoutes']++;
                } else {
                    $resultatImport['erreurs'][] =
                        'Ligne ' .
                        $numeroLigne .
                        ' : impossible d’ajouter ' .
                        $email .
                        '.';
                }
            } catch (Throwable $e) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : erreur lors de l’ajout de ' .
                    $email .
                    '.';
            }
        }

        fclose($handle);

        $_SESSION['resultat_import_utilisateurs'] =
            $resultatImport;

        header(
            'Location: ' .
            BASE_URL .
            '?page=utilisateurs'
        );
        exit;
    }

    /*
     * Télécharger un modèle de fichier CSV.
     */
    public function modeleImportCsv()
    {
        Auth::autoriser(['Administrateur']);

        header('Content-Type: text/csv; charset=UTF-8');

        header(
            'Content-Disposition: attachment; ' .
            'filename="modele_import_utilisateurs.csv"'
        );

        header('Pragma: no-cache');
        header('Expires: 0');

        $sortie = fopen('php://output', 'w');

        if ($sortie === false) {
            exit;
        }

        /*
         * BOM UTF-8 pour les accents dans Excel.
         */
        fwrite($sortie, "\xEF\xBB\xBF");

        fputcsv(
            $sortie,
            [
                'nom',
                'prenom',
                'email',
                'telephone',
                'statut',
                'id_role',
                'mot_de_passe_temporaire'
            ],
            ';',
            '"',
            ''
        );

        fputcsv(
            $sortie,
            [
                'El Khotri',
                'Chaimae',
                'chaimae.exemple@fsjes.ma',
                '0600000000',
                'Actif',
                '3',
                'Temporaire123'
            ],
            ';',
            '"',
            ''
        );

        fputcsv(
            $sortie,
            [
                'Amrani',
                'Youssef',
                'youssef.exemple@fsjes.ma',
                '0611111111',
                'Actif',
                '2',
                'Temporaire456'
            ],
            ';',
            '"',
            ''
        );

        fclose($sortie);
        exit;
    }

    /*
     * Supprimer un utilisateur.
     */
    public function delete()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] =
                'Utilisateur invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        /*
         * Empêcher l’administrateur de supprimer
         * son propre compte.
         */
        if ((int)Auth::idUtilisateur() === $id) {
            $_SESSION['error'] =
                'Vous ne pouvez pas supprimer votre propre compte.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=utilisateurs'
            );
            exit;
        }

        try {
            $resultat =
                $this->utilisateurModel->delete($id);

            if ($resultat) {
                $_SESSION['success'] =
                    'Utilisateur supprimé avec succès.';
            } else {
                $_SESSION['error'] =
                    'Impossible de supprimer cet utilisateur.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Impossible de supprimer cet utilisateur, car il est lié à d’autres données.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=utilisateurs'
        );
        exit;
    }

    /*
     * Normaliser le nom d’une colonne CSV.
     */
    private function normaliserEnteteCsv($entete)
    {
        $entete = trim((string)$entete);

        /*
         * Suppression du BOM UTF-8.
         */
        $entete = preg_replace(
            '/^\xEF\xBB\xBF/',
            '',
            $entete
        );

        $entete = mb_strtolower(
            $entete,
            'UTF-8'
        );

        $entete = str_replace(
            [' ', '-', '.', '/'],
            '_',
            $entete
        );

        /*
         * Quelques noms alternatifs acceptés.
         */
        $equivalences = [
            'tel' => 'telephone',
            'telephone_mobile' => 'telephone',
            'role' => 'id_role',
            'idrole' => 'id_role',
            'mot_de_passe' =>
                'mot_de_passe_temporaire',
            'password' =>
                'mot_de_passe_temporaire'
        ];

        return $equivalences[$entete] ?? $entete;
    }

    /*
     * Vérifier si une ligne CSV est vide.
     */
    private function ligneCsvVide($ligne)
    {
        foreach ($ligne as $valeur) {
            if (trim((string)$valeur) !== '') {
                return false;
            }
        }

        return true;
    }

    /*
     * Convertir le rôle du fichier CSV.
     */
    private function convertirRoleImport($role)
    {
        $role = mb_strtolower(
            trim((string)$role),
            'UTF-8'
        );

        $role = strtr($role, [
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e'
        ]);

        if (
            $role === '1' ||
            $role === 'admin' ||
            $role === 'administrateur'
        ) {
            return 1;
        }

        if (
            $role === '2' ||
            $role === 'technicien'
        ) {
            return 2;
        }

        if (
            $role === '3' ||
            $role === 'employe' ||
            $role === 'utilisateur'
        ) {
            return 3;
        }

        return null;
    }

    /*
     * Convertir le statut du fichier CSV.
     */
    private function convertirStatutImport($statut)
    {
        $statut = mb_strtolower(
            trim((string)$statut),
            'UTF-8'
        );

        if (
            $statut === 'actif' ||
            $statut === 'active' ||
            $statut === '1'
        ) {
            return 'Actif';
        }

        if (
            $statut === 'inactif' ||
            $statut === 'inactive' ||
            $statut === '0'
        ) {
            return 'Inactif';
        }

        return null;
    }
}