<?php

$page = $_GET['page'] ?? 'dashboard';

$icons = [

    'dashboard' => 'bi-house-door-fill',

    'utilisateurs' => 'bi-people-fill',
    'ajouter-utilisateur' => 'bi-person-plus-fill',
    'modifier-utilisateur' => 'bi-pencil-square',

    'categories' => 'bi-grid-3x3-gap-fill',
    'ajouter-categorie' => 'bi-plus-circle-fill',
    'modifier-categorie' => 'bi-pencil-square',

    'equipements' => 'bi-pc-display',
    'ajouter-equipement' => 'bi-plus-circle-fill',
    'modifier-equipement' => 'bi-pencil-square',
    'edit-equipement' => 'bi-pencil-square',

    'locals' => 'bi-building-fill',
    'ajouter-local' => 'bi-plus-circle-fill',
    'modifier-local' => 'bi-pencil-square',

    'occupations-locaux' => 'bi-calendar2-week-fill',
    'ajouter-occupation-local' => 'bi-calendar-plus-fill',
    'modifier-occupation-local' => 'bi-pencil-square',

    'affectations' => 'bi-arrow-left-right',
    'ajouter-affectation' => 'bi-plus-circle-fill',
    'modifier-affectation' => 'bi-pencil-square',

    'tickets' => 'bi-ticket-detailed-fill',
    'ajouter-ticket' => 'bi-plus-circle-fill',
    'modifier-ticket' => 'bi-pencil-square',

    'interventions' => 'bi-tools',
    'ajouter-intervention' => 'bi-plus-circle-fill',
    'modifier-intervention' => 'bi-pencil-square',

    'evaluations' => 'bi-star-fill',
    'ajouter-evaluation' => 'bi-plus-circle-fill',
    'modifier-evaluation' => 'bi-pencil-square',

    'logiciels' => 'bi-disc-fill',
    'ajouter-logiciel' => 'bi-plus-circle-fill',
    'modifier-logiciel' => 'bi-pencil-square',

    'licences' => 'bi-key-fill',
    'ajouter-licence' => 'bi-plus-circle-fill',
    'modifier-licence' => 'bi-pencil-square',

    'historiques' => 'bi-clock-history',

    'profil' => 'bi-person-circle',

    'parametres-compte' => 'bi-gear-fill'
];

$pageTraduction = $page;

if ($page === 'edit-equipement') {
    $pageTraduction = 'modifier-equipement';
}

$titreKey =
    'pages.'
    . $pageTraduction
    . '.title';

$sousTitreKey =
    'pages.'
    . $pageTraduction
    . '.subtitle';

$titre = t($titreKey);
$sousTitre = t($sousTitreKey);

if ($titre === $titreKey) {
    $titre = t('pages.dashboard.title');
}

if ($sousTitre === $sousTitreKey) {
    $sousTitre = t('pages.dashboard.subtitle');
}

$icon = $icons[$page]
    ?? 'bi-house-door-fill';

$prenom = $_SESSION['prenom']
    ?? 'FSJES';

$nom = $_SESSION['nom']
    ?? 'Admin';

if (Auth::estAdmin()) {
    $roleConnecte = t('role.admin');
} elseif (Auth::estTechnicien()) {
    $roleConnecte = t('role.technician');
} else {
    $roleConnecte = t('role.employee');
}

$initialesUser = mb_strtoupper(
    mb_substr($prenom, 0, 1, 'UTF-8')
    .
    mb_substr($nom, 0, 1, 'UTF-8'),
    'UTF-8'
);

if (!function_exists('languageUrl')) {
    function languageUrl(string $language): string
    {
        $parameters = $_GET;

        $parameters['lang'] = $language;

        return BASE_URL
            . '?'
            . http_build_query($parameters);
    }
}

?>

<nav class="top-navbar">

    <div class="page-title">

        <div class="page-icon">

            <i class="bi <?= htmlspecialchars($icon); ?>"></i>

        </div>

        <div>

            <h4>
                <?= htmlspecialchars($titre); ?>
            </h4>

            <small>
                <?= htmlspecialchars($sousTitre); ?>
            </small>

        </div>

    </div>

    <div class="navbar-right">

        <div class="search-box">

            <i class="bi bi-search"></i>

            <input
                type="text"
                placeholder="<?= htmlspecialchars(
                    t('navbar.search')
                ); ?>">

        </div>

        <div class="dropdown">

            <button
                class="btn btn-light border dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <i class="bi bi-translate"></i>

                <?= Language::isArabic()
                    ? 'العربية'
                    : 'Français'; ?>

            </button>

            <ul class="dropdown-menu dropdown-menu-end">

                <li>

                    <a
                        class="dropdown-item <?= Language::get() === 'fr'
                            ? 'active'
                            : ''; ?>"
                        href="<?= htmlspecialchars(
                            languageUrl('fr')
                        ); ?>">

                        🇫🇷 Français

                    </a>

                </li>

                <li>

                    <a
                        class="dropdown-item <?= Language::get() === 'ar'
                            ? 'active'
                            : ''; ?>"
                        href="<?= htmlspecialchars(
                            languageUrl('ar')
                        ); ?>">

                        🇲🇦 العربية

                    </a>

                </li>

            </ul>

        </div>

        <div class="date-box">

            <i class="bi bi-calendar3"></i>

            <?= date('d/m/Y'); ?>

        </div>

        <div class="dropdown user-menu">

            <button
                class="user-info user-info-button"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <div class="user-avatar">

                    <?= htmlspecialchars(
                        $initialesUser ?: 'FS'
                    ); ?>

                </div>

                <div class="user-text">

                    <strong>

                        <?= htmlspecialchars(
                            trim($prenom . ' ' . $nom)
                        ); ?>

                    </strong>

                    <small>
                        <?= htmlspecialchars($roleConnecte); ?>
                    </small>

                </div>

                <i class="bi bi-chevron-down user-arrow"></i>

            </button>

            <ul class="dropdown-menu dropdown-menu-end clean-profile-menu">

                <li class="clean-profile-header">

                    <div class="clean-profile-avatar">

                        <?= htmlspecialchars(
                            $initialesUser ?: 'FS'
                        ); ?>

                    </div>

                    <div>

                        <strong>

                            <?= htmlspecialchars(
                                trim($prenom . ' ' . $nom)
                            ); ?>

                        </strong>

                        <small>
                            <?= htmlspecialchars($roleConnecte); ?>
                        </small>

                    </div>

                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>

                    <a
                        class="clean-profile-item"
                        href="<?= BASE_URL ?>?page=profil">

                        <span>
                            <i class="bi bi-person-circle"></i>
                        </span>

                        <div>

                            <strong>
                                <?= htmlspecialchars(
                                    t('navbar.profile')
                                ); ?>
                            </strong>

                            <small>
                                <?= htmlspecialchars(
                                    t('navbar.profile_subtitle')
                                ); ?>
                            </small>

                        </div>

                    </a>

                </li>

                <li>

                    <a
                        class="clean-profile-item"
                        href="<?= BASE_URL ?>?page=dashboard">

                        <span>
                            <i class="bi bi-speedometer2"></i>
                        </span>

                        <div>

                            <strong>
                                <?= htmlspecialchars(
                                    t('navbar.dashboard')
                                ); ?>
                            </strong>

                            <small>
                                <?= htmlspecialchars(
                                    t('navbar.dashboard_subtitle')
                                ); ?>
                            </small>

                        </div>

                    </a>

                </li>

                <li>

                    <a
                        class="clean-profile-item"
                        href="<?= BASE_URL ?>?page=parametres-compte">

                        <span>
                            <i class="bi bi-gear-fill"></i>
                        </span>

                        <div>

                            <strong>
                                <?= htmlspecialchars(
                                    t('navbar.settings')
                                ); ?>
                            </strong>

                            <small>
                                <?= htmlspecialchars(
                                    t('navbar.settings_subtitle')
                                ); ?>
                            </small>

                        </div>

                    </a>

                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>

                    <a
                        class="clean-logout-item"
                        href="<?= BASE_URL ?>?page=logout">

                        <i class="bi bi-box-arrow-right"></i>

                        <?= htmlspecialchars(
                            t('navbar.logout')
                        ); ?>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>

<?php if (isset($_SESSION['success'])): ?>

    <div
        class="alert alert-success alert-dismissible fade show mt-3 shadow-sm"
        role="alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        <?= htmlspecialchars($_SESSION['success']); ?>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>