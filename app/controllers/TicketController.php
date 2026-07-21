<?php

require_once '../app/core/Auth.php';

require_once '../app/models/Ticket.php';
require_once '../app/models/Utilisateur.php';
require_once '../app/models/MoyenCommunication.php';
require_once '../app/models/Equipement.php';
require_once '../app/models/Historique.php';

class TicketController extends Controller
{
    private $ticketModel;
    private $utilisateurModel;
    private $moyenModel;
    private $equipementModel;

    public function __construct()
    {
        Auth::verifierConnexion();

        $this->ticketModel = new Ticket();
        $this->utilisateurModel = new Utilisateur();
        $this->moyenModel = new MoyenCommunication();
        $this->equipementModel = new Equipement();
    }

    public function index()
    {
        $search = trim($_GET['search'] ?? '');

        if (!empty($search)) {
            $tickets = $this->ticketModel->search($search);
        } else {
            $tickets = $this->ticketModel->getAll();
        }

        /*
         * L’employé ne doit voir que ses propres tickets.
         */
        if (Auth::estEmploye()) {
            $idUtilisateurConnecte = (int) Auth::idUtilisateur();

            $tickets = array_filter(
                $tickets,
                function ($ticket) use ($idUtilisateurConnecte) {
                    return (int)($ticket['id_utilisateur'] ?? 0)
                        === $idUtilisateurConnecte;
                }
            );

            $tickets = array_values($tickets);
        }

        $this->view('tickets/index', [
            'tickets' => $tickets
        ]);
    }

    public function create()
    {
        $utilisateurs = [];
        $moyens = $this->moyenModel->getAll();
        $equipements = $this->equipementModel->getAll();

        /*
         * L’employé est automatiquement associé à son ticket.
         * La liste des utilisateurs est utile seulement pour l’admin
         * et éventuellement le technicien.
         */
        if (!Auth::estEmploye()) {
            $utilisateurs = $this->utilisateurModel->getAll();
        }

        $this->view('tickets/create', [
            'utilisateurs' => $utilisateurs,
            'moyens' => $moyens,
            'equipements' => $equipements
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?page=tickets");
            exit();
        }

        /*
         * Pour l’employé :
         * - utilisateur connecté automatiquement ;
         * - statut automatiquement Ouvert.
         */
        if (Auth::estEmploye()) {
            $idUtilisateur = (int) Auth::idUtilisateur();
            $statut = 'Ouvert';
        } else {
            $idUtilisateur = (int)($_POST['id_utilisateur'] ?? 0);
            $statut = trim($_POST['statut'] ?? 'Ouvert');
        }

        $data = [
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'priorite' => trim($_POST['priorite'] ?? ''),
            'statut' => $statut,
            'id_utilisateur' => $idUtilisateur,
            'id_moyen' => (int)($_POST['id_moyen'] ?? 0),
            'id_equipement' => !empty($_POST['id_equipement'])
                ? (int)$_POST['id_equipement']
                : null
        ];

        if (
            empty($data['titre']) ||
            empty($data['description']) ||
            empty($data['priorite']) ||
            empty($data['id_utilisateur']) ||
            empty($data['id_moyen'])
        ) {
            header(
                "Location: " .
                BASE_URL .
                "?page=ajouter-ticket&erreur=champs-obligatoires"
            );
            exit();
        }

        $idTicket = $this->ticketModel->insert($data);

        if ($idTicket) {
            Historique::enregistrer(
                'ticket',
                $idTicket,
                'Ajout',
                null,
                'Ajout ticket : ' .
                $data['titre'] .
                ' | Priorité : ' .
                $data['priorite'] .
                ' | Statut : ' .
                $data['statut']
            );
        }

        header("Location: " . BASE_URL . "?page=tickets");
        exit();
    }

    public function edit()
    {
        if (empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=tickets");
            exit();
        }

        $idTicket = (int)$_GET['id'];

        $ticket = $this->ticketModel->getById($idTicket);

        if (!$ticket) {
            header("Location: " . BASE_URL . "?page=tickets");
            exit();
        }

        /*
         * Un employé ne peut modifier que son propre ticket.
         */
        if (
            Auth::estEmploye() &&
            (int)($ticket['id_utilisateur'] ?? 0) !==
            (int)Auth::idUtilisateur()
        ) {
            header("Location: " . BASE_URL . "?page=acces-refuse");
            exit();
        }

        $utilisateurs = [];
        $moyens = $this->moyenModel->getAll();
        $equipements = $this->equipementModel->getAll();

        if (!Auth::estEmploye()) {
            $utilisateurs = $this->utilisateurModel->getAll();
        }

        $this->view('tickets/edit', [
            'ticket' => $ticket,
            'utilisateurs' => $utilisateurs,
            'moyens' => $moyens,
            'equipements' => $equipements
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "?page=tickets");
            exit();
        }

        $idTicket = (int)($_POST['id_ticket'] ?? 0);

        if ($idTicket <= 0) {
            header("Location: " . BASE_URL . "?page=tickets");
            exit();
        }

        $ancienTicket = $this->ticketModel->getById($idTicket);

        if (!$ancienTicket) {
            header("Location: " . BASE_URL . "?page=tickets");
            exit();
        }

        /*
         * Un employé ne peut modifier que son propre ticket.
         */
        if (
            Auth::estEmploye() &&
            (int)($ancienTicket['id_utilisateur'] ?? 0) !==
            (int)Auth::idUtilisateur()
        ) {
            header("Location: " . BASE_URL . "?page=acces-refuse");
            exit();
        }

        if (Auth::estEmploye()) {
            $idUtilisateur = (int)Auth::idUtilisateur();

            /*
             * L’employé ne change pas lui-même le statut technique.
             */
            $statut = $ancienTicket['statut'] ?? 'Ouvert';
        } else {
            $idUtilisateur = (int)($_POST['id_utilisateur'] ?? 0);
            $statut = trim($_POST['statut'] ?? 'Ouvert');
        }

        $data = [
            'id_ticket' => $idTicket,
            'titre' => trim($_POST['titre'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'priorite' => trim($_POST['priorite'] ?? ''),
            'statut' => $statut,
            'id_utilisateur' => $idUtilisateur,
            'id_moyen' => (int)($_POST['id_moyen'] ?? 0),
            'id_equipement' => !empty($_POST['id_equipement'])
                ? (int)$_POST['id_equipement']
                : null
        ];

        $result = $this->ticketModel->update($data);

        if ($result) {
            Historique::enregistrer(
                'ticket',
                $data['id_ticket'],
                'Modification',
                json_encode(
                    $ancienTicket,
                    JSON_UNESCAPED_UNICODE
                ),
                json_encode(
                    $data,
                    JSON_UNESCAPED_UNICODE
                )
            );
        }

        header("Location: " . BASE_URL . "?page=tickets");
        exit();
    }

    public function delete()
    {
        /*
         * Seul l’administrateur peut supprimer un ticket.
         */
        Auth::autoriser([1]);

        if (empty($_GET['id'])) {
            header("Location: " . BASE_URL . "?page=tickets");
            exit();
        }

        $id = (int)$_GET['id'];

        $ancienTicket = $this->ticketModel->getById($id);

        if (!$ancienTicket) {
            header("Location: " . BASE_URL . "?page=tickets");
            exit();
        }

        $result = $this->ticketModel->delete($id);

        if ($result) {
            Historique::enregistrer(
                'ticket',
                $id,
                'Suppression',
                json_encode(
                    $ancienTicket,
                    JSON_UNESCAPED_UNICODE
                ),
                null
            );
        }

        header("Location: " . BASE_URL . "?page=tickets");
        exit();
    }
}