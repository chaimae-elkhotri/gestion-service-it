<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$logiciel = $logiciel ?? [];

$id = $logiciel['id_logiciel'] ?? $logiciel['ID_LOGICIEL'] ?? '';
$nomLogiciel = $logiciel['nom_logiciel'] ?? $logiciel['NOM_LOGICIEL'] ?? '';
$version = $logiciel['version'] ?? $logiciel['VERSION'] ?? '';
$editeur = $logiciel['editeur'] ?? $logiciel['EDITEUR'] ?? '';
$dateInstallation = $logiciel['date_installation'] ?? $logiciel['DATE_INSTALLATION'] ?? '';
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier un logiciel</h2>
            <p>Mettez à jour les informations du logiciel sélectionné.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=logiciels" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-logiciel&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_logiciel" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations du logiciel</h5>
                    <small>Modifiez le nom, la version et l’éditeur du logiciel.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Nom du logiciel</label>
                    <div class="input-with-icon">
                        <i class="bi bi-disc"></i>
                        <input type="text"
                               name="nom_logiciel"
                               class="form-control"
                               value="<?= htmlspecialchars($nomLogiciel); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Version</label>
                    <div class="input-with-icon">
                        <i class="bi bi-code-slash"></i>
                        <input type="text"
                               name="version"
                               class="form-control"
                               value="<?= htmlspecialchars($version); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Éditeur</label>
                    <div class="input-with-icon">
                        <i class="bi bi-building"></i>
                        <input type="text"
                               name="editeur"
                               class="form-control"
                               value="<?= htmlspecialchars($editeur); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d’installation</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-check"></i>
                        <input type="date"
                               name="date_installation"
                               class="form-control"
                               value="<?= htmlspecialchars($dateInstallation); ?>">
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div>
                    <h5>Remarque</h5>
                    <small>Les modifications peuvent influencer les licences associées.</small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>
                <div>
                    <strong>Conseil :</strong>
                    utilisez un nom clair comme “Microsoft Office 2021”, “Windows 11 Pro” ou “Adobe Reader”.
                </div>
            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=logiciels" class="btn btn-light border">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Mettre à jour
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>