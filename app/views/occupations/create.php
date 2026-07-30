<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

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

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>
                <?= htmlspecialchars(
                    occupationT('create_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    occupationT('create_subtitle')
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

        <form action="<?= BASE_URL ?>?page=enregistrer-occupation-local"
              method="POST">

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

                        <option value="">
                            <?= htmlspecialchars(
                                occupationT('choose_local')
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

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            occupationT('occupation_type')
                        ); ?>
                    </label>

                    <select name="type_occupation"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                occupationT('choose_type')
                            ); ?>
                        </option>

                        <option value="Cours">
                            <?= htmlspecialchars(occupationT('type_course')); ?>
                        </option>

                        <option value="Examen">
                            <?= htmlspecialchars(occupationT('type_exam')); ?>
                        </option>

                        <option value="Réunion">
                            <?= htmlspecialchars(occupationT('type_meeting')); ?>
                        </option>

                        <option value="Événement">
                            <?= htmlspecialchars(occupationT('type_event')); ?>
                        </option>

                        <option value="Maintenance">
                            <?= htmlspecialchars(occupationT('type_maintenance')); ?>
                        </option>

                        <option value="Autre">
                            <?= htmlspecialchars(occupationT('type_other')); ?>
                        </option>

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
                           placeholder="<?= htmlspecialchars(
                               occupationT('reason_placeholder')
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
                           required>

                </div>

            </div>

            <div class="info-form-box mt-4">

                <i class="bi bi-info-circle-fill"></i>

                <div>
                    <?= htmlspecialchars(
                        occupationT('conflict_explanation')
                    ); ?>
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
                        occupationT('save')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>