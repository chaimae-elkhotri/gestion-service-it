<?php

require_once '../app/core/Auth.php';

class DashboardController extends Controller
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    private function countQuery(string $sql, array $params = []): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    private function fetchAllQuery(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchOneQuery(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: [];
    }

    public function index()
    {
        Auth::verifierConnexion();

        if (Auth::estAdmin()) {
            $this->dashboardAdministrateur();
            return;
        }

        if (Auth::estTechnicien()) {
            $this->dashboardTechnicien();
            return;
        }

        $this->dashboardEmploye();
    }

    private function dashboardAdministrateur()
    {
        $stats = [
            'utilisateurs' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM utilisateur"
            ),

            'equipements' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM equipement"
            ),

            'equipements_disponibles' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM equipement
                 WHERE STATUT = 'Disponible'"
            ),

            'equipements_affectes' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM equipement
                 WHERE STATUT = 'Affecté'"
            ),

            'equipements_maintenance' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM equipement
                 WHERE STATUT IN ('En maintenance', 'Maintenance')"
            ),

            'tickets' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket"
            ),

            'tickets_ouverts' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket
                 WHERE STATUT = 'Ouvert'"
            ),

            'tickets_en_cours' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket
                 WHERE STATUT = 'En cours'"
            ),

            'tickets_en_attente' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket
                 WHERE STATUT = 'En attente'"
            ),

            'tickets_resolus' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket
                 WHERE STATUT = 'Résolu'"
            ),

            'interventions' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM intervention"
            ),

            'interventions_en_cours' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM intervention
                 WHERE STATUT = 'En cours'"
            ),

            'interventions_terminees' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM intervention
                 WHERE STATUT = 'Terminée'"
            ),

            'logiciels' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM logiciel"
            ),

            'licences' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM licence"
            ),

            'licences_expirees' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM licence
                 WHERE DATE_FIN < CURDATE()"
            ),

            'licences_bientot_expirees' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM licence
                 WHERE DATE_FIN BETWEEN CURDATE()
                 AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)"
            ),

            'locaux' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM `local`"
            ),

            'historiques' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM historique"
            ),

            'evaluations' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM evaluation"
            )
        ];

        $equipementsParLocal = $this->fetchAllQuery(
            "SELECT
                l.NOM_LOCAL AS nom_local,
                COUNT(e.ID_EQUIPEMENT_) AS total
             FROM `local` l
             LEFT JOIN equipement e
                ON l.ID_LOCAL = e.ID_LOCAL
             GROUP BY l.ID_LOCAL, l.NOM_LOCAL
             ORDER BY total DESC
             LIMIT 7"
        );

        $ticketsParStatut = $this->fetchAllQuery(
            "SELECT
                STATUT AS statut,
                COUNT(*) AS total
             FROM ticket
             GROUP BY STATUT
             ORDER BY total DESC"
        );

        $derniersTickets = $this->fetchAllQuery(
            "SELECT
                t.ID_TICKET AS id_ticket,
                t.TITRE AS titre,
                t.PRIORITE AS priorite,
                t.STATUT AS statut,
                t.DATE_CREATION AS date_creation,
                u.NOM AS nom,
                u.PRENOM AS prenom
             FROM ticket t
             LEFT JOIN utilisateur u
                ON t.ID_UTILISATEUR = u.ID_UTILISATEUR
             ORDER BY t.DATE_CREATION DESC
             LIMIT 5"
        );

        $licencesExpirees = $this->fetchAllQuery(
            "SELECT
                l.ID_LICENCE AS id_licence,
                l.DATE_FIN AS date_fin,
                lo.NOM_LOGICIEL AS nom_logiciel,
                lo.EDITEUR AS editeur
             FROM licence l
             LEFT JOIN logiciel lo
                ON l.ID_LOGICIEL = lo.ID_LOGICIEL
             WHERE l.DATE_FIN < CURDATE()
             ORDER BY l.DATE_FIN ASC
             LIMIT 5"
        );

        $activitesRecentes = $this->fetchAllQuery(
            "SELECT
                h.id_historique,
                h.table_concernee,
                h.action,
                h.nouvelle_valeur,
                h.date_action,
                u.NOM AS nom,
                u.PRENOM AS prenom
             FROM historique h
             LEFT JOIN utilisateur u
                ON h.utilisateur_action = u.ID_UTILISATEUR
             ORDER BY h.date_action DESC
             LIMIT 5"
        );

        $evaluation = $this->fetchOneQuery(
            "SELECT AVG(note) AS moyenne
             FROM evaluation"
        );

        $moyenneEvaluation = $evaluation['moyenne'] ?? 0;

        $this->view('dashboard/home', [
            'roleDashboard' => 'administrateur',
            'stats' => $stats,
            'equipementsParLocal' => $equipementsParLocal,
            'ticketsParStatut' => $ticketsParStatut,
            'derniersTickets' => $derniersTickets,
            'licencesExpirees' => $licencesExpirees,
            'activitesRecentes' => $activitesRecentes,
            'moyenneEvaluation' => $moyenneEvaluation
        ]);
    }

    private function dashboardTechnicien()
    {
        $idTechnicien = (int)Auth::idUtilisateur();

        $stats = [
            'mes_interventions' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM affectation_intervention ai
                 WHERE ai.ID_TECHNICIEN = :id",
                [':id' => $idTechnicien]
            ),

            'interventions_en_cours' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM affectation_intervention ai
                 INNER JOIN intervention i
                    ON ai.ID_INTERVENTION = i.ID_INTERVENTION
                 WHERE ai.ID_TECHNICIEN = :id
                 AND i.STATUT = 'En cours'",
                [':id' => $idTechnicien]
            ),

            'interventions_terminees' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM affectation_intervention ai
                 INNER JOIN intervention i
                    ON ai.ID_INTERVENTION = i.ID_INTERVENTION
                 WHERE ai.ID_TECHNICIEN = :id
                 AND i.STATUT = 'Terminée'",
                [':id' => $idTechnicien]
            ),

            'equipements_maintenance' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM equipement
                 WHERE STATUT IN (
                    'Maintenance',
                    'En maintenance',
                    'En panne'
                 )"
            )
        ];

        $mesInterventions = $this->fetchAllQuery(
            "SELECT
                i.ID_INTERVENTION AS id_intervention,
                i.ID_TICKET AS id_ticket,
                i.RAPPORT AS rapport,
                i.DUREE AS duree,
                i.DATE_INTERVENTION AS date_intervention,
                i.STATUT AS statut,
                t.TITRE AS titre_ticket,
                t.PRIORITE AS priorite
             FROM affectation_intervention ai
             INNER JOIN intervention i
                ON ai.ID_INTERVENTION = i.ID_INTERVENTION
             INNER JOIN ticket t
                ON i.ID_TICKET = t.ID_TICKET
             WHERE ai.ID_TECHNICIEN = :id
             ORDER BY i.DATE_INTERVENTION DESC
             LIMIT 6",
            [':id' => $idTechnicien]
        );

        $this->view('dashboard/home', [
            'roleDashboard' => 'technicien',
            'stats' => $stats,
            'mesInterventions' => $mesInterventions,

            'equipementsParLocal' => [],
            'ticketsParStatut' => [],
            'derniersTickets' => [],
            'licencesExpirees' => [],
            'activitesRecentes' => [],
            'moyenneEvaluation' => 0
        ]);
    }

    private function dashboardEmploye()
    {
        $idUtilisateur = (int)Auth::idUtilisateur();

        $stats = [
            'mes_tickets' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket
                 WHERE ID_UTILISATEUR = :id",
                [':id' => $idUtilisateur]
            ),

            'tickets_ouverts' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket
                 WHERE ID_UTILISATEUR = :id
                 AND STATUT = 'Ouvert'",
                [':id' => $idUtilisateur]
            ),

            'tickets_en_cours' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket
                 WHERE ID_UTILISATEUR = :id
                 AND STATUT = 'En cours'",
                [':id' => $idUtilisateur]
            ),

            'tickets_resolus' => $this->countQuery(
                "SELECT COUNT(*) AS total
                 FROM ticket
                 WHERE ID_UTILISATEUR = :id
                 AND STATUT = 'Résolu'",
                [':id' => $idUtilisateur]
            )
        ];

        $mesTickets = $this->fetchAllQuery(
            "SELECT
                ID_TICKET AS id_ticket,
                TITRE AS titre,
                DESCRIPTION AS description,
                PRIORITE AS priorite,
                STATUT AS statut,
                DATE_CREATION AS date_creation
             FROM ticket
             WHERE ID_UTILISATEUR = :id
             ORDER BY DATE_CREATION DESC
             LIMIT 6",
            [':id' => $idUtilisateur]
        );

        $this->view('dashboard/home', [
            'roleDashboard' => 'employe',
            'stats' => $stats,
            'mesTickets' => $mesTickets,

            'equipementsParLocal' => [],
            'ticketsParStatut' => [],
            'derniersTickets' => [],
            'licencesExpirees' => [],
            'activitesRecentes' => [],
            'moyenneEvaluation' => 0
        ]);
    }
}