<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$occupation = $occupation ?? [];
$locals = $locals ?? [];

$idOccupation =
    $occupation['id_occupation']
    ?? $occupation['ID_OCCUPATION']
    ?? 0;

$idLocalSelectionne =
    $occupation['id_local']
    ?? $occupation['ID_LOCAL']
    ?? 0;

$typeOccupation =
    $occupation['type_occupation']
    ?? $occupation['TYPE_OCCUPATION']
    ?? '';

$dateDebut = !empty(
    $occupation['date_debut']
    ?? $occupation['DATE_DEBUT']
    ?? ''
)
    ? date(
        'Y-m-d\TH:i',
        strtotime(
            $occupation['date_debut']
            ?? $occupation['DATE_DEBUT']
        )
    )
    : '';

$dateFin = !empty(
    $occupation['date_fin']
    ?? $occupation['DATE_FIN']
    ?? ''
)
    ? date(
        'Y-m-d\TH:i',
        strtotime(
            $occupation['date_fin']
            ?? $occupation['DATE_FIN']
        )
    )
    : '';

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

$types = [
    'Cours' => occupationT('type_course'),
    'Examen' => occupationT('type_exam'),
    'Réunion' => occupationT('type_meeting'),
    'Événement' => occupationT('type_event'),
    'Maintenance' => occupationT('type_maintenance'),
    'Autre' => occupationT('type_other')
];

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>
                <?= htmlspecialchars(
                    occupationT('edit_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    occupationT('edit_subtitle')
                ); ?>
            </p>
        </div>

        <a href="<?= BASE_URL ?>?page=occupations-locaux"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                occupationT('back')
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

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-occupation-local"
              method="POST">

            <input type="hidden"
                   name="id_occupation"
                   value="<?= (int)$idOccupation; ?>">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            occupationT('local')
                        ); ?>
                    </label>

                    <select name="id_local"
                            class="form-select"
                            required>

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

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            occupationT('occupation_type')
                        ); ?>
                    </label>

                    <select name="type_occupation"
                            class="form-select"
                            required>

                        <?php foreach ($types as $value => $label): ?>

                            <option value="<?= htmlspecialchars($value); ?>"
                                <?= $typeOccupation === $value
                                    ? 'selected'
                                    : ''; ?>>

                                <?= htmlspecialchars($label); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            occupationT('reason')
                        ); ?>
                    </label>

                    <input type="text"
                           name="motif"
                           class="form-control"
                           value="<?= htmlspecialchars(
                               $occupation['motif']
                               ?? $occupation['MOTIF']
                               ?? ''
                           ); ?>"
                           required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            occupationT('start_datetime')
                        ); ?>
                    </label>

                    <input type="datetime-local"
                           name="date_debut"
                           class="form-control"
                           value="<?= htmlspecialchars($dateDebut); ?>"
                           required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            occupationT('end_datetime')
                        ); ?>
                    </label>

                    <input type="datetime-local"
                           name="date_fin"
                           class="form-control"
                           value="<?= htmlspecialchars($dateFin); ?>"
                           required>

                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=occupations-locaux"
                   class="btn btn-light border">

                    <?= htmlspecialchars(
                        occupationT('cancel')
                    ); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars(
                        occupationT('update')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>