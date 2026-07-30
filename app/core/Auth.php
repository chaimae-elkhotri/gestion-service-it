<?php

class Auth
{
    public static function verifierConnexion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['id_utilisateur'])) {
            $_SESSION['error'] = 'Veuillez vous connecter.';

            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        /*
         * Tant que le mot de passe temporaire n’est pas changé,
         * l’utilisateur ne peut accéder à aucune autre page.
         */
        $pageActuelle =
            $_GET['page']
            ?? 'dashboard';

        $pagesAutorisees = [
            'changer-mot-de-passe-obligatoire',
            'logout'
        ];

        if (
            (int)($_SESSION['doit_changer_mdp'] ?? 0) === 1 &&
            !in_array($pageActuelle, $pagesAutorisees, true)
        ) {
            header(
                'Location: ' .
                BASE_URL .
                '?page=changer-mot-de-passe-obligatoire'
            );
            exit;
        }
    }

    public static function autoriser(array $rolesAutorises)
    {
        self::verifierConnexion();

        $roleSession = $_SESSION['nom_role'] ?? '';
        $idRoleSession = (int)($_SESSION['id_role'] ?? 0);

        $roleNormalise = self::normaliserRole($roleSession);

        $rolesNormalises = array_map(
            [self::class, 'normaliserRole'],
            $rolesAutorises
        );

        /*
         * 1 = Administrateur
         * 2 = Technicien
         * 3 = Employé
         */
        $roleParId = '';

        if ($idRoleSession === 1) {
            $roleParId = 'administrateur';
        } elseif ($idRoleSession === 2) {
            $roleParId = 'technicien';
        } elseif ($idRoleSession === 3) {
            $roleParId = 'employe';
        }

        $autoriseParNom = in_array(
            $roleNormalise,
            $rolesNormalises,
            true
        );

        $autoriseParId = in_array(
            $roleParId,
            $rolesNormalises,
            true
        );

        if (!$autoriseParNom && !$autoriseParId) {
            $_SESSION['error'] =
                'Accès refusé : vous ne disposez pas des permissions nécessaires.';

            header(
                'Location: ' .
                BASE_URL .
                '?page=acces-refuse'
            );
            exit;
        }
    }

    public static function estAdmin()
    {
        $role = self::normaliserRole(
            $_SESSION['nom_role'] ?? ''
        );

        $idRole = (int)($_SESSION['id_role'] ?? 0);

        return $role === 'administrateur'
            || $role === 'admin'
            || $idRole === 1;
    }

    public static function estTechnicien()
    {
        $role = self::normaliserRole(
            $_SESSION['nom_role'] ?? ''
        );

        $idRole = (int)($_SESSION['id_role'] ?? 0);

        return $role === 'technicien'
            || $idRole === 2;
    }

    public static function estEmploye()
    {
        $role = self::normaliserRole(
            $_SESSION['nom_role'] ?? ''
        );

        $idRole = (int)($_SESSION['id_role'] ?? 0);

        return in_array(
            $role,
            ['employe', 'utilisateur', 'user'],
            true
        ) || $idRole === 3;
    }

    public static function idUtilisateur()
    {
        return $_SESSION['id_utilisateur'] ?? null;
    }

    public static function role()
    {
        return $_SESSION['nom_role'] ?? null;
    }

    private static function normaliserRole($role)
    {
        $role = trim((string)$role);
        $role = mb_strtolower($role, 'UTF-8');

        $role = str_replace(
            [
                'é', 'è', 'ê', 'ë',
                'à', 'â', 'ä',
                'î', 'ï',
                'ô', 'ö',
                'ù', 'û', 'ü',
                'ç'
            ],
            [
                'e', 'e', 'e', 'e',
                'a', 'a', 'a',
                'i', 'i',
                'o', 'o',
                'u', 'u', 'u',
                'c'
            ],
            $role
        );

        if ($role === 'admin') {
            return 'administrateur';
        }

        if ($role === 'utilisateur') {
            return 'employe';
        }

        return $role;
    }
}