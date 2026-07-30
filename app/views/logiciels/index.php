<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$logiciels = $logiciels ?? [];

if (!function_exists('logicielT')) {
    function logicielT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'logiciels_module.' . $key,
            $replacements
        );
    }
}

$totalLogiciels = count($logiciels);
$editeurs = [];
$totalMicrosoft = 0;
$totalRecents = 0;

$dateLimite = date(
    'Y-m-d',
    strtotime('-30 days')
);

foreach ($logiciels as $logiciel) {
    $nomLogiciel = mb_strtolower(
        (string)(
            $logiciel['nom_logiciel']
            ?? $logiciel['NOM_LOGICIEL']
            ?? ''
        ),
        'UTF-8'
    );

    $editeur =
        $logiciel['editeur']
        ?? $logiciel['EDITEUR']
        ?? '';

    $dateInstallation =
        $logiciel['date_installation']
        ?? $logiciel['DATE_INSTALLATION']
        ?? '';

    if ($editeur !== '') {
        $editeurs[
            mb_strtolower(
                (string)$editeur,
                'UTF-8'
            )
        ] = true;
    }

    if (
        strpos($nomLogiciel, 'microsoft') !== false
        || mb_strtolower(
            (string)$editeur,
            'UTF-8'
        ) === 'microsoft'
    ) {
        $totalMicrosoft++;
    }

    if (
        $dateInstallation !== ''
        && $dateInstallation >= $dateLimite
    ) {
        $totalRecents++;
    }
}

$totalEditeurs = count($editeurs);

$resultatImport =
    $_SESSION['resultat_import_logiciels']
    ?? null;

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    logicielT('management_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    logicielT('management_subtitle')
                ); ?>
            </p>

        </div>

        <div class="d-flex flex-wrap gap-2">

            <button type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#importLogicielsModal">

                <i class="bi bi-file-earmark-spreadsheet"></i>

                <?= htmlspecialchars(
                    logicielT('import_csv')
                ); ?>

            </button>

            <a href="<?= BASE_URL ?>?page=ajouter-logiciel"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                <?= htmlspecialchars(
                    logicielT('add_software')
                ); ?>

            </a>

        </div>

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

    <?php if ($resultatImport !== null): ?>

        <?php

        $nombreAjoutes =
            (int)($resultatImport['ajoutes'] ?? 0);

        $nombreDoublons =
            (int)($resultatImport['doublons'] ?? 0);

        $erreursImport =
            $resultatImport['erreurs'] ?? [];

        $nombreErreurs = count($erreursImport);

        ?>

        <div class="alert alert-info">

            <h5 class="alert-heading">

                <i class="bi bi-file-earmark-check-fill me-2"></i>

                <?= htmlspecialchars(
                    logicielT('import_result_title')
                ); ?>

            </h5>

            <div class="row g-3 mt-2">

                <div class="col-md-4">

                    <div class="border rounded bg-white p-3">

                        <strong class="text-success fs-4">
                            <?= $nombreAjoutes; ?>
                        </strong>

                        <div>
                            <?= htmlspecialchars(
                                logicielT(
                                    'software_added_count'
                                )
                            ); ?>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="border rounded bg-white p-3">

                        <strong class="text-warning fs-4">
                            <?= $nombreDoublons; ?>
                        </strong>

                        <div>
                            <?= htmlspecialchars(
                                logicielT(
                                    'software_duplicate_count'
                                )
                            ); ?>
                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="border rounded bg-white p-3">

                        <strong class="text-danger fs-4">
                            <?= $nombreErreurs; ?>
                        </strong>

                        <div>
                            <?= htmlspecialchars(
                                logicielT('errors_count')
                            ); ?>
                        </div>

                    </div>

                </div>

            </div>

            <?php if ($nombreErreurs > 0): ?>

                <hr>

                <button type="button"
                        class="btn btn-sm btn-outline-danger"
                        data-bs-toggle="collapse"
                        data-bs-target="#detailsErreursLogiciels">

                    <i class="bi bi-exclamation-triangle"></i>

                    <?= htmlspecialchars(
                        logicielT('show_errors')
                    ); ?>

                </button>

                <div class="collapse mt-3"
                     id="detailsErreursLogiciels">

                    <div class="border rounded bg-white p-3">

                        <ul class="mb-0">

                            <?php foreach (
                                $erreursImport as $erreur
                            ): ?>

                                <li class="mb-1">
                                    <?= htmlspecialchars($erreur); ?>
                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </div>

                </div>

            <?php endif; ?>

        </div>

        <?php
        unset($_SESSION['resultat_import_logiciels']);
        ?>

    <?php endif; ?>

    <div class="module-stats-grid">

        <div class="module-stat-card">

            <div class="module-stat-icon purple">
                <i class="bi bi-disc-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        logicielT('total_software')
                    ); ?>
                </span>

                <h3><?= $totalLogiciels; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        logicielT(
                            'registered_software'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon blue">
                <i class="bi bi-building-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        logicielT('publishers')
                    ); ?>
                </span>

                <h3><?= $totalEditeurs; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        logicielT(
                            'software_providers'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon brown">
                <i class="bi bi-microsoft"></i>
            </div>

            <div>

                <span>Microsoft</span>

                <h3><?= $totalMicrosoft; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        logicielT(
                            'microsoft_software'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon green">
                <i class="bi bi-calendar-check-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        logicielT(
                            'recently_installed'
                        )
                    ); ?>
                </span>

                <h3><?= $totalRecents; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        logicielT('last_30_days')
                    ); ?>
                </small>

            </div>

        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="logiciels">

            <div class="row g-3 align-items-end">

                <div class="col-lg-8 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            logicielT('search')
                        ); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(
                                   logicielT(
                                       'search_placeholder'
                                   )
                               ); ?>"
                               value="<?= htmlspecialchars(
                                   $_GET['search'] ?? ''
                               ); ?>">

                    </div>

                </div>

                <div class="col-lg-4 col-md-12 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-fill">

                        <i class="bi bi-search"></i>

                        <?= htmlspecialchars(
                            logicielT('search_button')
                        ); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=logiciels"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(
                           logicielT('reset')
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
                        logicielT('software_list')
                    ); ?>
                </h5>

                <small>
                    <?= htmlspecialchars(
                        logicielT(
                            'software_found',
                            ['count' => $totalLogiciels]
                        )
                    ); ?>
                </small>

            </div>

            <span class="module-chip">

                <i class="bi bi-disc"></i>

                <?= htmlspecialchars(
                    logicielT(
                        'software_inventory'
                    )
                ); ?>

            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(logicielT('id')); ?></th>
                    <th><?= htmlspecialchars(logicielT('software')); ?></th>
                    <th><?= htmlspecialchars(logicielT('version')); ?></th>
                    <th><?= htmlspecialchars(logicielT('publisher')); ?></th>
                    <th><?= htmlspecialchars(logicielT('installation_date')); ?></th>
                    <th><?= htmlspecialchars(logicielT('status')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(logicielT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($logiciels)): ?>

                    <?php foreach (
                        $logiciels as $logiciel
                    ): ?>

                        <?php

                        $id =
                            $logiciel['id_logiciel']
                            ?? $logiciel['ID_LOGICIEL']
                            ?? '';

                        $nomLogiciel =
                            $logiciel['nom_logiciel']
                            ?? $logiciel['NOM_LOGICIEL']
                            ?? '';

                        $version =
                            $logiciel['version']
                            ?? $logiciel['VERSION']
                            ?? '';

                        $editeur =
                            $logiciel['editeur']
                            ?? $logiciel['EDITEUR']
                            ?? '';

                        $dateInstallation =
                            $logiciel['date_installation']
                            ?? $logiciel['DATE_INSTALLATION']
                            ?? '';

                        $nomLower = mb_strtolower(
                            (string)$nomLogiciel,
                            'UTF-8'
                        );

                        $editeurLower = mb_strtolower(
                            (string)$editeur,
                            'UTF-8'
                        );

                        if (
                            strpos($nomLower, 'office') !== false
                            || strpos($nomLower, 'word') !== false
                            || strpos($nomLower, 'excel') !== false
                        ) {
                            $icon =
                                'bi-file-earmark-word-fill';

                            $badgeClass =
                                'software-type-office';

                            $typeLogiciel =
                                logicielT('type_office');

                        } elseif (
                            strpos(
                                $nomLower,
                                'windows'
                            ) !== false
                        ) {
                            $icon = 'bi-windows';

                            $badgeClass =
                                'software-type-system';

                            $typeLogiciel =
                                logicielT('type_system');

                        } elseif (
                            strpos(
                                $nomLower,
                                'antivirus'
                            ) !== false
                            || strpos(
                                $editeurLower,
                                'avast'
                            ) !== false
                            || strpos(
                                $editeurLower,
                                'eset'
                            ) !== false
                        ) {
                            $icon =
                                'bi-shield-lock-fill';

                            $badgeClass =
                                'software-type-security';

                            $typeLogiciel =
                                logicielT('type_security');

                        } elseif (
                            strpos(
                                $editeurLower,
                                'adobe'
                            ) !== false
                        ) {
                            $icon =
                                'bi-file-earmark-pdf-fill';

                            $badgeClass =
                                'software-type-adobe';

                            $typeLogiciel = 'Adobe';

                        } else {
                            $icon = 'bi-disc-fill';

                            $badgeClass =
                                'software-type-default';

                            $typeLogiciel =
                                logicielT('software');
                        }

                        ?>

                        <tr>

                            <td>

                                <span class="table-id">
                                    #LOG-<?= htmlspecialchars($id); ?>
                                </span>

                            </td>

                            <td>

                                <div class="software-cell">

                                    <div class="software-icon">
                                        <i class="bi <?= $icon; ?>"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                $nomLogiciel
                                                ?: logicielT(
                                                    'software'
                                                )
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                $typeLogiciel
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="version-badge">
                                    <?= htmlspecialchars(
                                        $version ?: '-'
                                    ); ?>
                                </span>

                            </td>

                            <td>

                                <span class="editor-badge">

                                    <i class="bi bi-building"></i>

                                    <?= htmlspecialchars(
                                        $editeur
                                        ?: logicielT(
                                            'undefined'
                                        )
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="date-badge">

                                    <i class="bi bi-calendar-check"></i>

                                    <?= $dateInstallation !== ''
                                        ? date(
                                            'd/m/Y',
                                            strtotime(
                                                $dateInstallation
                                            )
                                        )
                                        : '-'; ?>

                                </span>

                            </td>

                            <td>

                                <span class="badge <?= $badgeClass; ?>">

                                    <i class="bi bi-check-circle-fill"></i>

                                    <?= htmlspecialchars(
                                        logicielT('installed')
                                    ); ?>

                                </span>

                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-logiciel&id=<?= (int)$id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="<?= htmlspecialchars(
                                       logicielT('edit')
                                   ); ?>">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-logiciel&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(
                                       logicielT('delete')
                                   ); ?>"
                                   onclick="return confirm('<?= htmlspecialchars(
                                       logicielT(
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

                        <td colspan="7"
                            class="text-center py-5 text-muted">

                            <i class="bi bi-disc fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(
                                logicielT(
                                    'no_software'
                                )
                            ); ?>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<div class="modal fade"
     id="importLogicielsModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content support-modal-content">

            <div class="modal-header support-modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-file-earmark-spreadsheet"></i>

                    <?= htmlspecialchars(
                        logicielT(
                            'import_multiple_software'
                        )
                    ); ?>

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="<?= htmlspecialchars(
                            logicielT('close')
                        ); ?>">
                </button>

            </div>

            <form action="<?= BASE_URL ?>?page=importer-logiciels"
                  method="POST"
                  enctype="multipart/form-data">

                <div class="modal-body">

                    <div class="alert alert-info">

                        <i class="bi bi-info-circle-fill me-2"></i>

                        <?= htmlspecialchars(
                            logicielT(
                                'csv_selection_help'
                            )
                        ); ?>

                    </div>

                    <h6>
                        <?= htmlspecialchars(
                            logicielT(
                                'file_columns'
                            )
                        ); ?>
                    </h6>

                    <div class="border rounded bg-light p-3 mb-4">

                        <code>
                            nom_logiciel;version;editeur;date_installation
                        </code>

                    </div>

                    <div class="alert alert-light border">

                        <strong>
                            <?= htmlspecialchars(
                                logicielT('example')
                            ); ?>
                        </strong>

                        <br>

                        <code>
                            Microsoft Office;2021;Microsoft;2026-07-28
                        </code>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            <?= htmlspecialchars(
                                logicielT('csv_file')
                            ); ?>
                        </label>

                        <input type="file"
                               name="fichier_csv"
                               class="form-control"
                               accept=".csv,text/csv"
                               required>

                        <small class="text-muted">

                            <?= htmlspecialchars(
                                logicielT(
                                    'csv_constraints'
                                )
                            ); ?>

                        </small>

                    </div>

                    <a href="<?= BASE_URL ?>?page=modele-import-logiciels"
                       class="btn btn-outline-primary">

                        <i class="bi bi-download"></i>

                        <?= htmlspecialchars(
                            logicielT(
                                'download_csv_template'
                            )
                        ); ?>

                    </a>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light border"
                            data-bs-dismiss="modal">

                        <?= htmlspecialchars(
                            logicielT('cancel')
                        ); ?>

                    </button>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="bi bi-upload"></i>

                        <?= htmlspecialchars(
                            logicielT('start_import')
                        ); ?>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>