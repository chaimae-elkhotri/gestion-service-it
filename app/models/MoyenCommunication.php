<?php

class MoyenCommunication
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
                    ID_MOYEN AS id_moyen,
                    LIBELLE AS libelle
                FROM moyen_communication
                ORDER BY LIBELLE ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}