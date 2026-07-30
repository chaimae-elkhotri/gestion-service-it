<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
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
            <h2><?= htmlspecialchars(categoryT('create_title')); ?></h2>
            <p><?= htmlspecialchars(categoryT('create_subtitle')); ?></p>
        </div>

        <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            <?= htmlspecialchars(categoryT('back')); ?>
        </a>
    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-categorie" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>

                <div>
                    <h5><?= htmlspecialchars(categoryT('category_information')); ?></h5>
                    <small><?= htmlspecialchars(categoryT('create_information_help')); ?></small>
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
                               placeholder="<?= htmlspecialchars(categoryT('name_placeholder')); ?>"
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
                    <small><?= htmlspecialchars(categoryT('create_note')); ?></small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>

                <div>
                    <strong><?= htmlspecialchars(categoryT('examples')); ?></strong>
                    <?= htmlspecialchars(categoryT('examples_text')); ?>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
                    <i class="bi bi-x-circle"></i>
                    <?= htmlspecialchars(categoryT('cancel')); ?>
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    <?= htmlspecialchars(categoryT('save')); ?>
                </button>
            </div>

        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>