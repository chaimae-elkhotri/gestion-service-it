<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$equipements = $equipements ?? [];

$totalEquipements = count($equipements);
$totalDisponibles = 0;
$totalAffectes = 0;
$totalMaintenance = 0;

foreach ($equipements as $eq) {
    $statut = strtolower($eq['statut'] ?? $eq['STATUT'] ?? '');

    if ($statut == 'disponible') {
        $totalDisponibles++;
    } elseif ($statut == 'affecté' || $statut == 'affecte') {
        $totalAffectes++;
    } elseif ($statut == 'maintenance' || $statut == 'en maintenance') {
        $totalMaintenance++;
    }
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des équipements</h2>
            <p>Gérez l’ensemble des équipements du parc informatique.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-equipement" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Ajouter un équipement
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-pc-display"></i>
            </div>
            <div>
                <span>Total équipements</span>
                <h3><?= $totalEquipements; ?></h3>
                <small>Parc informatique</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <span>Disponibles</span>
                <h3><?= $totalDisponibles; ?></h3>
                <small>Prêts à affecter</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <span>Affectés</span>
                <h3><?= $totalAffectes; ?></h3>
                <small>En cours d’utilisation</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-tools"></i>
            </div>
            <div>
                <span>Maintenance</span>
                <h3><?= $totalMaintenance; ?></h3>
                <small>À suivre</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="equipements">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par série, marque, modèle, catégorie, local..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Statut</label>
                    <select class="form-select" disabled>
                        <option>Tous les statuts</option>
                        <option>Disponible</option>
                        <option>Affecté</option>
                        <option>Maintenance</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=equipements" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des équipements</h5>
                <small><?= $totalEquipements; ?> équipement(s) trouvé(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-hdd-stack"></i>
                Parc FSJES
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>N° Série</th>
                    <th>Équipement</th>
                    <th>Catégorie</th>
                    <th>Local</th>
                    <th>Date achat</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($equipements)): ?>

                    <?php foreach ($equipements as $eq): ?>

                        <?php
                        $id = $eq['id_equipement'] ?? $eq['ID_EQUIPEMENT_'] ?? '';
                        $numeroSerie = $eq['numero_serie'] ?? $eq['NUMERO_SERIE'] ?? '';
                        $marque = $eq['marque'] ?? $eq['MARQUE'] ?? '';
                        $modele = $eq['modele'] ?? $eq['MODELE'] ?? '';
                        $dateAchat = $eq['date_achat'] ?? $eq['DATE_ACHAT'] ?? '';
                        $statut = $eq['statut'] ?? $eq['STATUT'] ?? '';
                        $categorie = $eq['nom_categorie'] ?? $eq['NOM_CATEGORIE'] ?? '';
                        $local = $eq['nom_local'] ?? $eq['NOM_LOCAL'] ?? '';

                        $statutLower = strtolower($statut);
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#EQP-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <span class="serial-badge">
                                    <?= htmlspecialchars($numeroSerie); ?>
                                </span>
                            </td>

                            <td>
                                <div class="equipment-cell">
                                    <div class="equipment-icon">
                                        <i class="bi bi-pc-display"></i>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($marque . ' ' . $modele); ?></strong>
                                        <small>Matériel informatique</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge category-badge">
                                    <?= htmlspecialchars($categorie ?: 'Non défini'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="local-badge">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <?= htmlspecialchars($local ?: 'Non défini'); ?>
                                </span>
                            </td>

                            <td>
                                <?= !empty($dateAchat) ? date('d/m/Y', strtotime($dateAchat)) : '-'; ?>
                            </td>

                            <td>
                                <?php if ($statutLower == 'disponible'): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Disponible
                                    </span>
                                <?php elseif ($statutLower == 'affecté' || $statutLower == 'affecte'): ?>
                                    <span class="badge bg-primary">
                                        <i class="bi bi-person-check-fill"></i>
                                        Affecté
                                    </span>
                                <?php elseif ($statutLower == 'maintenance' || $statutLower == 'en maintenance'): ?>
                                    <span class="badge bg-warning">
                                        <i class="bi bi-tools"></i>
                                        Maintenance
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($statut ?: 'Non défini'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-equipement&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-equipement&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cet équipement ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-pc-display fs-1"></i>
                            <br><br>
                            Aucun équipement trouvé.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>