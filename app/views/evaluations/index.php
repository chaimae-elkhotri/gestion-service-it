<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$evaluations = $evaluations ?? [];

if (!function_exists('evaluationT')) {
    function evaluationT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'evaluations_module.' . $key,
            $replacements
        );
    }
}

$filtreNote = isset($_GET['note'])
    ? (int)$_GET['note']
    : 0;

if ($filtreNote >= 1 && $filtreNote <= 5) {
    $evaluations = array_values(
        array_filter(
            $evaluations,
            function (array $evaluation) use (
                $filtreNote
            ): bool {
                $note = (int)(
                    $evaluation['note']
                    ?? $evaluation['NOTE']
                    ?? 0
                );

                return $note === $filtreNote;
            }
        )
    );
}

$totalEvaluations = count($evaluations);
$sommeNotes = 0;
$totalNotes5 = 0;
$totalNotes4Plus = 0;
$totalCommentaires = 0;

foreach ($evaluations as $evaluation) {
    $note = (int)(
        $evaluation['note']
        ?? $evaluation['NOTE']
        ?? 0
    );

    $commentaire =
        $evaluation['commentaire']
        ?? $evaluation['COMMENTAIRE']
        ?? '';

    $sommeNotes += $note;

    if ($note === 5) {
        $totalNotes5++;
    }

    if ($note >= 4) {
        $totalNotes4Plus++;
    }

    if (!empty($commentaire)) {
        $totalCommentaires++;
    }
}

$moyenneNote =
    $totalEvaluations > 0
        ? round(
            $sommeNotes / $totalEvaluations,
            1
        )
        : 0;

$tauxSatisfaction =
    $totalEvaluations > 0
        ? round(
            (
                $totalNotes4Plus
                / $totalEvaluations
            ) * 100
        )
        : 0;

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    evaluationT('management_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    evaluationT('management_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-evaluation"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>

            <?= htmlspecialchars(
                evaluationT('add_evaluation')
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
                <i class="bi bi-star-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        evaluationT('total_evaluations')
                    ); ?>
                </span>

                <h3><?= $totalEvaluations; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        evaluationT(
                            'registered_evaluations'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon orange">
                <i class="bi bi-star-half"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        evaluationT('average_score')
                    ); ?>
                </span>

                <h3><?= $moyenneNote; ?>/5</h3>

                <small>
                    <?= htmlspecialchars(
                        evaluationT('overall_average')
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon green">
                <i class="bi bi-emoji-smile-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        evaluationT('satisfaction')
                    ); ?>
                </span>

                <h3><?= $tauxSatisfaction; ?>%</h3>

                <small>
                    <?= htmlspecialchars(
                        evaluationT(
                            'four_or_five_stars'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon blue">
                <i class="bi bi-chat-left-text-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        evaluationT('comments')
                    ); ?>
                </span>

                <h3><?= $totalCommentaires; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        evaluationT('user_feedback')
                    ); ?>
                </small>

            </div>

        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="evaluations">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            evaluationT('search')
                        ); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(
                                   evaluationT(
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
                            evaluationT('score')
                        ); ?>
                    </label>

                    <select name="note"
                            class="form-select"
                            onchange="this.form.submit()">

                        <option value=""
                            <?= $filtreNote === 0
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                evaluationT('all_scores')
                            ); ?>

                        </option>

                        <?php for ($score = 5; $score >= 1; $score--): ?>

                            <option value="<?= $score; ?>"
                                <?= $filtreNote === $score
                                    ? 'selected'
                                    : ''; ?>>

                                <?= htmlspecialchars(
                                    evaluationT(
                                        'stars_count',
                                        ['count' => $score]
                                    )
                                ); ?>

                            </option>

                        <?php endfor; ?>

                    </select>

                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-fill">

                        <i class="bi bi-search"></i>

                        <?= htmlspecialchars(
                            evaluationT('search_button')
                        ); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=evaluations"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(
                           evaluationT('reset')
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
                        evaluationT('evaluation_list')
                    ); ?>
                </h5>

                <small>
                    <?= htmlspecialchars(
                        evaluationT(
                            'evaluations_found',
                            ['count' => $totalEvaluations]
                        )
                    ); ?>
                </small>

            </div>

            <span class="module-chip">

                <i class="bi bi-stars"></i>

                <?= htmlspecialchars(
                    evaluationT('user_satisfaction')
                ); ?>

            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(evaluationT('id')); ?></th>
                    <th><?= htmlspecialchars(evaluationT('user')); ?></th>
                    <th><?= htmlspecialchars(evaluationT('intervention')); ?></th>
                    <th><?= htmlspecialchars(evaluationT('technician')); ?></th>
                    <th><?= htmlspecialchars(evaluationT('score')); ?></th>
                    <th><?= htmlspecialchars(evaluationT('comment')); ?></th>
                    <th><?= htmlspecialchars(evaluationT('date')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(evaluationT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($evaluations)): ?>

                    <?php foreach (
                        $evaluations as $evaluation
                    ): ?>

                        <?php

                        $id =
                            $evaluation['id_evaluation']
                            ?? $evaluation['ID_EVALUATION']
                            ?? '';

                        $idIntervention =
                            $evaluation['id_intervention']
                            ?? $evaluation['ID_INTERVENTION']
                            ?? '';

                        $note = (int)(
                            $evaluation['note']
                            ?? $evaluation['NOTE']
                            ?? 0
                        );

                        $commentaire =
                            $evaluation['commentaire']
                            ?? $evaluation['COMMENTAIRE']
                            ?? '';

                        $dateEvaluation =
                            $evaluation['date_evaluation']
                            ?? $evaluation['DATE_EVALUATION']
                            ?? '';

                        $nomUtilisateur =
                            $evaluation['nom_utilisateur']
                            ?? $evaluation['NOM_UTILISATEUR']
                            ?? $evaluation['nom']
                            ?? $evaluation['NOM']
                            ?? '';

                        $prenomUtilisateur =
                            $evaluation['prenom_utilisateur']
                            ?? $evaluation['PRENOM_UTILISATEUR']
                            ?? $evaluation['prenom']
                            ?? $evaluation['PRENOM']
                            ?? '';

                        $nomTechnicien =
                            $evaluation['nom_technicien']
                            ?? $evaluation['NOM_TECHNICIEN']
                            ?? '';

                        $prenomTechnicien =
                            $evaluation['prenom_technicien']
                            ?? $evaluation['PRENOM_TECHNICIEN']
                            ?? '';

                        $titreTicket =
                            $evaluation['titre_ticket']
                            ?? $evaluation['TITRE']
                            ?? evaluationT(
                                'technical_intervention'
                            );

                        $initialesUtilisateur =
                            mb_strtoupper(
                                mb_substr(
                                    $prenomUtilisateur,
                                    0,
                                    1,
                                    'UTF-8'
                                )
                                .
                                mb_substr(
                                    $nomUtilisateur,
                                    0,
                                    1,
                                    'UTF-8'
                                ),
                                'UTF-8'
                            );

                        $initialesTechnicien =
                            mb_strtoupper(
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
                                    #EVA-<?= htmlspecialchars($id); ?>
                                </span>

                            </td>

                            <td>

                                <div class="user-cell">

                                    <div class="table-avatar">
                                        <?= htmlspecialchars(
                                            $initialesUtilisateur
                                            ?: 'U'
                                        ); ?>
                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                trim(
                                                    $prenomUtilisateur
                                                    . ' '
                                                    . $nomUtilisateur
                                                )
                                                ?: evaluationT('user')
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                evaluationT('evaluator')
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <div class="evaluation-intervention-cell">

                                    <div class="evaluation-intervention-icon">
                                        <i class="bi bi-tools"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            #INT-<?= htmlspecialchars(
                                                $idIntervention
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                $titreTicket
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <div class="user-cell">

                                    <div class="table-avatar tech-avatar">
                                        <?= htmlspecialchars(
                                            $initialesTechnicien
                                            ?: 'T'
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
                                                ?: evaluationT(
                                                    'technician'
                                                )
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                evaluationT(
                                                    'it_technician'
                                                )
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <div class="stars-cell">

                                    <?php for (
                                        $i = 1;
                                        $i <= 5;
                                        $i++
                                    ): ?>

                                        <?php if ($i <= $note): ?>

                                            <i class="bi bi-star-fill"></i>

                                        <?php else: ?>

                                            <i class="bi bi-star"></i>

                                        <?php endif; ?>

                                    <?php endfor; ?>

                                    <span><?= $note; ?>/5</span>

                                </div>

                            </td>

                            <td>

                                <span class="comment-badge"
                                      title="<?= htmlspecialchars(
                                          $commentaire
                                      ); ?>">

                                    <i class="bi bi-chat-left-text-fill"></i>

                                    <?= htmlspecialchars(
                                        !empty($commentaire)
                                            ? mb_strimwidth(
                                                $commentaire,
                                                0,
                                                35,
                                                '...',
                                                'UTF-8'
                                            )
                                            : evaluationT(
                                                'no_comment'
                                            )
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="date-badge">

                                    <i class="bi bi-calendar-check"></i>

                                    <?= !empty($dateEvaluation)
                                        ? date(
                                            'd/m/Y',
                                            strtotime(
                                                $dateEvaluation
                                            )
                                        )
                                        : '-'; ?>

                                </span>

                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-evaluation&id=<?= (int)$id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="<?= htmlspecialchars(
                                       evaluationT('edit')
                                   ); ?>">

                                    <i class="bi bi-pencil-square"></i>

                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-evaluation&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(
                                       evaluationT('delete')
                                   ); ?>"
                                   onclick="return confirm('<?= htmlspecialchars(
                                       evaluationT(
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

                            <i class="bi bi-star fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(
                                evaluationT('no_evaluation')
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