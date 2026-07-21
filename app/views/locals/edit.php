<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$local = $local ?? [];

$id = $local['id_local'] ?? $local['ID_LOCAL'] ?? '';
$nomLocal = $local['nom_local'] ?? $local['NOM_LOCAL'] ?? '';
$typeLocal = $local['type_local'] ?? $local['TYPE_LOCAL'] ?? '';
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier un local</h2>
            <p>Mettez à jour les informations du local sélectionné.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=locals" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-local&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_local" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations du local</h5>
                    <small>Modifiez le nom et le type du local.</small>
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
                               value="<?= htmlspecialchars($nomLocal); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Type du local</label>
                    <select name="type_local" class="form-select" required>
                        <option value="">Choisir un type</option>

                        <option value="Bureau" <?= ($typeLocal == 'Bureau') ? 'selected' : ''; ?>>
                            Bureau
                        </option>

                        <option value="Salle" <?= ($typeLocal == 'Salle') ? 'selected' : ''; ?>>
                            Salle
                        </option>

                        <option value="Salle informatique" <?= ($typeLocal == 'Salle informatique') ? 'selected' : ''; ?>>
                            Salle informatique
                        </option>

                        <option value="Amphithéâtre" <?= ($typeLocal == 'Amphithéâtre') ? 'selected' : ''; ?>>
                            Amphithéâtre
                        </option>

                        <option value="Laboratoire" <?= ($typeLocal == 'Laboratoire') ? 'selected' : ''; ?>>
                            Laboratoire
                        </option>

                        <option value="Service" <?= ($typeLocal == 'Service') ? 'selected' : ''; ?>>
                            Service
                        </option>

                        <option value="Autre" <?= ($typeLocal == 'Autre') ? 'selected' : ''; ?>>
                            Autre
                        </option>
                    </select>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div>
                    <h5>Remarque</h5>
                    <small>La modification du local peut influencer l’emplacement des équipements associés.</small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>
                <div>
                    <strong>Conseil :</strong>
                    utilisez des noms clairs comme “Bureau SI”, “Salle Informatique 1”, “Amphi A”.
                </div>
            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=locals" class="btn btn-light border">
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