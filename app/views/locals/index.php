<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$locals = $locals ?? [];

$totalLocaux = count($locals);
$totalBureaux = 0;
$totalSalles = 0;
$totalAmphis = 0;

foreach ($locals as $local) {
    $type = strtolower($local['type_local'] ?? $local['TYPE_LOCAL'] ?? '');

    if (strpos($type, 'bureau') !== false) {
        $totalBureaux++;
    } elseif (strpos($type, 'salle') !== false) {
        $totalSalles++;
    } elseif (strpos($type, 'amphi') !== false) {
        $totalAmphis++;
    }
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des locaux</h2>
            <p>Gérez les salles, bureaux et espaces de la FSJES Oujda.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-local" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Ajouter un local
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <span>Total locaux</span>
                <h3><?= $totalLocaux; ?></h3>
                <small>Espaces enregistrés</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-pc-display"></i>
            </div>
            <div>
                <span>Salles</span>
                <h3><?= $totalSalles; ?></h3>
                <small>Salles informatiques / cours</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-door-open-fill"></i>
            </div>
            <div>
                <span>Bureaux</span>
                <h3><?= $totalBureaux; ?></h3>
                <small>Bureaux administratifs</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-easel-fill"></i>
            </div>
            <div>
                <span>Amphithéâtres</span>
                <h3><?= $totalAmphis; ?></h3>
                <small>Espaces pédagogiques</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="locals">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par nom ou type de local..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Type local</label>
                    <select class="form-select" disabled>
                        <option>Tous les types</option>
                        <option>Bureau</option>
                        <option>Salle</option>
                        <option>Amphi</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=locals" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des locaux</h5>
                <small><?= $totalLocaux; ?> local(aux) trouvé(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-building"></i>
                Espaces FSJES
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Local</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($locals)): ?>

                    <?php foreach ($locals as $local): ?>

                        <?php
                        $id = $local['id_local'] ?? $local['ID_LOCAL'] ?? '';
                        $nomLocal = $local['nom_local'] ?? $local['NOM_LOCAL'] ?? '';
                        $typeLocal = $local['type_local'] ?? $local['TYPE_LOCAL'] ?? '';

                        $typeLower = strtolower($typeLocal);

                        if (strpos($typeLower, 'bureau') !== false) {
                            $icon = 'bi-door-open-fill';
                            $badgeClass = 'local-type-bureau';
                            $description = 'Bureau administratif';
                        } elseif (strpos($typeLower, 'amphi') !== false) {
                            $icon = 'bi-easel-fill';
                            $badgeClass = 'local-type-amphi';
                            $description = 'Amphithéâtre / espace pédagogique';
                        } elseif (strpos($typeLower, 'salle') !== false) {
                            $icon = 'bi-pc-display';
                            $badgeClass = 'local-type-salle';
                            $description = 'Salle informatique ou salle de cours';
                        } else {
                            $icon = 'bi-building-fill';
                            $badgeClass = 'local-type-default';
                            $description = 'Espace de l’établissement';
                        }
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#LOC-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="local-cell">
                                    <div class="local-icon">
                                        <i class="bi <?= $icon; ?>"></i>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($nomLocal); ?></strong>
                                        <small>Local FSJES Oujda</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge <?= $badgeClass; ?>">
                                    <?= htmlspecialchars($typeLocal ?: 'Non défini'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="local-description">
                                    <?= htmlspecialchars($description); ?>
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-local&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-local&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer ce local ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-building fs-1"></i>
                            <br><br>
                            Aucun local trouvé.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>