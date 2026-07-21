<?php

require_once '../app/models/Local.php';

class LocalController extends Controller
{
    private $localModel;

    public function __construct()
    {
        $this->localModel = new Local();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
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
        $this->view('locals/create');
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'nom_local' => $_POST['nom_local'],
                'type_local' => $_POST['type_local']
            ];

            $this->localModel->insert($data);

            header("Location: " . BASE_URL . "?page=locals");
            exit();
        }
    }

    public function edit()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=locals");
            exit();
        }

        $id = $_GET['id'];

        $local = $this->localModel->getById($id);

        if (!$local) {
            header("Location: " . BASE_URL . "?page=locals");
            exit();
        }

        $this->view('locals/edit', [
            'local' => $local
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'id_local' => $_POST['id_local'],
                'nom_local' => $_POST['nom_local'],
                'type_local' => $_POST['type_local']
            ];

            $this->localModel->update($data);

            header("Location: " . BASE_URL . "?page=locals");
            exit();
        }
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=locals");
            exit();
        }

        $id = $_GET['id'];

        try {
            $this->localModel->delete($id);
            header("Location: " . BASE_URL . "?page=locals");
            exit();
        } catch (PDOException $e) {
            header("Location: " . BASE_URL . "?page=locals&error=suppression");
            exit();
        }
    }
}