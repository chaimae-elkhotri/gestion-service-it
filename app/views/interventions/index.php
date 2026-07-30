<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$interventions = $interventions ?? [];

if (!function_exists('interventionT')) {
    function interventionT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'interventions_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('interventionNormalize')) {
    function interventionNormalize(string $value): string
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

if (!function_exists('interventionStatusLabel')) {
    function interventionStatusLabel(string $value): string
    {
        return match (interventionNormalize($value)) {
            'en attente' => interventionT('status_waiting'),
            'en cours' => interventionT('status_in_progress'),
            'terminee' => interventionT('status_completed'),
            default => $value !== ''
                ? $value
                : interventionT('undefined')
        };
    }
}

$filtreStatut = interventionNormalize(
    (string)($_GET['statut'] ?? '')
);

if ($filtreStatut !== '') {
    $interventions = array_values(
        array_filter(
            $interventions,
            function (array $intervention) use (
                $filtreStatut
            ): bool {
                $statut = interventionNormalize(
                    (string)(
                        $intervention['statut']
                        ?? $intervention['STATUT']
                        ?? ''
                    )
                );

                return $statut === $filtreStatut;
            }
        )
    );
}

$totalInterventions = count($interventions);
$totalEnCours = 0;
$totalTerminees = 0;
$totalEnAttente = 0;

$sommeTempsReponse = 0.0;
$sommeTempsResolution = 0.0;
$countTempsReponse = 0;
$countTempsResolution = 0;

foreach ($interventions as $intervention) {
    $statut = interventionNormalize(
        (string)(
            $intervention['statut']
            ?? $intervention['STATUT']
            ?? ''
        )
    );

    if ($statut === 'en cours') {
        $totalEnCours++;
    } elseif ($statut === 'terminee') {
        $totalTerminees++;
    } elseif ($statut === 'en attente') {
        $totalEnAttente++;
    }

    $tempsReponse =
        $intervention['temps_reponse']
        ?? $intervention['TEMPS_REPONSE']
        ?? null;

    $tempsResolution =
        $intervention['temps_resolution']
        ?? $intervention['TEMPS_RESOLUTION']
        ?? null;

    if (
        $tempsReponse !== null
        && $tempsReponse !== ''
        && is_numeric($tempsReponse)
    ) {
        $sommeTempsReponse += (float)$tempsReponse;
        $countTempsReponse++;
    }

    if (
        $tempsResolution !== null
        && $tempsResolution !== ''
        && is_numeric($tempsResolution)
    ) {
        $sommeTempsResolution +=
            (float)$tempsResolution;

        $countTempsResolution++;
    }
}

$moyenneTempsReponse =
    $countTempsReponse > 0
        ? round(
            $sommeTempsReponse
            / $countTempsReponse,
            1
        )
        : 0;

$moyenneTempsResolution =
    $countTempsResolution > 0
        ? round(
            $sommeTempsResolution
            / $countTempsResolution,
            1
        )
        : 0;

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    interventionT('management_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    interventionT('management_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-intervention"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            <?= htmlspecialchars(
                interventionT('new_intervention')
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
                <i class="bi bi-tools"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        interventionT('total_interventions')
                    ); ?>
                </span>

                <h3><?= $totalInterventions; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        interventionT(
                            'registered_interventions'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon blue">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        interventionT('in_progress')
                    ); ?>
                </span>

                <h3><?= $totalEnCours; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        interventionT('active_processing')
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
                        interventionT('completed_plural')
                    ); ?>
                </span>

                <h3><?= $totalTerminees; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        interventionT(
                            'closed_interventions'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon orange">
                <i class="bi bi-stopwatch-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        interventionT(
                            'average_response_time'
                        )
                    ); ?>
                </span>

                <h3><?= $moyenneTempsReponse; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        interventionT(
                            'average_recorded_delay'
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
                   value="interventions">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT('search')
                        ); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(
                                   interventionT(
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
                            interventionT('status')
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
                                interventionT(
                                    'all_statuses'
                                )
                            ); ?>

                        </option>

                        <option value="en cours"
                            <?= $filtreStatut === 'en cours'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                interventionT(
                                    'status_in_progress'
                                )
                            ); ?>

                        </option>

                        <option value="terminee"
                            <?= $filtreStatut === 'terminee'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                interventionT(
                                    'status_completed'
                                )
                            ); ?>

                        </option>

                        <option value="en attente"
                            <?= $filtreStatut === 'en attente'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                interventionT(
                                    'status_waiting'
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
                            interventionT('search_button')
                        ); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=interventions"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(
                           interventionT('reset')
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
                        interventionT(
                            'intervention_list'
                        )
                    ); ?>
                </h5>

                <small>
                    <?= htmlspecialchars(
                        interventionT(
                            'interventions_found',
                            ['count' => $totalInterventions]
                        )
                    ); ?>
                </small>

            </div>

            <span class="module-chip">

                <i class="bi bi-wrench-adjustable-circle"></i>

                <?= htmlspecialchars(
                    interventionT(
                        'technical_monitoring'
                    )
                ); ?>

            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(interventionT('id')); ?></th>
                    <th><?= htmlspecialchars(interventionT('ticket')); ?></th>
                    <th><?= htmlspecialchars(interventionT('technician')); ?></th>
                    <th><?= htmlspecialchars(interventionT('intervention_date')); ?></th>
                    <th><?= htmlspecialchars(interventionT('status')); ?></th>
                    <th><?= htmlspecialchars(interventionT('response_time')); ?></th>
                    <th><?= htmlspecialchars(interventionT('resolution_time')); ?></th>
                    <th><?= htmlspecialchars(interventionT('report')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(interventionT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($interventions)): ?>

                    <?php foreach (
                        $interventions as $intervention
                    ): ?>

                        <?php

                        $id =
                            $intervention['id_intervention']
                            ?? $intervention['ID_INTERVENTION']
                            ?? '';

                        $idTicket =
                            $intervention['id_ticket']
                            ?? $intervention['ID_TICKET']
                            ?? '';

                        $titreTicket =
                            $intervention['titre_ticket']
                            ?? $intervention['TITRE']
                            ?? interventionT('ticket');

                        $rapport =
                            $intervention['rapport']
                            ?? $intervention['RAPPORT']
                            ?? '';

                        $dateIntervention =
                            $intervention['date_intervention']
                            ?? $intervention['DATE_INTERVENTION']
                            ?? '';

                        $statut =
                            $intervention['statut']
                            ?? $intervention['STATUT']
                            ?? '';

                        $tempsReponse =
                            $intervention['temps_reponse']
                            ?? $intervention['TEMPS_REPONSE']
                            ?? '-';

                        $tempsResolution =
                            $intervention['temps_resolution']
                            ?? $intervention['TEMPS_RESOLUTION']
                            ?? '-';

                        $nomTechnicien =
                            $intervention['nom_technicien']
                            ?? $intervention['NOM']
                            ?? '';

                        $prenomTechnicien =
                            $intervention['prenom_technicien']
                            ?? $intervention['PRENOM']
                            ?? '';

                        $statutLower =
                            interventionNormalize(
                                (string)$statut
                            );

                        $initiales = mb_strtoupper(
                            mb_substr(
                                $prenomTechnicien,
                                0,
                                1,
                                'UTF-8'
                            )
                            .
                            mb_substr(
                                $nomTechnicien,
                                0,
                                1,
                                'UTF-8'
                            ),
                            'UTF-8'
                        );

                        ?>

                        <tr>

                            <td>

                                <span class="table-id">
                                    #INT-<?= htmlspecialchars($id); ?>
                                </span>

                            </td>

                            <td>

                                <div class="intervention-ticket-cell">

                                    <div class="intervention-icon">
                                        <i class="bi bi-ticket-detailed-fill"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            #TKT-<?= htmlspecialchars($idTicket); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars($titreTicket); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <div class="user-cell">

                                    <div class="table-avatar">

                                        <?= htmlspecialchars(
                                            $initiales ?: 'T'
                                        ); ?>

                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                trim(
                                                    $prenomTechnicien
                                                    . ' '
                                                    . $nomTechnicien
                                                )
                                                ?: interventionT(
                                                    'unassigned'
                                                )
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                interventionT(
                                                    'technician'
                                                )
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <?= !empty($dateIntervention)
                                    ? date(
                                        'd/m/Y H:i',
                                        strtotime(
                                            $dateIntervention
                                        )
                                    )
                                    : '-'; ?>

                            </td>

                            <td>

                                <?php if (
                                    $statutLower === 'en cours'
                                ): ?>

                                    <span class="badge intervention-progress">

                                        <i class="bi bi-hourglass-split"></i>

                                        <?= htmlspecialchars(
                                            interventionT(
                                                'status_in_progress'
                                            )
                                        ); ?>

                                    </span>

                                <?php elseif (
                                    $statutLower === 'terminee'
                                ): ?>

                                    <span class="badge intervention-done">

                                        <i class="bi bi-check-circle-fill"></i>

                                        <?= htmlspecialchars(
                                            interventionT(
                                                'status_completed'
                                            )
                                        ); ?>

                                    </span>

                                <?php elseif (
                                    $statutLower === 'en attente'
                                ): ?>

                                    <span class="badge intervention-waiting">

                                        <i class="bi bi-clock-fill"></i>

                                        <?= htmlspecialchars(
                                            interventionT(
                                                'status_waiting'
                                            )
                                        ); ?>

                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">

                                        <?= htmlspecialchars(
                                            interventionStatusLabel(
                                                (string)$statut
                                            )
                                        ); ?>

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <span class="duration-badge">

                                    <i class="bi bi-stopwatch"></i>

                                    <?= htmlspecialchars(
                                        (string)$tempsReponse
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="duration-badge">

                                    <i class="bi bi-clock-history"></i>

                                    <?= htmlspecialchars(
                                        (string)$tempsResolution
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="rapport-badge"
                                      title="<?= htmlspecialchars(
                                          $rapport
                                      ); ?>">

                                    <i class="bi bi-file-earmark-text-fill"></i>

                                    <?= htmlspecialchars(
                                        interventionT('report')
                                    ); ?>

                                </span>

                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-intervention&id=<?= (int)$id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="<?= htmlspecialchars(
                                       interventionT('edit')
                                   ); ?>">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-intervention&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(
                                       interventionT('delete')
                                   ); ?>"
                                   onclick="return confirm('<?= htmlspecialchars(
                                       interventionT(
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

                        <td colspan="9"
                            class="text-center py-5 text-muted">

                            <i class="bi bi-tools fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(
                                interventionT(
                                    'no_intervention'
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