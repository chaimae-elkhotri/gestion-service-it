<?php

require_once '../app/core/Auth.php';

$currentPage = $_GET['page'] ?? 'dashboard';

$isAdmin = Auth::estAdmin();
$isTechnicien = Auth::estTechnicien();
$isEmploye = Auth::estEmploye();

if ($isAdmin) {
    $roleLabel = t('role.admin');
} elseif ($isTechnicien) {
    $roleLabel = t('role.technician');
} else {
    $roleLabel = t('role.employee');
}

if (!function_exists('activeMenu')) {
    function activeMenu(array $pages): string
    {
        global $currentPage;

        return in_array(
            $currentPage,
            $pages,
            true
        ) ? 'active' : '';
    }
}

?>

<div class="sidebar">

    <div class="logo-section">

        <div class="brand-logo">

            <img
                src="<?= BASE_URL ?>assets/images/logo-fsjes.png"
                alt="<?= htmlspecialchars(t('app.brand')); ?>">

        </div>

        <div>

            <h4>
                <?= htmlspecialchars(t('app.brand')); ?>
            </h4>

            <small>

                <?= htmlspecialchars(
                    t('app.subtitle_line_1')
                ); ?>

                <br>

                <?= htmlspecialchars(
                    t('app.subtitle_line_2')
                ); ?>

            </small>

        </div>

    </div>

    <div class="sidebar-menu">

        <a
            class="<?= activeMenu(['dashboard']); ?>"
            href="<?= BASE_URL ?>?page=dashboard">

            <i class="bi bi-house-door-fill"></i>

            <span>
                <?= htmlspecialchars(t('menu.dashboard')); ?>
            </span>

        </a>

        <?php if ($isAdmin): ?>

            <a
                class="<?= activeMenu([
                    'utilisateurs',
                    'ajouter-utilisateur',
                    'modifier-utilisateur'
                ]); ?>"
                href="<?= BASE_URL ?>?page=utilisateurs">

                <i class="bi bi-people-fill"></i>

                <span>
                    <?= htmlspecialchars(t('menu.users')); ?>
                </span>

            </a>

            <a
                class="<?= activeMenu([
                    'categories',
                    'ajouter-categorie',
                    'modifier-categorie'
                ]); ?>"
                href="<?= BASE_URL ?>?page=categories">

                <i class="bi bi-grid-3x3-gap-fill"></i>

                <span>
                    <?= htmlspecialchars(t('menu.categories')); ?>
                </span>

            </a>

        <?php endif; ?>

        <?php if ($isAdmin || $isTechnicien): ?>

            <a
                class="<?= activeMenu([
                    'equipements',
                    'ajouter-equipement',
                    'modifier-equipement',
                    'edit-equipement'
                ]); ?>"
                href="<?= BASE_URL ?>?page=equipements">

                <i class="bi bi-pc-display"></i>

                <span>
                    <?= htmlspecialchars(t('menu.equipment')); ?>
                </span>

            </a>

            <a
                class="<?= activeMenu([
                    'locals',
                    'ajouter-local',
                    'modifier-local'
                ]); ?>"
                href="<?= BASE_URL ?>?page=locals">

                <i class="bi bi-building-fill"></i>

                <span>
                    <?= htmlspecialchars(t('menu.locals')); ?>
                </span>

            </a>

            <a
                class="<?= activeMenu([
                    'occupations-locaux',
                    'ajouter-occupation-local',
                    'modifier-occupation-local'
                ]); ?>"
                href="<?= BASE_URL ?>?page=occupations-locaux">

                <i class="bi bi-calendar2-week-fill"></i>

                <span>
                    <?= htmlspecialchars(
                        t('menu.local_occupations')
                    ); ?>
                </span>

            </a>

        <?php endif; ?>

        <?php if ($isAdmin): ?>

            <a
                class="<?= activeMenu([
                    'affectations',
                    'ajouter-affectation',
                    'modifier-affectation'
                ]); ?>"
                href="<?= BASE_URL ?>?page=affectations">

                <i class="bi bi-arrow-left-right"></i>

                <span>
                    <?= htmlspecialchars(t('menu.assignments')); ?>
                </span>

            </a>

        <?php endif; ?>

        <a
            class="<?= activeMenu([
                'tickets',
                'ajouter-ticket',
                'modifier-ticket'
            ]); ?>"
            href="<?= BASE_URL ?>?page=tickets">

            <i class="bi bi-ticket-detailed-fill"></i>

            <span>

                <?= htmlspecialchars(
                    $isEmploye
                        ? t('menu.my_tickets')
                        : t('menu.tickets')
                ); ?>

            </span>

        </a>

        <?php if ($isAdmin || $isTechnicien): ?>

            <a
                class="<?= activeMenu([
                    'interventions',
                    'ajouter-intervention',
                    'modifier-intervention'
                ]); ?>"
                href="<?= BASE_URL ?>?page=interventions">

                <i class="bi bi-tools"></i>

                <span>

                    <?= htmlspecialchars(
                        $isTechnicien
                            ? t('menu.my_interventions')
                            : t('menu.interventions')
                    ); ?>

                </span>

            </a>

        <?php endif; ?>

        <?php if ($isAdmin || $isEmploye): ?>

            <a
                class="<?= activeMenu([
                    'evaluations',
                    'ajouter-evaluation',
                    'modifier-evaluation'
                ]); ?>"
                href="<?= BASE_URL ?>?page=evaluations">

                <i class="bi bi-star-fill"></i>

                <span>
                    <?= htmlspecialchars(t('menu.evaluations')); ?>
                </span>

            </a>

        <?php endif; ?>

        <?php if ($isAdmin || $isTechnicien): ?>

            <a
                class="<?= activeMenu([
                    'logiciels',
                    'ajouter-logiciel',
                    'modifier-logiciel'
                ]); ?>"
                href="<?= BASE_URL ?>?page=logiciels">

                <i class="bi bi-disc-fill"></i>

                <span>
                    <?= htmlspecialchars(t('menu.software')); ?>
                </span>

            </a>

            <a
                class="<?= activeMenu([
                    'licences',
                    'ajouter-licence',
                    'modifier-licence'
                ]); ?>"
                href="<?= BASE_URL ?>?page=licences">

                <i class="bi bi-key-fill"></i>

                <span>
                    <?= htmlspecialchars(t('menu.licenses')); ?>
                </span>

            </a>

        <?php endif; ?>

        <?php if ($isAdmin): ?>

            <a
                class="<?= activeMenu(['historiques']); ?>"
                href="<?= BASE_URL ?>?page=historiques">

                <i class="bi bi-clock-history"></i>

                <span>
                    <?= htmlspecialchars(t('menu.history')); ?>
                </span>

            </a>

        <?php endif; ?>

        <a
            class="<?= activeMenu(['profil']); ?>"
            href="<?= BASE_URL ?>?page=profil">

            <i class="bi bi-person-circle"></i>

            <span>
                <?= htmlspecialchars(t('menu.profile')); ?>
            </span>

        </a>

        <a
            class="<?= activeMenu([
                'parametres-compte'
            ]); ?>"
            href="<?= BASE_URL ?>?page=parametres-compte">

            <i class="bi bi-gear-fill"></i>

            <span>
                <?= htmlspecialchars(t('menu.settings')); ?>
            </span>

        </a>

    </div>

    <button
        type="button"
        class="sidebar-help sidebar-help-btn"
        data-bs-toggle="modal"
        data-bs-target="#supportModal">

        <div class="help-icon">

            <i class="bi bi-headset"></i>

        </div>

        <div>

            <strong>
                <?= htmlspecialchars(t('menu.help')); ?>
            </strong>

            <small>
                <?= htmlspecialchars(t('menu.help_subtitle')); ?>
            </small>

        </div>

    </button>

    <div class="sidebar-logout">

        <a href="<?= BASE_URL ?>?page=logout">

            <i class="bi bi-box-arrow-right"></i>

            <span>
                <?= htmlspecialchars(t('menu.logout')); ?>
            </span>

        </a>

    </div>

</div>

<div
    class="modal fade"
    id="supportModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content support-modal-content">

            <div class="modal-header support-modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-headset"></i>

                    <?= htmlspecialchars(t('support.title')); ?>

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="support-item">

                    <i class="bi bi-info-circle-fill"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                t('support.application')
                            ); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars(
                                t('support.application_text')
                            ); ?>
                        </p>

                    </div>

                </div>

                <div class="support-item">

                    <i class="bi bi-person-gear"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                t('support.it_service')
                            ); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars(
                                t('support.it_service_text')
                            ); ?>
                        </p>

                    </div>

                </div>

                <div class="support-item">

                    <i class="bi bi-shield-check"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                t('support.your_role')
                            ); ?>
                        </strong>

                        <p>

                            <?= htmlspecialchars(
                                t('support.connected_as')
                            ); ?>

                            <strong>
                                <?= htmlspecialchars($roleLabel); ?>
                            </strong>

                        </p>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-dismiss="modal">

                    <?= htmlspecialchars(t('support.close')); ?>

                </button>

            </div>

        </div>

    </div>

</div>

<div class="content">