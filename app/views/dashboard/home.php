<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$roleDashboard = $roleDashboard ?? 'employe';
$stats = $stats ?? [];

$equipementsParLocal = $equipementsParLocal ?? [];
$ticketsParStatut = $ticketsParStatut ?? [];
$derniersTickets = $derniersTickets ?? [];
$licencesExpirees = $licencesExpirees ?? [];
$activitesRecentes = $activitesRecentes ?? [];

$mesInterventions = $mesInterventions ?? [];
$mesTickets = $mesTickets ?? [];

$prenomConnecte = $_SESSION['prenom'] ?? 'Utilisateur';
$nomConnecte = $_SESSION['nom'] ?? '';

$maxLocal = 1;

foreach ($equipementsParLocal as $local) {
    $totalLocal = (int)($local['total'] ?? 0);

    if ($totalLocal > $maxLocal) {
        $maxLocal = $totalLocal;
    }
}

$totalTickets = (int)($stats['tickets'] ?? 0);

?>

<div class="dashboard-page">

    <?php if ($roleDashboard === 'administrateur'): ?>

        <!-- ========================================================= -->
        <!-- DASHBOARD ADMINISTRATEUR -->
        <!-- ========================================================= -->

        <div class="dashboard-welcome-card">

            <div>
                <span class="dashboard-role-label">
                    <i class="bi bi-shield-check"></i>
                    Espace administrateur
                </span>

                <h2>
                    Bonjour <?= htmlspecialchars($prenomConnecte); ?> 👋
                </h2>

                <p>
                    Voici une vue globale du parc informatique et de l’activité
                    du service IT de la FSJES Oujda.
                </p>
            </div>

            <div class="dashboard-welcome-icon">
                <i class="bi bi-speedometer2"></i>
            </div>

        </div>

        <div class="dashboard-kpi-grid">

            <div class="dash-kpi-card">
                <div class="dash-kpi-icon brown">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div>
                    <span>Utilisateurs</span>
                    <h3><?= $stats['utilisateurs'] ?? 0; ?></h3>
                    <small class="trend-up">
                        <i class="bi bi-person-check"></i>
                        Comptes enregistrés
                    </small>
                </div>
            </div>

            <div class="dash-kpi-card">
                <div class="dash-kpi-icon green">
                    <i class="bi bi-pc-display"></i>
                </div>

                <div>
                    <span>Équipements</span>
                    <h3><?= $stats['equipements'] ?? 0; ?></h3>
                    <small class="trend-up">
                        <i class="bi bi-box-seam"></i>
                        Parc informatique
                    </small>
                </div>
            </div>

            <div class="dash-kpi-card">
                <div class="dash-kpi-icon orange">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>

                <div>
                    <span>Tickets</span>
                    <h3><?= $stats['tickets'] ?? 0; ?></h3>
                    <small class="trend-down">
                        <i class="bi bi-headset"></i>
                        Demandes d’assistance
                    </small>
                </div>
            </div>

            <div class="dash-kpi-card">
                <div class="dash-kpi-icon blue">
                    <i class="bi bi-tools"></i>
                </div>

                <div>
                    <span>Interventions</span>
                    <h3><?= $stats['interventions'] ?? 0; ?></h3>
                    <small class="trend-up">
                        <i class="bi bi-wrench-adjustable"></i>
                        Suivi technique
                    </small>
                </div>
            </div>

            <div class="dash-kpi-card">
                <div class="dash-kpi-icon purple">
                    <i class="bi bi-disc-fill"></i>
                </div>

                <div>
                    <span>Logiciels</span>
                    <h3><?= $stats['logiciels'] ?? 0; ?></h3>
                    <small class="trend-up">
                        <i class="bi bi-window"></i>
                        Logiciels enregistrés
                    </small>
                </div>
            </div>

            <div class="dash-kpi-card">
                <div class="dash-kpi-icon red">
                    <i class="bi bi-key-fill"></i>
                </div>

                <div>
                    <span>Licences</span>
                    <h3><?= $stats['licences'] ?? 0; ?></h3>
                    <small class="trend-down">
                        <i class="bi bi-calendar-event"></i>
                        Licences à surveiller
                    </small>
                </div>
            </div>

        </div>

        <div class="dashboard-status-grid">

            <div class="status-card">
                <div>
                    <span>Équipements disponibles</span>
                    <h4><?= $stats['equipements_disponibles'] ?? 0; ?></h4>
                    <small>Prêts à être affectés</small>
                </div>

                <div class="status-ring green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>

            <div class="status-card">
                <div>
                    <span>Équipements affectés</span>
                    <h4><?= $stats['equipements_affectes'] ?? 0; ?></h4>
                    <small>En cours d’utilisation</small>
                </div>

                <div class="status-ring blue">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>

            <div class="status-card">
                <div>
                    <span>Tickets ouverts</span>
                    <h4><?= $stats['tickets_ouverts'] ?? 0; ?></h4>
                    <small>Demandes non traitées</small>
                </div>

                <div class="status-ring orange">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
            </div>

            <div class="status-card">
                <div>
                    <span>Licences expirées</span>
                    <h4><?= $stats['licences_expirees'] ?? 0; ?></h4>
                    <small>À renouveler</small>
                </div>

                <div class="status-ring red">
                    <i class="bi bi-calendar-x-fill"></i>
                </div>
            </div>

        </div>

        <div class="dashboard-main-grid">

            <div class="dash-panel">

                <div class="panel-header">
                    <div>
                        <h5>Équipements par local</h5>
                        <small>Répartition du parc informatique par espace</small>
                    </div>

                    <span class="panel-chip">
                        Par nombre
                    </span>
                </div>

                <div class="local-bars">

                    <?php if (!empty($equipementsParLocal)): ?>

                        <?php foreach ($equipementsParLocal as $local): ?>

                            <?php
                            $totalLocal = (int)($local['total'] ?? 0);

                            $percent = $maxLocal > 0
                                ? ($totalLocal / $maxLocal) * 100
                                : 0;
                            ?>

                            <div class="local-bar-item">

                                <div class="local-bar-label">
                                    <span>
                                        <?= htmlspecialchars($local['nom_local'] ?? 'Local'); ?>
                                    </span>

                                    <strong><?= $totalLocal; ?></strong>
                                </div>

                                <div class="local-bar-track">
                                    <div class="local-bar-fill"
                                         style="width: <?= $percent; ?>%;">
                                    </div>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="empty-box">
                            Aucun local trouvé.
                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <div class="dash-panel">

                <div class="panel-header">
                    <div>
                        <h5>Tickets par statut</h5>
                        <small>Suivi de l’état des demandes</small>
                    </div>
                </div>

                <div class="ticket-status-list">

                    <?php if (!empty($ticketsParStatut)): ?>

                        <?php foreach ($ticketsParStatut as $ticketStatut): ?>

                            <?php
                            $totalStatut = (int)($ticketStatut['total'] ?? 0);

                            $percent = $totalTickets > 0
                                ? round(($totalStatut / $totalTickets) * 100, 1)
                                : 0;
                            ?>

                            <div class="ticket-status-item">

                                <div class="ticket-status-top">
                                    <span>
                                        <?= htmlspecialchars($ticketStatut['statut'] ?? 'Statut'); ?>
                                    </span>

                                    <strong>
                                        <?= $totalStatut; ?> / <?= $percent; ?>%
                                    </strong>
                                </div>

                                <div class="ticket-status-track">
                                    <div class="ticket-status-fill"
                                         style="width: <?= $percent; ?>%;">
                                    </div>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="empty-box">
                            Aucun ticket trouvé.
                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <div class="dash-panel">

                <div class="panel-header">
                    <div>
                        <h5>Licences expirées</h5>
                        <small>Licences nécessitant une action</small>
                    </div>

                    <a href="<?= BASE_URL ?>?page=licences">
                        Voir tout
                    </a>
                </div>

                <div class="expired-list">

                    <?php if (!empty($licencesExpirees)): ?>

                        <?php foreach ($licencesExpirees as $licence): ?>

                            <div class="expired-item">

                                <div class="expired-icon">
                                    <i class="bi bi-key-fill"></i>
                                </div>

                                <div>
                                    <strong>
                                        <?= htmlspecialchars($licence['nom_logiciel'] ?? 'Logiciel'); ?>
                                    </strong>

                                    <small>
                                        <?= htmlspecialchars($licence['editeur'] ?? ''); ?>
                                    </small>
                                </div>

                                <span>
                                    <?= !empty($licence['date_fin'])
                                        ? date('d/m/Y', strtotime($licence['date_fin']))
                                        : '-'; ?>
                                </span>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="success-empty">
                            <i class="bi bi-check-circle-fill"></i>
                            <p>Aucune licence expirée.</p>
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <div class="dashboard-bottom-grid">

            <div class="dash-panel">

                <div class="panel-header">
                    <div>
                        <h5>Derniers tickets</h5>
                        <small>Les dernières demandes d’assistance</small>
                    </div>

                    <a href="<?= BASE_URL ?>?page=tickets">
                        Voir tous les tickets
                    </a>
                </div>

                <div class="table-responsive">

                    <table class="table modern-dashboard-table">

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Demandeur</th>
                            <th>Statut</th>
                            <th>Priorité</th>
                            <th>Créé le</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php if (!empty($derniersTickets)): ?>

                            <?php foreach ($derniersTickets as $ticket): ?>

                                <tr>
                                    <td>
                                        #TKT-<?= htmlspecialchars($ticket['id_ticket'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($ticket['titre'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            trim(
                                                ($ticket['prenom'] ?? '') . ' ' .
                                                ($ticket['nom'] ?? '')
                                            )
                                        ); ?>
                                    </td>

                                    <td>
                                        <?php
                                        $statut = $ticket['statut'] ?? '';

                                        if ($statut === 'Ouvert') {
                                            echo '<span class="badge bg-warning">Ouvert</span>';
                                        } elseif ($statut === 'En cours') {
                                            echo '<span class="badge bg-primary">En cours</span>';
                                        } elseif ($statut === 'Résolu') {
                                            echo '<span class="badge bg-success">Résolu</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">'
                                                . htmlspecialchars($statut)
                                                . '</span>';
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        $priorite = $ticket['priorite'] ?? '';

                                        if ($priorite === 'Haute') {
                                            echo '<span class="priority-dot red"></span> Haute';
                                        } elseif ($priorite === 'Moyenne') {
                                            echo '<span class="priority-dot orange"></span> Moyenne';
                                        } else {
                                            echo '<span class="priority-dot green"></span> Basse';
                                        }
                                        ?>
                                    </td>

                                    <td>
                                        <?= !empty($ticket['date_creation'])
                                            ? date(
                                                'd/m/Y H:i',
                                                strtotime($ticket['date_creation'])
                                            )
                                            : '-'; ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted py-4">
                                    Aucun ticket trouvé.
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

            <div class="dash-panel">

                <div class="panel-header">
                    <div>
                        <h5>Activité récente</h5>
                        <small>Dernières actions enregistrées</small>
                    </div>

                    <a href="<?= BASE_URL ?>?page=historiques">
                        Voir tout
                    </a>
                </div>

                <div class="activity-list">

                    <?php if (!empty($activitesRecentes)): ?>

                        <?php foreach ($activitesRecentes as $activite): ?>

                            <div class="activity-item">

                                <div class="activity-dot">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                                <div>
                                    <span>
                                        <?= !empty($activite['date_action'])
                                            ? date(
                                                'd/m/Y H:i',
                                                strtotime($activite['date_action'])
                                            )
                                            : '-'; ?>
                                    </span>

                                    <strong>
                                        <?= htmlspecialchars($activite['action'] ?? 'Action'); ?>
                                        -
                                        <?= htmlspecialchars($activite['table_concernee'] ?? ''); ?>
                                    </strong>

                                    <small>
                                        Par
                                        <?= htmlspecialchars(
                                            trim(
                                                ($activite['prenom'] ?? 'FSJES') . ' ' .
                                                ($activite['nom'] ?? 'Admin')
                                            )
                                        ); ?>
                                    </small>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="empty-box">
                            Aucune activité trouvée.
                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    <?php elseif ($roleDashboard === 'technicien'): ?>

        <!-- ========================================================= -->
        <!-- DASHBOARD TECHNICIEN -->
        <!-- ========================================================= -->

        <div class="dashboard-welcome-card technician">

            <div>
                <span class="dashboard-role-label">
                    <i class="bi bi-tools"></i>
                    Espace technicien
                </span>

                <h2>
                    Bonjour <?= htmlspecialchars($prenomConnecte); ?> 👋
                </h2>

                <p>
                    Consultez vos interventions affectées et suivez leur état
                    d’avancement.
                </p>
            </div>

            <a href="<?= BASE_URL ?>?page=interventions"
               class="btn btn-primary">

                <i class="bi bi-tools"></i>
                Voir mes interventions

            </a>

        </div>

        <div class="role-dashboard-stats">

            <div class="role-stat-card">
                <div class="role-stat-icon purple">
                    <i class="bi bi-tools"></i>
                </div>

                <div>
                    <span>Mes interventions</span>
                    <h3><?= $stats['mes_interventions'] ?? 0; ?></h3>
                    <small>Total affecté</small>
                </div>
            </div>

            <div class="role-stat-card">
                <div class="role-stat-icon blue">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <span>En cours</span>
                    <h3><?= $stats['interventions_en_cours'] ?? 0; ?></h3>
                    <small>À traiter</small>
                </div>
            </div>

            <div class="role-stat-card">
                <div class="role-stat-icon green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div>
                    <span>Terminées</span>
                    <h3><?= $stats['interventions_terminees'] ?? 0; ?></h3>
                    <small>Interventions clôturées</small>
                </div>
            </div>

            <div class="role-stat-card">
                <div class="role-stat-icon orange">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <div>
                    <span>Équipements en maintenance</span>
                    <h3><?= $stats['equipements_maintenance'] ?? 0; ?></h3>
                    <small>À surveiller</small>
                </div>
            </div>

        </div>

        <div class="dash-panel">

            <div class="panel-header">

                <div>
                    <h5>Mes dernières interventions</h5>
                    <small>
                        Interventions qui vous sont actuellement affectées
                    </small>
                </div>

                <a href="<?= BASE_URL ?>?page=interventions">
                    Voir toutes
                </a>

            </div>

            <div class="table-responsive">

                <table class="table modern-dashboard-table">

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ticket</th>
                        <th>Titre</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Durée</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if (!empty($mesInterventions)): ?>

                        <?php foreach ($mesInterventions as $intervention): ?>

                            <?php
                            $statut = $intervention['statut'] ?? '';
                            $priorite = $intervention['priorite'] ?? '';
                            ?>

                            <tr>

                                <td>
                                    #INT-<?= htmlspecialchars(
                                        $intervention['id_intervention'] ?? ''
                                    ); ?>
                                </td>

                                <td>
                                    #TKT-<?= htmlspecialchars(
                                        $intervention['id_ticket'] ?? ''
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $intervention['titre_ticket'] ?? ''
                                    ); ?>
                                </td>

                                <td>
                                    <?php if ($priorite === 'Haute'): ?>
                                        <span class="badge priority-high">
                                            Haute
                                        </span>
                                    <?php elseif ($priorite === 'Moyenne'): ?>
                                        <span class="badge priority-medium">
                                            Moyenne
                                        </span>
                                    <?php else: ?>
                                        <span class="badge priority-low">
                                            Basse
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($statut === 'Terminée'): ?>
                                        <span class="badge intervention-done">
                                            Terminée
                                        </span>
                                    <?php elseif ($statut === 'En cours'): ?>
                                        <span class="badge intervention-progress">
                                            En cours
                                        </span>
                                    <?php else: ?>
                                        <span class="badge intervention-waiting">
                                            <?= htmlspecialchars($statut); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= !empty($intervention['date_intervention'])
                                        ? date(
                                            'd/m/Y H:i',
                                            strtotime(
                                                $intervention['date_intervention']
                                            )
                                        )
                                        : '-'; ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $intervention['duree'] ?? '-'
                                    ); ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7"
                                class="text-center text-muted py-5">

                                <i class="bi bi-tools fs-1"></i>
                                <br><br>
                                Aucune intervention ne vous est affectée.

                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="dashboard-quick-actions">

            <a href="<?= BASE_URL ?>?page=interventions"
               class="quick-action-card">

                <div class="quick-action-icon purple">
                    <i class="bi bi-tools"></i>
                </div>

                <div>
                    <strong>Mes interventions</strong>
                    <small>Consulter et mettre à jour le travail technique</small>
                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

            <a href="<?= BASE_URL ?>?page=tickets"
               class="quick-action-card">

                <div class="quick-action-icon orange">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>

                <div>
                    <strong>Tickets</strong>
                    <small>Consulter les demandes d’assistance</small>
                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

            <a href="<?= BASE_URL ?>?page=equipements"
               class="quick-action-card">

                <div class="quick-action-icon green">
                    <i class="bi bi-pc-display"></i>
                </div>

                <div>
                    <strong>Équipements</strong>
                    <small>Consulter le parc informatique</small>
                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

        </div>

    <?php else: ?>

        <!-- ========================================================= -->
        <!-- DASHBOARD EMPLOYÉ -->
        <!-- ========================================================= -->

        <div class="dashboard-welcome-card employee">

            <div>
                <span class="dashboard-role-label">
                    <i class="bi bi-person-circle"></i>
                    Espace utilisateur
                </span>

                <h2>
                    Bonjour <?= htmlspecialchars($prenomConnecte); ?> 👋
                </h2>

                <p>
                    Créez une demande d’assistance et suivez l’avancement
                    de vos tickets personnels.
                </p>
            </div>

            <a href="<?= BASE_URL ?>?page=ajouter-ticket"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Créer un ticket

            </a>

        </div>

        <div class="role-dashboard-stats">

            <div class="role-stat-card">
                <div class="role-stat-icon brown">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>

                <div>
                    <span>Mes tickets</span>
                    <h3><?= $stats['mes_tickets'] ?? 0; ?></h3>
                    <small>Total de vos demandes</small>
                </div>
            </div>

            <div class="role-stat-card">
                <div class="role-stat-icon orange">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>

                <div>
                    <span>Tickets ouverts</span>
                    <h3><?= $stats['tickets_ouverts'] ?? 0; ?></h3>
                    <small>En attente de traitement</small>
                </div>
            </div>

            <div class="role-stat-card">
                <div class="role-stat-icon blue">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <span>En cours</span>
                    <h3><?= $stats['tickets_en_cours'] ?? 0; ?></h3>
                    <small>Demandes en traitement</small>
                </div>
            </div>

            <div class="role-stat-card">
                <div class="role-stat-icon green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div>
                    <span>Résolus</span>
                    <h3><?= $stats['tickets_resolus'] ?? 0; ?></h3>
                    <small>Demandes terminées</small>
                </div>
            </div>

        </div>

        <div class="dash-panel">

            <div class="panel-header">

                <div>
                    <h5>Mes derniers tickets</h5>
                    <small>
                        Suivez l’avancement de vos demandes d’assistance
                    </small>
                </div>

                <a href="<?= BASE_URL ?>?page=tickets">
                    Voir tous mes tickets
                </a>

            </div>

            <div class="table-responsive">

                <table class="table modern-dashboard-table">

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Priorité</th>
                        <th>Statut</th>
                        <th>Créé le</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if (!empty($mesTickets)): ?>

                        <?php foreach ($mesTickets as $ticket): ?>

                            <?php
                            $statut = $ticket['statut'] ?? '';
                            $priorite = $ticket['priorite'] ?? '';
                            ?>

                            <tr>

                                <td>
                                    #TKT-<?= htmlspecialchars(
                                        $ticket['id_ticket'] ?? ''
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $ticket['titre'] ?? ''
                                    ); ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        mb_strimwidth(
                                            $ticket['description'] ?? '',
                                            0,
                                            60,
                                            '...'
                                        )
                                    ); ?>
                                </td>

                                <td>
                                    <?php if ($priorite === 'Haute'): ?>
                                        <span class="badge priority-high">
                                            Haute
                                        </span>
                                    <?php elseif ($priorite === 'Moyenne'): ?>
                                        <span class="badge priority-medium">
                                            Moyenne
                                        </span>
                                    <?php else: ?>
                                        <span class="badge priority-low">
                                            Basse
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($statut === 'Ouvert'): ?>
                                        <span class="badge ticket-open">
                                            Ouvert
                                        </span>
                                    <?php elseif ($statut === 'En cours'): ?>
                                        <span class="badge ticket-progress">
                                            En cours
                                        </span>
                                    <?php elseif ($statut === 'Résolu'): ?>
                                        <span class="badge ticket-done">
                                            Résolu
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($statut); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= !empty($ticket['date_creation'])
                                        ? date(
                                            'd/m/Y H:i',
                                            strtotime($ticket['date_creation'])
                                        )
                                        : '-'; ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6"
                                class="text-center text-muted py-5">

                                <i class="bi bi-ticket-detailed fs-1"></i>
                                <br><br>
                                Vous n’avez encore créé aucun ticket.

                                <br><br>

                                <a href="<?= BASE_URL ?>?page=ajouter-ticket"
                                   class="btn btn-primary">

                                    <i class="bi bi-plus-circle"></i>
                                    Créer mon premier ticket

                                </a>

                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="dashboard-quick-actions">

            <a href="<?= BASE_URL ?>?page=ajouter-ticket"
               class="quick-action-card">

                <div class="quick-action-icon orange">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>

                <div>
                    <strong>Créer un ticket</strong>
                    <small>Envoyer une nouvelle demande d’assistance</small>
                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

            <a href="<?= BASE_URL ?>?page=tickets"
               class="quick-action-card">

                <div class="quick-action-icon blue">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>

                <div>
                    <strong>Mes tickets</strong>
                    <small>Consulter le statut de mes demandes</small>
                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

            <a href="<?= BASE_URL ?>?page=profil"
               class="quick-action-card">

                <div class="quick-action-icon green">
                    <i class="bi bi-person-circle"></i>
                </div>

                <div>
                    <strong>Mon profil</strong>
                    <small>Consulter les informations de mon compte</small>
                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

        </div>

    <?php endif; ?>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>