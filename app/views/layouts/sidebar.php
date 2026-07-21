<?php

require_once '../app/core/Auth.php';

$currentPage = $_GET['page'] ?? 'dashboard';

$isAdmin = Auth::estAdmin();
$isTechnicien = Auth::estTechnicien();
$isEmploye = Auth::estEmploye();

if ($isAdmin) {
    $roleLabel = 'Administrateur';
} elseif ($isTechnicien) {
    $roleLabel = 'Technicien';
} else {
    $roleLabel = 'Employé';
}

function activeMenu(array $pages): string
{
    global $currentPage;

    return in_array($currentPage, $pages, true) ? 'active' : '';
}

?>

<div class="sidebar">

    <div class="logo-section">

        <div class="brand-logo">
            <img src="<?= BASE_URL ?>assets/images/logo-fsjes.png"
                 alt="Logo FSJES Oujda">
        </div>

        <div>
            <h4>FSJES Oujda</h4>
            <small>
                Système de Gestion du<br>
                Parc Informatique
            </small>
        </div>

    </div>

    <div class="sidebar-menu">

        <!-- Dashboard : tous les rôles -->
        <a class="<?= activeMenu(['dashboard']); ?>"
           href="<?= BASE_URL ?>?page=dashboard">

            <i class="bi bi-house-door-fill"></i>
            <span>Tableau de bord</span>

        </a>

        <!-- Utilisateurs : administrateur uniquement -->
        <?php if ($isAdmin): ?>

            <a class="<?= activeMenu([
                'utilisateurs',
                'ajouter-utilisateur',
                'modifier-utilisateur'
            ]); ?>"
               href="<?= BASE_URL ?>?page=utilisateurs">

                <i class="bi bi-people-fill"></i>
                <span>Utilisateurs</span>

            </a>

        <?php endif; ?>

        <!-- Catégories : administrateur uniquement -->
        <?php if ($isAdmin): ?>

            <a class="<?= activeMenu([
                'categories',
                'ajouter-categorie',
                'modifier-categorie'
            ]); ?>"
               href="<?= BASE_URL ?>?page=categories">

                <i class="bi bi-grid-3x3-gap-fill"></i>
                <span>Catégories</span>

            </a>

        <?php endif; ?>

        <!-- Équipements : administrateur + technicien -->
        <?php if ($isAdmin || $isTechnicien): ?>

            <a class="<?= activeMenu([
                'equipements',
                'ajouter-equipement',
                'modifier-equipement'
            ]); ?>"
               href="<?= BASE_URL ?>?page=equipements">

                <i class="bi bi-pc-display"></i>
                <span>Équipements</span>

            </a>

        <?php endif; ?>

        <!-- Locaux : administrateur + technicien -->
        <?php if ($isAdmin || $isTechnicien): ?>

            <a class="<?= activeMenu([
                'locals',
                'ajouter-local',
                'modifier-local'
            ]); ?>"
               href="<?= BASE_URL ?>?page=locals">

                <i class="bi bi-building-fill"></i>
                <span>Locaux</span>

            </a>

        <?php endif; ?>

        <!-- Affectations : administrateur uniquement -->
        <?php if ($isAdmin): ?>

            <a class="<?= activeMenu([
                'affectations',
                'ajouter-affectation',
                'modifier-affectation'
            ]); ?>"
               href="<?= BASE_URL ?>?page=affectations">

                <i class="bi bi-arrow-left-right"></i>
                <span>Affectations</span>

            </a>

        <?php endif; ?>

        <!-- Tickets : tous les rôles -->
        <a class="<?= activeMenu([
            'tickets',
            'ajouter-ticket',
            'modifier-ticket'
        ]); ?>"
           href="<?= BASE_URL ?>?page=tickets">

            <i class="bi bi-ticket-detailed-fill"></i>

            <span>
                <?= $isEmploye ? 'Mes tickets' : 'Tickets'; ?>
            </span>

        </a>

        <!-- Interventions : administrateur + technicien -->
        <?php if ($isAdmin || $isTechnicien): ?>

            <a class="<?= activeMenu([
                'interventions',
                'ajouter-intervention',
                'modifier-intervention'
            ]); ?>"
               href="<?= BASE_URL ?>?page=interventions">

                <i class="bi bi-tools"></i>

                <span>
                    <?= $isTechnicien ? 'Mes interventions' : 'Interventions'; ?>
                </span>

            </a>

        <?php endif; ?>

        <!-- Évaluations : administrateur + employé -->
        <?php if ($isAdmin || $isEmploye): ?>

            <a class="<?= activeMenu([
                'evaluations',
                'ajouter-evaluation',
                'modifier-evaluation'
            ]); ?>"
               href="<?= BASE_URL ?>?page=evaluations">

                <i class="bi bi-star-fill"></i>
                <span>Évaluations</span>

            </a>

        <?php endif; ?>

        <!-- Logiciels : administrateur + technicien -->
        <?php if ($isAdmin || $isTechnicien): ?>

            <a class="<?= activeMenu([
                'logiciels',
                'ajouter-logiciel',
                'modifier-logiciel'
            ]); ?>"
               href="<?= BASE_URL ?>?page=logiciels">

                <i class="bi bi-disc-fill"></i>
                <span>Logiciels</span>

            </a>

        <?php endif; ?>

        <!-- Licences : administrateur + technicien -->
        <?php if ($isAdmin || $isTechnicien): ?>

            <a class="<?= activeMenu([
                'licences',
                'ajouter-licence',
                'modifier-licence'
            ]); ?>"
               href="<?= BASE_URL ?>?page=licences">

                <i class="bi bi-key-fill"></i>
                <span>Licences</span>

            </a>

        <?php endif; ?>

        <!-- Historique : administrateur uniquement -->
        <?php if ($isAdmin): ?>

            <a class="<?= activeMenu(['historiques']); ?>"
               href="<?= BASE_URL ?>?page=historiques">

                <i class="bi bi-clock-history"></i>
                <span>Historique</span>

            </a>

        <?php endif; ?>

        <!-- Profil : tous les rôles -->
        <a class="<?= activeMenu(['profil']); ?>"
           href="<?= BASE_URL ?>?page=profil">

            <i class="bi bi-person-circle"></i>
            <span>Mon profil</span>

        </a>

        <!-- Paramètres : tous les rôles -->
        <a class="<?= activeMenu(['parametres-compte']); ?>"
           href="<?= BASE_URL ?>?page=parametres-compte">

            <i class="bi bi-gear-fill"></i>
            <span>Paramètres</span>

        </a>

    </div>

    <button type="button"
            class="sidebar-help sidebar-help-btn"
            data-bs-toggle="modal"
            data-bs-target="#supportModal">

        <div class="help-icon">
            <i class="bi bi-headset"></i>
        </div>

        <div>
            <strong>Besoin d'aide ?</strong>
            <small>Documentation & Support</small>
        </div>

    </button>

    <div class="sidebar-logout">

        <a href="<?= BASE_URL ?>?page=logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Déconnexion</span>
        </a>

    </div>

</div>

<div class="modal fade"
     id="supportModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content support-modal-content">

            <div class="modal-header support-modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-headset"></i>
                    Besoin d'aide ?
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="support-item">
                    <i class="bi bi-info-circle-fill"></i>

                    <div>
                        <strong>Application</strong>

                        <p>
                            Système de gestion du parc informatique
                            de la FSJES Oujda.
                        </p>
                    </div>
                </div>

                <div class="support-item">
                    <i class="bi bi-person-gear"></i>

                    <div>
                        <strong>Service informatique</strong>

                        <p>
                            Contactez l’administrateur en cas de
                            problème technique.
                        </p>
                    </div>
                </div>

                <div class="support-item">
                    <i class="bi bi-shield-check"></i>

                    <div>
                        <strong>Votre rôle</strong>

                        <p>
                            Vous êtes connecté en tant que :
                            <strong><?= htmlspecialchars($roleLabel); ?></strong>
                        </p>
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal">

                    D'accord

                </button>

            </div>

        </div>

    </div>

</div>

<div class="content">