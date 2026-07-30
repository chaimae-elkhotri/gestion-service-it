<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

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

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2><?= htmlspecialchars(localT('create_title')); ?></h2>

            <p>
                <?= htmlspecialchars(localT('create_subtitle')); ?>
            </p>
        </div>

        <a href="<?= BASE_URL ?>?page=locals"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>
            <?= htmlspecialchars(localT('back')); ?>

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

        <form action="<?= BASE_URL ?>?page=enregistrer-local"
              method="POST">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-building-fill"></i>
                </div>

                <div>
                    <h5><?= htmlspecialchars(localT('local_information')); ?></h5>

                    <small>
                        <?= htmlspecialchars(localT('create_information_help')); ?>
                    </small>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(localT('local_name')); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-door-open"></i>

                        <input type="text"
                               name="nom_local"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(localT('name_placeholder')); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(localT('local_type')); ?>
                    </label>

                    <select name="type_local"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(localT('choose_type')); ?>
                        </option>

                        <option value="Bureau">
                            <?= htmlspecialchars(localT('type_office')); ?>
                        </option>

                        <option value="Salle">
                            <?= htmlspecialchars(localT('type_room')); ?>
                        </option>

                        <option value="Salle informatique">
                            <?= htmlspecialchars(localT('type_computer_room')); ?>
                        </option>

                        <option value="Amphithéâtre">
                            <?= htmlspecialchars(localT('type_amphitheater')); ?>
                        </option>

                        <option value="Laboratoire">
                            <?= htmlspecialchars(localT('type_laboratory')); ?>
                        </option>

                        <option value="Service">
                            <?= htmlspecialchars(localT('type_service')); ?>
                        </option>

                        <option value="Autre">
                            <?= htmlspecialchars(localT('type_other')); ?>
                        </option>

                    </select>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(localT('general_status')); ?>
                    </label>

                    <select name="statut_general"
                            class="form-select"
                            required>

                        <option value="Actif">
                            <?= htmlspecialchars(localT('status_active')); ?>
                        </option>

                        <option value="Maintenance">
                            <?= htmlspecialchars(localT('status_maintenance')); ?>
                        </option>

                        <option value="Indisponible">
                            <?= htmlspecialchars(localT('status_unavailable')); ?>
                        </option>

                    </select>

                </div>

            </div>

            <div class="info-form-box mt-4">

                <i class="bi bi-info-circle-fill"></i>

                <div>

                    <strong><?= htmlspecialchars(localT('important')); ?></strong>

                    <?= htmlspecialchars(localT('active_status_explanation')); ?>

                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=locals"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>
                    <?= htmlspecialchars(localT('cancel')); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>
                    <?= htmlspecialchars(localT('save')); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>