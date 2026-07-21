<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Ajouter une catégorie</h2>
            <p>Ajoutez une nouvelle catégorie pour organiser les équipements et logiciels.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-categorie" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>
                <div>
                    <h5>Informations de la catégorie</h5>
                    <small>Renseignez le nom de la catégorie à ajouter.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-12">
                    <label class="form-label">Nom de la catégorie</label>
                    <div class="input-with-icon">
                        <i class="bi bi-tag-fill"></i>
                        <input type="text"
                               name="nom_categorie"
                               class="form-control"
                               placeholder="Ex : PC, Imprimante, Logiciel, Antivirus..."
                               required>
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div>
                    <h5>Remarque</h5>
                    <small>Les catégories permettent de mieux classer le parc informatique.</small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>
                <div>
                    <strong>Exemples :</strong>
                    PC, Imprimante, Serveur, Switch, Datashow, Logiciel, Antivirus, Microsoft Office.
                </div>
            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
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