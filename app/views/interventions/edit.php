<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$intervention = $intervention ?? [];
$tickets = $tickets ?? [];
$techniciens = $techniciens ?? [];

$id =
    $intervention['id_intervention']
    ?? $intervention['ID_INTERVENTION']
    ?? 0;

$idTicketIntervention =
    $intervention['id_ticket']
    ?? $intervention['ID_TICKET']
    ?? 0;

$idTechnicienIntervention =
    $intervention['id_technicien']
    ?? $intervention['ID_TECHNICIEN']
    ?? 0;

$rapport =
    $intervention['rapport']
    ?? $intervention['RAPPORT']
    ?? '';

$duree =
    $intervention['duree']
    ?? $intervention['DUREE']
    ?? '';

$statut =
    $intervention['statut']
    ?? $intervention['STATUT']
    ?? 'En attente';

$tempsReponse =
    $intervention['temps_reponse']
    ?? $intervention['TEMPS_REPONSE']
    ?? '';

$tempsResolution =
    $intervention['temps_resolution']
    ?? $intervention['TEMPS_RESOLUTION']
    ?? '';

$dateIntervention =
    $intervention['date_intervention']
    ?? $intervention['DATE_INTERVENTION']
    ?? '';

$dateFinPrevue =
    $intervention['date_fin_prevue']
    ?? $intervention['DATE_FIN_PREVUE']
    ?? '';

$dateInterventionInput = '';

if (!empty($dateIntervention)) {
    $dateInterventionInput = date(
        'Y-m-d\TH:i',
        strtotime($dateIntervention)
    );
}

$dateFinPrevueInput = '';

if (!empty($dateFinPrevue)) {
    $dateFinPrevueInput = date(
        'Y-m-d\TH:i',
        strtotime($dateFinPrevue)
    );
} elseif ($dateInterventionInput !== '') {
    $dateFinPrevueInput = date(
        'Y-m-d\TH:i',
        strtotime(
            $dateIntervention
            . ' +1 hour'
        )
    );
}

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

$statuts = [
    'En attente' =>
        interventionT('status_waiting'),
    'En cours' =>
        interventionT('status_in_progress'),
    'Terminée' =>
        interventionT('status_completed')
];

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
                    interventionT('edit_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    interventionT('edit_subtitle')
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

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-intervention"
              method="POST">

            <input type="hidden"
                   name="id_intervention"
                   value="<?= (int)$id; ?>">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
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
                                'edit_information_help'
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

                        <?php foreach ($tickets as $ticket): ?>

                            <?php

                            $idTicket =
                                $ticket['id_ticket']
                                ?? $ticket['ID_TICKET']
                                ?? 0;

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
                                ); ?>"
                                <?= (int)$idTicket ===
                                    (int)$idTicketIntervention
                                    ? 'selected'
                                    : ''; ?>>

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
                         class="alert alert-light border mt-3 mb-0">
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

                        <?php foreach (
                            $techniciens as $technicien
                        ): ?>

                            <?php

                            $idTechnicien =
                                $technicien['id_technicien']
                                ?? $technicien['ID_TECHNICIEN']
                                ?? $technicien['id_utilisateur']
                                ?? $technicien['ID_UTILISATEUR']
                                ?? 0;

                            $nom =
                                $technicien['nom']
                                ?? $technicien['NOM']
                                ?? '';

                            $prenom =
                                $technicien['prenom']
                                ?? $technicien['PRENOM']
                                ?? '';

                            ?>

                            <option
                                value="<?= (int)$idTechnicien; ?>"
                                <?= (int)$idTechnicien ===
                                    (int)$idTechnicienIntervention
                                    ? 'selected'
                                    : ''; ?>>

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
                            interventionT('start_datetime')
                        ); ?>
                    </label>

                    <input type="datetime-local"
                           name="date_intervention"
                           class="form-control"
                           value="<?= htmlspecialchars(
                               $dateInterventionInput
                           ); ?>"
                           required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT(
                                'expected_end_datetime'
                            )
                        ); ?>
                    </label>

                    <input type="datetime-local"
                           name="date_fin_prevue"
                           class="form-control"
                           value="<?= htmlspecialchars(
                               $dateFinPrevueInput
                           ); ?>"
                           required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT(
                                'indicative_duration'
                            )
                        ); ?>
                    </label>

                    <input type="text"
                           name="duree"
                           class="form-control"
                           value="<?= htmlspecialchars($duree); ?>">

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

                        <?php foreach (
                            $statuts as $value => $label
                        ): ?>

                            <option value="<?= htmlspecialchars(
                                $value
                            ); ?>"
                                <?= $statut === $value
                                    ? 'selected'
                                    : ''; ?>>

                                <?= htmlspecialchars($label); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT('response_time')
                        ); ?>
                    </label>

                    <input type="text"
                           name="temps_reponse"
                           class="form-control"
                           value="<?= htmlspecialchars(
                               $tempsReponse
                           ); ?>">

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            interventionT('resolution_time')
                        ); ?>
                    </label>

                    <input type="text"
                           name="temps_resolution"
                           class="form-control"
                           value="<?= htmlspecialchars(
                               $tempsResolution
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
                              rows="5"><?= htmlspecialchars(
                                  $rapport
                              ); ?></textarea>

                </div>

            </div>

            <div class="info-form-box mt-4">

                <i class="bi bi-info-circle-fill"></i>

                <div>
                    <?= htmlspecialchars(
                        interventionT(
                            'recheck_availability'
                        )
                    ); ?>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=interventions"
                   class="btn btn-light border">

                    <?= htmlspecialchars(
                        interventionT('cancel')
                    ); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars(
                        interventionT('update')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select =
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

    if (!select || !info) {
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
            select.options[select.selectedIndex];

        if (!option) {
            return;
        }

        const local =
            option.dataset.local || '';

        const statut =
            option.dataset.statutLocal || '';

        const equipement =
            option.dataset.equipement || '';

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

    select.addEventListener(
        'change',
        afficherLocal
    );

    afficherLocal();
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
