<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$occupations = $occupations ?? [];
$locals = $locals ?? [];
$idLocalSelectionne =
    $id_local_selectionne ?? 0;

if (!function_exists('occupationT')) {
    function occupationT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'local_occupations_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('occupationNormalize')) {
    function occupationNormalize(string $value): string
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

if (!function_exists('occupationTypeLabel')) {
    function occupationTypeLabel(string $value): string
    {
        return match (occupationNormalize($value)) {
            'cours' => occupationT('type_course'),
            'examen' => occupationT('type_exam'),
            'reunion' => occupationT('type_meeting'),
            'evenement' => occupationT('type_event'),
            'maintenance' => occupationT('type_maintenance'),
            'autre' => occupationT('type_other'),
            default => $value
        };
    }
}

if (!function_exists('occupationStatusLabel')) {
    function occupationStatusLabel(string $value): string
    {
        return match (occupationNormalize($value)) {
            'active', 'actif' => occupationT('status_active'),
            'annulee', 'annule' => occupationT('status_cancelled'),
            default => $value
        };
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>
                <?= htmlspecialchars(
                    occupationT('planning_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    occupationT('planning_subtitle')
                ); ?>
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="<?= BASE_URL ?>?page=locals"
               class="btn btn-light border">

                <i class="bi bi-arrow-left"></i>

                <?= htmlspecialchars(
                    occupationT('locals')
                ); ?>

            </a>

            <?php if (
                (int)($_SESSION['id_role'] ?? 0) === 1
            ): ?>

                <a href="<?= BASE_URL ?>?page=ajouter-occupation-local&id_local=<?= (int)$idLocalSelectionne; ?>"
                   class="btn btn-primary">

                    <i class="bi bi-calendar-plus"></i>

                    <?= htmlspecialchars(
                        occupationT('add_occupation')
                    ); ?>

                </a>

            <?php endif; ?>

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

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="occupations-locaux">

            <div class="row g-3 align-items-end">

                <div class="col-md-8">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            occupationT('local')
                        ); ?>
                    </label>

                    <select name="id_local"
                            class="form-select">

                        <option value="0">
                            <?= htmlspecialchars(
                                occupationT('all_locals')
                            ); ?>
                        </option>

                        <?php foreach ($locals as $local): ?>

                            <?php

                            $idLocal =
                                $local['id_local']
                                ?? $local['ID_LOCAL']
                                ?? 0;

                            $nomLocal =
                                $local['nom_local']
                                ?? $local['NOM_LOCAL']
                                ?? '';

                            ?>

                            <option value="<?= (int)$idLocal; ?>"
                                <?= (int)$idLocalSelectionne ===
                                    (int)$idLocal
                                    ? 'selected'
                                    : ''; ?>>

                                <?= htmlspecialchars($nomLocal); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-4 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-fill">

                        <i class="bi bi-search"></i>

                        <?= htmlspecialchars(
                            occupationT('display')
                        ); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=occupations-locaux"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(
                           occupationT('reset')
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
                        occupationT('registered_occupations')
                    ); ?>
                </h5>

                <small>
                    <?= htmlspecialchars(
                        occupationT(
                            'occupations_count',
                            ['count' => count($occupations)]
                        )
                    ); ?>
                </small>
            </div>

            <span class="module-chip">

                <i class="bi bi-calendar3"></i>

                <?= htmlspecialchars(
                    occupationT('fsjes_schedule')
                ); ?>

            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(occupationT('local')); ?></th>
                    <th><?= htmlspecialchars(occupationT('type')); ?></th>
                    <th><?= htmlspecialchars(occupationT('reason')); ?></th>
                    <th><?= htmlspecialchars(occupationT('start')); ?></th>
                    <th><?= htmlspecialchars(occupationT('end')); ?></th>
                    <th><?= htmlspecialchars(occupationT('status')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(occupationT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($occupations)): ?>

                    <?php foreach (
                        $occupations as $occupation
                    ): ?>

                        <?php

                        $id =
                            $occupation['id_occupation']
                            ?? $occupation['ID_OCCUPATION']
                            ?? 0;

                        $statut =
                            $occupation['statut']
                            ?? $occupation['STATUT']
                            ?? '';

                        $active = in_array(
                            occupationNormalize($statut),
                            ['active', 'actif'],
                            true
                        );

                        ?>

                        <tr>

                            <td>

                                <strong>
                                    <?= htmlspecialchars(
                                        $occupation['nom_local']
                                        ?? $occupation['NOM_LOCAL']
                                        ?? ''
                                    ); ?>
                                </strong>

                                <small class="d-block text-muted">

                                    <?= htmlspecialchars(
                                        $occupation['type_local']
                                        ?? $occupation['TYPE_LOCAL']
                                        ?? ''
                                    ); ?>

                                </small>

                            </td>

                            <td>

                                <span class="badge bg-info-subtle text-info-emphasis border">

                                    <?= htmlspecialchars(
                                        occupationTypeLabel(
                                            (string)(
                                                $occupation['type_occupation']
                                                ?? $occupation['TYPE_OCCUPATION']
                                                ?? ''
                                            )
                                        )
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <?= htmlspecialchars(
                                    $occupation['motif']
                                    ?? $occupation['MOTIF']
                                    ?? ''
                                ); ?>

                            </td>

                            <td>

                                <?= !empty(
                                    $occupation['date_debut']
                                    ?? $occupation['DATE_DEBUT']
                                    ?? ''
                                )
                                    ? date(
                                        'd/m/Y H:i',
                                        strtotime(
                                            $occupation['date_debut']
                                            ?? $occupation['DATE_DEBUT']
                                        )
                                    )
                                    : '-'; ?>

                            </td>

                            <td>

                                <?= !empty(
                                    $occupation['date_fin']
                                    ?? $occupation['DATE_FIN']
                                    ?? ''
                                )
                                    ? date(
                                        'd/m/Y H:i',
                                        strtotime(
                                            $occupation['date_fin']
                                            ?? $occupation['DATE_FIN']
                                        )
                                    )
                                    : '-'; ?>

                            </td>

                            <td>

                                <?php if ($active): ?>

                                    <span class="badge bg-success-subtle text-success border">

                                        <i class="bi bi-check-circle-fill"></i>

                                        <?= htmlspecialchars(
                                            occupationT('status_active')
                                        ); ?>

                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary-subtle text-secondary border">

                                        <i class="bi bi-x-circle-fill"></i>

                                        <?= htmlspecialchars(
                                            occupationStatusLabel(
                                                (string)$statut
                                            )
                                        ); ?>

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="text-center">

                                <?php if (
                                    $active &&
                                    (int)(
                                        $_SESSION['id_role']
                                        ?? 0
                                    ) === 1
                                ): ?>

                                    <a href="<?= BASE_URL ?>?page=modifier-occupation-local&id=<?= (int)$id; ?>"
                                       class="btn btn-warning btn-sm"
                                       title="<?= htmlspecialchars(
                                           occupationT('edit')
                                       ); ?>">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>

                                    <a href="<?= BASE_URL ?>?page=annuler-occupation-local&id=<?= (int)$id; ?>"
                                       class="btn btn-danger btn-sm"
                                       title="<?= htmlspecialchars(
                                           occupationT('cancel_occupation')
                                       ); ?>"
                                       onclick="return confirm('<?= htmlspecialchars(
                                           occupationT(
                                               'cancel_confirmation'
                                           ),
                                           ENT_QUOTES,
                                           'UTF-8'
                                       ); ?>');">

                                        <i class="bi bi-x-circle"></i>

                                    </a>

                                <?php else: ?>

                                    <span class="text-muted">
                                        —
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="7"
                            class="text-center py-5 text-muted">

                            <i class="bi bi-calendar-x fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(
                                occupationT('no_occupation')
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