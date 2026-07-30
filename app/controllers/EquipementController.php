<?php

require_once '../app/models/Equipement.php';
require_once '../app/models/Categorie.php';
require_once '../app/models/Local.php';
require_once '../app/models/Historique.php';
require_once '../app/core/Auth.php';

class EquipementController extends Controller
{
    private $equipementModel;
    private $categorieModel;
    private $localModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->equipementModel = new Equipement();
        $this->categorieModel = new Categorie();
        $this->localModel = new Local();
    }

    public function index()
    {
        Auth::autoriser(['Administrateur']);

        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            $equipements =
                $this->equipementModel->search($search);
        } else {
            $equipements =
                $this->equipementModel->getAll();
        }

        $categories =
            $this->categorieModel->getAll();

        $locals =
            $this->localModel->getAll();

        $this->view('equipements/index', [
            'equipements' => $equipements,
            'categories' => $categories,
            'locals' => $locals
        ]);
    }

    public function create()
    {
        Auth::autoriser(['Administrateur']);

        $categories =
            $this->categorieModel->getAll();

        $locals =
            $this->localModel->getAll();

        $this->view('equipements/create', [
            'categories' => $categories,
            'locals' => $locals
        ]);
    }

    public function store()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        $numeroSerie =
            trim($_POST['numero_serie'] ?? '');

        $marque =
            trim($_POST['marque'] ?? '');

        $modele =
            trim($_POST['modele'] ?? '');

        $dateAchat =
            trim($_POST['date_achat'] ?? '');

        $statut =
            $this->normaliserStatut(
                $_POST['statut'] ?? ''
            );

        $idCategorie =
            (int)($_POST['id_categorie'] ?? 0);

        $idLocal =
            (int)($_POST['id_local'] ?? 0);

        if (
            $numeroSerie === '' ||
            $marque === '' ||
            $modele === '' ||
            $dateAchat === '' ||
            $statut === null ||
            $idCategorie <= 0 ||
            $idLocal <= 0
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement tous les champs.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-equipement'
            );
            exit;
        }

        if (!$this->dateValide($dateAchat)) {
            $_SESSION['error'] =
                'La date d’achat est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-equipement'
            );
            exit;
        }

        if (
            $this->equipementModel
                ->trouverParNumeroSerie($numeroSerie)
        ) {
            $_SESSION['error'] =
                'Ce numéro de série existe déjà.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-equipement'
            );
            exit;
        }

        if (
            !$this->equipementModel
                ->categorieExiste($idCategorie)
        ) {
            $_SESSION['error'] =
                'La catégorie sélectionnée est introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-equipement'
            );
            exit;
        }

        if (
            !$this->equipementModel
                ->localExiste($idLocal)
        ) {
            $_SESSION['error'] =
                'Le local sélectionné est introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-equipement'
            );
            exit;
        }

        $data = [
            'numero_serie' => $numeroSerie,
            'marque' => $marque,
            'modele' => $modele,
            'date_achat' => $dateAchat,
            'statut' => $statut,
            'id_categorie' => $idCategorie,
            'id_local' => $idLocal
        ];

        try {
            $idEquipement =
                $this->equipementModel->insert($data);

            if ($idEquipement) {
                Historique::enregistrer(
                    'equipement',
                    $idEquipement,
                    'Ajout',
                    null,
                    'Ajout équipement : ' .
                    $marque .
                    ' ' .
                    $modele .
                    ' - Série : ' .
                    $numeroSerie
                );

                $_SESSION['success'] =
                    'Équipement ajouté avec succès.';
            } else {
                $_SESSION['error'] =
                    'Impossible d’ajouter l’équipement.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Impossible d’ajouter l’équipement.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=equipements'
        );
        exit;
    }

    public function edit()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] =
                'Équipement invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        $equipement =
            $this->equipementModel->getById($id);

        if (!$equipement) {
            $_SESSION['error'] =
                'Équipement introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        $categories =
            $this->categorieModel->getAll();

        $locals =
            $this->localModel->getAll();

        $this->view('equipements/edit', [
            'equipement' => $equipement,
            'categories' => $categories,
            'locals' => $locals
        ]);
    }

    public function update()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        $idEquipement =
            (int)($_POST['id_equipement'] ?? 0);

        $numeroSerie =
            trim($_POST['numero_serie'] ?? '');

        $marque =
            trim($_POST['marque'] ?? '');

        $modele =
            trim($_POST['modele'] ?? '');

        $dateAchat =
            trim($_POST['date_achat'] ?? '');

        $statut =
            $this->normaliserStatut(
                $_POST['statut'] ?? ''
            );

        $idCategorie =
            (int)($_POST['id_categorie'] ?? 0);

        $idLocal =
            (int)($_POST['id_local'] ?? 0);

        if (
            $idEquipement <= 0 ||
            $numeroSerie === '' ||
            $marque === '' ||
            $modele === '' ||
            $dateAchat === '' ||
            $statut === null ||
            $idCategorie <= 0 ||
            $idLocal <= 0
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement tous les champs.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-equipement&id=' .
                $idEquipement
            );
            exit;
        }

        if (!$this->dateValide($dateAchat)) {
            $_SESSION['error'] =
                'La date d’achat est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-equipement&id=' .
                $idEquipement
            );
            exit;
        }

        $doublon =
            $this->equipementModel
                ->trouverParNumeroSerie(
                    $numeroSerie,
                    $idEquipement
                );

        if ($doublon) {
            $_SESSION['error'] =
                'Ce numéro de série appartient déjà à un autre équipement.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-equipement&id=' .
                $idEquipement
            );
            exit;
        }

        if (
            !$this->equipementModel
                ->categorieExiste($idCategorie)
        ) {
            $_SESSION['error'] =
                'La catégorie sélectionnée est introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-equipement&id=' .
                $idEquipement
            );
            exit;
        }

        if (
            !$this->equipementModel
                ->localExiste($idLocal)
        ) {
            $_SESSION['error'] =
                'Le local sélectionné est introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-equipement&id=' .
                $idEquipement
            );
            exit;
        }

        $ancienEquipement =
            $this->equipementModel
                ->getById($idEquipement);

        $data = [
            'id_equipement' => $idEquipement,
            'numero_serie' => $numeroSerie,
            'marque' => $marque,
            'modele' => $modele,
            'date_achat' => $dateAchat,
            'statut' => $statut,
            'id_categorie' => $idCategorie,
            'id_local' => $idLocal
        ];

        try {
            $resultat =
                $this->equipementModel->update($data);

            if ($resultat) {
                Historique::enregistrer(
                    'equipement',
                    $idEquipement,
                    'Modification',
                    json_encode(
                        $ancienEquipement,
                        JSON_UNESCAPED_UNICODE
                    ),
                    json_encode(
                        $data,
                        JSON_UNESCAPED_UNICODE
                    )
                );

                $_SESSION['success'] =
                    'Équipement modifié avec succès.';
            } else {
                $_SESSION['error'] =
                    'Impossible de modifier l’équipement.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Impossible de modifier l’équipement.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=equipements'
        );
        exit;
    }

    public function importCsv()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        if (
            !isset($_FILES['fichier_csv']) ||
            $_FILES['fichier_csv']['error']
                !== UPLOAD_ERR_OK
        ) {
            $_SESSION['error'] =
                'Veuillez sélectionner un fichier CSV valide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
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
                'Le fichier doit être au format CSV.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        if ((int)$fichier['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] =
                'Le fichier CSV ne doit pas dépasser 2 Mo.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
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
                '?page=equipements'
            );
            exit;
        }

        $premiereLigne = fgets($handle);

        if ($premiereLigne === false) {
            fclose($handle);

            $_SESSION['error'] =
                'Le fichier CSV est vide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        $separateur =
            substr_count($premiereLigne, ';')
            >= substr_count($premiereLigne, ',')
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
                '?page=equipements'
            );
            exit;
        }

        $entetesNormalisees = [];

        foreach ($entetes as $entete) {
            $entetesNormalisees[] =
                $this->normaliserEnteteCsv($entete);
        }

        $colonnesObligatoires = [
            'numero_serie',
            'marque',
            'modele',
            'date_achat',
            'statut',
            'id_categorie',
            'id_local'
        ];

        $colonnesManquantes = array_diff(
            $colonnesObligatoires,
            $entetesNormalisees
        );

        if (!empty($colonnesManquantes)) {
            fclose($handle);

            $_SESSION['error'] =
                'Colonnes CSV manquantes : ' .
                implode(', ', $colonnesManquantes) .
                '.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
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
                    ' : nombre de colonnes incorrect.';

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

            $numeroSerie =
                trim($donnees['numero_serie'] ?? '');

            $marque =
                trim($donnees['marque'] ?? '');

            $modele =
                trim($donnees['modele'] ?? '');

            $dateAchat =
                trim($donnees['date_achat'] ?? '');

            $statut =
                $this->normaliserStatut(
                    $donnees['statut'] ?? ''
                );

            $idCategorie =
                (int)($donnees['id_categorie'] ?? 0);

            $idLocal =
                (int)($donnees['id_local'] ?? 0);

            if (
                $numeroSerie === '' ||
                $marque === '' ||
                $modele === '' ||
                $dateAchat === ''
            ) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : série, marque, modèle et date sont obligatoires.';

                continue;
            }

            if (!$this->dateValide($dateAchat)) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : date invalide. Format attendu : AAAA-MM-JJ.';

                continue;
            }

            if ($statut === null) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : statut invalide.';

                continue;
            }

            if ($idCategorie <= 0) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : identifiant de catégorie invalide.';

                continue;
            }

            if ($idLocal <= 0) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : identifiant du local invalide.';

                continue;
            }

            if (
                $this->equipementModel
                    ->trouverParNumeroSerie($numeroSerie)
            ) {
                $resultatImport['doublons']++;
                continue;
            }

            if (
                !$this->equipementModel
                    ->categorieExiste($idCategorie)
            ) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : la catégorie #' .
                    $idCategorie .
                    ' n’existe pas.';

                continue;
            }

            if (
                !$this->equipementModel
                    ->localExiste($idLocal)
            ) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : le local #' .
                    $idLocal .
                    ' n’existe pas.';

                continue;
            }

            $data = [
                'numero_serie' => $numeroSerie,
                'marque' => $marque,
                'modele' => $modele,
                'date_achat' => $dateAchat,
                'statut' => $statut,
                'id_categorie' => $idCategorie,
                'id_local' => $idLocal
            ];

            try {
                $idEquipement =
                    $this->equipementModel
                        ->insert($data);

                if ($idEquipement) {
                    $resultatImport['ajoutes']++;

                    Historique::enregistrer(
                        'equipement',
                        $idEquipement,
                        'Ajout',
                        null,
                        'Import CSV équipement : ' .
                        $marque .
                        ' ' .
                        $modele .
                        ' - Série : ' .
                        $numeroSerie
                    );
                } else {
                    $resultatImport['erreurs'][] =
                        'Ligne ' .
                        $numeroLigne .
                        ' : impossible d’ajouter ' .
                        $numeroSerie .
                        '.';
                }
            } catch (Throwable $e) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : erreur lors de l’ajout de ' .
                    $numeroSerie .
                    '.';
            }
        }

        fclose($handle);

        $_SESSION['resultat_import_equipements'] =
            $resultatImport;

        header(
            'Location: ' .
            BASE_URL .
            '?page=equipements'
        );
        exit;
    }

    public function modeleImportCsv()
    {
        Auth::autoriser(['Administrateur']);

        header(
            'Content-Type: text/csv; charset=UTF-8'
        );

        header(
            'Content-Disposition: attachment; ' .
            'filename="modele_import_equipements.csv"'
        );

        header('Pragma: no-cache');
        header('Expires: 0');

        $sortie = fopen('php://output', 'w');

        if ($sortie === false) {
            exit;
        }

        fwrite($sortie, "\xEF\xBB\xBF");

        fputcsv(
            $sortie,
            [
                'numero_serie',
                'marque',
                'modele',
                'date_achat',
                'statut',
                'id_categorie',
                'id_local'
            ],
            ';',
            '"',
            ''
        );

        fclose($sortie);
        exit;
    }

    public function delete()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] =
                'Équipement invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        $ancienEquipement =
            $this->equipementModel->getById($id);

        if (!$ancienEquipement) {
            $_SESSION['error'] =
                'Équipement introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=equipements'
            );
            exit;
        }

        try {
            $resultat =
                $this->equipementModel->delete($id);

            if ($resultat) {
                Historique::enregistrer(
                    'equipement',
                    $id,
                    'Suppression',
                    json_encode(
                        $ancienEquipement,
                        JSON_UNESCAPED_UNICODE
                    ),
                    null
                );

                $_SESSION['success'] =
                    'Équipement supprimé avec succès.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Impossible de supprimer cet équipement, car il est lié à d’autres données.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=equipements'
        );
        exit;
    }

    private function normaliserStatut($statut)
    {
        $statut = mb_strtolower(
            trim((string)$statut),
            'UTF-8'
        );

        $statutSansAccent = strtr($statut, [
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'à' => 'a',
            'â' => 'a'
        ]);

        if ($statutSansAccent === 'disponible') {
            return 'Disponible';
        }

        if ($statutSansAccent === 'affecte') {
            return 'Affecté';
        }

        if (
            $statutSansAccent === 'maintenance' ||
            $statutSansAccent === 'en maintenance'
        ) {
            return 'Maintenance';
        }

        return null;
    }

    private function dateValide($date)
    {
        $dateObjet = DateTime::createFromFormat(
            '!Y-m-d',
            $date
        );

        return (
            $dateObjet !== false &&
            $dateObjet->format('Y-m-d') === $date
        );
    }

    private function normaliserEnteteCsv($entete)
    {
        $entete = trim((string)$entete);

        $entete = preg_replace(
            '/^\xEF\xBB\xBF/',
            '',
            $entete
        );

        $entete = mb_strtolower(
            $entete,
            'UTF-8'
        );

        $entete = strtr($entete, [
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'à' => 'a',
            'â' => 'a',
            'ô' => 'o',
            'ù' => 'u',
            'û' => 'u'
        ]);

        $entete = str_replace(
            [' ', '-', '.', '/'],
            '_',
            $entete
        );

        $equivalences = [
            'n_serie' => 'numero_serie',
            'num_serie' => 'numero_serie',
            'numero_de_serie' => 'numero_serie',
            'categorie' => 'id_categorie',
            'local' => 'id_local',
            'date' => 'date_achat'
        ];

        return $equivalences[$entete] ?? $entete;
    }

    private function ligneCsvVide($ligne)
    {
        foreach ($ligne as $valeur) {
            if (trim((string)$valeur) !== '') {
                return false;
            }
        }

        return true;
    }
}