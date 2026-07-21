<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$logiciels = $logiciels ?? [];

$totalLogiciels = count($logiciels);
$editeurs = [];
$totalMicrosoft = 0;
$totalRecents = 0;

$dateLimite = date('Y-m-d', strtotime('-30 days'));

foreach ($logiciels as $logiciel) {
    $nomLogiciel = strtolower($logiciel['nom_logiciel'] ?? $logiciel['NOM_LOGICIEL'] ?? '');
    $editeur = $logiciel['editeur'] ?? $logiciel['EDITEUR'] ?? '';
    $dateInstallation = $logiciel['date_installation'] ?? $logiciel['DATE_INSTALLATION'] ?? '';

    if (!empty($editeur)) {
        $editeurs[$editeur] = true;
    }

    if (strpos($nomLogiciel, 'microsoft') !== false || strtolower($editeur) == 'microsoft') {
        $totalMicrosoft++;
    }

    if (!empty($dateInstallation) && $dateInstallation >= $dateLimite) {
        $totalRecents++;
    }
}

$totalEditeurs = count($editeurs);
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des logiciels</h2>
            <p>Gérez les logiciels installés et leurs informations principales.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-logiciel" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Ajouter un logiciel
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon purple">
                <i class="bi bi-disc-fill"></i>
            </div>
            <div>
                <span>Total logiciels</span>
                <h3><?= $totalLogiciels; ?></h3>
                <small>Logiciels enregistrés</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <span>Éditeurs</span>
                <h3><?= $totalEditeurs; ?></h3>
                <small>Fournisseurs logiciels</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-microsoft"></i>
            </div>
            <div>
                <span>Microsoft</span>
                <h3><?= $totalMicrosoft; ?></h3>
                <small>Logiciels Microsoft</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div>
                <span>Installés récemment</span>
                <h3><?= $totalRecents; ?></h3>
                <small>Derniers 30 jours</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="logiciels">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par nom, version, éditeur..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Éditeur</label>
                    <select class="form-select" disabled>
                        <option>Tous les éditeurs</option>
                        <option>Microsoft</option>
                        <option>Adobe</option>
                        <option>Avast</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=logiciels" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des logiciels</h5>
                <small><?= $totalLogiciels; ?> logiciel(s) trouvé(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-disc"></i>
                Inventaire logiciels
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Logiciel</th>
                    <th>Version</th>
                    <th>Éditeur</th>
                    <th>Date installation</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($logiciels)): ?>

                    <?php foreach ($logiciels as $logiciel): ?>

                        <?php
                        $id = $logiciel['id_logiciel'] ?? $logiciel['ID_LOGICIEL'] ?? '';
                        $nomLogiciel = $logiciel['nom_logiciel'] ?? $logiciel['NOM_LOGICIEL'] ?? '';
                        $version = $logiciel['version'] ?? $logiciel['VERSION'] ?? '';
                        $editeur = $logiciel['editeur'] ?? $logiciel['EDITEUR'] ?? '';
                        $dateInstallation = $logiciel['date_installation'] ?? $logiciel['DATE_INSTALLATION'] ?? '';

                        $nomLower = strtolower($nomLogiciel);
                        $editeurLower = strtolower($editeur);

                        if (strpos($nomLower, 'office') !== false || strpos($nomLower, 'word') !== false || strpos($nomLower, 'excel') !== false) {
                            $icon = 'bi-file-earmark-word-fill';
                            $badgeClass = 'software-type-office';
                            $typeLogiciel = 'Bureautique';
                        } elseif (strpos($nomLower, 'windows') !== false) {
                            $icon = 'bi-windows';
                            $badgeClass = 'software-type-system';
                            $typeLogiciel = 'Système';
                        } elseif (strpos($nomLower, 'antivirus') !== false || strpos($editeurLower, 'avast') !== false || strpos($editeurLower, 'eset') !== false) {
                            $icon = 'bi-shield-lock-fill';
                            $badgeClass = 'software-type-security';
                            $typeLogiciel = 'Sécurité';
                        } elseif (strpos($editeurLower, 'adobe') !== false) {
                            $icon = 'bi-file-earmark-pdf-fill';
                            $badgeClass = 'software-type-adobe';
                            $typeLogiciel = 'Adobe';
                        } else {
                            $icon = 'bi-disc-fill';
                            $badgeClass = 'software-type-default';
                            $typeLogiciel = 'Logiciel';
                        }
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#LOG-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="software-cell">
                                    <div class="software-icon">
                                        <i class="bi <?= $icon; ?>"></i>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($nomLogiciel ?: 'Logiciel'); ?></strong>
                                        <small><?= htmlspecialchars($typeLogiciel); ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="version-badge">
                                    <?= htmlspecialchars($version ?: '-'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="editor-badge">
                                    <i class="bi bi-building"></i>
                                    <?= htmlspecialchars($editeur ?: 'Non défini'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="date-badge">
                                    <i class="bi bi-calendar-check"></i>
                                    <?= !empty($dateInstallation) ? date('d/m/Y', strtotime($dateInstallation)) : '-'; ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge <?= $badgeClass; ?>">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Installé
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-logiciel&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-logiciel&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer ce logiciel ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-disc fs-1"></i>
                            <br><br>
                            Aucun logiciel trouvé.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>