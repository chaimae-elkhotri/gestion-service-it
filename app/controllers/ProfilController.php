<?php

class ProfilController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once '../app/views/profil/index.php';
    }

    public function parametres()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once '../app/views/profil/parametres.php';
    }
}