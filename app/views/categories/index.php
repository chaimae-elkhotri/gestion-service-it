<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$categories = $categories ?? [];

if (!function_exists('categoryT')) {
    function categoryT(string $key, array $replacements = []): string
    {
        return t('categories_module.' . $key, $replacements);
    }
}

if (!function_exists('categoryNormalize')) {
    function categoryNormalize(string $value): string
    {
        $value = mb_strtolower(trim($value), 'UTF-8');

        return strtr($value, [
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'à' => 'a', 'â' => 'a', 'î' => 'i', 'ï' => 'i',
            'ô' => 'o', 'ù' => 'u', 'û' => 'u', 'ç' => 'c'
        ]);
    }
}

if (!function_exists('categoryTypeInfo')) {
    function categoryTypeInfo(string $name): array
    {
        $name = categoryNormalize($name);

        $software = [
            'logiciel', 'antivirus', 'office', 'windows', 'adobe',
            'systeme', 'licence', 'برنامج', 'برمجيات', 'ترخيص',
            'تراخيص', 'مضاد الفيروسات'
        ];

        $equipment = [
            'pc', 'ordinateur', 'imprimante', 'routeur', 'switch',
            'serveur', 'datashow', 'ecran', 'حاسوب', 'طابعة',
            'موجه', 'خادم', 'شاشة', 'جهاز عرض'
        ];

        foreach ($software as $word) {
            if (mb_strpos($name, $word, 0, 'UTF-8') !== false) {
                return [
                    'type' => 'type_software',
                    'description' => 'software_description',
                    'class' => 'category-type-logiciel',
                    'icon' => 'bi-disc-fill'
                ];
            }
        }

        foreach ($equipment as $word) {
            if (mb_strpos($name, $word, 0, 'UTF-8') !== false) {
                return [
                    'type' => 'type_equipment',
                    'description' => 'equipment_description',
                    'class' => 'category-type-equipement',
                    'icon' => 'bi-pc-display'
                ];
            }
        }

        return [
            'type' => 'type_other',
            'description' => 'general_description',
            'class' => 'category-type-default',
            'icon' => 'bi-folder-fill'
        ];
    }
}

$totalCategories = count($categories);
$totalEquipement = 0;
$totalLogiciel = 0;
$totalAutres = 0;

foreach ($categories as $cat) {
    $nom = $cat['nom_categorie'] ?? $cat['NOM_CATEGORIE'] ?? '';
    $info = categoryTypeInfo((string)$nom);

    if ($info['type'] === 'type_equipment') {
        $totalEquipement++;
    } elseif ($info['type'] === 'type_software') {
        $totalLogiciel++;
    } else {
        $totalAutres++;
    }
}
?>

<div class="module-page">

    <div class="module-header">
        <div>
            <h2><?= htmlspecialchars(categoryT('management_title')); ?></h2>
            <p><?= htmlspecialchars(categoryT('management_subtitle')); ?></p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-categorie" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            <?= htmlspecialchars(categoryT('add_category')); ?>
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['success']); ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown"><i class="bi bi-grid-3x3-gap-fill"></i></div>
            <div>
                <span><?= htmlspecialchars(categoryT('total_categories')); ?></span>
                <h3><?= $totalCategories; ?></h3>
                <small><?= htmlspecialchars(categoryT('all_categories')); ?></small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue"><i class="bi bi-pc-display"></i></div>
            <div>
                <span><?= htmlspecialchars(categoryT('equipment')); ?></span>
                <h3><?= $totalEquipement; ?></h3>
                <small><?= htmlspecialchars(categoryT('it_hardware')); ?></small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon purple"><i class="bi bi-disc-fill"></i></div>
            <div>
                <span><?= htmlspecialchars(categoryT('software')); ?></span>
                <h3><?= $totalLogiciel; ?></h3>
                <small><?= htmlspecialchars(categoryT('applications_licenses')); ?></small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange"><i class="bi bi-folder-fill"></i></div>
            <div>
                <span><?= htmlspecialchars(categoryT('other')); ?></span>
                <h3><?= $totalAutres; ?></h3>
                <small><?= htmlspecialchars(categoryT('various_categories')); ?></small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">
        <form action="<?= BASE_URL ?>" method="GET">
            <input type="hidden" name="page" value="categories">

            <div class="row g-3 align-items-end">
                <div class="col-lg-8 col-md-12">
                    <label class="form-label"><?= htmlspecialchars(categoryT('search')); ?></label>

                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(categoryT('search_placeholder')); ?>"
                               value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        <?= htmlspecialchars(categoryT('search_button')); ?>
                    </button>

                    <a href="<?= BASE_URL ?>?page=categories"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(categoryT('reset')); ?>">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="module-table-card">

        <div class="module-table-header">
            <div>
                <h5><?= htmlspecialchars(categoryT('category_list')); ?></h5>
                <small>
                    <?= htmlspecialchars(categoryT('categories_found', ['count' => $totalCategories])); ?>
                </small>
            </div>

            <span class="module-chip">
                <i class="bi bi-tags-fill"></i>
                <?= htmlspecialchars(categoryT('classification')); ?>
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th><?= htmlspecialchars(categoryT('id')); ?></th>
                    <th><?= htmlspecialchars(categoryT('category')); ?></th>
                    <th><?= htmlspecialchars(categoryT('estimated_type')); ?></th>
                    <th><?= htmlspecialchars(categoryT('description')); ?></th>
                    <th><?= htmlspecialchars(categoryT('status')); ?></th>
                    <th class="text-center"><?= htmlspecialchars(categoryT('actions')); ?></th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($categories)): ?>

                    <?php foreach ($categories as $cat): ?>
                        <?php
                        $id = $cat['id_categorie'] ?? $cat['ID_CATEGORIE'] ?? '';
                        $nomCategorie = $cat['nom_categorie'] ?? $cat['NOM_CATEGORIE'] ?? '';
                        $info = categoryTypeInfo((string)$nomCategorie);
                        ?>

                        <tr>
                            <td>
                                <span class="table-id">#CAT-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="category-cell">
                                    <div class="category-icon">
                                        <i class="bi <?= htmlspecialchars($info['icon']); ?>"></i>
                                    </div>

                                    <div>
                                        <strong><?= htmlspecialchars($nomCategorie); ?></strong>
                                        <small><?= htmlspecialchars(categoryT('it_asset_category')); ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge <?= htmlspecialchars($info['class']); ?>">
                                    <?= htmlspecialchars(categoryT($info['type'])); ?>
                                </span>
                            </td>

                            <td>
                                <span class="category-description">
                                    <?= htmlspecialchars(categoryT($info['description'])); ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <?= htmlspecialchars(categoryT('active')); ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="<?= BASE_URL ?>?page=modifier-categorie&id=<?= (int)$id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="<?= htmlspecialchars(categoryT('edit')); ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-categorie&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(categoryT('delete')); ?>"
                                   onclick="return confirm('<?= htmlspecialchars(categoryT('delete_confirmation'), ENT_QUOTES, 'UTF-8'); ?>');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-grid-3x3-gap fs-1"></i>
                            <br><br>
                            <?= htmlspecialchars(categoryT('no_category')); ?>
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>