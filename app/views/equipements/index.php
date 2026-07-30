<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$equipements = $equipements ?? [];
$categories = $categories ?? [];
$locals = $locals ?? [];

if (!function_exists('equipmentT')) {
    function equipmentT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'equipment_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('equipmentNormalize')) {
    function equipmentNormalize(string $value): string
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

if (!function_exists('equipmentStatusLabel')) {
    function equipmentStatusLabel(string $status): string
    {
        return match (equipmentNormalize($status)) {
            'disponible' => equipmentT('status_available'),
            'affecte' => equipmentT('status_assigned'),
            'maintenance',
            'en maintenance' => equipmentT('status_maintenance'),
            'en panne' => equipmentT('status_broken'),
            default => $status !== ''
                ? $status
                : equipmentT('undefined')
        };
    }
}

$totalEquipements = count($equipements);
$totalDisponibles = 0;
$totalAffectes = 0;
$totalMaintenance = 0;

foreach ($equipements as $equipement) {
    $statut = equipmentNormalize(
        (string)(
            $equipement['statut']
            ?? $equipement['STATUT']
            ?? ''
        )
    );

    if ($statut === 'disponible') {
        $totalDisponibles++;
    } elseif ($statut === 'affecte') {
        $totalAffectes++;
    } elseif (
        $statut === 'maintenance'
        || $statut === 'en maintenance'
    ) {
        $totalMaintenance++;
    }
}

$resultatImport =
    $_SESSION['resultat_import_equipements']
    ?? null;

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    equipmentT('management_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    equipmentT('management_subtitle')
                ); ?>
            </p>

        </div>

        <div class="d-flex flex-wrap gap-2">

            <button type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#importEquipementsModal">

                <i class="bi bi-file-earmark-spreadsheet"></i>

                <?= htmlspecialchars(
                    equipmentT('import_csv')
                ); ?>

            </button>

            <a href="<?= BASE_URL ?>?page=ajouter-equipement"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                <?= htmlspecialchars(
                    equipmentT('add_equipment')
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

        $nombreErreurs =
            count($erreursImport);

        ?>

        <div class="alert alert-info">

            <h5 class="alert-heading">

                <i class="bi bi-file-earmark-check-fill me-2"></i>

                <?= htmlspecialchars(
                    equipmentT('import_result_title')
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
                                equipmentT('equipment_added_count')
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
                                equipmentT('existing_serial_count')
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
                                equipmentT('error_count')
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
                        data-bs-target="#detailsErreursEquipements">

                    <i class="bi bi-exclamation-triangle"></i>

                    <?= htmlspecialchars(
                        equipmentT('view_errors')
                    ); ?>

                </button>

                <div class="collapse mt-3"
                     id="detailsErreursEquipements">

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
        unset(
            $_SESSION['resultat_import_equipements']
        );
        ?>

    <?php endif; ?>

    <div class="module-stats-grid">

        <div class="module-stat-card">

            <div class="module-stat-icon brown">
                <i class="bi bi-pc-display"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        equipmentT('total_equipment')
                    ); ?>
                </span>

                <h3><?= $totalEquipements; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        equipmentT('it_assets')
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
                        equipmentT('available')
                    ); ?>
                </span>

                <h3><?= $totalDisponibles; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        equipmentT('ready_to_assign')
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon blue">
                <i class="bi bi-person-check-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        equipmentT('assigned')
                    ); ?>
                </span>

                <h3><?= $totalAffectes; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        equipmentT('currently_used')
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon orange">
                <i class="bi bi-tools"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        equipmentT('maintenance')
                    ); ?>
                </span>

                <h3><?= $totalMaintenance; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        equipmentT('equipment_to_monitor')
                    ); ?>
                </small>

            </div>

        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="equipements">

            <div class="row g-3 align-items-end">

                <div class="col-lg-8 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            equipmentT('search')
                        ); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(
                                   equipmentT('search_placeholder')
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
                            equipmentT('search_button')
                        ); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=equipements"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(
                           equipmentT('reset')
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
                        equipmentT('equipment_list')
                    ); ?>
                </h5>

                <small>
                    <?= htmlspecialchars(
                        equipmentT(
                            'equipment_found',
                            ['count' => $totalEquipements]
                        )
                    ); ?>
                </small>

            </div>

            <span class="module-chip">

                <i class="bi bi-hdd-stack"></i>

                <?= htmlspecialchars(
                    equipmentT('fsjes_assets')
                ); ?>

            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(equipmentT('id')); ?></th>
                    <th><?= htmlspecialchars(equipmentT('serial_number_short')); ?></th>
                    <th><?= htmlspecialchars(equipmentT('equipment')); ?></th>
                    <th><?= htmlspecialchars(equipmentT('category')); ?></th>
                    <th><?= htmlspecialchars(equipmentT('local')); ?></th>
                    <th><?= htmlspecialchars(equipmentT('purchase_date')); ?></th>
                    <th><?= htmlspecialchars(equipmentT('status')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(equipmentT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($equipements)): ?>

                    <?php foreach (
                        $equipements as $equipement
                    ): ?>

                        <?php

                        $id =
                            $equipement['id_equipement']
                            ?? $equipement['ID_EQUIPEMENT_']
                            ?? '';

                        $numeroSerie =
                            $equipement['numero_serie']
                            ?? $equipement['NUMERO_SERIE']
                            ?? '';

                        $marque =
                            $equipement['marque']
                            ?? $equipement['MARQUE']
                            ?? '';

                        $modele =
                            $equipement['modele']
                            ?? $equipement['MODELE']
                            ?? '';

                        $dateAchat =
                            $equipement['date_achat']
                            ?? $equipement['DATE_ACHAT']
                            ?? '';

                        $statut =
                            $equipement['statut']
                            ?? $equipement['STATUT']
                            ?? '';

                        $categorie =
                            $equipement['nom_categorie']
                            ?? $equipement['NOM_CATEGORIE']
                            ?? '';

                        $local =
                            $equipement['nom_local']
                            ?? $equipement['NOM_LOCAL']
                            ?? '';

                        $statutLower =
                            equipmentNormalize(
                                (string)$statut
                            );

                        ?>

                        <tr>

                            <td>
                                <span class="table-id">
                                    #EQP-<?= htmlspecialchars($id); ?>
                                </span>
                            </td>

                            <td>
                                <span class="serial-badge">
                                    <?= htmlspecialchars($numeroSerie); ?>
                                </span>
                            </td>

                            <td>

                                <div class="equipment-cell">

                                    <div class="equipment-icon">
                                        <i class="bi bi-pc-display"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                trim(
                                                    $marque .
                                                    ' ' .
                                                    $modele
                                                )
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                equipmentT(
                                                    'it_hardware'
                                                )
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="badge category-badge">

                                    <?= htmlspecialchars(
                                        $categorie
                                        ?: equipmentT('undefined')
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="local-badge">

                                    <i class="bi bi-geo-alt-fill"></i>

                                    <?= htmlspecialchars(
                                        $local
                                        ?: equipmentT('undefined')
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <?= !empty($dateAchat)
                                    ? date(
                                        'd/m/Y',
                                        strtotime($dateAchat)
                                    )
                                    : '-'; ?>

                            </td>

                            <td>

                                <?php if (
                                    $statutLower === 'disponible'
                                ): ?>

                                    <span class="badge bg-success">

                                        <i class="bi bi-check-circle-fill"></i>

                                        <?= htmlspecialchars(
                                            equipmentT(
                                                'status_available'
                                            )
                                        ); ?>

                                    </span>

                                <?php elseif (
                                    $statutLower === 'affecte'
                                ): ?>

                                    <span class="badge bg-primary">

                                        <i class="bi bi-person-check-fill"></i>

                                        <?= htmlspecialchars(
                                            equipmentT(
                                                'status_assigned'
                                            )
                                        ); ?>

                                    </span>

                                <?php elseif (
                                    $statutLower === 'maintenance'
                                    || $statutLower === 'en maintenance'
                                ): ?>

                                    <span class="badge bg-warning">

                                        <i class="bi bi-tools"></i>

                                        <?= htmlspecialchars(
                                            equipmentT(
                                                'status_maintenance'
                                            )
                                        ); ?>

                                    </span>

                                <?php elseif (
                                    $statutLower === 'en panne'
                                ): ?>

                                    <span class="badge bg-danger">

                                        <i class="bi bi-exclamation-triangle-fill"></i>

                                        <?= htmlspecialchars(
                                            equipmentT(
                                                'status_broken'
                                            )
                                        ); ?>

                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">

                                        <?= htmlspecialchars(
                                            equipmentStatusLabel(
                                                (string)$statut
                                            )
                                        ); ?>

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-equipement&id=<?= (int)$id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="<?= htmlspecialchars(
                                       equipmentT('edit')
                                   ); ?>">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-equipement&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(
                                       equipmentT('delete')
                                   ); ?>"
                                   onclick="return confirm('<?= htmlspecialchars(
                                       equipmentT(
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

                            <i class="bi bi-pc-display fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(
                                equipmentT('no_equipment')
                            ); ?>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<style>
    .simple-import-modal .modal-content {
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 22px 60px rgba(67, 40, 23, 0.22);
    }

    .simple-import-modal .modal-header {
        padding: 1rem 1.25rem;
        color: #fff;
        border: 0;
        background: #7b4727;
    }

    .simple-import-modal .modal-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin: 0;
        color: #fff;
        font-weight: 800;
    }

    .simple-import-modal .modal-header .btn-close {
        margin: 0;
        filter: brightness(0) invert(1);
        opacity: 0.9;
    }

    .simple-import-modal .modal-body {
        padding: 1.35rem;
    }

    .simple-import-format {
        margin-bottom: 1rem;
        padding: 0.85rem 1rem;
        border: 1px solid #eadfd7;
        border-radius: 12px;
        background: #faf7f4;
    }

    .simple-import-format small {
        display: block;
        margin-bottom: 0.4rem;
        color: #6b7280;
        font-weight: 700;
    }

    .simple-import-format code {
        color: #a12d62;
        font-size: 0.8rem;
        overflow-wrap: anywhere;
        white-space: normal;
    }

    .simple-import-file {
        padding: 1rem;
        border: 2px dashed #d9c6b8;
        border-radius: 14px;
        text-align: center;
        background: #fffdfb;
    }

    .simple-import-file > i {
        display: block;
        margin-bottom: 0.55rem;
        color: #198754;
        font-size: 2rem;
    }

    .simple-import-file label {
        display: block;
        margin-bottom: 0.65rem;
        color: #243247;
        font-weight: 800;
    }

    .simple-import-file .form-control {
        max-width: 560px;
        margin-inline: auto;
        border-radius: 10px;
    }

    .simple-import-file small {
        display: block;
        margin-top: 0.55rem;
        color: #7b8493;
    }

    .simple-import-template {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-top: 0.9rem;
        font-weight: 700;
    }

    .simple-import-modal .modal-footer {
        gap: 0.6rem;
        padding: 0.85rem 1.25rem;
        border-top: 1px solid #eee7e2;
        background: #fbfaf9;
    }

    .simple-import-modal .modal-footer .btn {
        min-height: 42px;
        border-radius: 10px;
        font-weight: 700;
    }

    @media (max-width: 575.98px) {
        .simple-import-modal .modal-dialog {
            margin: 0.75rem;
        }

        .simple-import-modal .modal-footer {
            flex-direction: column-reverse;
        }

        .simple-import-modal .modal-footer .btn {
            width: 100%;
        }
    }
</style>


<div class="modal fade simple-import-modal"
     id="importEquipementsModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    <?= htmlspecialchars(
                        equipmentT('import_multiple_equipment')
                    ); ?>
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <form action="<?= BASE_URL ?>?page=importer-equipements"
                  method="POST"
                  enctype="multipart/form-data">

                <div class="modal-body">

                    <div class="simple-import-format">

                        <small>
                            <?= htmlspecialchars(
                                equipmentT('required_columns')
                            ); ?>
                        </small>

                        <code>
                            numero_serie;marque;modele;date_achat;statut;id_categorie;id_local
                        </code>

                    </div>

                    <div class="simple-import-file">

                        <i class="bi bi-cloud-arrow-up-fill"></i>

                        <label for="equipmentCsvFile">
                            <?= htmlspecialchars(
                                equipmentT('csv_file')
                            ); ?>
                        </label>

                        <input type="file"
                               id="equipmentCsvFile"
                               name="fichier_csv"
                               class="form-control"
                               accept=".csv,text/csv"
                               required>

                        <small>
                            <?= htmlspecialchars(
                                equipmentT('csv_file_help')
                            ); ?>
                        </small>

                        <a href="<?= BASE_URL ?>?page=modele-import-equipements"
                           class="btn btn-outline-primary simple-import-template">

                            <i class="bi bi-download"></i>

                            <?= htmlspecialchars(
                                equipmentT('download_csv_template')
                            ); ?>

                        </a>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light border"
                            data-bs-dismiss="modal">
                        <?= htmlspecialchars(
                            equipmentT('cancel')
                        ); ?>
                    </button>

                    <button type="submit"
                            class="btn btn-success">
                        <i class="bi bi-upload"></i>
                        <?= htmlspecialchars(
                            equipmentT('start_import')
                        ); ?>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<?php require_once '../app/views/layouts/footer.php'; ?>