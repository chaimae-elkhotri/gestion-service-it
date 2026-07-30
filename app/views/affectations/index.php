<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$affectations = $affectations ?? [];

if (!function_exists('affectationT')) {
    function affectationT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'affectations_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('affectationNormalize')) {
    function affectationNormalize(string $value): string
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

$filtreStatut = affectationNormalize(
    (string)($_GET['statut'] ?? '')
);

if ($filtreStatut !== '') {
    $affectations = array_values(
        array_filter(
            $affectations,
            function (array $a) use (
                $filtreStatut,
                $today,
                $dateDans30Jours
            ): bool {
                $dateFin =
                    $a['date_fin_affectation']
                    ?? $a['DATE_FIN_AFFECTATION']
                    ?? '';

                if (empty($dateFin)) {
                    $statutCalcule = 'active';
                } elseif ($dateFin < $today) {
                    $statutCalcule = 'terminee';
                } elseif (
                    $dateFin <= $dateDans30Jours
                ) {
                    $statutCalcule = 'retour prevu';
                } else {
                    $statutCalcule = 'active';
                }

                return $statutCalcule ===
                    $filtreStatut;
            }
        )
    );
}

$totalAffectations = count($affectations);
$totalActives = 0;
$totalTerminees = 0;
$totalRetoursPrevus = 0;
$totalSansDateFin = 0;

foreach ($affectations as $a) {
    $dateFin =
        $a['date_fin_affectation']
        ?? $a['DATE_FIN_AFFECTATION']
        ?? null;

    if (empty($dateFin)) {
        $totalActives++;
        $totalSansDateFin++;
    } elseif ($dateFin >= $today) {
        $totalActives++;

        if ($dateFin <= $dateDans30Jours) {
            $totalRetoursPrevus++;
        }
    } else {
        $totalTerminees++;
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    affectationT('management_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    affectationT('management_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-affectation"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            <?= htmlspecialchars(
                affectationT('new_assignment')
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
                <i class="bi bi-arrow-left-right"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        affectationT(
                            'total_assignments'
                        )
                    ); ?>
                </span>

                <h3><?= $totalAffectations; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        affectationT(
                            'registered_assignments'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon green">
                <i class="bi bi-person-check-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        affectationT(
                            'active_assignments'
                        )
                    ); ?>
                </span>

                <h3><?= $totalActives; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        affectationT('used_equipment')
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
                        affectationT(
                            'expected_returns'
                        )
                    ); ?>
                </span>

                <h3><?= $totalRetoursPrevus; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        affectationT(
                            'within_30_days'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon blue">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        affectationT(
                            'completed_assignments'
                        )
                    ); ?>
                </span>

                <h3><?= $totalTerminees; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        affectationT(
                            'returned_equipment'
                        )
                    ); ?>
                </small>

            </div>

        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="affectations">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            affectationT('search')
                        ); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(
                                   affectationT(
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
                            affectationT('status')
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
                                affectationT(
                                    'all_statuses'
                                )
                            ); ?>

                        </option>

                        <option value="active"
                            <?= $filtreStatut === 'active'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                affectationT(
                                    'status_active'
                                )
                            ); ?>

                        </option>

                        <option value="retour prevu"
                            <?= $filtreStatut === 'retour prevu'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                affectationT(
                                    'status_return_expected'
                                )
                            ); ?>

                        </option>

                        <option value="terminee"
                            <?= $filtreStatut === 'terminee'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                affectationT(
                                    'status_completed'
                                )
                            ); ?>

                        </option>

                    </select>

                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-fill">

                        <i class="bi bi-search"></i>

                        <?= htmlspecialchars(
                            affectationT(
                                'search_button'
                            )
                        ); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=affectations"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(
                           affectationT('reset')
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
                        affectationT(
                            'assignment_list'
                        )
                    ); ?>
                </h5>

                <small>
                    <?= htmlspecialchars(
                        affectationT(
                            'assignments_found',
                            ['count' => $totalAffectations]
                        )
                    ); ?>
                </small>

            </div>

            <span class="module-chip">

                <i class="bi bi-pc-display-horizontal"></i>

                <?= htmlspecialchars(
                    affectationT(
                        'equipment_assignment'
                    )
                ); ?>

            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(affectationT('id')); ?></th>
                    <th><?= htmlspecialchars(affectationT('user')); ?></th>
                    <th><?= htmlspecialchars(affectationT('equipment')); ?></th>
                    <th><?= htmlspecialchars(affectationT('assignment_date')); ?></th>
                    <th><?= htmlspecialchars(affectationT('end_date')); ?></th>
                    <th><?= htmlspecialchars(affectationT('status')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(affectationT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($affectations)): ?>

                    <?php foreach (
                        $affectations as $a
                    ): ?>

                        <?php

                        $id =
                            $a['id_affectation']
                            ?? $a['ID_AFFECTATION_EQUIP']
                            ?? '';

                        $idUtilisateur =
                            $a['id_utilisateur']
                            ?? $a['ID_UTILISATEUR']
                            ?? '';

                        $nom =
                            $a['nom']
                            ?? $a['NOM']
                            ?? '';

                        $prenom =
                            $a['prenom']
                            ?? $a['PRENOM']
                            ?? '';

                        $numeroSerie =
                            $a['numero_serie']
                            ?? $a['NUMERO_SERIE']
                            ?? '';

                        $marque =
                            $a['marque']
                            ?? $a['MARQUE']
                            ?? '';

                        $modele =
                            $a['modele']
                            ?? $a['MODELE']
                            ?? '';

                        $dateAffectation =
                            $a['date_affectation']
                            ?? $a['DATE_AFFECTATION']
                            ?? '';

                        $dateFin =
                            $a['date_fin_affectation']
                            ?? $a['DATE_FIN_AFFECTATION']
                            ?? '';

                        $initiales = mb_strtoupper(
                            mb_substr(
                                $prenom,
                                0,
                                1,
                                'UTF-8'
                            )
                            .
                            mb_substr(
                                $nom,
                                0,
                                1,
                                'UTF-8'
                            ),
                            'UTF-8'
                        );

                        if (empty($dateFin)) {
                            $statutAffectation =
                                affectationT(
                                    'status_active'
                                );

                            $classStatut =
                                'affectation-active';

                            $iconStatut =
                                'bi-check-circle-fill';

                        } elseif ($dateFin < $today) {
                            $statutAffectation =
                                affectationT(
                                    'status_completed'
                                );

                            $classStatut =
                                'affectation-done';

                            $iconStatut =
                                'bi-check2-circle';

                        } elseif (
                            $dateFin <= $dateDans30Jours
                        ) {
                            $statutAffectation =
                                affectationT(
                                    'status_return_expected'
                                );

                            $classStatut =
                                'affectation-return';

                            $iconStatut =
                                'bi-calendar-event-fill';

                        } else {
                            $statutAffectation =
                                affectationT(
                                    'status_active'
                                );

                            $classStatut =
                                'affectation-active';

                            $iconStatut =
                                'bi-check-circle-fill';
                        }

                        ?>

                        <tr>

                            <td>

                                <span class="table-id">
                                    #AFF-<?= htmlspecialchars($id); ?>
                                </span>

                            </td>

                            <td>

                                <div class="user-cell">

                                    <div class="table-avatar">

                                        <?= htmlspecialchars(
                                            $initiales ?: 'U'
                                        ); ?>

                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                trim(
                                                    $prenom
                                                    . ' '
                                                    . $nom
                                                )
                                                ?: affectationT(
                                                    'user'
                                                )
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                affectationT(
                                                    'user_id',
                                                    [
                                                        'id' =>
                                                            $idUtilisateur
                                                    ]
                                                )
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <div class="affectation-equipment-cell">

                                    <div class="affectation-equipment-icon">

                                        <i class="bi bi-pc-display"></i>

                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                trim(
                                                    $marque
                                                    . ' '
                                                    . $modele
                                                )
                                                ?: affectationT(
                                                    'equipment'
                                                )
                                            ); ?>
                                        </strong>

                                        <small>

                                            <?= htmlspecialchars(
                                                affectationT(
                                                    'serial_number'
                                                )
                                            ); ?>

                                            :

                                            <?= htmlspecialchars(
                                                $numeroSerie
                                                ?: affectationT(
                                                    'undefined_feminine'
                                                )
                                            ); ?>

                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="date-badge">

                                    <i class="bi bi-calendar-check"></i>

                                    <?= !empty($dateAffectation)
                                        ? date(
                                            'd/m/Y',
                                            strtotime(
                                                $dateAffectation
                                            )
                                        )
                                        : '-'; ?>

                                </span>

                            </td>

                            <td>

                                <?php if (!empty($dateFin)): ?>

                                    <span class="date-badge">

                                        <i class="bi bi-calendar-x"></i>

                                        <?= date(
                                            'd/m/Y',
                                            strtotime($dateFin)
                                        ); ?>

                                    </span>

                                <?php else: ?>

                                    <span class="no-date-badge">

                                        <i class="bi bi-dash-circle"></i>

                                        <?= htmlspecialchars(
                                            affectationT(
                                                'undefined_feminine'
                                            )
                                        ); ?>

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <span class="badge <?= $classStatut; ?>">

                                    <i class="bi <?= $iconStatut; ?>"></i>

                                    <?= htmlspecialchars(
                                        $statutAffectation
                                    ); ?>

                                </span>

                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-affectation&id=<?= (int)$id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="<?= htmlspecialchars(
                                       affectationT('edit')
                                   ); ?>">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-affectation&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(
                                       affectationT('delete')
                                   ); ?>"
                                   onclick="return confirm('<?= htmlspecialchars(
                                       affectationT(
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

                            <i class="bi bi-arrow-left-right fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(
                                affectationT(
                                    'no_assignment'
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

<?php require_once '../app/views/layouts/footer.php'; ?>