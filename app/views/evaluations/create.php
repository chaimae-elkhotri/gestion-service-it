<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$utilisateurs = $utilisateurs ?? [];
$interventions = $interventions ?? [];

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

$notes = [
    5 => evaluationT('score_excellent'),
    4 => evaluationT('score_very_good'),
    3 => evaluationT('score_average'),
    2 => evaluationT('score_weak'),
    1 => evaluationT('score_bad')
];

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    evaluationT('create_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    evaluationT('create_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=evaluations"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                evaluationT('back')
            ); ?>

        </a>

    </div>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <?= htmlspecialchars($_SESSION['error']); ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-evaluation"
              method="POST">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-star-fill"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            evaluationT(
                                'evaluation_information'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            evaluationT(
                                'create_information_help'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            evaluationT('user')
                        ); ?>
                    </label>

                    <select name="id_utilisateur"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                evaluationT('choose_user')
                            ); ?>
                        </option>

                        <?php foreach (
                            $utilisateurs as $user
                        ): ?>

                            <?php

                            $idUtilisateur =
                                $user['id_utilisateur']
                                ?? $user['ID_UTILISATEUR']
                                ?? '';

                            $nom =
                                $user['nom']
                                ?? $user['NOM']
                                ?? '';

                            $prenom =
                                $user['prenom']
                                ?? $user['PRENOM']
                                ?? '';

                            ?>

                            <option value="<?= htmlspecialchars(
                                $idUtilisateur
                            ); ?>">

                                <?= htmlspecialchars(
                                    trim(
                                        $prenom
                                        . ' '
                                        . $nom
                                    )
                                ); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            evaluationT('intervention')
                        ); ?>
                    </label>

                    <select name="id_intervention"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                evaluationT(
                                    'choose_intervention'
                                )
                            ); ?>
                        </option>

                        <?php foreach (
                            $interventions as $intervention
                        ): ?>

                            <?php

                            $idIntervention =
                                $intervention['id_intervention']
                                ?? $intervention['ID_INTERVENTION']
                                ?? '';

                            $idTicket =
                                $intervention['id_ticket']
                                ?? $intervention['ID_TICKET']
                                ?? '';

                            $rapport =
                                $intervention['rapport']
                                ?? $intervention['RAPPORT']
                                ?? '';

                            ?>

                            <option value="<?= htmlspecialchars(
                                $idIntervention
                            ); ?>">

                                #INT-<?= htmlspecialchars(
                                    $idIntervention
                                ); ?>

                                <?php if (!empty($idTicket)): ?>

                                    -
                                    <?= htmlspecialchars(
                                        evaluationT(
                                            'ticket_number',
                                            ['id' => $idTicket]
                                        )
                                    ); ?>

                                <?php endif; ?>

                                <?php if (!empty($rapport)): ?>

                                    -
                                    <?= htmlspecialchars(
                                        mb_strimwidth(
                                            $rapport,
                                            0,
                                            35,
                                            '...',
                                            'UTF-8'
                                        )
                                    ); ?>

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            evaluationT('score')
                        ); ?>
                    </label>

                    <select name="note"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                evaluationT('choose_score')
                            ); ?>
                        </option>

                        <?php foreach (
                            $notes as $value => $label
                        ): ?>

                            <option value="<?= $value; ?>">

                                <?= str_repeat('★', $value); ?>
                                <?= str_repeat('☆', 5 - $value); ?>
                                -
                                <?= htmlspecialchars($label); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            evaluationT('evaluation_date')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-calendar-check"></i>

                        <input type="date"
                               name="date_evaluation"
                               class="form-control"
                               value="<?= date('Y-m-d'); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            evaluationT('comment')
                        ); ?>
                    </label>

                    <div class="textarea-with-icon">

                        <i class="bi bi-chat-left-text"></i>

                        <textarea name="commentaire"
                                  class="form-control"
                                  rows="5"
                                  placeholder="<?= htmlspecialchars(
                                      evaluationT(
                                          'comment_placeholder'
                                      )
                                  ); ?>"></textarea>

                    </div>

                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=evaluations"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>

                    <?= htmlspecialchars(
                        evaluationT('cancel')
                    ); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars(
                        evaluationT('save')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>