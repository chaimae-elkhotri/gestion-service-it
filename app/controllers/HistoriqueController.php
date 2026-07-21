<?php

require_once '../app/models/Historique.php';

class HistoriqueController extends Controller
{
    private $historiqueModel;

    public function __construct()
    {
        $this->historiqueModel = new Historique();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $historiques = $this->historiqueModel->search($search);
        } else {
            $historiques = $this->historiqueModel->getAll();
        }

        $this->view('historiques/index', [
            'historiques' => $historiques
        ]);
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=historiques");
            exit();
        }

        $this->historiqueModel->delete($_GET['id']);

        header("Location: " . BASE_URL . "?page=historiques");
        exit();
    }
}