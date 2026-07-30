<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$categories = $categories ?? [];
$locals = $locals ?? [];

if (!function_exists('equipmentT')) {
    function equipmentT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'equipment_module.' . $key,
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
                    equipmentT('create_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    equipmentT('create_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=equipements"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                equipmentT('back')
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

        <form action="<?= BASE_URL ?>?page=enregistrer-equipement"
              method="POST">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-pc-display"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            equipmentT(
                                'equipment_information'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            equipmentT(
                                'create_information_help'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            equipmentT('serial_number')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-upc-scan"></i>

                        <input type="text"
                               name="numero_serie"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(
                                   equipmentT(
                                       'serial_placeholder'
                                   )
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            equipmentT('brand')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-tag"></i>

                        <input type="text"
                               name="marque"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(
                                   equipmentT(
                                       'brand_placeholder'
                                   )
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            equipmentT('model')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-laptop"></i>

                        <input type="text"
                               name="modele"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(
                                   equipmentT(
                                       'model_placeholder'
                                   )
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            equipmentT('purchase_date')
                        ); ?>
                    </label>

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

                    <h5>
                        <?= htmlspecialchars(
                            equipmentT(
                                'classification_location'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            equipmentT(
                                'create_classification_help'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            equipmentT('category')
                        ); ?>
                    </label>

                    <select name="id_categorie"
                            class="form-select"
                            required>

                        <option value="">

                            <?= htmlspecialchars(
                                equipmentT('choose_category')
                            ); ?>

                        </option>

                        <?php foreach (
                            $categories as $categorie
                        ): ?>

                            <?php

                            $idCategorie =
                                $categorie['id_categorie']
                                ?? $categorie['ID_CATEGORIE']
                                ?? '';

                            $nomCategorie =
                                $categorie['nom_categorie']
                                ?? $categorie['NOM_CATEGORIE']
                                ?? '';

                            ?>

                            <option value="<?= htmlspecialchars(
                                $idCategorie
                            ); ?>">

                                <?= htmlspecialchars(
                                    $nomCategorie
                                ); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            equipmentT('local')
                        ); ?>
                    </label>

                    <select name="id_local"
                            class="form-select">

                        <option value="">

                            <?= htmlspecialchars(
                                equipmentT('choose_local')
                            ); ?>

                        </option>

                        <?php foreach ($locals as $local): ?>

                            <?php

                            $idLocal =
                                $local['id_local']
                                ?? $local['ID_LOCAL']
                                ?? '';

                            $nomLocal =
                                $local['nom_local']
                                ?? $local['NOM_LOCAL']
                                ?? '';

                            ?>

                            <option value="<?= htmlspecialchars(
                                $idLocal
                            ); ?>">

                                <?= htmlspecialchars(
                                    $nomLocal
                                ); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            equipmentT('status')
                        ); ?>
                    </label>

                    <select name="statut"
                            class="form-select"
                            required>

                        <option value="Disponible">
                            <?= htmlspecialchars(
                                equipmentT('status_available')
                            ); ?>
                        </option>

                        <option value="Affecté">
                            <?= htmlspecialchars(
                                equipmentT('status_assigned')
                            ); ?>
                        </option>

                        <option value="Maintenance">
                            <?= htmlspecialchars(
                                equipmentT(
                                    'status_maintenance'
                                )
                            ); ?>
                        </option>

                        <option value="En panne">
                            <?= htmlspecialchars(
                                equipmentT('status_broken')
                            ); ?>
                        </option>

                    </select>

                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=equipements"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>

                    <?= htmlspecialchars(
                        equipmentT('cancel')
                    ); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars(
                        equipmentT('save')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>