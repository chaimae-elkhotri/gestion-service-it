<?php

class Logiciel
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
                    ID_LOGICIEL AS id_logiciel,
                    NOM_LOGICIEL AS nom_logiciel,
                    VERSION AS version,
                    EDITEUR AS editeur,
                    DATE_INSTALLATION AS date_installation
                FROM logiciel
                ORDER BY ID_LOGICIEL DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO logiciel
                (
                    NOM_LOGICIEL,
                    VERSION,
                    EDITEUR,
                    DATE_INSTALLATION
                )
                VALUES
                (
                    :nom_logiciel,
                    :version,
                    :editeur,
                    :date_installation
                )";

        $stmt = $this->db->prepare($sql);

        $resultat = $stmt->execute([
            ':nom_logiciel' => $data['nom_logiciel'],
            ':version' => $data['version'],
            ':editeur' => $data['editeur'],
            ':date_installation' => $data['date_installation']
        ]);

        if ($resultat) {
            return (int)$this->db->lastInsertId();
        }

        return false;
    }

    public function getById($id)
    {
        $sql = "SELECT
                    ID_LOGICIEL AS id_logiciel,
                    NOM_LOGICIEL AS nom_logiciel,
                    VERSION AS version,
                    EDITEUR AS editeur,
                    DATE_INSTALLATION AS date_installation
                FROM logiciel
                WHERE ID_LOGICIEL = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function trouverDoublon(
        $nomLogiciel,
        $version,
        $editeur,
        $idLogicielExclu = null
    ) {
        $sql = "SELECT
                    ID_LOGICIEL AS id_logiciel,
                    NOM_LOGICIEL AS nom_logiciel,
                    VERSION AS version,
                    EDITEUR AS editeur
                FROM logiciel
                WHERE LOWER(NOM_LOGICIEL) = LOWER(:nom_logiciel)
                  AND LOWER(VERSION) = LOWER(:version)
                  AND LOWER(EDITEUR) = LOWER(:editeur)";

        $parametres = [
            ':nom_logiciel' => $nomLogiciel,
            ':version' => $version,
            ':editeur' => $editeur
        ];

        if ($idLogicielExclu !== null) {
            $sql .= " AND ID_LOGICIEL <> :id_exclu";

            $parametres[':id_exclu'] = $idLogicielExclu;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametres);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $sql = "UPDATE logiciel
                SET NOM_LOGICIEL = :nom_logiciel,
                    VERSION = :version,
                    EDITEUR = :editeur,
                    DATE_INSTALLATION = :date_installation
                WHERE ID_LOGICIEL = :id_logiciel";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nom_logiciel' => $data['nom_logiciel'],
            ':version' => $data['version'],
            ':editeur' => $data['editeur'],
            ':date_installation' => $data['date_installation'],
            ':id_logiciel' => $data['id_logiciel']
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM logiciel
                WHERE ID_LOGICIEL = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM logiciel";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($resultat['total'] ?? 0);
    }

    public function search($search)
    {
        $sql = "SELECT
                    ID_LOGICIEL AS id_logiciel,
                    NOM_LOGICIEL AS nom_logiciel,
                    VERSION AS version,
                    EDITEUR AS editeur,
                    DATE_INSTALLATION AS date_installation
                FROM logiciel
                WHERE NOM_LOGICIEL LIKE :search1
                   OR VERSION LIKE :search2
                   OR EDITEUR LIKE :search3
                   OR DATE_INSTALLATION LIKE :search4
                ORDER BY ID_LOGICIEL DESC";

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