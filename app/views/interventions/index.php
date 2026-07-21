<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$interventions = $interventions ?? [];

$totalInterventions = count($interventions);
$totalEnCours = 0;
$totalTerminees = 0;
$totalEnAttente = 0;

$sommeTempsReponse = 0;
$sommeTempsResolution = 0;
$countTempsReponse = 0;
$countTempsResolution = 0;

foreach ($interventions as $intervention) {
    $statut = strtolower($intervention['statut'] ?? $intervention['STATUT'] ?? '');

    if ($statut == 'en cours') {
        $totalEnCours++;
    } elseif ($statut == 'terminée' || $statut == 'terminee') {
        $totalTerminees++;
    } elseif ($statut == 'en attente') {
        $totalEnAttente++;
    }

    $tempsReponse = $intervention['temps_reponse'] ?? 0;
    $tempsResolution = $intervention['temps_resolution'] ?? 0;

    if (!empty($tempsReponse)) {
        $sommeTempsReponse += $tempsReponse;
        $countTempsReponse++;
    }

    if (!empty($tempsResolution)) {
        $sommeTempsResolution += $tempsResolution;
        $countTempsResolution++;
    }
}

$moyenneTempsReponse = $countTempsReponse > 0 ? round($sommeTempsReponse / $countTempsReponse, 1) : 0;
$moyenneTempsResolution = $countTempsResolution > 0 ? round($sommeTempsResolution / $countTempsResolution, 1) : 0;
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des interventions</h2>
            <p>Suivez les interventions techniques, les techniciens et les délais de traitement.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-intervention" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Nouvelle intervention
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-tools"></i>
            </div>
            <div>
                <span>Total interventions</span>
                <h3><?= $totalInterventions; ?></h3>
                <small>Interventions enregistrées</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <span>En cours</span>
                <h3><?= $totalEnCours; ?></h3>
                <small>Traitement actif</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <span>Terminées</span>
                <h3><?= $totalTerminees; ?></h3>
                <small>Interventions clôturées</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-stopwatch-fill"></i>
            </div>
            <div>
                <span>Temps réponse moy.</span>
                <h3><?= $moyenneTempsReponse; ?></h3>
                <small>Délai moyen enregistré</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="interventions">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par ticket, technicien, rapport, statut..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Statut</label>
                    <select class="form-select" disabled>
                        <option>Tous les statuts</option>
                        <option>En cours</option>
                        <option>Terminée</option>
                        <option>En attente</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=interventions" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des interventions</h5>
                <small><?= $totalInterventions; ?> intervention(s) trouvée(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-wrench-adjustable-circle"></i>
                Suivi technique
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Ticket</th>
                    <th>Technicien</th>
                    <th>Date intervention</th>
                    <th>Statut</th>
                    <th>Temps réponse</th>
                    <th>Temps résolution</th>
                    <th>Rapport</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($interventions)): ?>

                    <?php foreach ($interventions as $intervention): ?>

                        <?php
                        $id = $intervention['id_intervention'] ?? $intervention['ID_INTERVENTION'] ?? '';
                        $idTicket = $intervention['id_ticket'] ?? $intervention['ID_TICKET'] ?? '';
                        $titreTicket = $intervention['titre_ticket'] ?? $intervention['TITRE'] ?? 'Ticket';
                        $rapport = $intervention['rapport'] ?? $intervention['RAPPORT'] ?? '';
                        $duree = $intervention['duree'] ?? $intervention['DUREE'] ?? '';
                        $dateIntervention = $intervention['date_intervention'] ?? $intervention['DATE_INTERVENTION'] ?? '';
                        $statut = $intervention['statut'] ?? $intervention['STATUT'] ?? '';
                        $tempsReponse = $intervention['temps_reponse'] ?? '-';
                        $tempsResolution = $intervention['temps_resolution'] ?? '-';
                        $nomTechnicien = $intervention['nom_technicien'] ?? $intervention['NOM'] ?? '';
                        $prenomTechnicien = $intervention['prenom_technicien'] ?? $intervention['PRENOM'] ?? '';

                        $statutLower = strtolower($statut);
                        $initiales = strtoupper(substr($prenomTechnicien, 0, 1) . substr($nomTechnicien, 0, 1));
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#INT-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="intervention-ticket-cell">
                                    <div class="intervention-icon">
                                        <i class="bi bi-ticket-detailed-fill"></i>
                                    </div>
                                    <div>
                                        <strong>#TKT-<?= htmlspecialchars($idTicket); ?></strong>
                                        <small><?= htmlspecialchars($titreTicket); ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="user-cell">
                                    <div class="table-avatar">
                                        <?= htmlspecialchars($initiales ?: 'T'); ?>
                                    </div>
                                    <div>
                                        <strong>
                                            <?= htmlspecialchars(trim($prenomTechnicien . ' ' . $nomTechnicien) ?: 'Non assigné'); ?>
                                        </strong>
                                        <small>Technicien</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <?= !empty($dateIntervention) ? date('d/m/Y H:i', strtotime($dateIntervention)) : '-'; ?>
                            </td>

                            <td>
                                <?php if ($statutLower == 'en cours'): ?>
                                    <span class="badge intervention-progress">
                                        <i class="bi bi-hourglass-split"></i>
                                        En cours
                                    </span>
                                <?php elseif ($statutLower == 'terminée' || $statutLower == 'terminee'): ?>
                                    <span class="badge intervention-done">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Terminée
                                    </span>
                                <?php elseif ($statutLower == 'en attente'): ?>
                                    <span class="badge intervention-waiting">
                                        <i class="bi bi-clock-fill"></i>
                                        En attente
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($statut ?: 'Non défini'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="duration-badge">
                                    <i class="bi bi-stopwatch"></i>
                                    <?= htmlspecialchars($tempsReponse); ?>
                                </span>
                            </td>

                            <td>
                                <span class="duration-badge">
                                    <i class="bi bi-clock-history"></i>
                                    <?= htmlspecialchars($tempsResolution); ?>
                                </span>
                            </td>

                            <td>
                                <span class="rapport-badge"
                                      title="<?= htmlspecialchars($rapport); ?>">
                                    <i class="bi bi-file-earmark-text-fill"></i>
                                    Rapport
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-intervention&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-intervention&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette intervention ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-tools fs-1"></i>
                            <br><br>
                            Aucune intervention trouvée.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>