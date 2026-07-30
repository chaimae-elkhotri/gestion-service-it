<?php

require_once '../app/models/Local.php';
require_once '../app/core/Auth.php';

class LocalController extends Controller
{
    private $localModel;

    public function __construct()
    {
        Auth::verifierConnexion();

        $this->localModel = new Local();
    }

    public function index()
    {
        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            $locals = $this->localModel->search($search);
        } else {
            $locals = $this->localModel->getAll();
        }

        $this->view('locals/index', [
            'locals' => $locals
        ]);
    }

    public function create()
    {
        Auth::autoriser(['Administrateur']);

        $this->view('locals/create');
    }

    public function store()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?page=locals');
            exit;
        }

        $nomLocal = trim($_POST['nom_local'] ?? '');
        $typeLocal = trim($_POST['type_local'] ?? '');
        $statutGeneral = trim(
            $_POST['statut_general'] ?? 'Actif'
        );

        $statutsAutorises = [
            'Actif',
            'Maintenance',
            'Indisponible'
        ];

        if (
            $nomLocal === '' ||
            $typeLocal === '' ||
            !in_array(
                $statutGeneral,
                $statutsAutorises,
                true
            )
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement les champs.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-local'
            );
            exit;
        }

        $resultat = $this->localModel->insert([
            'nom_local' => $nomLocal,
            'type_local' => $typeLocal,
            'statut_general' => $statutGeneral
        ]);

        if ($resultat) {
            $_SESSION['success'] =
                'Le local a été ajouté avec succès.';
        } else {
            $_SESSION['error'] =
                'Impossible d’ajouter le local.';
        }

        header('Location: ' . BASE_URL . '?page=locals');
        exit;
    }

    public function edit()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: ' . BASE_URL . '?page=locals');
            exit;
        }

        $local = $this->localModel->getById($id);

        if (!$local) {
            $_SESSION['error'] = 'Local introuvable.';

            header('Location: ' . BASE_URL . '?page=locals');
            exit;
        }

        $this->view('locals/edit', [
            'local' => $local
        ]);
    }

    public function update()
    {
        Auth::autoriser(['Administrateur']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?page=locals');
            exit;
        }

        $idLocal = (int)($_POST['id_local'] ?? 0);
        $nomLocal = trim($_POST['nom_local'] ?? '');
        $typeLocal = trim($_POST['type_local'] ?? '');
        $statutGeneral = trim(
            $_POST['statut_general'] ?? 'Actif'
        );

        $statutsAutorises = [
            'Actif',
            'Maintenance',
            'Indisponible'
        ];

        if (
            $idLocal <= 0 ||
            $nomLocal === '' ||
            $typeLocal === '' ||
            !in_array(
                $statutGeneral,
                $statutsAutorises,
                true
            )
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement les champs.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-local&id=' .
                $idLocal
            );
            exit;
        }

        $resultat = $this->localModel->update([
            'id_local' => $idLocal,
            'nom_local' => $nomLocal,
            'type_local' => $typeLocal,
            'statut_general' => $statutGeneral
        ]);

        if ($resultat) {
            $_SESSION['success'] =
                'Le local a été modifié avec succès.';
        } else {
            $_SESSION['error'] =
                'Impossible de modifier le local.';
        }

        header('Location: ' . BASE_URL . '?page=locals');
        exit;
    }

    public function delete()
    {
        Auth::autoriser(['Administrateur']);

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: ' . BASE_URL . '?page=locals');
            exit;
        }

        try {
            $this->localModel->delete($id);

            $_SESSION['success'] =
                'Le local a été supprimé avec succès.';
        } catch (PDOException $e) {
            $_SESSION['error'] =
                'Impossible de supprimer ce local, car il est lié à des équipements ou à des occupations.';
        }

        header('Location: ' . BASE_URL . '?page=locals');
        exit;
    }
}