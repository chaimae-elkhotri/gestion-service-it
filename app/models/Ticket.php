<?php

class Ticket
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
                    t.ID_TICKET AS id_ticket,
                    t.TITRE AS titre,
                    t.DESCRIPTION AS description,
                    t.PRIORITE AS priorite,
                    t.STATUT AS statut,
                    t.ID_UTILISATEUR AS id_utilisateur,
                    t.ID_MOYEN AS id_moyen,
                    t.ID_EQUIPEMENT AS id_equipement,
                    t.DATE_CREATION AS date_creation,

                    u.NOM AS nom,
                    u.PRENOM AS prenom,

                    m.LIBELLE AS moyen,

                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele,

                    l.NOM_LOCAL AS nom_local

                FROM ticket t

                LEFT JOIN utilisateur u
                    ON t.ID_UTILISATEUR = u.ID_UTILISATEUR

                LEFT JOIN moyen_communication m
                    ON t.ID_MOYEN = m.ID_MOYEN

                LEFT JOIN equipement e
                    ON t.ID_EQUIPEMENT = e.ID_EQUIPEMENT_

                LEFT JOIN `local` l
                    ON e.ID_LOCAL = l.ID_LOCAL

                ORDER BY t.DATE_CREATION DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO ticket
                (
                    TITRE,
                    DESCRIPTION,
                    PRIORITE,
                    STATUT,
                    ID_UTILISATEUR,
                    ID_MOYEN,
                    ID_EQUIPEMENT,
                    DATE_CREATION
                )
                VALUES
                (
                    :titre,
                    :description,
                    :priorite,
                    :statut,
                    :id_utilisateur,
                    :id_moyen,
                    :id_equipement,
                    NOW()
                )";

        $stmt = $this->db->prepare($sql);

        $result = $stmt->execute([
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':priorite' => $data['priorite'],
            ':statut' => $data['statut'],
            ':id_utilisateur' => $data['id_utilisateur'],
            ':id_moyen' => $data['id_moyen'],
            ':id_equipement' => !empty($data['id_equipement'])
                ? $data['id_equipement']
                : null
        ]);

        if ($result) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function getById($id)
    {
        $sql = "SELECT
                    t.ID_TICKET AS id_ticket,
                    t.TITRE AS titre,
                    t.DESCRIPTION AS description,
                    t.PRIORITE AS priorite,
                    t.STATUT AS statut,
                    t.ID_UTILISATEUR AS id_utilisateur,
                    t.ID_MOYEN AS id_moyen,
                    t.ID_EQUIPEMENT AS id_equipement,
                    t.DATE_CREATION AS date_creation,

                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele,

                    l.NOM_LOCAL AS nom_local

                FROM ticket t

                LEFT JOIN equipement e
                    ON t.ID_EQUIPEMENT = e.ID_EQUIPEMENT_

                LEFT JOIN `local` l
                    ON e.ID_LOCAL = l.ID_LOCAL

                WHERE t.ID_TICKET = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $sql = "UPDATE ticket SET
                    TITRE = :titre,
                    DESCRIPTION = :description,
                    PRIORITE = :priorite,
                    STATUT = :statut,
                    ID_UTILISATEUR = :id_utilisateur,
                    ID_MOYEN = :id_moyen,
                    ID_EQUIPEMENT = :id_equipement
                WHERE ID_TICKET = :id_ticket";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':priorite' => $data['priorite'],
            ':statut' => $data['statut'],
            ':id_utilisateur' => $data['id_utilisateur'],
            ':id_moyen' => $data['id_moyen'],
            ':id_equipement' => !empty($data['id_equipement'])
                ? $data['id_equipement']
                : null,
            ':id_ticket' => $data['id_ticket']
        ]);
    }

    public function cancel($id)
    {
        $sql = "UPDATE ticket
                SET STATUT = 'Annulé'
                WHERE ID_TICKET = :id
                AND STATUT NOT IN ('Annulé', 'Annule')";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM ticket";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function search($search)
    {
        $sql = "SELECT
                    t.ID_TICKET AS id_ticket,
                    t.TITRE AS titre,
                    t.DESCRIPTION AS description,
                    t.PRIORITE AS priorite,
                    t.STATUT AS statut,
                    t.ID_UTILISATEUR AS id_utilisateur,
                    t.ID_MOYEN AS id_moyen,
                    t.ID_EQUIPEMENT AS id_equipement,
                    t.DATE_CREATION AS date_creation,

                    u.NOM AS nom,
                    u.PRENOM AS prenom,

                    m.LIBELLE AS moyen,

                    e.NUMERO_SERIE AS numero_serie,
                    e.MARQUE AS marque,
                    e.MODELE AS modele,

                    l.NOM_LOCAL AS nom_local

                FROM ticket t

                LEFT JOIN utilisateur u
                    ON t.ID_UTILISATEUR = u.ID_UTILISATEUR

                LEFT JOIN moyen_communication m
                    ON t.ID_MOYEN = m.ID_MOYEN

                LEFT JOIN equipement e
                    ON t.ID_EQUIPEMENT = e.ID_EQUIPEMENT_

                LEFT JOIN `local` l
                    ON e.ID_LOCAL = l.ID_LOCAL

                WHERE t.TITRE LIKE :search
                   OR t.DESCRIPTION LIKE :search
                   OR t.PRIORITE LIKE :search
                   OR t.STATUT LIKE :search
                   OR u.NOM LIKE :search
                   OR u.PRENOM LIKE :search
                   OR m.LIBELLE LIKE :search
                   OR e.NUMERO_SERIE LIKE :search
                   OR e.MARQUE LIKE :search
                   OR e.MODELE LIKE :search
                   OR l.NOM_LOCAL LIKE :search

                ORDER BY t.DATE_CREATION DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}