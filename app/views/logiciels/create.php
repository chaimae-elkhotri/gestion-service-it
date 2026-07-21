<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Ajouter un logiciel</h2>
            <p>Ajoutez un nouveau logiciel installé dans le parc informatique.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=logiciels" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-logiciel" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-disc-fill"></i>
                </div>
                <div>
                    <h5>Informations du logiciel</h5>
                    <small>Renseignez le nom, la version et l’éditeur du logiciel.</small>
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
                               placeholder="Ex : Microsoft Office"
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
                               placeholder="Ex : 2021 / 365 / 10.0"
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
                               placeholder="Ex : Microsoft, Adobe, Avast"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d’installation</label>
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
                    <h5>Remarque</h5>
                    <small>Les licences seront associées après au logiciel concerné.</small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>
                <div>
                    <strong>Exemples :</strong>
                    Microsoft Office, Windows 11, Adobe Reader, Antivirus Avast.
                </div>
            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=logiciels" class="btn btn-light border">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>