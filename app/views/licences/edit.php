<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$licence = $licence ?? [];
$logiciels = $logiciels ?? [];

$id =
    $licence['id_licence']
    ?? $licence['ID_LICENCE']
    ?? '';

$idLogicielLicence =
    $licence['id_logiciel']
    ?? $licence['ID_LOGICIEL']
    ?? '';

$cleLicence =
    $licence['cle_licence']
    ?? $licence['CLE_LICENCE']
    ?? '';

$dateDebut =
    $licence['date_debut']
    ?? $licence['DATE_DEBUT']
    ?? '';

$dateFin =
    $licence['date_fin']
    ?? $licence['DATE_FIN']
    ?? '';

$nombrePostes =
    $licence['nombre_postes']
    ?? $licence['NOMBRE_POSTES']
    ?? '';

if (!function_exists('licenceT')) {
    function licenceT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'licences_module.' . $key,
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
                    licenceT('edit_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    licenceT('edit_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=licences"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                licenceT('back')
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

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-licence&id=<?= (int)$id; ?>"
              method="POST">

            <input type="hidden"
                   name="id_licence"
                   value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            licenceT(
                                'licence_information'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            licenceT(
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
                            licenceT('software')
                        ); ?>
                    </label>

                    <select name="id_logiciel"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                licenceT(
                                    'choose_software'
                                )
                            ); ?>
                        </option>

                        <?php foreach (
                            $logiciels as $logiciel
                        ): ?>

                            <?php

                            $idLogiciel =
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

                            ?>

                            <option value="<?= htmlspecialchars(
                                $idLogiciel
                            ); ?>"
                                <?= $idLogiciel == $idLogicielLicence
                                    ? 'selected'
                                    : ''; ?>>

                                <?= htmlspecialchars(
                                    $nomLogiciel
                                ); ?>

                                <?php if (!empty($version)): ?>

                                    - v<?= htmlspecialchars(
                                        $version
                                    ); ?>

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            licenceT('licence_key')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-key"></i>

                        <input type="text"
                               name="cle_licence"
                               class="form-control"
                               value="<?= htmlspecialchars(
                                   $cleLicence
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            licenceT('start_date')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-calendar-check"></i>

                        <input type="date"
                               name="date_debut"
                               class="form-control"
                               value="<?= htmlspecialchars(
                                   $dateDebut
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            licenceT('end_date')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-calendar-x"></i>

                        <input type="date"
                               name="date_fin"
                               class="form-control"
                               value="<?= htmlspecialchars(
                                   $dateFin
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            licenceT('number_of_posts')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-pc-display"></i>

                        <input type="number"
                               name="nombre_postes"
                               class="form-control"
                               value="<?= htmlspecialchars(
                                   $nombrePostes
                               ); ?>"
                               min="1"
                               required>

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
                            licenceT('note')
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            licenceT('edit_note')
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="info-form-box">

                <i class="bi bi-lightbulb-fill"></i>

                <div>

                    <strong>
                        <?= htmlspecialchars(
                            licenceT('advice')
                        ); ?>
                    </strong>

                    <?= htmlspecialchars(
                        licenceT('edit_advice')
                    ); ?>

                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=licences"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>

                    <?= htmlspecialchars(
                        licenceT('cancel')
                    ); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars(
                        licenceT('update')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>