<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

if (!function_exists('logicielT')) {
    function logicielT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'logiciels_module.' . $key,
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
                    logicielT('create_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    logicielT('create_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=logiciels"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                logicielT('back')
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

        <form action="<?= BASE_URL ?>?page=enregistrer-logiciel"
              method="POST">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-disc-fill"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            logicielT(
                                'software_information'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            logicielT(
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
                            logicielT('software_name')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-disc"></i>

                        <input type="text"
                               name="nom_logiciel"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(
                                   logicielT(
                                       'name_placeholder'
                                   )
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            logicielT('version')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-code-slash"></i>

                        <input type="text"
                               name="version"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(
                                   logicielT(
                                       'version_placeholder'
                                   )
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            logicielT('publisher')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-building"></i>

                        <input type="text"
                               name="editeur"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(
                                   logicielT(
                                       'publisher_placeholder'
                                   )
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            logicielT(
                                'installation_date'
                            )
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-calendar-check"></i>

                        <input type="date"
                               name="date_installation"
                               class="form-control">

                    </div>

                </div>

            </div>

            <div class="form-section-title mt-5">

                <div class="form-section-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            logicielT('note')
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            logicielT('create_note')
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="info-form-box">

                <i class="bi bi-lightbulb-fill"></i>

                <div>

                    <strong>
                        <?= htmlspecialchars(
                            logicielT('examples')
                        ); ?>
                    </strong>

                    <?= htmlspecialchars(
                        logicielT('software_examples')
                    ); ?>

                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=logiciels"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>

                    <?= htmlspecialchars(
                        logicielT('cancel')
                    ); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars(
                        logicielT('save')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>