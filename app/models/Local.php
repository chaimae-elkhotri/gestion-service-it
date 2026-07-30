<?php

class Local
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
                    l.ID_LOCAL AS id_local,
                    l.NOM_LOCAL AS nom_local,
                    l.TYPE_LOCAL AS type_local,
                    l.STATUT_GENERAL AS statut_general,

                    CASE
                        WHEN l.STATUT_GENERAL = 'Maintenance'
                            THEN 'En maintenance'

                        WHEN l.STATUT_GENERAL = 'Indisponible'
                            THEN 'Indisponible'

                        WHEN EXISTS (
                            SELECT 1
                            FROM occupation_local o
                            WHERE o.ID_LOCAL = l.ID_LOCAL
                              AND o.STATUT = 'Active'
                              AND NOW() >= o.DATE_DEBUT
                              AND NOW() < o.DATE_FIN
                        )
                            THEN 'Occupé'

                        ELSE 'Disponible'
                    END AS disponibilite,

                    (
                        SELECT o.MOTIF
                        FROM occupation_local o
                        WHERE o.ID_LOCAL = l.ID_LOCAL
                          AND o.STATUT = 'Active'
                          AND NOW() >= o.DATE_DEBUT
                          AND NOW() < o.DATE_FIN
                        ORDER BY o.DATE_DEBUT DESC
                        LIMIT 1
                    ) AS occupation_actuelle,

                    (
                        SELECT o.DATE_FIN
                        FROM occupation_local o
                        WHERE o.ID_LOCAL = l.ID_LOCAL
                          AND o.STATUT = 'Active'
                          AND NOW() >= o.DATE_DEBUT
                          AND NOW() < o.DATE_FIN
                        ORDER BY o.DATE_DEBUT DESC
                        LIMIT 1
                    ) AS date_fin_occupation

                FROM `local` l
                ORDER BY l.ID_LOCAL DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO `local`
                (
                    NOM_LOCAL,
                    TYPE_LOCAL,
                    STATUT_GENERAL
                )
                VALUES
                (
                    :nom_local,
                    :type_local,
                    :statut_general
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nom_local' => $data['nom_local'],
            ':type_local' => $data['type_local'],
            ':statut_general' => $data['statut_general']
        ]);
    }

    public function getById($id)
    {
        $sql = "SELECT
                    ID_LOCAL AS id_local,
                    NOM_LOCAL AS nom_local,
                    TYPE_LOCAL AS type_local,
                    STATUT_GENERAL AS statut_general
                FROM `local`
                WHERE ID_LOCAL = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $sql = "UPDATE `local`
                SET NOM_LOCAL = :nom_local,
                    TYPE_LOCAL = :type_local,
                    STATUT_GENERAL = :statut_general
                WHERE ID_LOCAL = :id_local";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nom_local' => $data['nom_local'],
            ':type_local' => $data['type_local'],
            ':statut_general' => $data['statut_general'],
            ':id_local' => $data['id_local']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM `local`
                WHERE ID_LOCAL = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM `local`";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    public function search($search)
    {
        $sql = "SELECT *
                FROM (
                    SELECT
                        l.ID_LOCAL AS id_local,
                        l.NOM_LOCAL AS nom_local,
                        l.TYPE_LOCAL AS type_local,
                        l.STATUT_GENERAL AS statut_general,

                        CASE
                            WHEN l.STATUT_GENERAL = 'Maintenance'
                                THEN 'En maintenance'

                            WHEN l.STATUT_GENERAL = 'Indisponible'
                                THEN 'Indisponible'

                            WHEN EXISTS (
                                SELECT 1
                                FROM occupation_local o
                                WHERE o.ID_LOCAL = l.ID_LOCAL
                                  AND o.STATUT = 'Active'
                                  AND NOW() >= o.DATE_DEBUT
                                  AND NOW() < o.DATE_FIN
                            )
                                THEN 'Occupé'

                            ELSE 'Disponible'
                        END AS disponibilite,

                        (
                            SELECT o.MOTIF
                            FROM occupation_local o
                            WHERE o.ID_LOCAL = l.ID_LOCAL
                              AND o.STATUT = 'Active'
                              AND NOW() >= o.DATE_DEBUT
                              AND NOW() < o.DATE_FIN
                            ORDER BY o.DATE_DEBUT DESC
                            LIMIT 1
                        ) AS occupation_actuelle,

                        (
                            SELECT o.DATE_FIN
                            FROM occupation_local o
                            WHERE o.ID_LOCAL = l.ID_LOCAL
                              AND o.STATUT = 'Active'
                              AND NOW() >= o.DATE_DEBUT
                              AND NOW() < o.DATE_FIN
                            ORDER BY o.DATE_DEBUT DESC
                            LIMIT 1
                        ) AS date_fin_occupation

                    FROM `local` l
                ) AS resultats

                WHERE nom_local LIKE :search1
                   OR type_local LIKE :search2
                   OR statut_general LIKE :search3
                   OR disponibilite LIKE :search4

                ORDER BY id_local DESC";

        $stmt = $this->db->prepare($sql);

        $valeur = '%' . $search . '%';

        $stmt->execute([
            ':search1' => $valeur,
            ':search2' => $valeur,
            ':search3' => $valeur,
            ':search4' => $valeur
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}