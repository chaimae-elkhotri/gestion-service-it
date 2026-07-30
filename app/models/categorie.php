<?php

class Categorie
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Récupérer toutes les catégories.
     */
    public function getAll(string $search = ''): array
    {
        if ($search !== '') {
            return $this->search($search);
        }

        $sql = "
            SELECT
                ID_CATEGORIE AS id_categorie,
                NOM_CATEGORIE AS nom_categorie
            FROM categorie
            ORDER BY NOM_CATEGORIE ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Rechercher une catégorie.
     */
    public function search(string $search): array
    {
        $sql = "
            SELECT
                ID_CATEGORIE AS id_categorie,
                NOM_CATEGORIE AS nom_categorie
            FROM categorie
            WHERE NOM_CATEGORIE LIKE :search
            ORDER BY NOM_CATEGORIE ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':search' => '%' . trim($search) . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajouter une catégorie.
     */
    public function insert(string $nomCategorie): bool
    {
        $nomCategorie = trim($nomCategorie);

        if ($nomCategorie === '') {
            return false;
        }

        $sql = "
            INSERT INTO categorie (
                NOM_CATEGORIE
            )
            VALUES (
                :nom_categorie
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nom_categorie' => $nomCategorie
        ]);
    }

    /**
     * Récupérer une catégorie par son identifiant.
     */
    public function getById(int $id): array|false
    {
        $sql = "
            SELECT
                ID_CATEGORIE AS id_categorie,
                NOM_CATEGORIE AS nom_categorie
            FROM categorie
            WHERE ID_CATEGORIE = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Modifier une catégorie.
     */
    public function update(
        int $id,
        string $nomCategorie
    ): bool {
        $nomCategorie = trim($nomCategorie);

        if ($id <= 0 || $nomCategorie === '') {
            return false;
        }

        $sql = "
            UPDATE categorie
            SET NOM_CATEGORIE = :nom_categorie
            WHERE ID_CATEGORIE = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nom_categorie' => $nomCategorie,
            ':id' => $id
        ]);
    }

    /**
     * Supprimer une catégorie.
     */
    public function delete(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $sql = "
            DELETE FROM categorie
            WHERE ID_CATEGORIE = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    /**
     * Compter les catégories.
     */
    public function count(): int
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM categorie
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }
}