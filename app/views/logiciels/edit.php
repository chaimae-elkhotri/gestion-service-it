<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$logiciel = $logiciel ?? [];

$id =
    $logiciel['id_logiciel']
    ?? $logiciel['ID_LOGICIEL']
    ?? '';

$nomLogiciel =
    $logiciel['nom_logiciel']
    ?? $logiciel['NOM_LOGICIEL']
    ?? '';

$version =
    $logiciel['version']
    ?? $logiciel['VERSION']
    ?? '';

$editeur =
    $logiciel['editeur']
    ?? $logiciel['EDITEUR']
    ?? '';

$dateInstallation =
    $logiciel['date_installation']
    ?? $logiciel['DATE_INSTALLATION']
    ?? '';

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
                    logicielT('edit_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    logicielT('edit_subtitle')
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

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-logiciel&id=<?= (int)$id; ?>"
              method="POST">

            <input type="hidden"
                   name="id_logiciel"
                   value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
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
                            logicielT('software_name')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-disc"></i>

                        <input type="text"
                               name="nom_logiciel"
                               class="form-control"
                               value="<?= htmlspecialchars(
                                   $nomLogiciel
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
                               value="<?= htmlspecialchars(
                                   $version
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
                               value="<?= htmlspecialchars(
                                   $editeur
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
                               class="form-control"
                               value="<?= htmlspecialchars(
                                   $dateInstallation
                               ); ?>">

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
                            logicielT('edit_note')
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="info-form-box">

                <i class="bi bi-lightbulb-fill"></i>

                <div>

                    <strong>
                        <?= htmlspecialchars(
                            logicielT('advice')
                        ); ?>
                    </strong>

                    <?= htmlspecialchars(
                        logicielT('edit_advice')
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
                        logicielT('update')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>