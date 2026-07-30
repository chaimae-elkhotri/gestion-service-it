<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$licences = $licences ?? [];

if (!function_exists('licenceT')) {
    function licenceT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'licences_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('licenceNormalize')) {
    function licenceNormalize(string $value): string
    {
        $value = mb_strtolower(
            trim($value),
            'UTF-8'
        );

        return strtr($value, [
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'à' => 'a',
            'â' => 'a',
            'î' => 'i',
            'ï' => 'i',
            'ô' => 'o',
            'ù' => 'u',
            'û' => 'u',
            'ç' => 'c'
        ]);
    }
}

$today = date('Y-m-d');

$dateDans30Jours = date(
    'Y-m-d',
    strtotime('+30 days')
);

$filtreStatut = licenceNormalize(
    (string)($_GET['statut'] ?? '')
);

if ($filtreStatut !== '') {
    $licences = array_values(
        array_filter(
            $licences,
            function (array $licence) use (
                $filtreStatut,
                $today,
                $dateDans30Jours
            ): bool {
                $dateFin =
                    $licence['date_fin']
                    ?? $licence['DATE_FIN']
                    ?? '';

                if (!empty($dateFin) && $dateFin < $today) {
                    $statutCalcule = 'expiree';
                } elseif (
                    !empty($dateFin)
                    && $dateFin <= $dateDans30Jours
                ) {
                    $statutCalcule = 'expire bientot';
                } else {
                    $statutCalcule = 'active';
                }

                return $statutCalcule ===
                    $filtreStatut;
            }
        )
    );
}

$totalLicences = count($licences);
$totalActives = 0;
$totalExpirees = 0;
$totalBientotExpirees = 0;
$totalPostes = 0;

foreach ($licences as $licence) {
    $dateFin =
        $licence['date_fin']
        ?? $licence['DATE_FIN']
        ?? '';

    $nombrePostes =
        $licence['nombre_postes']
        ?? $licence['NOMBRE_POSTES']
        ?? 0;

    $totalPostes += (int)$nombrePostes;

    if (!empty($dateFin)) {
        if ($dateFin < $today) {
            $totalExpirees++;
        } elseif ($dateFin <= $dateDans30Jours) {
            $totalBientotExpirees++;
        } else {
            $totalActives++;
        }
    } else {
        $totalActives++;
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    licenceT('management_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    licenceT('management_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-licence"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            <?= htmlspecialchars(
                licenceT('add_licence')
            ); ?>

        </a>

    </div>

    <?php if (isset($_SESSION['success'])): ?>

        <div class="alert alert-success">

            <i class="bi bi-check-circle-fill me-2"></i>

            <?= htmlspecialchars($_SESSION['success']); ?>

        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <?= htmlspecialchars($_SESSION['error']); ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <div class="module-stats-grid">

        <div class="module-stat-card">

            <div class="module-stat-icon brown">
                <i class="bi bi-key-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        licenceT('total_licences')
                    ); ?>
                </span>

                <h3><?= $totalLicences; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        licenceT(
                            'registered_licences'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        licenceT('active_licences')
                    ); ?>
                </span>

                <h3><?= $totalActives; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        licenceT('valid_licences')
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon orange">
                <i class="bi bi-calendar-event-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        licenceT('expiring_soon')
                    ); ?>
                </span>

                <h3><?= $totalBientotExpirees; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        licenceT('within_30_days')
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon red">
                <i class="bi bi-calendar-x-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        licenceT('expired_licences')
                    ); ?>
                </span>

                <h3><?= $totalExpirees; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        licenceT('to_renew')
                    ); ?>
                </small>

            </div>

        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="licences">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            licenceT('search')
                        ); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(
                                   licenceT(
                                       'search_placeholder'
                                   )
                               ); ?>"
                               value="<?= htmlspecialchars(
                                   $_GET['search'] ?? ''
                               ); ?>">

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            licenceT('status')
                        ); ?>
                    </label>

                    <select name="statut"
                            class="form-select"
                            onchange="this.form.submit()">

                        <option value=""
                            <?= $filtreStatut === ''
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                licenceT('all_statuses')
                            ); ?>

                        </option>

                        <option value="active"
                            <?= $filtreStatut === 'active'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                licenceT('status_active')
                            ); ?>

                        </option>

                        <option value="expire bientot"
                            <?= $filtreStatut === 'expire bientot'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                licenceT(
                                    'status_expiring_soon'
                                )
                            ); ?>

                        </option>

                        <option value="expiree"
                            <?= $filtreStatut === 'expiree'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                licenceT('status_expired')
                            ); ?>

                        </option>

                    </select>

                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-fill">

                        <i class="bi bi-search"></i>

                        <?= htmlspecialchars(
                            licenceT('search_button')
                        ); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=licences"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(
                           licenceT('reset')
                       ); ?>">

                        <i class="bi bi-arrow-clockwise"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>

                <h5>
                    <?= htmlspecialchars(
                        licenceT('licence_list')
                    ); ?>
                </h5>

                <small>
                    <?= htmlspecialchars(
                        licenceT(
                            'licences_found_posts',
                            [
                                'count' => $totalLicences,
                                'posts' => $totalPostes
                            ]
                        )
                    ); ?>
                </small>

            </div>

            <span class="module-chip">

                <i class="bi bi-shield-lock-fill"></i>

                <?= htmlspecialchars(
                    licenceT('software_licences')
                ); ?>

            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(licenceT('id')); ?></th>
                    <th><?= htmlspecialchars(licenceT('software')); ?></th>
                    <th><?= htmlspecialchars(licenceT('licence_key')); ?></th>
                    <th><?= htmlspecialchars(licenceT('start_date')); ?></th>
                    <th><?= htmlspecialchars(licenceT('end_date')); ?></th>
                    <th><?= htmlspecialchars(licenceT('posts')); ?></th>
                    <th><?= htmlspecialchars(licenceT('status')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(licenceT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($licences)): ?>

                    <?php foreach (
                        $licences as $licence
                    ): ?>

                        <?php

                        $id =
                            $licence['id_licence']
                            ?? $licence['ID_LICENCE']
                            ?? '';

                        $nomLogiciel =
                            $licence['nom_logiciel']
                            ?? $licence['NOM_LOGICIEL']
                            ?? licenceT('software');

                        $version =
                            $licence['version']
                            ?? $licence['VERSION']
                            ?? '';

                        $editeur =
                            $licence['editeur']
                            ?? $licence['EDITEUR']
                            ?? '';

                        $cleLicence =
                            $licence['cle_licence']
                            ?? $licence['CLE_LICENCE']
                            ?? '';

                        $dateDebut =
                            $licence['date_debut']
                            ?? $licence['DATE_DEBUT']
                            ?? '';

                        $dateFin =
                            $licence['date_fin']
                            ?? $licence['DATE_FIN']
                            ?? '';

                        $nombrePostes =
                            $licence['nombre_postes']
                            ?? $licence['NOMBRE_POSTES']
                            ?? 0;

                        $cleAffichee = $cleLicence;

                        if (strlen($cleLicence) > 22) {
                            $cleAffichee =
                                substr($cleLicence, 0, 8)
                                . '••••'
                                . substr($cleLicence, -6);
                        }

                        if (
                            !empty($dateFin)
                            && $dateFin < $today
                        ) {
                            $statutLicence =
                                licenceT(
                                    'status_expired'
                                );

                            $badgeClass =
                                'licence-expired';

                            $iconStatut =
                                'bi-x-circle-fill';

                        } elseif (
                            !empty($dateFin)
                            && $dateFin <= $dateDans30Jours
                        ) {
                            $statutLicence =
                                licenceT(
                                    'status_expiring_soon'
                                );

                            $badgeClass =
                                'licence-warning';

                            $iconStatut =
                                'bi-exclamation-triangle-fill';

                        } else {
                            $statutLicence =
                                licenceT(
                                    'status_active'
                                );

                            $badgeClass =
                                'licence-active';

                            $iconStatut =
                                'bi-check-circle-fill';
                        }

                        ?>

                        <tr>

                            <td>

                                <span class="table-id">
                                    #LIC-<?= htmlspecialchars($id); ?>
                                </span>

                            </td>

                            <td>

                                <div class="licence-software-cell">

                                    <div class="licence-software-icon">
                                        <i class="bi bi-disc-fill"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                $nomLogiciel
                                                ?: licenceT(
                                                    'software'
                                                )
                                            ); ?>
                                        </strong>

                                        <small>

                                            <?= htmlspecialchars(
                                                $editeur
                                                ?: licenceT(
                                                    'publisher_undefined'
                                                )
                                            ); ?>

                                            <?php if (!empty($version)): ?>

                                                • v<?= htmlspecialchars(
                                                    $version
                                                ); ?>

                                            <?php endif; ?>

                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="license-key-badge"
                                      title="<?= htmlspecialchars(
                                          $cleLicence
                                      ); ?>">

                                    <i class="bi bi-key-fill"></i>

                                    <?= htmlspecialchars(
                                        $cleAffichee ?: '-'
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="date-badge">

                                    <i class="bi bi-calendar-check"></i>

                                    <?= !empty($dateDebut)
                                        ? date(
                                            'd/m/Y',
                                            strtotime(
                                                $dateDebut
                                            )
                                        )
                                        : '-'; ?>

                                </span>

                            </td>

                            <td>

                                <span class="date-badge">

                                    <i class="bi bi-calendar-x"></i>

                                    <?= !empty($dateFin)
                                        ? date(
                                            'd/m/Y',
                                            strtotime($dateFin)
                                        )
                                        : '-'; ?>

                                </span>

                            </td>

                            <td>

                                <span class="posts-badge">

                                    <i class="bi bi-pc-display"></i>

                                    <?= htmlspecialchars(
                                        licenceT(
                                            'posts_count',
                                            [
                                                'count' =>
                                                    $nombrePostes
                                            ]
                                        )
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="badge <?= $badgeClass; ?>">

                                    <i class="bi <?= $iconStatut; ?>"></i>

                                    <?= htmlspecialchars(
                                        $statutLicence
                                    ); ?>

                                </span>

                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-licence&id=<?= (int)$id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="<?= htmlspecialchars(
                                       licenceT('edit')
                                   ); ?>">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-licence&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(
                                       licenceT('delete')
                                   ); ?>"
                                   onclick="return confirm('<?= htmlspecialchars(
                                       licenceT(
                                           'delete_confirmation'
                                       ),
                                       ENT_QUOTES,
                                       'UTF-8'
                                   ); ?>');">

                                    <i class="bi bi-trash"></i>

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="8"
                            class="text-center py-5 text-muted">

                            <i class="bi bi-key fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(
                                licenceT('no_licence')
                            ); ?>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>