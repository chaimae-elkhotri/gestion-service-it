<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$categorie = $categorie ?? [];

$id = $categorie['id_categorie'] ?? $categorie['ID_CATEGORIE'] ?? '';
$nomCategorie = $categorie['nom_categorie'] ?? $categorie['NOM_CATEGORIE'] ?? '';

if (!function_exists('categoryT')) {
    function categoryT(string $key, array $replacements = []): string
    {
        return t('categories_module.' . $key, $replacements);
    }
}
?>

<div class="module-page">

    <div class="module-header">
        <div>
            <h2><?= htmlspecialchars(categoryT('edit_title')); ?></h2>
            <p><?= htmlspecialchars(categoryT('edit_subtitle')); ?></p>
        </div>

        <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            <?= htmlspecialchars(categoryT('back')); ?>
        </a>
    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-categorie&id=<?= (int)$id; ?>"
              method="POST">

            <input type="hidden"
                   name="id_categorie"
                   value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h5><?= htmlspecialchars(categoryT('category_information')); ?></h5>
                    <small><?= htmlspecialchars(categoryT('edit_information_help')); ?></small>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-12">
                    <label class="form-label"><?= htmlspecialchars(categoryT('category_name')); ?></label>

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
                    <h5><?= htmlspecialchars(categoryT('note')); ?></h5>
                    <small><?= htmlspecialchars(categoryT('edit_note')); ?></small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>

                <div>
                    <strong><?= htmlspecialchars(categoryT('advice')); ?></strong>
                    <?= htmlspecialchars(categoryT('advice_text')); ?>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
                    <i class="bi bi-x-circle"></i>
                    <?= htmlspecialchars(categoryT('cancel')); ?>
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    <?= htmlspecialchars(categoryT('update')); ?>
                </button>
            </div>

        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>