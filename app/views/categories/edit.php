<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$categorie = $categorie ?? [];

$id = $categorie['id_categorie'] ?? $categorie['ID_CATEGORIE'] ?? '';
$nomCategorie = $categorie['nom_categorie'] ?? $categorie['NOM_CATEGORIE'] ?? '';
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier une catégorie</h2>
            <p>Mettez à jour le nom de la catégorie sélectionnée.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-categorie&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_categorie" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations de la catégorie</h5>
                    <small>Modifiez le nom de la catégorie.</small>
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
                               value="<?= htmlspecialchars($nomCategorie); ?>"
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
                    <small>La modification d’une catégorie peut influencer l’affichage des équipements associés.</small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>
                <div>
                    <strong>Conseil :</strong>
                    utilisez un nom clair comme “PC”, “Imprimante”, “Logiciel”, “Antivirus” ou “Datashow”.
                </div>
            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
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