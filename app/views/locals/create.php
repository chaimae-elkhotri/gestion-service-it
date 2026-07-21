<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Ajouter un local</h2>
            <p>Ajoutez un nouveau local, bureau ou salle de la FSJES Oujda.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=locals" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-local" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-building-fill"></i>
                </div>
                <div>
                    <h5>Informations du local</h5>
                    <small>Renseignez le nom et le type du local.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Nom du local</label>
                    <div class="input-with-icon">
                        <i class="bi bi-door-open"></i>
                        <input type="text"
                               name="nom_local"
                               class="form-control"
                               placeholder="Ex : Salle Informatique 1"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Type du local</label>
                    <select name="type_local" class="form-select" required>
                        <option value="">Choisir un type</option>
                        <option value="Bureau">Bureau</option>
                        <option value="Salle">Salle</option>
                        <option value="Salle informatique">Salle informatique</option>
                        <option value="Amphithéâtre">Amphithéâtre</option>
                        <option value="Laboratoire">Laboratoire</option>
                        <option value="Service">Service</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div>
                    <h5>Remarque</h5>
                    <small>Ce local sera utilisé pour localiser les équipements du parc informatique.</small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>
                <div>
                    <strong>Exemples :</strong>
                    Salle Informatique 1, Bureau Service Informatique, Amphi A, Salle des professeurs.
                </div>
            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=locals" class="btn btn-light border">
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