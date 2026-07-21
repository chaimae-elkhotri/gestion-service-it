<?php

require_once '../app/models/Equipement.php';
require_once '../app/models/Categorie.php';
require_once '../app/models/Local.php';
require_once '../app/models/Historique.php';

class EquipementController extends Controller
{
    private $equipementModel;
    private $categorieModel;
    private $localModel;

    public function __construct()
    {
        $this->equipementModel = new Equipement();
        $this->categorieModel = new Categorie();
        $this->localModel = new Local();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $equipements = $this->equipementModel->search($search);
        } else {
            $equipements = $this->equipementModel->getAll();
        }

        $this->view('equipements/index', [
            'equipements' => $equipements
        ]);
    }

    public function create()
    {
        $categories = $this->categorieModel->getAll();
        $locals = $this->localModel->getAll();

        $this->view('equipements/create', [
            'categories' => $categories,
            'locals' => $locals
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'numero_serie' => $_POST['numero_serie'],
                'marque' => $_POST['marque'],
                'modele' => $_POST['modele'],
                'date_achat' => $_POST['date_achat'],
                'statut' => $_POST['statut'],
                'id_categorie' => $_POST['id_categorie'],
                'id_local' => $_POST['id_local']
            ];

            $idEquipement = $this->equipementModel->insert($data);

            if ($idEquipement) {
                Historique::enregistrer(
                    'equipement',
                    $idEquipement,
                    'Ajout',
                    null,
                    'Ajout équipement : ' . $data['marque'] . ' ' . $data['modele'] . ' - Série : ' . $data['numero_serie']
                );
            }

            header("Location: " . BASE_URL . "?page=equipements");
            exit();
        }
    }

    public function edit()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=equipements");
            exit();
        }

        $id = $_GET['id'];

        $equipement = $this->equipementModel->getById($id);
        $categories = $this->categorieModel->getAll();
        $locals = $this->localModel->getAll();

        if (!$equipement) {
            header("Location: " . BASE_URL . "?page=equipements");
            exit();
        }

        $this->view('equipements/edit', [
            'equipement' => $equipement,
            'categories' => $categories,
            'locals' => $locals
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ancienEquipement = $this->equipementModel->getById($_POST['id_equipement']);

            $data = [
                'id_equipement' => $_POST['id_equipement'],
                'numero_serie' => $_POST['numero_serie'],
                'marque' => $_POST['marque'],
                'modele' => $_POST['modele'],
                'date_achat' => $_POST['date_achat'],
                'statut' => $_POST['statut'],
                'id_categorie' => $_POST['id_categorie'],
                'id_local' => $_POST['id_local']
            ];

            $result = $this->equipementModel->update($data);

            if ($result) {
                Historique::enregistrer(
                    'equipement',
                    $data['id_equipement'],
                    'Modification',
                    json_encode($ancienEquipement, JSON_UNESCAPED_UNICODE),
                    json_encode($data, JSON_UNESCAPED_UNICODE)
                );
            }

            header("Location: " . BASE_URL . "?page=equipements");
            exit();
        }
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=equipements");
            exit();
        }

        $id = $_GET['id'];

        $ancienEquipement = $this->equipementModel->getById($id);

        $result = $this->equipementModel->delete($id);

        if ($result) {
            Historique::enregistrer(
                'equipement',
                $id,
                'Suppression',
                json_encode($ancienEquipement, JSON_UNESCAPED_UNICODE),
                null
            );
        }

        header("Location: " . BASE_URL . "?page=equipements");
        exit();
    }
}