<?php

require_once '../app/models/Logiciel.php';
require_once '../app/models/Historique.php';
require_once '../app/core/Auth.php';

class LogicielController extends Controller
{
    private $logicielModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->logicielModel = new Logiciel();
    }

    public function index()
    {
        Auth::autoriser(['Administrateur']);

        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            $logiciels = $this->logicielModel->search($search);
        } else {
            $logiciels = $this->logicielModel->getAll();
        }

        $this->view('logiciels/index', [
            'logiciels' => $logiciels
        ]);
    }

    public function create()
    {
        Auth::autoriser(['Administrateur']);

        $this->view('logiciels/create');
    }

    public function store()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        $nomLogiciel = trim($_POST['nom_logiciel'] ?? '');
        $version = trim($_POST['version'] ?? '');
        $editeur = trim($_POST['editeur'] ?? '');
        $dateInstallation = trim($_POST['date_installation'] ?? '');

        if (
            $nomLogiciel === '' ||
            $version === '' ||
            $editeur === ''
        ) {
            $_SESSION['error'] =
                'Le nom, la version et l’éditeur sont obligatoires.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-logiciel'
            );
            exit;
        }

        if (
            $dateInstallation !== '' &&
            !$this->dateValide($dateInstallation)
        ) {
            $_SESSION['error'] =
                'La date d’installation est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-logiciel'
            );
            exit;
        }

        $doublon = $this->logicielModel->trouverDoublon(
            $nomLogiciel,
            $version,
            $editeur
        );

        if ($doublon) {
            $_SESSION['error'] =
                'Ce logiciel, avec cette version et cet éditeur, existe déjà.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-logiciel'
            );
            exit;
        }

        $data = [
            'nom_logiciel' => $nomLogiciel,
            'version' => $version,
            'editeur' => $editeur,
            'date_installation' =>
                $dateInstallation !== ''
                    ? $dateInstallation
                    : null
        ];

        try {
            $idLogiciel = $this->logicielModel->insert($data);

            if ($idLogiciel) {
                Historique::enregistrer(
                    'logiciel',
                    $idLogiciel,
                    'Ajout',
                    null,
                    'Ajout du logiciel : ' .
                    $nomLogiciel .
                    ' - Version : ' .
                    $version .
                    ' - Éditeur : ' .
                    $editeur
                );

                $_SESSION['success'] =
                    'Logiciel ajouté avec succès.';
            } else {
                $_SESSION['error'] =
                    'Impossible d’ajouter le logiciel.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Une erreur est survenue pendant l’ajout du logiciel.';
        }

        header('Location: ' . BASE_URL . '?page=logiciels');
        exit;
    }

    public function edit()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'Logiciel invalide.';

            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        $logiciel = $this->logicielModel->getById($id);

        if (!$logiciel) {
            $_SESSION['error'] = 'Logiciel introuvable.';

            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        $this->view('logiciels/edit', [
            'logiciel' => $logiciel
        ]);
    }

    public function update()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        $idLogiciel = (int)($_POST['id_logiciel'] ?? 0);
        $nomLogiciel = trim($_POST['nom_logiciel'] ?? '');
        $version = trim($_POST['version'] ?? '');
        $editeur = trim($_POST['editeur'] ?? '');
        $dateInstallation = trim($_POST['date_installation'] ?? '');

        if (
            $idLogiciel <= 0 ||
            $nomLogiciel === '' ||
            $version === '' ||
            $editeur === ''
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement les champs obligatoires.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-logiciel&id=' .
                $idLogiciel
            );
            exit;
        }

        if (
            $dateInstallation !== '' &&
            !$this->dateValide($dateInstallation)
        ) {
            $_SESSION['error'] =
                'La date d’installation est invalide.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-logiciel&id=' .
                $idLogiciel
            );
            exit;
        }

        $doublon = $this->logicielModel->trouverDoublon(
            $nomLogiciel,
            $version,
            $editeur,
            $idLogiciel
        );

        if ($doublon) {
            $_SESSION['error'] =
                'Un autre logiciel possède déjà les mêmes informations.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-logiciel&id=' .
                $idLogiciel
            );
            exit;
        }

        $ancienLogiciel =
            $this->logicielModel->getById($idLogiciel);

        $data = [
            'id_logiciel' => $idLogiciel,
            'nom_logiciel' => $nomLogiciel,
            'version' => $version,
            'editeur' => $editeur,
            'date_installation' =>
                $dateInstallation !== ''
                    ? $dateInstallation
                    : null
        ];

        try {
            $resultat = $this->logicielModel->update($data);

            if ($resultat) {
                Historique::enregistrer(
                    'logiciel',
                    $idLogiciel,
                    'Modification',
                    json_encode(
                        $ancienLogiciel,
                        JSON_UNESCAPED_UNICODE
                    ),
                    json_encode(
                        $data,
                        JSON_UNESCAPED_UNICODE
                    )
                );

                $_SESSION['success'] =
                    'Logiciel modifié avec succès.';
            } else {
                $_SESSION['error'] =
                    'Impossible de modifier le logiciel.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Une erreur est survenue pendant la modification.';
        }

        header('Location: ' . BASE_URL . '?page=logiciels');
        exit;
    }

    public function importCsv()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        if (
            !isset($_FILES['fichier_csv']) ||
            $_FILES['fichier_csv']['error'] !== UPLOAD_ERR_OK
        ) {
            $_SESSION['error'] =
                'Veuillez sélectionner un fichier CSV valide.';

            header('Location: ' . BASE_URL . '?page=logiciels');
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

            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        if ((int)$fichier['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] =
                'Le fichier CSV ne doit pas dépasser 2 Mo.';

            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        $handle = fopen($fichier['tmp_name'], 'r');

        if ($handle === false) {
            $_SESSION['error'] =
                'Impossible de lire le fichier CSV.';

            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        $premiereLigne = fgets($handle);

        if ($premiereLigne === false) {
            fclose($handle);

            $_SESSION['error'] =
                'Le fichier CSV est vide.';

            header('Location: ' . BASE_URL . '?page=logiciels');
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
            '\\'
        );

        if ($entetes === false || empty($entetes)) {
            fclose($handle);

            $_SESSION['error'] =
                'Les en-têtes du fichier CSV sont invalides.';

            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        $entetesNormalisees = [];

        foreach ($entetes as $entete) {
            $entetesNormalisees[] =
                $this->normaliserEnteteCsv($entete);
        }

        $colonnesObligatoires = [
            'nom_logiciel',
            'version',
            'editeur',
            'date_installation'
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

            header('Location: ' . BASE_URL . '?page=logiciels');
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
                    '\\'
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

            $nomLogiciel =
                trim($donnees['nom_logiciel'] ?? '');

            $version =
                trim($donnees['version'] ?? '');

            $editeur =
                trim($donnees['editeur'] ?? '');

            $dateInstallation =
                trim($donnees['date_installation'] ?? '');

            if (
                $nomLogiciel === '' ||
                $version === '' ||
                $editeur === ''
            ) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : nom, version et éditeur sont obligatoires.';

                continue;
            }

            if (
                $dateInstallation !== '' &&
                !$this->dateValide($dateInstallation)
            ) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : date invalide. Format attendu : AAAA-MM-JJ.';

                continue;
            }

            $doublon = $this->logicielModel->trouverDoublon(
                $nomLogiciel,
                $version,
                $editeur
            );

            if ($doublon) {
                $resultatImport['doublons']++;
                continue;
            }

            $data = [
                'nom_logiciel' => $nomLogiciel,
                'version' => $version,
                'editeur' => $editeur,
                'date_installation' =>
                    $dateInstallation !== ''
                        ? $dateInstallation
                        : null
            ];

            try {
                $idLogiciel =
                    $this->logicielModel->insert($data);

                if ($idLogiciel) {
                    $resultatImport['ajoutes']++;

                    Historique::enregistrer(
                        'logiciel',
                        $idLogiciel,
                        'Ajout',
                        null,
                        'Import CSV logiciel : ' .
                        $nomLogiciel .
                        ' - Version : ' .
                        $version .
                        ' - Éditeur : ' .
                        $editeur
                    );
                } else {
                    $resultatImport['erreurs'][] =
                        'Ligne ' .
                        $numeroLigne .
                        ' : impossible d’ajouter ' .
                        $nomLogiciel .
                        '.';
                }
            } catch (Throwable $e) {
                $resultatImport['erreurs'][] =
                    'Ligne ' .
                    $numeroLigne .
                    ' : erreur pendant l’ajout de ' .
                    $nomLogiciel .
                    '.';
            }
        }

        fclose($handle);

        $_SESSION['resultat_import_logiciels'] =
            $resultatImport;

        header('Location: ' . BASE_URL . '?page=logiciels');
        exit;
    }

    public function modeleImportCsv()
    {
        Auth::autoriser(['Administrateur']);

        header('Content-Type: text/csv; charset=UTF-8');

        header(
            'Content-Disposition: attachment; ' .
            'filename="modele_import_logiciels.csv"'
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
                'nom_logiciel',
                'version',
                'editeur',
                'date_installation'
            ],
            ';',
            '"',
            '\\'
        );

        fputcsv(
            $sortie,
            [
                'Microsoft Office',
                '2021',
                'Microsoft',
                date('Y-m-d')
            ],
            ';',
            '"',
            '\\'
        );

        fputcsv(
            $sortie,
            [
                'Adobe Acrobat Reader',
                '2026',
                'Adobe',
                date('Y-m-d')
            ],
            ';',
            '"',
            '\\'
        );

        fclose($sortie);
        exit;
    }

    public function delete()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'Logiciel invalide.';

            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        $ancienLogiciel =
            $this->logicielModel->getById($id);

        if (!$ancienLogiciel) {
            $_SESSION['error'] =
                'Logiciel introuvable.';

            header('Location: ' . BASE_URL . '?page=logiciels');
            exit;
        }

        try {
            $resultat = $this->logicielModel->delete($id);

            if ($resultat) {
                Historique::enregistrer(
                    'logiciel',
                    $id,
                    'Suppression',
                    json_encode(
                        $ancienLogiciel,
                        JSON_UNESCAPED_UNICODE
                    ),
                    null
                );

                $_SESSION['success'] =
                    'Logiciel supprimé avec succès.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Impossible de supprimer ce logiciel, car il est lié à d’autres données.';
        }

        header('Location: ' . BASE_URL . '?page=logiciels');
        exit;
    }

    private function dateValide($date)
    {
        $objetDate = DateTime::createFromFormat(
            '!Y-m-d',
            $date
        );

        return (
            $objetDate !== false &&
            $objetDate->format('Y-m-d') === $date
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
            'â' => 'a'
        ]);

        $entete = str_replace(
            [' ', '-', '.', '/'],
            '_',
            $entete
        );

        $equivalences = [
            'nom' => 'nom_logiciel',
            'logiciel' => 'nom_logiciel',
            'nom_du_logiciel' => 'nom_logiciel',
            'éditeur' => 'editeur',
            'date' => 'date_installation',
            'date_d_installation' => 'date_installation'
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