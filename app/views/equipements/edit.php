<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$equipement = $equipement ?? [];
$categories = $categories ?? [];
$locals = $locals ?? [];

$id = $equipement['id_equipement'] ?? $equipement['ID_EQUIPEMENT_'] ?? '';
$numeroSerie = $equipement['numero_serie'] ?? $equipement['NUMERO_SERIE'] ?? '';
$marque = $equipement['marque'] ?? $equipement['MARQUE'] ?? '';
$modele = $equipement['modele'] ?? $equipement['MODELE'] ?? '';
$dateAchat = $equipement['date_achat'] ?? $equipement['DATE_ACHAT'] ?? '';
$statut = $equipement['statut'] ?? $equipement['STATUT'] ?? 'Disponible';
$idCategorieEq = $equipement['id_categorie'] ?? $equipement['ID_CATEGORIE'] ?? '';
$idLocalEq = $equipement['id_local'] ?? $equipement['ID_LOCAL'] ?? '';
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier un équipement</h2>
            <p>Mettez à jour les informations du matériel sélectionné.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=equipements" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-equipement&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_equipement" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations de l’équipement</h5>
                    <small>Modifiez les informations principales du matériel.</small>
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
                               value="<?= htmlspecialchars($numeroSerie); ?>"
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
                               value="<?= htmlspecialchars($marque); ?>"
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
                               value="<?= htmlspecialchars($modele); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d’achat</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-check"></i>
                        <input type="date"
                               name="date_achat"
                               class="form-control"
                               value="<?= htmlspecialchars($dateAchat); ?>">
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div>
                    <h5>Classification et localisation</h5>
                    <small>Modifiez la catégorie, le local et le statut.</small>
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

                            <option value="<?= htmlspecialchars($idCategorie); ?>"
                                <?= ($idCategorie == $idCategorieEq) ? 'selected' : ''; ?>>
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

                            <option value="<?= htmlspecialchars($idLocal); ?>"
                                <?= ($idLocal == $idLocalEq) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($nomLocal); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="Disponible" <?= ($statut == 'Disponible') ? 'selected' : ''; ?>>Disponible</option>
                        <option value="Affecté" <?= ($statut == 'Affecté') ? 'selected' : ''; ?>>Affecté</option>
                        <option value="Maintenance" <?= ($statut == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                        <option value="En panne" <?= ($statut == 'En panne') ? 'selected' : ''; ?>>En panne</option>
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
                    Mettre à jour
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>