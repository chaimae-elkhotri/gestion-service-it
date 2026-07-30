<?php

require_once '../app/core/Auth.php';
require_once '../app/models/OccupationLocal.php';
require_once '../app/models/Local.php';

class OccupationLocalController extends Controller
{
    private $occupationModel;
    private $localModel;

    public function __construct()
    {
        Auth::verifierConnexion();

        $this->occupationModel =
            new OccupationLocal();

        $this->localModel =
            new Local();
    }

    public function index()
    {
        $idLocal = (int)($_GET['id_local'] ?? 0);

        $occupations =
            $this->occupationModel->getAll(
                $idLocal > 0 ? $idLocal : null
            );

        $locals = $this->localModel->getAll();

        $this->view('occupations/index', [
            'occupations' => $occupations,
            'locals' => $locals,
            'id_local_selectionne' => $idLocal
        ]);
    }

    public function create()
    {
        Auth::autoriser(['Administrateur']);

        $locals = $this->localModel->getAll();

        $idLocalSelectionne =
            (int)($_GET['id_local'] ?? 0);

        $this->view('occupations/create', [
            'locals' => $locals,
            'id_local_selectionne' =>
                $idLocalSelectionne
        ]);
    }

    public function store()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=occupations-locaux'
            );
            exit;
        }

        $idLocal =
            (int)($_POST['id_local'] ?? 0);

        $typeOccupation =
            trim($_POST['type_occupation'] ?? '');

        $motif =
            trim($_POST['motif'] ?? '');

        $dateDebut =
            $this->normaliserDate(
                $_POST['date_debut'] ?? ''
            );

        $dateFin =
            $this->normaliserDate(
                $_POST['date_fin'] ?? ''
            );

        if (
            $idLocal <= 0 ||
            $typeOccupation === '' ||
            $motif === '' ||
            !$dateDebut ||
            !$dateFin
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement tous les champs.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-occupation-local&id_local=' .
                $idLocal
            );
            exit;
        }

        if (
            strtotime($dateFin) <=
            strtotime($dateDebut)
        ) {
            $_SESSION['error'] =
                'La date de fin doit être postérieure à la date de début.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-occupation-local&id_local=' .
                $idLocal
            );
            exit;
        }

        $local = $this->localModel->getById($idLocal);

        if (!$local) {
            $_SESSION['error'] =
                'Le local sélectionné est introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=occupations-locaux'
            );
            exit;
        }

        if (
            ($local['statut_general'] ?? 'Actif')
            !== 'Actif'
        ) {
            $_SESSION['error'] =
                'Ce local est en maintenance ou indisponible.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=occupations-locaux&id_local=' .
                $idLocal
            );
            exit;
        }

        $conflit =
            $this->occupationModel->hasConflict(
                $idLocal,
                $dateDebut,
                $dateFin
            );

        if ($conflit) {
            $_SESSION['error'] =
                'Ce local est déjà occupé pendant cette période.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-occupation-local&id_local=' .
                $idLocal
            );
            exit;
        }

        $resultat =
            $this->occupationModel->insert([
                'id_local' => $idLocal,
                'type_occupation' =>
                    $typeOccupation,
                'motif' => $motif,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'id_utilisateur' =>
                    Auth::idUtilisateur()
            ]);

        if ($resultat) {
            $_SESSION['success'] =
                'La période d’occupation a été enregistrée.';
        } else {
            $_SESSION['error'] =
                'Impossible d’enregistrer l’occupation.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=occupations-locaux&id_local=' .
            $idLocal
        );
        exit;
    }

    public function edit()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        $occupation =
            $this->occupationModel->getById($id);

        if (!$occupation) {
            $_SESSION['error'] =
                'Occupation introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=occupations-locaux'
            );
            exit;
        }

        if (
            ($occupation['statut'] ?? '') !==
            'Active'
        ) {
            $_SESSION['error'] =
                'Une occupation annulée ne peut plus être modifiée.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=occupations-locaux'
            );
            exit;
        }

        $locals = $this->localModel->getAll();

        $this->view('occupations/edit', [
            'occupation' => $occupation,
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
                '?page=occupations-locaux'
            );
            exit;
        }

        $idOccupation =
            (int)($_POST['id_occupation'] ?? 0);

        $idLocal =
            (int)($_POST['id_local'] ?? 0);

        $typeOccupation =
            trim($_POST['type_occupation'] ?? '');

        $motif =
            trim($_POST['motif'] ?? '');

        $dateDebut =
            $this->normaliserDate(
                $_POST['date_debut'] ?? ''
            );

        $dateFin =
            $this->normaliserDate(
                $_POST['date_fin'] ?? ''
            );

        if (
            $idOccupation <= 0 ||
            $idLocal <= 0 ||
            $typeOccupation === '' ||
            $motif === '' ||
            !$dateDebut ||
            !$dateFin
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement les champs.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-occupation-local&id=' .
                $idOccupation
            );
            exit;
        }

        if (
            strtotime($dateFin) <=
            strtotime($dateDebut)
        ) {
            $_SESSION['error'] =
                'La date de fin doit être postérieure à la date de début.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-occupation-local&id=' .
                $idOccupation
            );
            exit;
        }

        $conflit =
            $this->occupationModel->hasConflict(
                $idLocal,
                $dateDebut,
                $dateFin,
                $idOccupation
            );

        if ($conflit) {
            $_SESSION['error'] =
                'Ce local est déjà occupé pendant cette période.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-occupation-local&id=' .
                $idOccupation
            );
            exit;
        }

        $resultat =
            $this->occupationModel->update([
                'id_occupation' => $idOccupation,
                'id_local' => $idLocal,
                'type_occupation' =>
                    $typeOccupation,
                'motif' => $motif,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin
            ]);

        if ($resultat) {
            $_SESSION['success'] =
                'L’occupation a été modifiée.';
        } else {
            $_SESSION['error'] =
                'Impossible de modifier l’occupation.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=occupations-locaux&id_local=' .
            $idLocal
        );
        exit;
    }

    public function cancel()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        $occupation =
            $this->occupationModel->getById($id);

        if (!$occupation) {
            $_SESSION['error'] =
                'Occupation introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=occupations-locaux'
            );
            exit;
        }

        $this->occupationModel->cancel($id);

        $_SESSION['success'] =
            'L’occupation a été annulée et conservée.';

        header(
            'Location: ' .
            BASE_URL .
            '?page=occupations-locaux&id_local=' .
            (int)$occupation['id_local']
        );
        exit;
    }

    private function normaliserDate($date)
    {
        $date = trim((string)$date);

        if ($date === '') {
            return null;
        }

        $objetDate = DateTime::createFromFormat(
            'Y-m-d\TH:i',
            $date
        );

        if (!$objetDate) {
            return null;
        }

        return $objetDate->format(
            'Y-m-d H:i:s'
        );
    }
}