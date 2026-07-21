<?php

require_once '../app/models/Categorie.php';

class CategorieController extends Controller
{
    private $categorieModel;

    public function __construct()
    {
        $this->categorieModel = new Categorie();
    }

   // Afficher toutes les catégories
public function index()
{
    $search = $_GET['search'] ?? '';

    if (!empty($search)) {
        $categories = $this->categorieModel->search($search);
    } else {
        $categories = $this->categorieModel->getAll();
    }

    $this->view('categories/index', [
        'categories' => $categories
    ]);
}

    // Afficher le formulaire d'ajout
    public function create()
    {
        $this->view('categories/create');
    }

    // Enregistrer une catégorie
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->categorieModel->insert($_POST['nom_categorie']);

            header("Location: " . BASE_URL . "?page=categories");
            exit();
        }
    }

    // Afficher le formulaire de modification
    public function edit()
    {
        $categorie = $this->categorieModel->getById($_GET['id']);

        $this->view('categories/edit', [
            'categorie' => $categorie
        ]);
    }

    // Mettre à jour une catégorie
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $this->categorieModel->update(
                $_POST['id_categorie'],
                $_POST['nom_categorie']
            );

            header("Location: " . BASE_URL . "?page=categories");
            exit();
        }
    }

    // Supprimer une catégorie
    public function delete()
    {
        $this->categorieModel->delete($_GET['id']);

        header("Location: " . BASE_URL . "?page=categories");
        exit();
    }
}