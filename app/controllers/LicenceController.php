<?php

require_once '../app/models/Licence.php';
require_once '../app/models/Logiciel.php';
require_once '../app/models/Historique.php';

class LicenceController extends Controller
{
    private $licenceModel;
    private $logicielModel;

    public function __construct()
    {
        $this->licenceModel = new Licence();
        $this->logicielModel = new Logiciel();
    }

    public function index()
    {
        $search = $_GET['search'] ?? '';

        if (!empty($search)) {
            $licences = $this->licenceModel->search($search);
        } else {
            $licences = $this->licenceModel->getAll();
        }

        $this->view('licences/index', [
            'licences' => $licences
        ]);
    }

    public function create()
    {
        $logiciels = $this->logicielModel->getAll();

        $this->view('licences/create', [
            'logiciels' => $logiciels
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'id_logiciel' => $_POST['id_logiciel'],
                'cle_licence' => $_POST['cle_licence'],
                'date_debut' => $_POST['date_debut'],
                'date_fin' => $_POST['date_fin'],
                'nombre_postes' => $_POST['nombre_postes']
            ];

            $idLicence = $this->licenceModel->insert($data);

            if ($idLicence) {
                Historique::enregistrer(
                    'licence',
                    $idLicence,
                    'Ajout',
                    null,
                    'Ajout licence pour logiciel #' . $data['id_logiciel'] . ' | Date fin : ' . $data['date_fin'] . ' | Postes : ' . $data['nombre_postes']
                );
            }

            header("Location: " . BASE_URL . "?page=licences");
            exit();
        }
    }

    public function edit()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=licences");
            exit();
        }

        $licence = $this->licenceModel->getById($_GET['id']);
        $logiciels = $this->logicielModel->getAll();

        if (!$licence) {
            header("Location: " . BASE_URL . "?page=licences");
            exit();
        }

        $this->view('licences/edit', [
            'licence' => $licence,
            'logiciels' => $logiciels
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ancienneLicence = $this->licenceModel->getById($_POST['id_licence']);

            $data = [
                'id_licence' => $_POST['id_licence'],
                'id_logiciel' => $_POST['id_logiciel'],
                'cle_licence' => $_POST['cle_licence'],
                'date_debut' => $_POST['date_debut'],
                'date_fin' => $_POST['date_fin'],
                'nombre_postes' => $_POST['nombre_postes']
            ];

            $result = $this->licenceModel->update($data);

            if ($result) {
                Historique::enregistrer(
                    'licence',
                    $data['id_licence'],
                    'Modification',
                    json_encode($ancienneLicence, JSON_UNESCAPED_UNICODE),
                    json_encode($data, JSON_UNESCAPED_UNICODE)
                );
            }

            header("Location: " . BASE_URL . "?page=licences");
            exit();
        }
    }

    public function delete()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=licences");
            exit();
        }

        $id = $_GET['id'];

        $ancienneLicence = $this->licenceModel->getById($id);

        $result = $this->licenceModel->delete($id);

        if ($result) {
            Historique::enregistrer(
                'licence',
                $id,
                'Suppression',
                json_encode($ancienneLicence, JSON_UNESCAPED_UNICODE),
                null
            );
        }

        header("Location: " . BASE_URL . "?page=licences");
        exit();
    }
}