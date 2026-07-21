<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$tickets = $tickets ?? [];

$totalTickets = count($tickets);
$totalOuverts = 0;
$totalEnCours = 0;
$totalEnAttente = 0;
$totalResolus = 0;

foreach ($tickets as $ticket) {
    $statut = strtolower($ticket['statut'] ?? $ticket['STATUT'] ?? '');

    if ($statut == 'ouvert') {
        $totalOuverts++;
    } elseif ($statut == 'en cours') {
        $totalEnCours++;
    } elseif ($statut == 'en attente') {
        $totalEnAttente++;
    } elseif ($statut == 'résolu' || $statut == 'resolu') {
        $totalResolus++;
    }
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des tickets</h2>
            <p>Suivez les demandes d’assistance et les incidents déclarés.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-ticket" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Nouveau ticket
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-ticket-detailed-fill"></i>
            </div>
            <div>
                <span>Total tickets</span>
                <h3><?= $totalTickets; ?></h3>
                <small>Demandes enregistrées</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <div>
                <span>Tickets ouverts</span>
                <h3><?= $totalOuverts; ?></h3>
                <small>À traiter</small>
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
                <span>Résolus</span>
                <h3><?= $totalResolus; ?></h3>
                <small>Tickets clôturés</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="tickets">

            <div class="row g-3 align-items-end">

                <div class="col-lg-5 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par titre, utilisateur, statut, priorité..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Priorité</label>
                    <select class="form-select" disabled>
                        <option>Toutes</option>
                        <option>Haute</option>
                        <option>Moyenne</option>
                        <option>Basse</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label">Statut</label>
                    <select class="form-select" disabled>
                        <option>Tous</option>
                        <option>Ouvert</option>
                        <option>En cours</option>
                        <option>Résolu</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=tickets" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des tickets</h5>
                <small><?= $totalTickets; ?> ticket(s) trouvé(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-headset"></i>
                Assistance IT
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Ticket</th>
                    <th>Demandeur</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Moyen</th>
                    <th>Date création</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($tickets)): ?>

                    <?php foreach ($tickets as $ticket): ?>

                        <?php
                        $id = $ticket['id_ticket'] ?? $ticket['ID_TICKET'] ?? '';
                        $titre = $ticket['titre'] ?? $ticket['TITRE'] ?? '';
                        $description = $ticket['description'] ?? $ticket['DESCRIPTION'] ?? '';
                        $priorite = $ticket['priorite'] ?? $ticket['PRIORITE'] ?? '';
                        $statut = $ticket['statut'] ?? $ticket['STATUT'] ?? '';
                        $dateCreation = $ticket['date_creation'] ?? $ticket['DATE_CREATION'] ?? '';
                        $nom = $ticket['nom'] ?? $ticket['NOM'] ?? '';
                        $prenom = $ticket['prenom'] ?? $ticket['PRENOM'] ?? '';
                        $moyen = $ticket['moyen'] ?? $ticket['LIBELLE'] ?? '-';

                        $prioriteLower = strtolower($priorite);
                        $statutLower = strtolower($statut);

                        $initiales = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#TKT-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="ticket-cell">
                                    <div class="ticket-icon">
                                        <i class="bi bi-ticket-detailed-fill"></i>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($titre); ?></strong>
                                        <small>
                                            <?= htmlspecialchars(mb_strimwidth($description, 0, 55, '...')); ?>
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="user-cell">
                                    <div class="table-avatar">
                                        <?= htmlspecialchars($initiales ?: 'U'); ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($prenom . ' ' . $nom); ?></strong>
                                        <small>Demandeur</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <?php if ($prioriteLower == 'haute'): ?>
                                    <span class="badge priority-high">
                                        <i class="bi bi-arrow-up-circle-fill"></i>
                                        Haute
                                    </span>
                                <?php elseif ($prioriteLower == 'moyenne'): ?>
                                    <span class="badge priority-medium">
                                        <i class="bi bi-dash-circle-fill"></i>
                                        Moyenne
                                    </span>
                                <?php else: ?>
                                    <span class="badge priority-low">
                                        <i class="bi bi-arrow-down-circle-fill"></i>
                                        <?= htmlspecialchars($priorite ?: 'Basse'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($statutLower == 'ouvert'): ?>
                                    <span class="badge ticket-open">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        Ouvert
                                    </span>
                                <?php elseif ($statutLower == 'en cours'): ?>
                                    <span class="badge ticket-progress">
                                        <i class="bi bi-hourglass-split"></i>
                                        En cours
                                    </span>
                                <?php elseif ($statutLower == 'en attente'): ?>
                                    <span class="badge ticket-waiting">
                                        <i class="bi bi-clock-fill"></i>
                                        En attente
                                    </span>
                                <?php elseif ($statutLower == 'résolu' || $statutLower == 'resolu'): ?>
                                    <span class="badge ticket-done">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Résolu
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($statut ?: 'Non défini'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <span class="moyen-badge">
                                    <i class="bi bi-chat-dots-fill"></i>
                                    <?= htmlspecialchars($moyen); ?>
                                </span>
                            </td>

                            <td>
                                <?= !empty($dateCreation) ? date('d/m/Y H:i', strtotime($dateCreation)) : '-'; ?>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-ticket&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-ticket&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer ce ticket ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-ticket-detailed fs-1"></i>
                            <br><br>
                            Aucun ticket trouvé.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>