<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$licences = $licences ?? [];

$totalLicences = count($licences);
$totalActives = 0;
$totalExpirees = 0;
$totalBientotExpirees = 0;
$totalPostes = 0;

$today = date('Y-m-d');
$dateDans30Jours = date('Y-m-d', strtotime('+30 days'));

foreach ($licences as $licence) {
    $dateFin = $licence['date_fin'] ?? $licence['DATE_FIN'] ?? '';
    $nombrePostes = $licence['nombre_postes'] ?? $licence['NOMBRE_POSTES'] ?? 0;

    $totalPostes += (int)$nombrePostes;

    if (!empty($dateFin)) {
        if ($dateFin < $today) {
            $totalExpirees++;
        } elseif ($dateFin <= $dateDans30Jours) {
            $totalBientotExpirees++;
        } else {
            $totalActives++;
        }
    } else {
        $totalActives++;
    }
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des licences</h2>
            <p>Suivez les licences logicielles, leurs clés, dates d’expiration et postes couverts.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-licence" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Ajouter une licence
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-key-fill"></i>
            </div>
            <div>
                <span>Total licences</span>
                <h3><?= $totalLicences; ?></h3>
                <small>Licences enregistrées</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <span>Licences actives</span>
                <h3><?= $totalActives; ?></h3>
                <small>Licences valides</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
            <div>
                <span>Expire bientôt</span>
                <h3><?= $totalBientotExpirees; ?></h3>
                <small>Dans les 30 jours</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon red">
                <i class="bi bi-calendar-x-fill"></i>
            </div>
            <div>
                <span>Licences expirées</span>
                <h3><?= $totalExpirees; ?></h3>
                <small>À renouveler</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="licences">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par logiciel, éditeur, clé licence..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Statut</label>
                    <select class="form-select" disabled>
                        <option>Tous les statuts</option>
                        <option>Active</option>
                        <option>Expire bientôt</option>
                        <option>Expirée</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=licences" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des licences</h5>
                <small>
                    <?= $totalLicences; ?> licence(s) trouvée(s) • <?= $totalPostes; ?> poste(s) couvert(s)
                </small>
            </div>

            <span class="module-chip">
                <i class="bi bi-shield-lock-fill"></i>
                Licences logicielles
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Logiciel</th>
                    <th>Clé licence</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Postes</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($licences)): ?>

                    <?php foreach ($licences as $licence): ?>

                        <?php
                        $id = $licence['id_licence'] ?? $licence['ID_LICENCE'] ?? '';
                        $idLogiciel = $licence['id_logiciel'] ?? $licence['ID_LOGICIEL'] ?? '';
                        $nomLogiciel = $licence['nom_logiciel'] ?? $licence['NOM_LOGICIEL'] ?? 'Logiciel';
                        $version = $licence['version'] ?? $licence['VERSION'] ?? '';
                        $editeur = $licence['editeur'] ?? $licence['EDITEUR'] ?? '';
                        $cleLicence = $licence['cle_licence'] ?? $licence['CLE_LICENCE'] ?? '';
                        $dateDebut = $licence['date_debut'] ?? $licence['DATE_DEBUT'] ?? '';
                        $dateFin = $licence['date_fin'] ?? $licence['DATE_FIN'] ?? '';
                        $nombrePostes = $licence['nombre_postes'] ?? $licence['NOMBRE_POSTES'] ?? 0;

                        $cleAffichee = $cleLicence;

                        if (strlen($cleLicence) > 22) {
                            $cleAffichee = substr($cleLicence, 0, 8) . '••••' . substr($cleLicence, -6);
                        }

                        if (!empty($dateFin) && $dateFin < $today) {
                            $statutLicence = 'Expirée';
                            $badgeClass = 'licence-expired';
                            $iconStatut = 'bi-x-circle-fill';
                        } elseif (!empty($dateFin) && $dateFin <= $dateDans30Jours) {
                            $statutLicence = 'Expire bientôt';
                            $badgeClass = 'licence-warning';
                            $iconStatut = 'bi-exclamation-triangle-fill';
                        } else {
                            $statutLicence = 'Active';
                            $badgeClass = 'licence-active';
                            $iconStatut = 'bi-check-circle-fill';
                        }
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#LIC-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="licence-software-cell">
                                    <div class="licence-software-icon">
                                        <i class="bi bi-disc-fill"></i>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($nomLogiciel ?: 'Logiciel'); ?></strong>
                                        <small>
                                            <?= htmlspecialchars($editeur ?: 'Éditeur non défini'); ?>
                                            <?php if (!empty($version)): ?>
                                                • v<?= htmlspecialchars($version); ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="license-key-badge"
                                      title="<?= htmlspecialchars($cleLicence); ?>">
                                    <i class="bi bi-key-fill"></i>
                                    <?= htmlspecialchars($cleAffichee ?: '-'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="date-badge">
                                    <i class="bi bi-calendar-check"></i>
                                    <?= !empty($dateDebut) ? date('d/m/Y', strtotime($dateDebut)) : '-'; ?>
                                </span>
                            </td>

                            <td>
                                <span class="date-badge">
                                    <i class="bi bi-calendar-x"></i>
                                    <?= !empty($dateFin) ? date('d/m/Y', strtotime($dateFin)) : '-'; ?>
                                </span>
                            </td>

                            <td>
                                <span class="posts-badge">
                                    <i class="bi bi-pc-display"></i>
                                    <?= htmlspecialchars($nombrePostes); ?> postes
                                </span>
                            </td>

                            <td>
                                <span class="badge <?= $badgeClass; ?>">
                                    <i class="bi <?= $iconStatut; ?>"></i>
                                    <?= $statutLicence; ?>
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-licence&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-licence&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette licence ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-key fs-1"></i>
                            <br><br>
                            Aucune licence trouvée.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>