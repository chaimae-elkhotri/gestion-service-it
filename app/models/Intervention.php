<?php

class Intervention
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAll()
    {
        $sql = "SELECT 
                    i.ID_INTERVENTION AS id_intervention,
                    i.ID_TICKET AS id_ticket,
                    i.RAPPORT AS rapport,
                    i.DUREE AS duree,
                    i.DATE_INTERVENTION AS date_intervention,
                    i.STATUT AS statut,
                    i.temps_reponse,
                    i.temps_resolution,

                    t.TITRE AS titre_ticket,

                    ai.ID_TECHNICIEN AS id_technicien,

                    u.NOM AS nom_technicien,
                    u.PRENOM AS prenom_technicien

                FROM intervention i

                LEFT JOIN ticket t
                    ON i.ID_TICKET = t.ID_TICKET

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION = ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                LEFT JOIN utilisateur u
                    ON ai.ID_TECHNICIEN = u.ID_UTILISATEUR

                ORDER BY i.DATE_INTERVENTION DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTickets()
    {
        $sql = "SELECT 
                    ID_TICKET AS id_ticket,
                    TITRE AS titre
                FROM ticket
                ORDER BY ID_TICKET DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTechniciens()
    {
        $sql = "SELECT 
                    u.ID_UTILISATEUR AS id_technicien,
                    u.NOM AS nom,
                    u.PRENOM AS prenom
                FROM utilisateur u
                INNER JOIN role r
                    ON u.ID_ROLE = r.ID_ROLE
                WHERE u.ID_ROLE = 2
                   OR r.NOM_ROLE = 'Technicien'
                ORDER BY u.NOM ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO intervention
                (ID_TICKET, RAPPORT, DUREE, STATUT, temps_reponse, temps_resolution)
                VALUES
                (:id_ticket, :rapport, :duree, :statut, :temps_reponse, :temps_resolution)";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':id_ticket' => $data['id_ticket'],
            ':rapport' => $data['rapport'],
            ':duree' => $data['duree'],
            ':statut' => $data['statut'],
            ':temps_reponse' => $data['temps_reponse'],
            ':temps_resolution' => $data['temps_resolution']
        ]);

        if ($result) {

            $idIntervention = $this->db->lastInsertId();

            $sqlAffectation = "INSERT INTO affectation_intervention
                               (ID_INTERVENTION, ID_TECHNICIEN, DATE_AFFECTATION, STATUT_AFFECTATION)
                               VALUES
                               (:id_intervention, :id_technicien, NOW(), 'Active')";

            $stmtAffectation = $this->db->prepare($sqlAffectation);

            $stmtAffectation->execute([
                ':id_intervention' => $idIntervention,
                ':id_technicien' => $data['id_technicien']
            ]);

            $sqlTicket = "UPDATE ticket 
                          SET STATUT = 'En cours'
                          WHERE ID_TICKET = :id_ticket";

            $stmtTicket = $this->db->prepare($sqlTicket);

            $stmtTicket->execute([
                ':id_ticket' => $data['id_ticket']
            ]);

            return $idIntervention;
        }

        return false;
    }

    public function getById($id)
    {
        $sql = "SELECT 
                    i.ID_INTERVENTION AS id_intervention,
                    i.ID_TICKET AS id_ticket,
                    i.RAPPORT AS rapport,
                    i.DUREE AS duree,
                    i.DATE_INTERVENTION AS date_intervention,
                    i.STATUT AS statut,
                    i.temps_reponse,
                    i.temps_resolution,

                    ai.ID_TECHNICIEN AS id_technicien

                FROM intervention i

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION = ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                WHERE i.ID_INTERVENTION = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $sql = "UPDATE intervention SET
                    ID_TICKET = :id_ticket,
                    RAPPORT = :rapport,
                    DUREE = :duree,
                    STATUT = :statut,
                    temps_reponse = :temps_reponse,
                    temps_resolution = :temps_resolution
                WHERE ID_INTERVENTION = :id_intervention";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':id_ticket' => $data['id_ticket'],
            ':rapport' => $data['rapport'],
            ':duree' => $data['duree'],
            ':statut' => $data['statut'],
            ':temps_reponse' => $data['temps_reponse'],
            ':temps_resolution' => $data['temps_resolution'],
            ':id_intervention' => $data['id_intervention']
        ]);

        if ($result) {

            $sqlOld = "UPDATE affectation_intervention
                       SET STATUT_AFFECTATION = 'Inactive'
                       WHERE ID_INTERVENTION = :id_intervention";

            $stmtOld = $this->db->prepare($sqlOld);

            $stmtOld->execute([
                ':id_intervention' => $data['id_intervention']
            ]);

            $sqlNew = "INSERT INTO affectation_intervention
                       (ID_INTERVENTION, ID_TECHNICIEN, DATE_AFFECTATION, STATUT_AFFECTATION)
                       VALUES
                       (:id_intervention, :id_technicien, NOW(), 'Active')";

            $stmtNew = $this->db->prepare($sqlNew);

            $stmtNew->execute([
                ':id_intervention' => $data['id_intervention'],
                ':id_technicien' => $data['id_technicien']
            ]);

            if ($data['statut'] == 'Terminée') {
                $statutTicket = 'Résolu';
            } else {
                $statutTicket = 'En cours';
            }

            $sqlTicket = "UPDATE ticket 
                          SET STATUT = :statut
                          WHERE ID_TICKET = :id_ticket";

            $stmtTicket = $this->db->prepare($sqlTicket);

            $stmtTicket->execute([
                ':statut' => $statutTicket,
                ':id_ticket' => $data['id_ticket']
            ]);
        }

        return $result;
    }

    public function delete($id)
    {
        $sqlAffectation = "DELETE FROM affectation_intervention
                           WHERE ID_INTERVENTION = :id";

        $stmtAffectation = $this->db->prepare($sqlAffectation);

        $stmtAffectation->execute([
            ':id' => $id
        ]);

        $sql = "DELETE FROM intervention
                WHERE ID_INTERVENTION = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM intervention";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function search($search)
    {
        $sql = "SELECT 
                    i.ID_INTERVENTION AS id_intervention,
                    i.ID_TICKET AS id_ticket,
                    i.RAPPORT AS rapport,
                    i.DUREE AS duree,
                    i.DATE_INTERVENTION AS date_intervention,
                    i.STATUT AS statut,
                    i.temps_reponse,
                    i.temps_resolution,

                    t.TITRE AS titre_ticket,

                    ai.ID_TECHNICIEN AS id_technicien,

                    u.NOM AS nom_technicien,
                    u.PRENOM AS prenom_technicien

                FROM intervention i

                LEFT JOIN ticket t
                    ON i.ID_TICKET = t.ID_TICKET

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION = ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                LEFT JOIN utilisateur u
                    ON ai.ID_TECHNICIEN = u.ID_UTILISATEUR

                WHERE i.RAPPORT LIKE :search
                   OR i.STATUT LIKE :search
                   OR t.TITRE LIKE :search
                   OR u.NOM LIKE :search
                   OR u.PRENOM LIKE :search

                ORDER BY i.DATE_INTERVENTION DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}