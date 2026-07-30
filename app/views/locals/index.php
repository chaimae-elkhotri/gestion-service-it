<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$locals = $locals ?? [];

if (!function_exists('localT')) {
    function localT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'locals_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('localNormalize')) {
    function localNormalize(string $value): string
    {
        $value = mb_strtolower(trim($value), 'UTF-8');

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

if (!function_exists('localAvailabilityLabel')) {
    function localAvailabilityLabel(string $value): string
    {
        return match (localNormalize($value)) {
            'disponible' => localT('available'),
            'occupe' => localT('occupied'),
            'en maintenance' => localT('maintenance'),
            'indisponible' => localT('unavailable'),
            default => $value
        };
    }
}

if (!function_exists('localTypeLabel')) {
    function localTypeLabel(string $value): string
    {
        return match (localNormalize($value)) {
            'bureau' => localT('type_office'),
            'salle' => localT('type_room'),
            'salle informatique' => localT('type_computer_room'),
            'amphitheatre' => localT('type_amphitheater'),
            'laboratoire' => localT('type_laboratory'),
            'service' => localT('type_service'),
            'autre' => localT('type_other'),
            default => $value
        };
    }
}

$totalLocaux = count($locals);
$totalDisponibles = 0;
$totalOccupes = 0;
$totalHorsService = 0;

foreach ($locals as $local) {
    $disponibilite = localNormalize(
        (string)($local['disponibilite'] ?? '')
    );

    if ($disponibilite === 'disponible') {
        $totalDisponibles++;
    } elseif ($disponibilite === 'occupe') {
        $totalOccupes++;
    } else {
        $totalHorsService++;
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2><?= htmlspecialchars(localT('management_title')); ?></h2>

            <p>
                <?= htmlspecialchars(localT('management_subtitle')); ?>
            </p>
        </div>

        <?php if ((int)($_SESSION['id_role'] ?? 0) === 1): ?>

            <a href="<?= BASE_URL ?>?page=ajouter-local"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                <?= htmlspecialchars(localT('add_local')); ?>

            </a>

        <?php endif; ?>

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
                <i class="bi bi-building-fill"></i>
            </div>

            <div>
                <span><?= htmlspecialchars(localT('total_locals')); ?></span>
                <h3><?= $totalLocaux; ?></h3>
                <small><?= htmlspecialchars(localT('registered_spaces')); ?></small>
            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>
                <span><?= htmlspecialchars(localT('available_plural')); ?></span>
                <h3><?= $totalDisponibles; ?></h3>
                <small><?= htmlspecialchars(localT('currently_free')); ?></small>
            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon orange">
                <i class="bi bi-clock-fill"></i>
            </div>

            <div>
                <span><?= htmlspecialchars(localT('occupied_plural')); ?></span>
                <h3><?= $totalOccupes; ?></h3>
                <small><?= htmlspecialchars(localT('ongoing_courses_events')); ?></small>
            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon blue">
                <i class="bi bi-tools"></i>
            </div>

            <div>
                <span><?= htmlspecialchars(localT('unavailable_plural')); ?></span>
                <h3><?= $totalHorsService; ?></h3>
                <small><?= htmlspecialchars(localT('maintenance_or_closure')); ?></small>
            </div>

        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="locals">

            <div class="row g-3 align-items-end">

                <div class="col-lg-8 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(localT('search')); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(localT('search_placeholder')); ?>"
                               value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">

                    </div>

                </div>

                <div class="col-lg-4 col-md-12 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-fill">

                        <i class="bi bi-search"></i>
                        <?= htmlspecialchars(localT('search_button')); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=locals"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(localT('reset')); ?>">

                        <i class="bi bi-arrow-clockwise"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5><?= htmlspecialchars(localT('local_list')); ?></h5>

                <small>
                    <?= htmlspecialchars(
                        localT(
                            'locals_found',
                            ['count' => $totalLocaux]
                        )
                    ); ?>
                </small>
            </div>

            <a href="<?= BASE_URL ?>?page=occupations-locaux"
               class="btn btn-outline-primary btn-sm">

                <i class="bi bi-calendar3"></i>
                <?= htmlspecialchars(localT('general_schedule')); ?>

            </a>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(localT('id')); ?></th>
                    <th><?= htmlspecialchars(localT('local')); ?></th>
                    <th><?= htmlspecialchars(localT('type')); ?></th>
                    <th><?= htmlspecialchars(localT('availability')); ?></th>
                    <th><?= htmlspecialchars(localT('details')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(localT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($locals)): ?>

                    <?php foreach ($locals as $local): ?>

                        <?php

                        $id =
                            $local['id_local']
                            ?? $local['ID_LOCAL']
                            ?? '';

                        $nomLocal =
                            $local['nom_local']
                            ?? $local['NOM_LOCAL']
                            ?? '';

                        $typeLocal =
                            $local['type_local']
                            ?? $local['TYPE_LOCAL']
                            ?? '';

                        $disponibilite =
                            $local['disponibilite']
                            ?? 'Disponible';

                        $occupationActuelle =
                            $local['occupation_actuelle']
                            ?? '';

                        $dateFinOccupation =
                            $local['date_fin_occupation']
                            ?? '';

                        $disponibiliteLower =
                            localNormalize((string)$disponibilite);

                        if ($disponibiliteLower === 'disponible') {
                            $badgeClass =
                                'bg-success-subtle text-success border border-success-subtle';

                            $iconDisponibilite =
                                'bi-check-circle-fill';
                        } elseif ($disponibiliteLower === 'occupe') {
                            $badgeClass =
                                'bg-warning-subtle text-warning-emphasis border border-warning-subtle';

                            $iconDisponibilite =
                                'bi-clock-fill';
                        } elseif ($disponibiliteLower === 'en maintenance') {
                            $badgeClass =
                                'bg-danger-subtle text-danger border border-danger-subtle';

                            $iconDisponibilite =
                                'bi-tools';
                        } else {
                            $badgeClass =
                                'bg-secondary-subtle text-secondary border';

                            $iconDisponibilite =
                                'bi-slash-circle-fill';
                        }

                        ?>

                        <tr>

                            <td>
                                <span class="table-id">
                                    #LOC-<?= htmlspecialchars($id); ?>
                                </span>
                            </td>

                            <td>

                                <div class="local-cell">

                                    <div class="local-icon">
                                        <i class="bi bi-building-fill"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars($nomLocal); ?>
                                        </strong>

                                        <small>FSJES Oujda</small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="badge bg-light text-dark border">

                                    <?= htmlspecialchars(
                                        $typeLocal
                                            ? localTypeLabel((string)$typeLocal)
                                            : localT('undefined')
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="badge rounded-pill <?= $badgeClass; ?>">

                                    <i class="bi <?= $iconDisponibilite; ?>"></i>

                                    <?= htmlspecialchars(
                                        localAvailabilityLabel(
                                            (string)$disponibilite
                                        )
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <?php if ($disponibiliteLower === 'occupe'): ?>

                                    <strong>
                                        <?= htmlspecialchars(
                                            $occupationActuelle
                                                ?: localT('current_occupation')
                                        ); ?>
                                    </strong>

                                    <?php if (!empty($dateFinOccupation)): ?>

                                        <small class="d-block text-muted">

                                            <?= htmlspecialchars(localT('available_from')); ?>

                                            <?= date(
                                                'd/m/Y H:i',
                                                strtotime($dateFinOccupation)
                                            ); ?>

                                        </small>

                                    <?php endif; ?>

                                <?php elseif ($disponibiliteLower === 'en maintenance'): ?>

                                    <span class="text-danger">
                                        <?= htmlspecialchars(localT('local_in_maintenance')); ?>
                                    </span>

                                <?php elseif ($disponibiliteLower === 'indisponible'): ?>

                                    <span class="text-secondary">
                                        <?= htmlspecialchars(localT('temporarily_unavailable')); ?>
                                    </span>

                                <?php else: ?>

                                    <span class="text-success">
                                        <?= htmlspecialchars(localT('currently_available')); ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=occupations-locaux&id_local=<?= (int)$id; ?>"
                                   class="btn btn-info btn-sm"
                                   title="<?= htmlspecialchars(localT('view_schedule')); ?>">

                                    <i class="bi bi-calendar3"></i>

                                </a>

                                <?php if ((int)($_SESSION['id_role'] ?? 0) === 1): ?>

                                    <a href="<?= BASE_URL ?>?page=ajouter-occupation-local&id_local=<?= (int)$id; ?>"
                                       class="btn btn-success btn-sm"
                                       title="<?= htmlspecialchars(localT('add_occupation')); ?>">

                                        <i class="bi bi-calendar-plus"></i>

                                    </a>

                                    <a href="<?= BASE_URL ?>?page=modifier-local&id=<?= (int)$id; ?>"
                                       class="btn btn-warning btn-sm"
                                       title="<?= htmlspecialchars(localT('edit')); ?>">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>

                                    <a href="<?= BASE_URL ?>?page=supprimer-local&id=<?= (int)$id; ?>"
                                       class="btn btn-danger btn-sm"
                                       title="<?= htmlspecialchars(localT('delete')); ?>"
                                       onclick="return confirm('<?= htmlspecialchars(
                                           localT('delete_confirmation'),
                                           ENT_QUOTES,
                                           'UTF-8'
                                       ); ?>');">

                                        <i class="bi bi-trash"></i>

                                    </a>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="6"
                            class="text-center py-5 text-muted">

                            <i class="bi bi-building fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(localT('no_local')); ?>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>