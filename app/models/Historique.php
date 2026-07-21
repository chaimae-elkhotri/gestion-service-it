<?php

class Historique
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
                    h.id_historique,
                    h.table_concernee,
                    h.id_element,
                    h.action,
                    h.ancienne_valeur,
                    h.nouvelle_valeur,
                    h.utilisateur_action,
                    h.date_action,

                    u.NOM AS nom_utilisateur,
                    u.PRENOM AS prenom_utilisateur

                FROM historique h

                LEFT JOIN utilisateur u
                    ON h.utilisateur_action = u.ID_UTILISATEUR

                ORDER BY h.date_action DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO historique
                (table_concernee, id_element, action, ancienne_valeur, nouvelle_valeur, utilisateur_action, date_action)
                VALUES
                (:table_concernee, :id_element, :action, :ancienne_valeur, :nouvelle_valeur, :utilisateur_action, NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':table_concernee' => $data['table_concernee'],
            ':id_element' => $data['id_element'],
            ':action' => $data['action'],
            ':ancienne_valeur' => $data['ancienne_valeur'],
            ':nouvelle_valeur' => $data['nouvelle_valeur'],
            ':utilisateur_action' => $data['utilisateur_action']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM historique WHERE id_historique = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function search($search)
    {
        $sql = "SELECT 
                    h.id_historique,
                    h.table_concernee,
                    h.id_element,
                    h.action,
                    h.ancienne_valeur,
                    h.nouvelle_valeur,
                    h.utilisateur_action,
                    h.date_action,

                    u.NOM AS nom_utilisateur,
                    u.PRENOM AS prenom_utilisateur

                FROM historique h

                LEFT JOIN utilisateur u
                    ON h.utilisateur_action = u.ID_UTILISATEUR

                WHERE h.table_concernee LIKE :search
                   OR h.action LIKE :search
                   OR h.ancienne_valeur LIKE :search
                   OR h.nouvelle_valeur LIKE :search
                   OR u.NOM LIKE :search
                   OR u.PRENOM LIKE :search

                ORDER BY h.date_action DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total FROM historique";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public static function enregistrer($table, $idElement, $action, $ancienneValeur, $nouvelleValeur)
    {
        $database = new Database();
        $db = $database->connect();

        $idUtilisateur = $_SESSION['id_utilisateur'] 
            ?? $_SESSION['ID_UTILISATEUR'] 
            ?? null;

        $sql = "INSERT INTO historique
                (table_concernee, id_element, action, ancienne_valeur, nouvelle_valeur, utilisateur_action, date_action)
                VALUES
                (:table_concernee, :id_element, :action, :ancienne_valeur, :nouvelle_valeur, :utilisateur_action, NOW())";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':table_concernee' => $table,
            ':id_element' => $idElement,
            ':action' => $action,
            ':ancienne_valeur' => $ancienneValeur,
            ':nouvelle_valeur' => $nouvelleValeur,
            ':utilisateur_action' => $idUtilisateur
        ]);
    }
}