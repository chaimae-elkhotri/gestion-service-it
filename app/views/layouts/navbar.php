<?php

$page = $_GET['page'] ?? 'dashboard';

$titres = [

    'dashboard' => [
        'icon' => 'bi-house-door-fill',
        'titre' => 'Tableau de bord',
        'sousTitre' => 'Vue d’ensemble du parc informatique'
    ],

    'utilisateurs' => [
        'icon' => 'bi-people-fill',
        'titre' => 'Utilisateurs',
        'sousTitre' => 'Gestion des comptes et des accès au système'
    ],

    'ajouter-utilisateur' => [
        'icon' => 'bi-person-plus-fill',
        'titre' => 'Ajouter un utilisateur',
        'sousTitre' => 'Création d’un nouveau compte utilisateur'
    ],

    'modifier-utilisateur' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier un utilisateur',
        'sousTitre' => 'Mise à jour des informations utilisateur'
    ],

    'categories' => [
        'icon' => 'bi-grid-3x3-gap-fill',
        'titre' => 'Catégories',
        'sousTitre' => 'Gestion des catégories d’équipements et de logiciels'
    ],

    'ajouter-categorie' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter une catégorie',
        'sousTitre' => 'Création d’une nouvelle catégorie'
    ],

    'modifier-categorie' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier une catégorie',
        'sousTitre' => 'Mise à jour d’une catégorie'
    ],

    'equipements' => [
        'icon' => 'bi-pc-display',
        'titre' => 'Équipements',
        'sousTitre' => 'Gestion de l’ensemble des équipements du parc informatique'
    ],

    'ajouter-equipement' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter un équipement',
        'sousTitre' => 'Ajout d’un nouveau matériel'
    ],

    'modifier-equipement' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier un équipement',
        'sousTitre' => 'Mise à jour des informations du matériel'
    ],

    'locals' => [
        'icon' => 'bi-building-fill',
        'titre' => 'Locaux',
        'sousTitre' => 'Gestion des locaux et espaces de l’établissement'
    ],

    'ajouter-local' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter un local',
        'sousTitre' => 'Création d’un nouveau local'
    ],

    'modifier-local' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier un local',
        'sousTitre' => 'Mise à jour des informations du local'
    ],

    'affectations' => [
        'icon' => 'bi-arrow-left-right',
        'titre' => 'Affectations',
        'sousTitre' => 'Gestion des affectations d’équipements aux utilisateurs'
    ],

    'ajouter-affectation' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter une affectation',
        'sousTitre' => 'Affectation d’un équipement à un utilisateur'
    ],

    'modifier-affectation' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier une affectation',
        'sousTitre' => 'Mise à jour d’une affectation'
    ],

    'tickets' => [
        'icon' => 'bi-ticket-detailed-fill',
        'titre' => 'Tickets',
        'sousTitre' => 'Suivi et gestion des demandes d’assistance'
    ],

    'ajouter-ticket' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter un ticket',
        'sousTitre' => 'Création d’une nouvelle demande'
    ],

    'modifier-ticket' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier un ticket',
        'sousTitre' => 'Mise à jour d’une demande d’assistance'
    ],

    'interventions' => [
        'icon' => 'bi-tools',
        'titre' => 'Interventions',
        'sousTitre' => 'Suivi et gestion des interventions techniques'
    ],

    'ajouter-intervention' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter une intervention',
        'sousTitre' => 'Création d’une intervention technique'
    ],

    'modifier-intervention' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier une intervention',
        'sousTitre' => 'Mise à jour d’une intervention technique'
    ],

    'evaluations' => [
        'icon' => 'bi-star-fill',
        'titre' => 'Évaluations',
        'sousTitre' => 'Évaluation des interventions et des techniciens'
    ],

    'ajouter-evaluation' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter une évaluation',
        'sousTitre' => 'Ajout d’une note et d’un commentaire'
    ],

    'modifier-evaluation' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier une évaluation',
        'sousTitre' => 'Mise à jour d’une évaluation'
    ],

    'logiciels' => [
        'icon' => 'bi-disc-fill',
        'titre' => 'Logiciels',
        'sousTitre' => 'Gestion des logiciels installés'
    ],

    'ajouter-logiciel' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter un logiciel',
        'sousTitre' => 'Ajout d’un nouveau logiciel'
    ],

    'modifier-logiciel' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier un logiciel',
        'sousTitre' => 'Mise à jour des informations du logiciel'
    ],

    'licences' => [
        'icon' => 'bi-key-fill',
        'titre' => 'Licences',
        'sousTitre' => 'Gestion des licences logicielles'
    ],

    'ajouter-licence' => [
        'icon' => 'bi-plus-circle-fill',
        'titre' => 'Ajouter une licence',
        'sousTitre' => 'Création d’une nouvelle licence logicielle'
    ],

    'modifier-licence' => [
        'icon' => 'bi-pencil-square',
        'titre' => 'Modifier une licence',
        'sousTitre' => 'Mise à jour d’une licence logicielle'
    ],

    'historiques' => [
        'icon' => 'bi-clock-history',
        'titre' => 'Historique',
        'sousTitre' => 'Traçabilité des actions réalisées dans l’application'
    ],

    'profil' => [
        'icon' => 'bi-person-circle',
        'titre' => 'Mon profil',
        'sousTitre' => 'Informations du compte connecté'
    ],

    'parametres-compte' => [
        'icon' => 'bi-gear-fill',
        'titre' => 'Paramètres du compte',
        'sousTitre' => 'Préférences et sécurité du compte'
    ]

];

$navbar = $titres[$page] ?? $titres['dashboard'];

$prenom = $_SESSION['prenom'] ?? 'FSJES';
$nom = $_SESSION['nom'] ?? 'Admin';
$roleConnecte = $_SESSION['nom_role'] ?? $_SESSION['role'] ?? 'Administrateur';

$initialesUser = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));

?>

<nav class="top-navbar">

    <div class="page-title">

        <div class="page-icon">
            <i class="bi <?= $navbar['icon']; ?>"></i>
        </div>

        <div>
            <h4><?= $navbar['titre']; ?></h4>
            <small><?= $navbar['sousTitre']; ?></small>
        </div>

    </div>

    <div class="navbar-right">

        <div class="search-box">

            <i class="bi bi-search"></i>

            <input type="text" placeholder="Rechercher...">

        </div>

        <div class="date-box">

            <i class="bi bi-calendar3"></i>

            <?= date('d/m/Y'); ?>

        </div>

        <div class="dropdown user-menu">

            <button class="user-info user-info-button"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                <div class="user-avatar">
                    <?= htmlspecialchars($initialesUser ?: 'FS'); ?>
                </div>

                <div class="user-text">

                    <strong>
                        <?= htmlspecialchars($prenom . ' ' . $nom); ?>
                    </strong>

                    <small><?= htmlspecialchars($roleConnecte); ?></small>

                </div>

                <i class="bi bi-chevron-down user-arrow"></i>

            </button>

            <ul class="dropdown-menu dropdown-menu-end clean-profile-menu">

                <li class="clean-profile-header">

                    <div class="clean-profile-avatar">
                        <?= htmlspecialchars($initialesUser ?: 'FS'); ?>
                    </div>

                    <div>
                        <strong><?= htmlspecialchars($prenom . ' ' . $nom); ?></strong>
                        <small><?= htmlspecialchars($roleConnecte); ?></small>
                    </div>

                </li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <a class="clean-profile-item" href="<?= BASE_URL ?>?page=profil">
                        <span><i class="bi bi-person-circle"></i></span>
                        <div>
                            <strong>Mon profil</strong>
                            <small>Voir mes informations</small>
                        </div>
                    </a>
                </li>

                <li>
                    <a class="clean-profile-item" href="<?= BASE_URL ?>?page=dashboard">
                        <span><i class="bi bi-speedometer2"></i></span>
                        <div>
                            <strong>Tableau de bord</strong>
                            <small>Retour à l’accueil</small>
                        </div>
                    </a>
                </li>

                <li>
                    <a class="clean-profile-item" href="<?= BASE_URL ?>?page=parametres-compte">
                        <span><i class="bi bi-gear-fill"></i></span>
                        <div>
                            <strong>Paramètres</strong>
                            <small>Compte et sécurité</small>
                        </div>
                    </a>
                </li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <a class="clean-logout-item" href="<?= BASE_URL ?>?page=logout">
                        <i class="bi bi-box-arrow-right"></i>
                        Déconnexion
                    </a>
                </li>

            </ul>

        </div>

    </div>

</nav>

<?php if (isset($_SESSION['success'])): ?>

    <div class="alert alert-success alert-dismissible fade show mt-3 shadow-sm" role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        <?= $_SESSION['success']; ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>