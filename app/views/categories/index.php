<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$categories = $categories ?? [];

$totalCategories = count($categories);
$totalEquipement = 0;
$totalLogiciel = 0;
$totalAutres = 0;

$categoriesLogiciels = ['logiciel', 'antivirus', 'office', 'windows', 'adobe', 'système', 'systeme', 'licence'];
$categoriesEquipements = ['pc', 'ordinateur', 'imprimante', 'routeur', 'switch', 'serveur', 'datashow', 'écran', 'ecran'];

foreach ($categories as $cat) {
    $nomCategorie = strtolower($cat['nom_categorie'] ?? $cat['NOM_CATEGORIE'] ?? '');
    $typeTrouve = false;

    foreach ($categoriesLogiciels as $mot) {
        if (strpos($nomCategorie, $mot) !== false) {
            $totalLogiciel++;
            $typeTrouve = true;
            break;
        }
    }

    if (!$typeTrouve) {
        foreach ($categoriesEquipements as $mot) {
            if (strpos($nomCategorie, $mot) !== false) {
                $totalEquipement++;
                $typeTrouve = true;
                break;
            }
        }
    }

    if (!$typeTrouve) {
        $totalAutres++;
    }
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des catégories</h2>
            <p>Organisez les équipements et les logiciels par catégories.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-categorie" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Ajouter une catégorie
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <span>Total catégories</span>
                <h3><?= $totalCategories; ?></h3>
                <small>Toutes les catégories</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-pc-display"></i>
            </div>
            <div>
                <span>Équipements</span>
                <h3><?= $totalEquipement; ?></h3>
                <small>Matériel informatique</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon purple">
                <i class="bi bi-disc-fill"></i>
            </div>
            <div>
                <span>Logiciels</span>
                <h3><?= $totalLogiciel; ?></h3>
                <small>Applications et licences</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-folder-fill"></i>
            </div>
            <div>
                <span>Autres</span>
                <h3><?= $totalAutres; ?></h3>
                <small>Catégories diverses</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="categories">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher une catégorie..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Type</label>
                    <select class="form-select" disabled>
                        <option>Tous les types</option>
                        <option>Équipement</option>
                        <option>Logiciel</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=categories" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des catégories</h5>
                <small><?= $totalCategories; ?> catégorie(s) trouvée(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-tags-fill"></i>
                Classification
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Catégorie</th>
                    <th>Type estimé</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($categories)): ?>

                    <?php foreach ($categories as $cat): ?>

                        <?php
                        $id = $cat['id_categorie'] ?? $cat['ID_CATEGORIE'] ?? '';
                        $nomCategorie = $cat['nom_categorie'] ?? $cat['NOM_CATEGORIE'] ?? '';

                        $nomLower = strtolower($nomCategorie);

                        $typeCategorie = 'Autre';
                        $badgeClass = 'category-type-default';
                        $icon = 'bi-folder-fill';
                        $description = 'Catégorie générale';

                        foreach ($categoriesLogiciels as $mot) {
                            if (strpos($nomLower, $mot) !== false) {
                                $typeCategorie = 'Logiciel';
                                $badgeClass = 'category-type-logiciel';
                                $icon = 'bi-disc-fill';
                                $description = 'Catégorie liée aux logiciels et licences';
                                break;
                            }
                        }

                        if ($typeCategorie == 'Autre') {
                            foreach ($categoriesEquipements as $mot) {
                                if (strpos($nomLower, $mot) !== false) {
                                    $typeCategorie = 'Équipement';
                                    $badgeClass = 'category-type-equipement';
                                    $icon = 'bi-pc-display';
                                    $description = 'Catégorie liée au matériel informatique';
                                    break;
                                }
                            }
                        }
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#CAT-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="category-cell">
                                    <div class="category-icon">
                                        <i class="bi <?= $icon; ?>"></i>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($nomCategorie); ?></strong>
                                        <small>Catégorie du parc informatique</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge <?= $badgeClass; ?>">
                                    <?= htmlspecialchars($typeCategorie); ?>
                                </span>
                            </td>

                            <td>
                                <span class="category-description">
                                    <?= htmlspecialchars($description); ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Active
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-categorie&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-categorie&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette catégorie ?');">
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
                            Aucune catégorie trouvée.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>