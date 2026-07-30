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
                    i.DATE_FIN_PREVUE AS date_fin_prevue,
                    i.STATUT AS statut,
                    i.temps_reponse,
                    i.temps_resolution,

                    t.TITRE AS titre_ticket,

                    ai.ID_TECHNICIEN AS id_technicien,

                    u.NOM AS nom_technicien,
                    u.PRENOM AS prenom_technicien,

                    e.ID_EQUIPEMENT_ AS id_equipement,
                    e.NUMERO_SERIE AS numero_serie,

                    l.ID_LOCAL AS id_local,
                    l.NOM_LOCAL AS nom_local

                FROM intervention i

                LEFT JOIN ticket t
                    ON i.ID_TICKET = t.ID_TICKET

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION = ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                LEFT JOIN utilisateur u
                    ON ai.ID_TECHNICIEN = u.ID_UTILISATEUR

                LEFT JOIN equipement e
                    ON t.ID_EQUIPEMENT = e.ID_EQUIPEMENT_

                LEFT JOIN `local` l
                    ON e.ID_LOCAL = l.ID_LOCAL

                ORDER BY i.DATE_INTERVENTION DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTickets()
    {
        $sql = "SELECT
                    t.ID_TICKET AS id_ticket,
                    t.TITRE AS titre,
                    t.STATUT AS statut_ticket,
                    t.ID_EQUIPEMENT AS id_equipement,

                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele,
                    e.ID_LOCAL AS id_local,

                    l.NOM_LOCAL AS nom_local,
                    l.TYPE_LOCAL AS type_local,
                    l.STATUT_GENERAL AS statut_local

                FROM ticket t

                LEFT JOIN equipement e
                    ON t.ID_EQUIPEMENT = e.ID_EQUIPEMENT_

                LEFT JOIN `local` l
                    ON e.ID_LOCAL = l.ID_LOCAL

                WHERE t.STATUT NOT IN ('Annulé', 'Annule')

                ORDER BY t.ID_TICKET DESC";

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
                WHERE (
                    u.ID_ROLE = 2
                    OR r.NOM_ROLE = 'Technicien'
                )
                AND u.STATUT = 'Actif'
                ORDER BY u.NOM ASC, u.PRENOM ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTicketLocal($idTicket)
    {
        $sql = "SELECT
                    t.ID_TICKET AS id_ticket,
                    t.TITRE AS titre_ticket,
                    t.STATUT AS statut_ticket,
                    t.ID_EQUIPEMENT AS id_equipement,

                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele,
                    e.ID_LOCAL AS id_local,

                    l.NOM_LOCAL AS nom_local,
                    l.TYPE_LOCAL AS type_local,
                    l.STATUT_GENERAL AS statut_general

                FROM ticket t

                LEFT JOIN equipement e
                    ON t.ID_EQUIPEMENT = e.ID_EQUIPEMENT_

                LEFT JOIN `local` l
                    ON e.ID_LOCAL = l.ID_LOCAL

                WHERE t.ID_TICKET = :id_ticket
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_ticket' => $idTicket
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOccupationConflict(
        $idLocal,
        $dateDebut,
        $dateFin
    ) {
        $sql = "SELECT
                    ID_OCCUPATION AS id_occupation,
                    TYPE_OCCUPATION AS type_occupation,
                    MOTIF AS motif,
                    DATE_DEBUT AS date_debut,
                    DATE_FIN AS date_fin
                FROM occupation_local
                WHERE ID_LOCAL = :id_local
                  AND STATUT = 'Active'
                  AND DATE_DEBUT < :date_fin
                  AND DATE_FIN > :date_debut
                ORDER BY DATE_DEBUT ASC
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id_local' => $idLocal,
            ':date_debut' => $dateDebut,
            ':date_fin' => $dateFin
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO intervention
                    (
                        ID_TICKET,
                        RAPPORT,
                        DUREE,
                        DATE_INTERVENTION,
                        DATE_FIN_PREVUE,
                        STATUT,
                        temps_reponse,
                        temps_resolution
                    )
                    VALUES
                    (
                        :id_ticket,
                        :rapport,
                        :duree,
                        :date_intervention,
                        :date_fin_prevue,
                        :statut,
                        :temps_reponse,
                        :temps_resolution
                    )";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':id_ticket' => $data['id_ticket'],
                ':rapport' => $data['rapport'],
                ':duree' => $data['duree'],
                ':date_intervention' =>
                    $data['date_intervention'],
                ':date_fin_prevue' =>
                    $data['date_fin_prevue'],
                ':statut' => $data['statut'],
                ':temps_reponse' =>
                    $data['temps_reponse'],
                ':temps_resolution' =>
                    $data['temps_resolution']
            ]);

            $idIntervention =
                (int)$this->db->lastInsertId();

            $sqlAffectation = "INSERT INTO affectation_intervention
                               (
                                   ID_INTERVENTION,
                                   ID_TECHNICIEN,
                                   DATE_AFFECTATION,
                                   STATUT_AFFECTATION
                               )
                               VALUES
                               (
                                   :id_intervention,
                                   :id_technicien,
                                   NOW(),
                                   'Active'
                               )";

            $stmtAffectation =
                $this->db->prepare($sqlAffectation);

            $stmtAffectation->execute([
                ':id_intervention' => $idIntervention,
                ':id_technicien' =>
                    $data['id_technicien']
            ]);

            $statutTicket = $this->determinerStatutTicket(
                $data['statut']
            );

            $sqlTicket = "UPDATE ticket
                          SET STATUT = :statut
                          WHERE ID_TICKET = :id_ticket";

            $stmtTicket =
                $this->db->prepare($sqlTicket);

            $stmtTicket->execute([
                ':statut' => $statutTicket,
                ':id_ticket' => $data['id_ticket']
            ]);

            $this->db->commit();

            return $idIntervention;

        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function getById($id)
    {
        $sql = "SELECT
                    i.ID_INTERVENTION AS id_intervention,
                    i.ID_TICKET AS id_ticket,
                    i.RAPPORT AS rapport,
                    i.DUREE AS duree,
                    i.DATE_INTERVENTION AS date_intervention,
                    i.DATE_FIN_PREVUE AS date_fin_prevue,
                    i.STATUT AS statut,
                    i.temps_reponse,
                    i.temps_resolution,

                    ai.ID_TECHNICIEN AS id_technicien

                FROM intervention i

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION = ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                WHERE i.ID_INTERVENTION = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE intervention SET
                        ID_TICKET = :id_ticket,
                        RAPPORT = :rapport,
                        DUREE = :duree,
                        DATE_INTERVENTION =
                            :date_intervention,
                        DATE_FIN_PREVUE =
                            :date_fin_prevue,
                        STATUT = :statut,
                        temps_reponse =
                            :temps_reponse,
                        temps_resolution =
                            :temps_resolution
                    WHERE ID_INTERVENTION =
                        :id_intervention";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':id_ticket' => $data['id_ticket'],
                ':rapport' => $data['rapport'],
                ':duree' => $data['duree'],
                ':date_intervention' =>
                    $data['date_intervention'],
                ':date_fin_prevue' =>
                    $data['date_fin_prevue'],
                ':statut' => $data['statut'],
                ':temps_reponse' =>
                    $data['temps_reponse'],
                ':temps_resolution' =>
                    $data['temps_resolution'],
                ':id_intervention' =>
                    $data['id_intervention']
            ]);

            $sqlOld = "UPDATE affectation_intervention
                       SET STATUT_AFFECTATION = 'Inactive'
                       WHERE ID_INTERVENTION =
                           :id_intervention
                       AND STATUT_AFFECTATION = 'Active'";

            $stmtOld = $this->db->prepare($sqlOld);

            $stmtOld->execute([
                ':id_intervention' =>
                    $data['id_intervention']
            ]);

            $sqlNew = "INSERT INTO affectation_intervention
                       (
                           ID_INTERVENTION,
                           ID_TECHNICIEN,
                           DATE_AFFECTATION,
                           STATUT_AFFECTATION
                       )
                       VALUES
                       (
                           :id_intervention,
                           :id_technicien,
                           NOW(),
                           'Active'
                       )";

            $stmtNew = $this->db->prepare($sqlNew);

            $stmtNew->execute([
                ':id_intervention' =>
                    $data['id_intervention'],
                ':id_technicien' =>
                    $data['id_technicien']
            ]);

            $statutTicket = $this->determinerStatutTicket(
                $data['statut']
            );

            $sqlTicket = "UPDATE ticket
                          SET STATUT = :statut
                          WHERE ID_TICKET = :id_ticket";

            $stmtTicket =
                $this->db->prepare($sqlTicket);

            $stmtTicket->execute([
                ':statut' => $statutTicket,
                ':id_ticket' => $data['id_ticket']
            ]);

            $this->db->commit();

            return true;

        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            $sqlAffectation =
                "DELETE FROM affectation_intervention
                 WHERE ID_INTERVENTION = :id";

            $stmtAffectation =
                $this->db->prepare($sqlAffectation);

            $stmtAffectation->execute([
                ':id' => $id
            ]);

            $sql = "DELETE FROM intervention
                    WHERE ID_INTERVENTION = :id";

            $stmt = $this->db->prepare($sql);

            $resultat = $stmt->execute([
                ':id' => $id
            ]);

            $this->db->commit();

            return $resultat;

        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM intervention";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($resultat['total'] ?? 0);
    }

    public function search($search)
    {
        $sql = "SELECT
                    i.ID_INTERVENTION AS id_intervention,
                    i.ID_TICKET AS id_ticket,
                    i.RAPPORT AS rapport,
                    i.DUREE AS duree,
                    i.DATE_INTERVENTION AS date_intervention,
                    i.DATE_FIN_PREVUE AS date_fin_prevue,
                    i.STATUT AS statut,
                    i.temps_reponse,
                    i.temps_resolution,

                    t.TITRE AS titre_ticket,

                    ai.ID_TECHNICIEN AS id_technicien,

                    u.NOM AS nom_technicien,
                    u.PRENOM AS prenom_technicien,

                    l.NOM_LOCAL AS nom_local

                FROM intervention i

                LEFT JOIN ticket t
                    ON i.ID_TICKET = t.ID_TICKET

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION =
                       ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                LEFT JOIN utilisateur u
                    ON ai.ID_TECHNICIEN =
                       u.ID_UTILISATEUR

                LEFT JOIN equipement e
                    ON t.ID_EQUIPEMENT =
                       e.ID_EQUIPEMENT_

                LEFT JOIN `local` l
                    ON e.ID_LOCAL = l.ID_LOCAL

                WHERE i.RAPPORT LIKE :search1
                   OR i.STATUT LIKE :search2
                   OR t.TITRE LIKE :search3
                   OR u.NOM LIKE :search4
                   OR u.PRENOM LIKE :search5
                   OR l.NOM_LOCAL LIKE :search6

                ORDER BY i.DATE_INTERVENTION DESC";

        $stmt = $this->db->prepare($sql);

        $valeur = '%' . $search . '%';

        $stmt->execute([
            ':search1' => $valeur,
            ':search2' => $valeur,
            ':search3' => $valeur,
            ':search4' => $valeur,
            ':search5' => $valeur,
            ':search6' => $valeur
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function determinerStatutTicket($statutIntervention)
    {
        if ($statutIntervention === 'Terminée') {
            return 'Résolu';
        }

        if ($statutIntervention === 'En attente') {
            return 'En attente';
        }

        return 'En cours';
    }
}