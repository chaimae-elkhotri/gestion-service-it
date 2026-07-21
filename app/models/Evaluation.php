<?php

class Evaluation
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
                    ev.ID_EVALUATION AS id_evaluation,
                    ev.ID_UTILISATEUR AS id_utilisateur,
                    ev.ID_INTERVENTION AS id_intervention,
                    ev.note AS note,
                    ev.COMMENTAIRE AS commentaire,
                    ev.DATE_EVALUATION AS date_evaluation,

                    u.NOM AS nom_utilisateur,
                    u.PRENOM AS prenom_utilisateur,

                    i.RAPPORT AS rapport,
                    i.STATUT AS statut_intervention,

                    t.TITRE AS titre_ticket,

                    tech.NOM AS nom_technicien,
                    tech.PRENOM AS prenom_technicien

                FROM evaluation ev

                INNER JOIN utilisateur u
                    ON ev.ID_UTILISATEUR = u.ID_UTILISATEUR

                INNER JOIN intervention i
                    ON ev.ID_INTERVENTION = i.ID_INTERVENTION

                LEFT JOIN ticket t
                    ON i.ID_TICKET = t.ID_TICKET

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION = ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                LEFT JOIN utilisateur tech
                    ON ai.ID_TECHNICIEN = tech.ID_UTILISATEUR

                ORDER BY ev.ID_EVALUATION DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInterventions()
    {
        $sql = "SELECT
                    i.ID_INTERVENTION AS id_intervention,
                    i.RAPPORT AS rapport,
                    i.STATUT AS statut,
                    t.TITRE AS titre_ticket,
                    tech.NOM AS nom_technicien,
                    tech.PRENOM AS prenom_technicien
                FROM intervention i

                LEFT JOIN ticket t
                    ON i.ID_TICKET = t.ID_TICKET

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION = ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                LEFT JOIN utilisateur tech
                    ON ai.ID_TECHNICIEN = tech.ID_UTILISATEUR

                ORDER BY i.ID_INTERVENTION DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO evaluation
                (ID_UTILISATEUR, ID_INTERVENTION, note, COMMENTAIRE, DATE_EVALUATION)
                VALUES
                (:id_utilisateur, :id_intervention, :note, :commentaire, :date_evaluation)";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':id_utilisateur' => $data['id_utilisateur'],
            ':id_intervention' => $data['id_intervention'],
            ':note' => $data['note'],
            ':commentaire' => $data['commentaire'],
            ':date_evaluation' => $data['date_evaluation']
        ]);

        if ($result) {
            $idEvaluation = $this->db->lastInsertId();
            $this->lierEvaluationIntervention($idEvaluation, $data['id_intervention']);
        }

        return $result;
    }

    public function getById($id)
    {
        $sql = "SELECT
                    ID_EVALUATION AS id_evaluation,
                    ID_UTILISATEUR AS id_utilisateur,
                    ID_INTERVENTION AS id_intervention,
                    note AS note,
                    COMMENTAIRE AS commentaire,
                    DATE_EVALUATION AS date_evaluation
                FROM evaluation
                WHERE ID_EVALUATION = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $sql = "UPDATE evaluation SET
                    ID_UTILISATEUR = :id_utilisateur,
                    ID_INTERVENTION = :id_intervention,
                    note = :note,
                    COMMENTAIRE = :commentaire,
                    DATE_EVALUATION = :date_evaluation
                WHERE ID_EVALUATION = :id_evaluation";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':id_utilisateur' => $data['id_utilisateur'],
            ':id_intervention' => $data['id_intervention'],
            ':note' => $data['note'],
            ':commentaire' => $data['commentaire'],
            ':date_evaluation' => $data['date_evaluation'],
            ':id_evaluation' => $data['id_evaluation']
        ]);

        if ($result) {
            $this->lierEvaluationIntervention($data['id_evaluation'], $data['id_intervention']);
        }

        return $result;
    }

    public function delete($id)
    {
        $sql0 = "UPDATE intervention
                 SET ID_EVALUATION = NULL
                 WHERE ID_EVALUATION = :id";

        $stmt0 = $this->db->prepare($sql0);
        $stmt0->execute([
            ':id' => $id
        ]);

        $sql = "DELETE FROM evaluation
                WHERE ID_EVALUATION = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM evaluation";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function search($search)
    {
        $sql = "SELECT
                    ev.ID_EVALUATION AS id_evaluation,
                    ev.ID_UTILISATEUR AS id_utilisateur,
                    ev.ID_INTERVENTION AS id_intervention,
                    ev.note AS note,
                    ev.COMMENTAIRE AS commentaire,
                    ev.DATE_EVALUATION AS date_evaluation,

                    u.NOM AS nom_utilisateur,
                    u.PRENOM AS prenom_utilisateur,

                    i.RAPPORT AS rapport,
                    i.STATUT AS statut_intervention,

                    t.TITRE AS titre_ticket,

                    tech.NOM AS nom_technicien,
                    tech.PRENOM AS prenom_technicien

                FROM evaluation ev

                INNER JOIN utilisateur u
                    ON ev.ID_UTILISATEUR = u.ID_UTILISATEUR

                INNER JOIN intervention i
                    ON ev.ID_INTERVENTION = i.ID_INTERVENTION

                LEFT JOIN ticket t
                    ON i.ID_TICKET = t.ID_TICKET

                LEFT JOIN affectation_intervention ai
                    ON i.ID_INTERVENTION = ai.ID_INTERVENTION
                    AND ai.STATUT_AFFECTATION = 'Active'

                LEFT JOIN utilisateur tech
                    ON ai.ID_TECHNICIEN = tech.ID_UTILISATEUR

                WHERE u.NOM LIKE :search
                   OR u.PRENOM LIKE :search
                   OR tech.NOM LIKE :search
                   OR tech.PRENOM LIKE :search
                   OR t.TITRE LIKE :search
                   OR ev.COMMENTAIRE LIKE :search
                   OR ev.note LIKE :search

                ORDER BY ev.ID_EVALUATION DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function lierEvaluationIntervention($idEvaluation, $idIntervention)
    {
        $sql = "UPDATE intervention
                SET ID_EVALUATION = :id_evaluation
                WHERE ID_INTERVENTION = :id_intervention";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_evaluation' => $idEvaluation,
            ':id_intervention' => $idIntervention
        ]);
    }
}