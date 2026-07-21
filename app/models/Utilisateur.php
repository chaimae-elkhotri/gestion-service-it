<?php

class Utilisateur
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function trouverParEmail($email)
    {
        $sql = "SELECT
                    u.*,
                    r.NOM_ROLE
                FROM utilisateur u
                INNER JOIN role r
                    ON u.ID_ROLE = r.ID_ROLE
                WHERE u.EMAIL = :email
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $sql = "SELECT
                    u.*,
                    r.NOM_ROLE
                FROM utilisateur u
                INNER JOIN role r
                    ON u.ID_ROLE = r.ID_ROLE
                ORDER BY u.ID_UTILISATEUR DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        $sql = "INSERT INTO utilisateur
                (
                    NOM,
                    PRENOM,
                    EMAIL,
                    MOT_DE_PASSE,
                    TELEPHONE,
                    STATUT,
                    ID_ROLE
                )
                VALUES
                (
                    :nom,
                    :prenom,
                    :email,
                    :mot_de_passe,
                    :tel,
                    :statut,
                    :id_role
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':mot_de_passe' => $data['mot_de_passe'],
            ':tel' => $data['tel'],
            ':statut' => $data['statut'],
            ':id_role' => $data['id_role']
        ]);
    }

    public function getById($id)
    {
        $sql = "SELECT
                    u.*,
                    r.NOM_ROLE
                FROM utilisateur u
                INNER JOIN role r
                    ON u.ID_ROLE = r.ID_ROLE
                WHERE u.ID_UTILISATEUR = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data)
    {
        if (!empty($data['mot_de_passe'])) {

            $sql = "UPDATE utilisateur SET
                        NOM = :nom,
                        PRENOM = :prenom,
                        EMAIL = :email,
                        MOT_DE_PASSE = :mot_de_passe,
                        TELEPHONE = :tel,
                        STATUT = :statut,
                        ID_ROLE = :id_role
                    WHERE ID_UTILISATEUR = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':email' => $data['email'],
                ':mot_de_passe' => $data['mot_de_passe'],
                ':tel' => $data['tel'],
                ':statut' => $data['statut'],
                ':id_role' => $data['id_role'],
                ':id' => $id
            ]);

        } else {

            $sql = "UPDATE utilisateur SET
                        NOM = :nom,
                        PRENOM = :prenom,
                        EMAIL = :email,
                        TELEPHONE = :tel,
                        STATUT = :statut,
                        ID_ROLE = :id_role
                    WHERE ID_UTILISATEUR = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':nom' => $data['nom'],
                ':prenom' => $data['prenom'],
                ':email' => $data['email'],
                ':tel' => $data['tel'],
                ':statut' => $data['statut'],
                ':id_role' => $data['id_role'],
                ':id' => $id
            ]);
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM utilisateur
                WHERE ID_UTILISATEUR = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) AS total
                FROM utilisateur";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'] ?? 0;
    }

    public function search($search)
    {
        $sql = "SELECT
                    u.*,
                    r.NOM_ROLE
                FROM utilisateur u
                INNER JOIN role r
                    ON u.ID_ROLE = r.ID_ROLE
                WHERE u.NOM LIKE :search
                   OR u.PRENOM LIKE :search
                   OR u.EMAIL LIKE :search
                   OR u.TELEPHONE LIKE :search
                   OR r.NOM_ROLE LIKE :search
                ORDER BY u.ID_UTILISATEUR DESC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . $search . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}