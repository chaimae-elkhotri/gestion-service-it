<?php

class OccupationLocal
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAll($idLocal = null)
    {
        $sql = "SELECT
                    o.ID_OCCUPATION AS id_occupation,
                    o.ID_LOCAL AS id_local,
                    o.TYPE_OCCUPATION AS type_occupation,
                    o.MOTIF AS motif,
                    o.DATE_DEBUT AS date_debut,
                    o.DATE_FIN AS date_fin,
                    o.STATUT AS statut,
                    o.ID_UTILISATEUR AS id_utilisateur,
                    o.DATE_CREATION AS date_creation,

                    l.NOM_LOCAL AS nom_local,
                    l.TYPE_LOCAL AS type_local

                FROM occupation_local o

                INNER JOIN `local` l
                    ON o.ID_LOCAL = l.ID_LOCAL";

        $params = [];

        if (!empty($idLocal)) {
            $sql .= " WHERE o.ID_LOCAL = :id_local";

            $params[':id_local'] = $idLocal;
        }

        $sql .= " ORDER BY o.DATE_DEBUT DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT
                    o.ID_OCCUPATION AS id_occupation,
                    o.ID_LOCAL AS id_local,
                    o.TYPE_OCCUPATION AS type_occupation,
                    o.MOTIF AS motif,
                    o.DATE_DEBUT AS date_debut,
                    o.DATE_FIN AS date_fin,
                    o.STATUT AS statut,

                    l.NOM_LOCAL AS nom_local

                FROM occupation_local o

                INNER JOIN `local` l
                    ON o.ID_LOCAL = l.ID_LOCAL

                WHERE o.ID_OCCUPATION = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasConflict(
        $idLocal,
        $dateDebut,
        $dateFin,
        $idOccupationExclue = null
    ) {
        $sql = "SELECT COUNT(*) AS total
                FROM occupation_local
                WHERE ID_LOCAL = :id_local
                  AND STATUT = 'Active'
                  AND DATE_DEBUT < :date_fin
                  AND DATE_FIN > :date_debut";

        $params = [
            ':id_local' => $idLocal,
            ':date_debut' => $dateDebut,
            ':date_fin' => $dateFin
        ];

        if (!empty($idOccupationExclue)) {
            $sql .= " AND ID_OCCUPATION <> :id_exclu";

            $params[':id_exclu'] = $idOccupationExclue;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($resultat['total'] ?? 0) > 0;
    }

    public function insert($data)
    {
        $sql = "INSERT INTO occupation_local
                (
                    ID_LOCAL,
                    TYPE_OCCUPATION,
                    MOTIF,
                    DATE_DEBUT,
                    DATE_FIN,
                    STATUT,
                    ID_UTILISATEUR
                )
                VALUES
                (
                    :id_local,
                    :type_occupation,
                    :motif,
                    :date_debut,
                    :date_fin,
                    'Active',
                    :id_utilisateur
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_local' => $data['id_local'],
            ':type_occupation' =>
                $data['type_occupation'],
            ':motif' => $data['motif'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':id_utilisateur' =>
                $data['id_utilisateur']
        ]);
    }

    public function update($data)
    {
        $sql = "UPDATE occupation_local
                SET ID_LOCAL = :id_local,
                    TYPE_OCCUPATION = :type_occupation,
                    MOTIF = :motif,
                    DATE_DEBUT = :date_debut,
                    DATE_FIN = :date_fin
                WHERE ID_OCCUPATION = :id_occupation
                  AND STATUT = 'Active'";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id_local' => $data['id_local'],
            ':type_occupation' =>
                $data['type_occupation'],
            ':motif' => $data['motif'],
            ':date_debut' => $data['date_debut'],
            ':date_fin' => $data['date_fin'],
            ':id_occupation' =>
                $data['id_occupation']
        ]);
    }

    public function cancel($id)
    {
        $sql = "UPDATE occupation_local
                SET STATUT = 'Annulée'
                WHERE ID_OCCUPATION = :id
                  AND STATUT = 'Active'";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}