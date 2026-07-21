<?php

require_once '../app/models/Evaluation.php';
require_once '../app/models/Utilisateur.php';

class EvaluationController extends Controller
{
    private $evaluationModel;
    private $utilisateurModel;

    public function __construct()
    {
        $this->evaluationModel = new Evaluation();
        $this->utilisateurModel = new Utilisateur();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $evaluations = $this->evaluationModel->search($search);
        } else {
            $evaluations = $this->evaluationModel->getAll();
        }

        $this->view('evaluations/index', [
            'evaluations' => $evaluations
        ]);
    }

    public function create()
    {
        $utilisateurs = $this->utilisateurModel->getAll();
        $interventions = $this->evaluationModel->getInterventions();

        $this->view('evaluations/create', [
            'utilisateurs' => $utilisateurs,
            'interventions' => $interventions
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'id_utilisateur' => $_POST['id_utilisateur'],
                'id_intervention' => $_POST['id_intervention'],
                'note' => $_POST['note'],
                'commentaire' => $_POST['commentaire'],
                'date_evaluation' => $_POST['date_evaluation']
            ];

            $this->evaluationModel->insert($data);

            header("Location: " . BASE_URL . "?page=evaluations");
            exit();
        }
    }

    public function edit()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=evaluations");
            exit();
        }

        $evaluation = $this->evaluationModel->getById($_GET['id']);
        $utilisateurs = $this->utilisateurModel->getAll();
        $interventions = $this->evaluationModel->getInterventions();

        if (!$evaluation) {
            header("Location: " . BASE_URL . "?page=evaluations");
            exit();
        }

        $this->view('evaluations/edit', [
            'evaluation' => $evaluation,
            'utilisateurs' => $utilisateurs,
            'interventions' => $interventions
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'id_evaluation' => $_POST['id_evaluation'],
                'id_utilisateur' => $_POST['id_utilisateur'],
                'id_intervention' => $_POST['id_intervention'],
                'note' => $_POST['note'],
                'commentaire' => $_POST['commentaire'],
                'date_evaluation' => $_POST['date_evaluation']
            ];

            $this->evaluationModel->update($data);

            header("Location: " . BASE_URL . "?page=evaluations");
            exit();
        }
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=evaluations");
            exit();
        }

        $this->evaluationModel->delete($_GET['id']);

        header("Location: " . BASE_URL . "?page=evaluations");
        exit();
    }
}