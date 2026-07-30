<?php

require_once '../app/core/Auth.php';
require_once '../app/models/Intervention.php';
require_once '../app/models/Historique.php';

class InterventionController extends Controller
{
    private $interventionModel;

    public function __construct()
    {
        Auth::verifierConnexion();

        $this->interventionModel =
            new Intervention();
    }

    public function index()
    {
        $search = trim($_GET['search'] ?? '');

        if ($search !== '') {
            $interventions =
                $this->interventionModel->search($search);
        } else {
            $interventions =
                $this->interventionModel->getAll();
        }

        $this->view('interventions/index', [
            'interventions' => $interventions
        ]);
    }

    public function create()
    {
        $tickets =
            $this->interventionModel->getTickets();

        $techniciens =
            $this->interventionModel->getTechniciens();

        $this->view('interventions/create', [
            'tickets' => $tickets,
            'techniciens' => $techniciens
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=interventions'
            );
            exit;
        }

        $idTicket =
            (int)($_POST['id_ticket'] ?? 0);

        $idTechnicien =
            (int)($_POST['id_technicien'] ?? 0);

        $dateIntervention =
            $this->normaliserDate(
                $_POST['date_intervention'] ?? ''
            );

        $dateFinPrevue =
            $this->normaliserDate(
                $_POST['date_fin_prevue'] ?? ''
            );

        $statut =
            trim($_POST['statut'] ?? 'En attente');

        $statutsAutorises = [
            'En attente',
            'En cours',
            'Terminée'
        ];

        if (
            $idTicket <= 0 ||
            $idTechnicien <= 0 ||
            !$dateIntervention ||
            !$dateFinPrevue ||
            !in_array($statut, $statutsAutorises, true)
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement les champs obligatoires.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-intervention'
            );
            exit;
        }

        if (
            strtotime($dateFinPrevue) <=
            strtotime($dateIntervention)
        ) {
            $_SESSION['error'] =
                'La date de fin doit être postérieure à la date de début.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-intervention'
            );
            exit;
        }

        $erreurDisponibilite =
            $this->verifierDisponibiliteLocal(
                $idTicket,
                $dateIntervention,
                $dateFinPrevue
            );

        if ($erreurDisponibilite !== null) {
            $_SESSION['error'] =
                $erreurDisponibilite;

            header(
                'Location: ' .
                BASE_URL .
                '?page=ajouter-intervention'
            );
            exit;
        }

        $data = [
            'id_ticket' => $idTicket,
            'id_technicien' => $idTechnicien,
            'rapport' =>
                trim($_POST['rapport'] ?? ''),
            'duree' =>
                trim($_POST['duree'] ?? ''),
            'date_intervention' =>
                $dateIntervention,
            'date_fin_prevue' =>
                $dateFinPrevue,
            'statut' => $statut,
            'temps_reponse' =>
                trim($_POST['temps_reponse'] ?? ''),
            'temps_resolution' =>
                trim($_POST['temps_resolution'] ?? '')
        ];

        try {
            $idIntervention =
                $this->interventionModel->insert($data);

            if ($idIntervention) {
                Historique::enregistrer(
                    'intervention',
                    $idIntervention,
                    'Ajout',
                    null,
                    'Ajout intervention pour ticket #' .
                    $idTicket .
                    ' | Technicien #' .
                    $idTechnicien .
                    ' | Début : ' .
                    $dateIntervention .
                    ' | Fin : ' .
                    $dateFinPrevue
                );

                $_SESSION['success'] =
                    'L’intervention a été enregistrée avec succès.';
            }

        } catch (Throwable $e) {
            $_SESSION['error'] =
                'Impossible d’enregistrer l’intervention.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=interventions'
        );
        exit;
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header(
                'Location: ' .
                BASE_URL .
                '?page=interventions'
            );
            exit;
        }

        $intervention =
            $this->interventionModel->getById($id);

        if (!$intervention) {
            $_SESSION['error'] =
                'Intervention introuvable.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=interventions'
            );
            exit;
        }

        $tickets =
            $this->interventionModel->getTickets();

        $techniciens =
            $this->interventionModel->getTechniciens();

        $this->view('interventions/edit', [
            'intervention' => $intervention,
            'tickets' => $tickets,
            'techniciens' => $techniciens
        ]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header(
                'Location: ' .
                BASE_URL .
                '?page=interventions'
            );
            exit;
        }

        $idIntervention =
            (int)($_POST['id_intervention'] ?? 0);

        $idTicket =
            (int)($_POST['id_ticket'] ?? 0);

        $idTechnicien =
            (int)($_POST['id_technicien'] ?? 0);

        $dateIntervention =
            $this->normaliserDate(
                $_POST['date_intervention'] ?? ''
            );

        $dateFinPrevue =
            $this->normaliserDate(
                $_POST['date_fin_prevue'] ?? ''
            );

        $statut =
            trim($_POST['statut'] ?? 'En attente');

        $statutsAutorises = [
            'En attente',
            'En cours',
            'Terminée'
        ];

        if (
            $idIntervention <= 0 ||
            $idTicket <= 0 ||
            $idTechnicien <= 0 ||
            !$dateIntervention ||
            !$dateFinPrevue ||
            !in_array($statut, $statutsAutorises, true)
        ) {
            $_SESSION['error'] =
                'Veuillez remplir correctement les champs obligatoires.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-intervention&id=' .
                $idIntervention
            );
            exit;
        }

        if (
            strtotime($dateFinPrevue) <=
            strtotime($dateIntervention)
        ) {
            $_SESSION['error'] =
                'La date de fin doit être postérieure à la date de début.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-intervention&id=' .
                $idIntervention
            );
            exit;
        }

        $erreurDisponibilite =
            $this->verifierDisponibiliteLocal(
                $idTicket,
                $dateIntervention,
                $dateFinPrevue
            );

        if ($erreurDisponibilite !== null) {
            $_SESSION['error'] =
                $erreurDisponibilite;

            header(
                'Location: ' .
                BASE_URL .
                '?page=modifier-intervention&id=' .
                $idIntervention
            );
            exit;
        }

        $ancienneIntervention =
            $this->interventionModel->getById(
                $idIntervention
            );

        $data = [
            'id_intervention' => $idIntervention,
            'id_ticket' => $idTicket,
            'id_technicien' => $idTechnicien,
            'rapport' =>
                trim($_POST['rapport'] ?? ''),
            'duree' =>
                trim($_POST['duree'] ?? ''),
            'date_intervention' =>
                $dateIntervention,
            'date_fin_prevue' =>
                $dateFinPrevue,
            'statut' => $statut,
            'temps_reponse' =>
                trim($_POST['temps_reponse'] ?? ''),
            'temps_resolution' =>
                trim($_POST['temps_resolution'] ?? '')
        ];

        try {
            $resultat =
                $this->interventionModel->update($data);

            if ($resultat) {
                Historique::enregistrer(
                    'intervention',
                    $idIntervention,
                    'Modification',
                    json_encode(
                        $ancienneIntervention,
                        JSON_UNESCAPED_UNICODE
                    ),
                    json_encode(
                        $data,
                        JSON_UNESCAPED_UNICODE
                    )
                );

                $_SESSION['success'] =
                    'L’intervention a été modifiée avec succès.';
            }

        } catch (Throwable $e) {
            $_SESSION['error'] =
                'Impossible de modifier l’intervention.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=interventions'
        );
        exit;
    }

    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            header(
                'Location: ' .
                BASE_URL .
                '?page=interventions'
            );
            exit;
        }

        $ancienneIntervention =
            $this->interventionModel->getById($id);

        try {
            $resultat =
                $this->interventionModel->delete($id);

            if ($resultat) {
                Historique::enregistrer(
                    'intervention',
                    $id,
                    'Suppression',
                    json_encode(
                        $ancienneIntervention,
                        JSON_UNESCAPED_UNICODE
                    ),
                    null
                );

                $_SESSION['success'] =
                    'L’intervention a été supprimée.';
            }

        } catch (Throwable $e) {
            $_SESSION['error'] =
                'Impossible de supprimer l’intervention.';
        }

        header(
            'Location: ' .
            BASE_URL .
            '?page=interventions'
        );
        exit;
    }

    private function verifierDisponibiliteLocal(
        $idTicket,
        $dateDebut,
        $dateFin
    ) {
        $ticket =
            $this->interventionModel->getTicketLocal(
                $idTicket
            );

        if (!$ticket) {
            return 'Le ticket sélectionné est introuvable.';
        }

        $statutTicket = mb_strtolower(
            trim($ticket['statut_ticket'] ?? ''),
            'UTF-8'
        );

        if (
            $statutTicket === 'annulé' ||
            $statutTicket === 'annule'
        ) {
            return 'Une intervention ne peut pas être créée pour un ticket annulé.';
        }

        $idLocal = (int)($ticket['id_local'] ?? 0);

        /*
         * Si le ticket n’est associé à aucun équipement
         * ou local, l’intervention reste autorisée.
         */
        if ($idLocal <= 0) {
            return null;
        }

        $statutGeneral =
            $ticket['statut_general'] ?? 'Actif';

        if ($statutGeneral !== 'Actif') {
            $nomLocal =
                $ticket['nom_local'] ?? 'Le local';

            return $nomLocal .
                ' est actuellement en maintenance ou indisponible.';
        }

        $conflit =
            $this->interventionModel
                ->getOccupationConflict(
                    $idLocal,
                    $dateDebut,
                    $dateFin
                );

        if (!$conflit) {
            return null;
        }

        $nomLocal =
            $ticket['nom_local'] ?? 'Ce local';

        $motif =
            $conflit['motif']
            ?? 'Occupation enregistrée';

        $debutConflit = date(
            'd/m/Y à H:i',
            strtotime($conflit['date_debut'])
        );

        $finConflit = date(
            'd/m/Y à H:i',
            strtotime($conflit['date_fin'])
        );

        return $nomLocal .
            ' est occupé pour « ' .
            $motif .
            ' » du ' .
            $debutConflit .
            ' au ' .
            $finConflit .
            '. Choisissez une autre période.';
    }

    private function normaliserDate($date)
    {
        $date = trim((string)$date);

        if ($date === '') {
            return null;
        }

        $objetDate = DateTime::createFromFormat(
            'Y-m-d\TH:i',
            $date
        );

        if (!$objetDate) {
            return null;
        }

        return $objetDate->format(
            'Y-m-d H:i:s'
        );
    }
}