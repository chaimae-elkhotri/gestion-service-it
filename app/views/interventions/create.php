<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$tickets = $tickets ?? [];
$techniciens = $techniciens ?? [];

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

$jsMessages = [
    'no_local' => interventionT('ticket_without_local'),
    'local_label' => interventionT('local_label'),
    'equipment_label' => interventionT(
        'equipment_label'
    )
];

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    interventionT('create_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    interventionT('create_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=interventions"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                interventionT('back')
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

        <form action="<?= BASE_URL ?>?page=enregistrer-intervention"
              method="POST">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-tools"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            interventionT(
                                'intervention_information'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            interventionT(
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
                            interventionT(
                                'concerned_ticket'
                            )
                        ); ?>
                    </label>

                    <select name="id_ticket"
                            id="ticketSelect"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                interventionT(
                                    'choose_ticket'
                                )
                            ); ?>
                        </option>

                        <?php foreach ($tickets as $ticket): ?>

                            <?php

                            $idTicket =
                                $ticket['id_ticket']
                                ?? $ticket['ID_TICKET']
                                ?? '';

                            $titre =
                                $ticket['titre']
                                ?? $ticket['TITRE']
                                ?? '';

                            $nomLocal =
                                $ticket['nom_local']
                                ?? $ticket['NOM_LOCAL']
                                ?? '';

                            $statutLocal =
                                $ticket['statut_local']
                                ?? $ticket['STATUT_LOCAL']
                                ?? '';

                            $numeroSerie =
                                $ticket['numero_serie']
                                ?? $ticket['NUMERO_SERIE']
                                ?? '';

                            ?>

                            <option
                                value="<?= (int)$idTicket; ?>"
                                data-local="<?= htmlspecialchars(
                                    $nomLocal
                                ); ?>"
                                data-statut-local="<?= htmlspecialchars(
                                    $statutLocal
                                ); ?>"
                                data-equipement="<?= htmlspecialchars(
                                    $numeroSerie
                                ); ?>">

                                #TKT-<?= (int)$idTicket; ?>
                                -
                                <?= htmlspecialchars($titre); ?>

                                <?php if ($nomLocal !== ''): ?>

                                    —
                                    <?= htmlspecialchars($nomLocal); ?>

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <div id="ticketLocalInfo"
                         class="alert alert-light border mt-3 mb-0"
                         style="display: none;">
                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT('technician')
                        ); ?>
                    </label>

                    <select name="id_technicien"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                interventionT(
                                    'choose_technician'
                                )
                            ); ?>
                        </option>

                        <?php foreach (
                            $techniciens as $technicien
                        ): ?>

                            <?php

                            $idTechnicien =
                                $technicien['id_technicien']
                                ?? $technicien['ID_TECHNICIEN']
                                ?? $technicien['id_utilisateur']
                                ?? $technicien['ID_UTILISATEUR']
                                ?? '';

                            $nom =
                                $technicien['nom']
                                ?? $technicien['NOM']
                                ?? '';

                            $prenom =
                                $technicien['prenom']
                                ?? $technicien['PRENOM']
                                ?? '';

                            ?>

                            <option value="<?= (int)$idTechnicien; ?>">

                                <?= htmlspecialchars(
                                    trim($prenom . ' ' . $nom)
                                ); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT(
                                'start_datetime'
                            )
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-calendar-check"></i>

                        <input type="datetime-local"
                               name="date_intervention"
                               class="form-control"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT(
                                'expected_end_datetime'
                            )
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-calendar-x"></i>

                        <input type="datetime-local"
                               name="date_fin_prevue"
                               class="form-control"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT(
                                'indicative_duration'
                            )
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-clock"></i>

                        <input type="text"
                               name="duree"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(
                                   interventionT(
                                       'duration_placeholder'
                                   )
                               ); ?>">

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT('status')
                        ); ?>
                    </label>

                    <select name="statut"
                            class="form-select"
                            required>

                        <option value="En attente" selected>
                            <?= htmlspecialchars(
                                interventionT(
                                    'status_waiting'
                                )
                            ); ?>
                        </option>

                        <option value="En cours">
                            <?= htmlspecialchars(
                                interventionT(
                                    'status_in_progress'
                                )
                            ); ?>
                        </option>

                        <option value="Terminée">
                            <?= htmlspecialchars(
                                interventionT(
                                    'status_completed'
                                )
                            ); ?>
                        </option>

                    </select>

                </div>

            </div>

            <div class="info-form-box mt-4">

                <i class="bi bi-calendar-check-fill"></i>

                <div>
                    <?= htmlspecialchars(
                        interventionT(
                            'availability_check_explanation'
                        )
                    ); ?>
                </div>

            </div>

            <div class="form-section-title mt-5">

                <div class="form-section-icon">
                    <i class="bi bi-stopwatch-fill"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            interventionT(
                                'delays_and_report'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            interventionT(
                                'processing_information_help'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT(
                                'response_time'
                            )
                        ); ?>
                    </label>

                    <input type="text"
                           name="temps_reponse"
                           class="form-control"
                           placeholder="<?= htmlspecialchars(
                               interventionT(
                                   'response_time_placeholder'
                               )
                           ); ?>">

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT(
                                'resolution_time'
                            )
                        ); ?>
                    </label>

                    <input type="text"
                           name="temps_resolution"
                           class="form-control"
                           placeholder="<?= htmlspecialchars(
                               interventionT(
                                   'resolution_time_placeholder'
                               )
                           ); ?>">

                </div>

                <div class="col-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT(
                                'intervention_report'
                            )
                        ); ?>
                    </label>

                    <textarea name="rapport"
                              class="form-control"
                              rows="5"
                              placeholder="<?= htmlspecialchars(
                                  interventionT(
                                      'report_placeholder'
                                  )
                              ); ?>"></textarea>

                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=interventions"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>

                    <?= htmlspecialchars(
                        interventionT('cancel')
                    ); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars(
                        interventionT('save')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ticketSelect =
        document.getElementById('ticketSelect');

    const info =
        document.getElementById('ticketLocalInfo');

    const messages = <?= json_encode(
        $jsMessages,
        JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES
        | JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
    ); ?>;

    if (!ticketSelect || !info) {
        return;
    }

    function escapeHtml(value) {
        const element =
            document.createElement('div');

        element.textContent = value || '';

        return element.innerHTML;
    }

    function afficherLocal() {
        const option =
            ticketSelect.options[
                ticketSelect.selectedIndex
            ];

        if (!option || !option.value) {
            info.style.display = 'none';
            info.innerHTML = '';
            return;
        }

        const local =
            option.dataset.local || '';

        const statut =
            option.dataset.statutLocal || '';

        const equipement =
            option.dataset.equipement || '';

        info.style.display = 'block';

        if (!local) {
            info.className =
                'alert alert-warning border mt-3 mb-0';

            info.innerHTML =
                '<i class="bi bi-exclamation-triangle-fill me-2"></i>'
                + escapeHtml(messages.no_local);

            return;
        }

        if (statut && statut !== 'Actif') {
            info.className =
                'alert alert-danger border mt-3 mb-0';

            info.innerHTML =
                '<i class="bi bi-x-circle-fill me-2"></i>'
                + '<strong>'
                + escapeHtml(messages.local_label)
                + ' </strong>'
                + escapeHtml(local)
                + ' — '
                + escapeHtml(statut);

            return;
        }

        info.className =
            'alert alert-success border mt-3 mb-0';

        info.innerHTML =
            '<i class="bi bi-geo-alt-fill me-2"></i>'
            + '<strong>'
            + escapeHtml(messages.local_label)
            + ' </strong>'
            + escapeHtml(local)
            + (
                equipement
                    ? ' — '
                        + escapeHtml(
                            messages.equipment_label
                        )
                        + ' '
                        + escapeHtml(equipement)
                    : ''
            );
    }

    ticketSelect.addEventListener(
        'change',
        afficherLocal
    );

    afficherLocal();
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>