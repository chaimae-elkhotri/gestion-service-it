<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$affectations = $affectations ?? [];

$totalAffectations = count($affectations);
$totalActives = 0;
$totalTerminees = 0;
$totalRetoursPrevus = 0;
$totalSansDateFin = 0;

$today = date('Y-m-d');
$dateDans30Jours = date('Y-m-d', strtotime('+30 days'));

foreach ($affectations as $a) {
    $dateFin = $a['date_fin_affectation'] ?? $a['DATE_FIN_AFFECTATION'] ?? null;

    if (empty($dateFin)) {
        $totalActives++;
        $totalSansDateFin++;
    } elseif ($dateFin >= $today) {
        $totalActives++;

        if ($dateFin <= $dateDans30Jours) {
            $totalRetoursPrevus++;
        }
    } else {
        $totalTerminees++;
    }
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des affectations</h2>
            <p>Gérez les affectations des équipements aux utilisateurs.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-affectation" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Nouvelle affectation
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <div>
                <span>Total affectations</span>
                <h3><?= $totalAffectations; ?></h3>
                <small>Affectations enregistrées</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <span>Affectations actives</span>
                <h3><?= $totalActives; ?></h3>
                <small>Équipements utilisés</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
            <div>
                <span>Retours prévus</span>
                <h3><?= $totalRetoursPrevus; ?></h3>
                <small>Dans les 30 jours</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <span>Affectations terminées</span>
                <h3><?= $totalTerminees; ?></h3>
                <small>Équipements retournés</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="affectations">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par utilisateur, équipement, date..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Statut</label>
                    <select class="form-select" disabled>
                        <option>Tous les statuts</option>
                        <option>Active</option>
                        <option>Retour prévu</option>
                        <option>Terminée</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=affectations" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des affectations</h5>
                <small><?= $totalAffectations; ?> affectation(s) trouvée(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-pc-display-horizontal"></i>
                Affectation matériel
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Équipement</th>
                    <th>Date affectation</th>
                    <th>Date fin</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($affectations)): ?>

                    <?php foreach ($affectations as $a): ?>

                        <?php
                        $id = $a['id_affectation'] ?? $a['ID_AFFECTATION_EQUIP'] ?? '';
                        $idUtilisateur = $a['id_utilisateur'] ?? $a['ID_UTILISATEUR'] ?? '';
                        $idEquipement = $a['id_equipement'] ?? $a['ID_EQUIPEMENT_'] ?? '';

                        $nom = $a['nom'] ?? $a['NOM'] ?? '';
                        $prenom = $a['prenom'] ?? $a['PRENOM'] ?? '';

                        $numeroSerie = $a['numero_serie'] ?? $a['NUMERO_SERIE'] ?? '';
                        $marque = $a['marque'] ?? $a['MARQUE'] ?? '';
                        $modele = $a['modele'] ?? $a['MODELE'] ?? '';

                        $dateAffectation = $a['date_affectation'] ?? $a['DATE_AFFECTATION'] ?? '';
                        $dateFin = $a['date_fin_affectation'] ?? $a['DATE_FIN_AFFECTATION'] ?? '';

                        $initiales = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));

                        if (empty($dateFin)) {
                            $statutAffectation = 'Active';
                            $classStatut = 'affectation-active';
                            $iconStatut = 'bi-check-circle-fill';
                        } elseif ($dateFin < $today) {
                            $statutAffectation = 'Terminée';
                            $classStatut = 'affectation-done';
                            $iconStatut = 'bi-check2-circle';
                        } elseif ($dateFin <= $dateDans30Jours) {
                            $statutAffectation = 'Retour prévu';
                            $classStatut = 'affectation-return';
                            $iconStatut = 'bi-calendar-event-fill';
                        } else {
                            $statutAffectation = 'Active';
                            $classStatut = 'affectation-active';
                            $iconStatut = 'bi-check-circle-fill';
                        }
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#AFF-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="user-cell">
                                    <div class="table-avatar">
                                        <?= htmlspecialchars($initiales ?: 'U'); ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars(trim($prenom . ' ' . $nom) ?: 'Utilisateur'); ?></strong>
                                        <small>ID utilisateur : <?= htmlspecialchars($idUtilisateur); ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="affectation-equipment-cell">
                                    <div class="affectation-equipment-icon">
                                        <i class="bi bi-pc-display"></i>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars(trim($marque . ' ' . $modele) ?: 'Équipement'); ?></strong>
                                        <small>
                                            Série :
                                            <?= htmlspecialchars($numeroSerie ?: 'Non définie'); ?>
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="date-badge">
                                    <i class="bi bi-calendar-check"></i>
                                    <?= !empty($dateAffectation) ? date('d/m/Y', strtotime($dateAffectation)) : '-'; ?>
                                </span>
                            </td>

                            <td>
                                <?php if (!empty($dateFin)): ?>
                                    <span class="date-badge">
                                        <i class="bi bi-calendar-x"></i>
                                        <?= date('d/m/Y', strtotime($dateFin)); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="no-date-badge">
                                        <i class="bi bi-dash-circle"></i>
                                        Non définie
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="badge <?= $classStatut; ?>">
                                    <i class="bi <?= $iconStatut; ?>"></i>
                                    <?= $statutAffectation; ?>
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-affectation&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-affectation&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette affectation ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-arrow-left-right fs-1"></i>
                            <br><br>
                            Aucune affectation trouvée.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>