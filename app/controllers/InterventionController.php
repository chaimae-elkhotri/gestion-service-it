<?php

require_once '../app/models/Intervention.php';
require_once '../app/models/Historique.php';

class InterventionController extends Controller
{
    private $interventionModel;

    public function __construct()
    {
        $this->interventionModel = new Intervention();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $interventions = $this->interventionModel->search($search);
        } else {
            $interventions = $this->interventionModel->getAll();
        }

        $this->view('interventions/index', [
            'interventions' => $interventions
        ]);
    }

    public function create()
    {
        $tickets = $this->interventionModel->getTickets();
        $techniciens = $this->interventionModel->getTechniciens();

        $this->view('interventions/create', [
            'tickets' => $tickets,
            'techniciens' => $techniciens
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'id_ticket' => $_POST['id_ticket'],
                'id_technicien' => $_POST['id_technicien'],
                'rapport' => $_POST['rapport'],
                'duree' => $_POST['duree'],
                'statut' => $_POST['statut'],
                'temps_reponse' => $_POST['temps_reponse'],
                'temps_resolution' => $_POST['temps_resolution']
            ];

            $idIntervention = $this->interventionModel->insert($data);

            if ($idIntervention) {
                Historique::enregistrer(
                    'intervention',
                    $idIntervention,
                    'Ajout',
                    null,
                    'Ajout intervention pour ticket #' . $data['id_ticket'] . ' | Technicien #' . $data['id_technicien'] . ' | Statut : ' . $data['statut']
                );
            }

            header("Location: " . BASE_URL . "?page=interventions");
            exit();
        }
    }

    public function edit()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=interventions");
            exit();
        }

        $intervention = $this->interventionModel->getById($_GET['id']);
        $tickets = $this->interventionModel->getTickets();
        $techniciens = $this->interventionModel->getTechniciens();

        if (!$intervention) {
            header("Location: " . BASE_URL . "?page=interventions");
            exit();
        }

        $this->view('interventions/edit', [
            'intervention' => $intervention,
            'tickets' => $tickets,
            'techniciens' => $techniciens
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ancienneIntervention = $this->interventionModel->getById($_POST['id_intervention']);

            $data = [
                'id_intervention' => $_POST['id_intervention'],
                'id_ticket' => $_POST['id_ticket'],
                'id_technicien' => $_POST['id_technicien'],
                'rapport' => $_POST['rapport'],
                'duree' => $_POST['duree'],
                'statut' => $_POST['statut'],
                'temps_reponse' => $_POST['temps_reponse'],
                'temps_resolution' => $_POST['temps_resolution']
            ];

            $result = $this->interventionModel->update($data);

            if ($result) {
                Historique::enregistrer(
                    'intervention',
                    $data['id_intervention'],
                    'Modification',
                    json_encode($ancienneIntervention, JSON_UNESCAPED_UNICODE),
                    json_encode($data, JSON_UNESCAPED_UNICODE)
                );
            }

            header("Location: " . BASE_URL . "?page=interventions");
            exit();
        }
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=interventions");
            exit();
        }

        $id = $_GET['id'];

        $ancienneIntervention = $this->interventionModel->getById($id);

        $result = $this->interventionModel->delete($id);

        if ($result) {
            Historique::enregistrer(
                'intervention',
                $id,
                'Suppression',
                json_encode($ancienneIntervention, JSON_UNESCAPED_UNICODE),
                null
            );
        }

        header("Location: " . BASE_URL . "?page=interventions");
        exit();
    }
}