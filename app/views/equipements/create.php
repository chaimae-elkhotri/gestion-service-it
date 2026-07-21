<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$categories = $categories ?? [];
$locals = $locals ?? [];
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Ajouter un équipement</h2>
            <p>Ajoutez un nouveau matériel au parc informatique.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=equipements" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-equipement" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pc-display"></i>
                </div>
                <div>
                    <h5>Informations de l’équipement</h5>
                    <small>Renseignez les informations principales du matériel.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Numéro de série</label>
                    <div class="input-with-icon">
                        <i class="bi bi-upc-scan"></i>
                        <input type="text"
                               name="numero_serie"
                               class="form-control"
                               placeholder="Ex : PC-2026-001"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Marque</label>
                    <div class="input-with-icon">
                        <i class="bi bi-tag"></i>
                        <input type="text"
                               name="marque"
                               class="form-control"
                               placeholder="Ex : HP, Dell, Lenovo"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Modèle</label>
                    <div class="input-with-icon">
                        <i class="bi bi-laptop"></i>
                        <input type="text"
                               name="modele"
                               class="form-control"
                               placeholder="Ex : ProBook 450 G8"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d’achat</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-check"></i>
                        <input type="date"
                               name="date_achat"
                               class="form-control">
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div>
                    <h5>Classification et localisation</h5>
                    <small>Associez l’équipement à une catégorie et à un local.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Catégorie</label>
                    <select name="id_categorie" class="form-select" required>

                        <option value="">Choisir une catégorie</option>

                        <?php foreach ($categories as $categorie): ?>

                            <?php
                            $idCategorie = $categorie['id_categorie'] ?? $categorie['ID_CATEGORIE'] ?? '';
                            $nomCategorie = $categorie['nom_categorie'] ?? $categorie['NOM_CATEGORIE'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idCategorie); ?>">
                                <?= htmlspecialchars($nomCategorie); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Local</label>
                    <select name="id_local" class="form-select">

                        <option value="">Choisir un local</option>

                        <?php foreach ($locals as $local): ?>

                            <?php
                            $idLocal = $local['id_local'] ?? $local['ID_LOCAL'] ?? '';
                            $nomLocal = $local['nom_local'] ?? $local['NOM_LOCAL'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idLocal); ?>">
                                <?= htmlspecialchars($nomLocal); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="Disponible">Disponible</option>
                        <option value="Affecté">Affecté</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="En panne">En panne</option>
                    </select>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=equipements" class="btn btn-light border">
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