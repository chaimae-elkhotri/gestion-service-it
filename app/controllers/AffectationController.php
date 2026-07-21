<?php

require_once '../app/models/Affectation.php';
require_once '../app/models/Utilisateur.php';
require_once '../app/models/Equipement.php';
require_once '../app/models/Historique.php';

class AffectationController extends Controller
{
    private $affectationModel;
    private $utilisateurModel;
    private $equipementModel;

    public function __construct()
    {
        $this->affectationModel = new Affectation();
        $this->utilisateurModel = new Utilisateur();
        $this->equipementModel = new Equipement();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $affectations = $this->affectationModel->search($search);
        } else {
            $affectations = $this->affectationModel->getAll();
        }

        $this->view('affectations/index', [
            'affectations' => $affectations
        ]);
    }

    public function create()
    {
        $utilisateurs = $this->utilisateurModel->getAll();
        $equipements = $this->equipementModel->getAll();

        $this->view('affectations/create', [
            'utilisateurs' => $utilisateurs,
            'equipements' => $equipements
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'id_utilisateur' => $_POST['id_utilisateur'],
                'id_equipement' => $_POST['id_equipement'],
                'date_affectation' => $_POST['date_affectation'],
                'date_fin_affectation' => $_POST['date_fin_affectation'] ?? null
            ];

            $idAffectation = $this->affectationModel->insert($data);

            if ($idAffectation) {
                Historique::enregistrer(
                    'affectation_equipement',
                    $idAffectation,
                    'Ajout',
                    null,
                    'Affectation équipement #' . $data['id_equipement'] . ' à utilisateur #' . $data['id_utilisateur']
                );
            }

            header("Location: " . BASE_URL . "?page=affectations");
            exit();
        }
    }

    public function edit()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=affectations");
            exit();
        }

        $affectation = $this->affectationModel->getById($_GET['id']);
        $utilisateurs = $this->utilisateurModel->getAll();
        $equipements = $this->equipementModel->getAll();

        if (!$affectation) {
            header("Location: " . BASE_URL . "?page=affectations");
            exit();
        }

        $this->view('affectations/edit', [
            'affectation' => $affectation,
            'utilisateurs' => $utilisateurs,
            'equipements' => $equipements
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ancienneAffectation = $this->affectationModel->getById($_POST['id_affectation']);

            $data = [
                'id_affectation' => $_POST['id_affectation'],
                'id_utilisateur' => $_POST['id_utilisateur'],
                'id_equipement' => $_POST['id_equipement'],
                'date_affectation' => $_POST['date_affectation'],
                'date_fin_affectation' => $_POST['date_fin_affectation'] ?? null
            ];

            $result = $this->affectationModel->update($data);

            if ($result) {
                Historique::enregistrer(
                    'affectation_equipement',
                    $data['id_affectation'],
                    'Modification',
                    json_encode($ancienneAffectation, JSON_UNESCAPED_UNICODE),
                    json_encode($data, JSON_UNESCAPED_UNICODE)
                );
            }

            header("Location: " . BASE_URL . "?page=affectations");
            exit();
        }
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=affectations");
            exit();
        }

        $id = $_GET['id'];

        $ancienneAffectation = $this->affectationModel->getById($id);

        $result = $this->affectationModel->delete($id);

        if ($result) {
            Historique::enregistrer(
                'affectation_equipement',
                $id,
                'Suppression',
                json_encode($ancienneAffectation, JSON_UNESCAPED_UNICODE),
                null
            );
        }

        header("Location: " . BASE_URL . "?page=affectations");
        exit();
    }
}