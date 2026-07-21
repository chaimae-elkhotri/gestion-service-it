<?php

require_once '../app/models/Logiciel.php';

class LogicielController extends Controller
{
    private $logicielModel;

    public function __construct()
    {
        $this->logicielModel = new Logiciel();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
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
        $this->view('logiciels/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'nom_logiciel' => $_POST['nom_logiciel'],
                'version' => $_POST['version'],
                'editeur' => $_POST['editeur'],
                'date_installation' => $_POST['date_installation']
            ];

            $this->logicielModel->insert($data);

            header("Location: " . BASE_URL . "?page=logiciels");
            exit();
        }
    }

    public function edit()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=logiciels");
            exit();
        }

        $logiciel = $this->logicielModel->getById($_GET['id']);

        if (!$logiciel) {
            header("Location: " . BASE_URL . "?page=logiciels");
            exit();
        }

        $this->view('logiciels/edit', [
            'logiciel' => $logiciel
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'id_logiciel' => $_POST['id_logiciel'],
                'nom_logiciel' => $_POST['nom_logiciel'],
                'version' => $_POST['version'],
                'editeur' => $_POST['editeur'],
                'date_installation' => $_POST['date_installation']
            ];

            $this->logicielModel->update($data);

            header("Location: " . BASE_URL . "?page=logiciels");
            exit();
        }
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=logiciels");
            exit();
        }

        $this->logicielModel->delete($_GET['id']);

        header("Location: " . BASE_URL . "?page=logiciels");
        exit();
    }
}