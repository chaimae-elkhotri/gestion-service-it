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

if (!function_exists('dashboardT')) {
    function dashboardT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'dashboard_content.' . $key,
            $replacements
        );
    }
}

if (!function_exists('dashboardNormalize')) {
    function dashboardNormalize(string $value): string
    {
        $value = mb_strtolower(
            trim($value),
            'UTF-8'
        );

        return strtr($value, [
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'à' => 'a',
            'â' => 'a',
            'î' => 'i',
            'ï' => 'i',
            'ô' => 'o',
            'ù' => 'u',
            'û' => 'u',
            'ç' => 'c'
        ]);
    }
}

if (!function_exists('dashboardStatusLabel')) {
    function dashboardStatusLabel(string $status): string
    {
        $normalized = dashboardNormalize($status);

        return match ($normalized) {
            'ouvert', 'ouverte' =>
                dashboardT('status_open'),

            'en cours' =>
                dashboardT('status_progress'),

            'resolu', 'resolue' =>
                dashboardT('status_resolved'),

            'annule', 'annulee' =>
                dashboardT('status_cancelled'),

            'termine', 'terminee' =>
                dashboardT('status_completed'),

            'en attente' =>
                dashboardT('status_waiting'),

            default => $status
        };
    }
}

if (!function_exists('dashboardPriorityLabel')) {
    function dashboardPriorityLabel(string $priority): string
    {
        $normalized = dashboardNormalize($priority);

        return match ($normalized) {
            'haute' => dashboardT('priority_high'),
            'moyenne' => dashboardT('priority_medium'),
            'basse' => dashboardT('priority_low'),
            default => $priority
        };
    }
}

if (!function_exists('dashboardActionLabel')) {
    function dashboardActionLabel(string $action): string
    {
        $normalized = dashboardNormalize($action);

        if (str_contains($normalized, 'import')) {
            return dashboardT('action_import');
        }

        return match ($normalized) {
            'ajout' => dashboardT('action_add'),
            'modification' => dashboardT('action_update'),
            'suppression' => dashboardT('action_delete'),
            'annulation' => dashboardT('action_cancel'),
            default => $action
        };
    }
}

if (!function_exists('dashboardTableLabel')) {
    function dashboardTableLabel(string $table): string
    {
        $normalized = dashboardNormalize($table);

        return match ($normalized) {
            'utilisateur' => dashboardT('table_user'),
            'equipement' => dashboardT('table_equipment'),
            'ticket' => dashboardT('table_ticket'),
            'intervention' => dashboardT('table_intervention'),
            'logiciel' => dashboardT('table_software'),
            'licence' => dashboardT('table_license'),
            'affectation',
            'affectation_equipement' =>
                dashboardT('table_assignment'),
            'local' => dashboardT('table_local'),
            'categorie' => dashboardT('table_category'),
            default => $table
        };
    }
}

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

        <div class="dashboard-welcome-card">

            <div>

                <span class="dashboard-role-label">

                    <i class="bi bi-shield-check"></i>

                    <?= htmlspecialchars(
                        dashboardT('admin_space')
                    ); ?>

                </span>

                <h2>

                    <?= htmlspecialchars(
                        dashboardT(
                            'hello',
                            ['name' => $prenomConnecte]
                        )
                    ); ?>

                </h2>

                <p>
                    <?= htmlspecialchars(
                        dashboardT('admin_intro')
                    ); ?>
                </p>

            </div>

            <div class="dashboard-welcome-icon">
                <i class="bi bi-speedometer2"></i>
            </div>

        </div>

        <?php

        $adminKpis = [

            [
                'icon' => 'bi-people-fill',
                'color' => 'brown',
                'label' => dashboardT('users'),
                'value' => $stats['utilisateurs'] ?? 0,
                'smallIcon' => 'bi-person-check',
                'small' => dashboardT('registered_accounts')
            ],

            [
                'icon' => 'bi-pc-display',
                'color' => 'green',
                'label' => dashboardT('equipment'),
                'value' => $stats['equipements'] ?? 0,
                'smallIcon' => 'bi-box-seam',
                'small' => dashboardT('it_assets')
            ],

            [
                'icon' => 'bi-ticket-detailed-fill',
                'color' => 'orange',
                'label' => dashboardT('tickets'),
                'value' => $stats['tickets'] ?? 0,
                'smallIcon' => 'bi-headset',
                'small' => dashboardT('support_requests')
            ],

            [
                'icon' => 'bi-tools',
                'color' => 'blue',
                'label' => dashboardT('interventions'),
                'value' => $stats['interventions'] ?? 0,
                'smallIcon' => 'bi-wrench-adjustable',
                'small' => dashboardT('technical_tracking')
            ],

            [
                'icon' => 'bi-disc-fill',
                'color' => 'purple',
                'label' => dashboardT('software'),
                'value' => $stats['logiciels'] ?? 0,
                'smallIcon' => 'bi-window',
                'small' => dashboardT('registered_software')
            ],

            [
                'icon' => 'bi-key-fill',
                'color' => 'red',
                'label' => dashboardT('licenses'),
                'value' => $stats['licences'] ?? 0,
                'smallIcon' => 'bi-calendar-event',
                'small' => dashboardT('licenses_to_monitor')
            ]
        ];

        ?>

        <div class="dashboard-kpi-grid">

            <?php foreach ($adminKpis as $kpi): ?>

                <div class="dash-kpi-card">

                    <div class="dash-kpi-icon <?= $kpi['color']; ?>">

                        <i class="bi <?= $kpi['icon']; ?>"></i>

                    </div>

                    <div>

                        <span>
                            <?= htmlspecialchars($kpi['label']); ?>
                        </span>

                        <h3>
                            <?= (int)$kpi['value']; ?>
                        </h3>

                        <small class="trend-up">

                            <i class="bi <?= $kpi['smallIcon']; ?>"></i>

                            <?= htmlspecialchars($kpi['small']); ?>

                        </small>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <?php

        $statusCards = [

            [
                'label' => dashboardT('available_equipment'),
                'value' =>
                    $stats['equipements_disponibles'] ?? 0,
                'small' => dashboardT('ready_to_assign'),
                'color' => 'green',
                'icon' => 'bi-check-circle-fill'
            ],

            [
                'label' => dashboardT('assigned_equipment'),
                'value' =>
                    $stats['equipements_affectes'] ?? 0,
                'small' => dashboardT('currently_used'),
                'color' => 'blue',
                'icon' => 'bi-person-check-fill'
            ],

            [
                'label' => dashboardT('open_tickets'),
                'value' => $stats['tickets_ouverts'] ?? 0,
                'small' =>
                    dashboardT('unprocessed_requests'),
                'color' => 'orange',
                'icon' => 'bi-exclamation-circle-fill'
            ],

            [
                'label' => dashboardT('expired_licenses'),
                'value' => $stats['licences_expirees'] ?? 0,
                'small' => dashboardT('renew'),
                'color' => 'red',
                'icon' => 'bi-calendar-x-fill'
            ]
        ];

        ?>

        <div class="dashboard-status-grid">

            <?php foreach ($statusCards as $card): ?>

                <div class="status-card">

                    <div>

                        <span>
                            <?= htmlspecialchars($card['label']); ?>
                        </span>

                        <h4>
                            <?= (int)$card['value']; ?>
                        </h4>

                        <small>
                            <?= htmlspecialchars($card['small']); ?>
                        </small>

                    </div>

                    <div class="status-ring <?= $card['color']; ?>">

                        <i class="bi <?= $card['icon']; ?>"></i>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="dashboard-main-grid">

            <div class="dash-panel">

                <div class="panel-header">

                    <div>

                        <h5>
                            <?= htmlspecialchars(
                                dashboardT('equipment_by_local')
                            ); ?>
                        </h5>

                        <small>
                            <?= htmlspecialchars(
                                dashboardT(
                                    'equipment_distribution'
                                )
                            ); ?>
                        </small>

                    </div>

                    <span class="panel-chip">

                        <?= htmlspecialchars(
                            dashboardT('by_number')
                        ); ?>

                    </span>

                </div>

                <div class="local-bars">

                    <?php if (!empty($equipementsParLocal)): ?>

                        <?php foreach (
                            $equipementsParLocal as $local
                        ): ?>

                            <?php

                            $totalLocal =
                                (int)($local['total'] ?? 0);

                            $percent = $maxLocal > 0
                                ? ($totalLocal / $maxLocal) * 100
                                : 0;

                            ?>

                            <div class="local-bar-item">

                                <div class="local-bar-label">

                                    <span>

                                        <?= htmlspecialchars(
                                            $local['nom_local']
                                            ?? dashboardT(
                                                'default_local'
                                            )
                                        ); ?>

                                    </span>

                                    <strong>
                                        <?= $totalLocal; ?>
                                    </strong>

                                </div>

                                <div class="local-bar-track">

                                    <div
                                        class="local-bar-fill"
                                        style="width: <?= $percent; ?>%;">
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="empty-box">

                            <?= htmlspecialchars(
                                dashboardT('no_local')
                            ); ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <div class="dash-panel">

                <div class="panel-header">

                    <div>

                        <h5>
                            <?= htmlspecialchars(
                                dashboardT('tickets_by_status')
                            ); ?>
                        </h5>

                        <small>
                            <?= htmlspecialchars(
                                dashboardT(
                                    'request_status_tracking'
                                )
                            ); ?>
                        </small>

                    </div>

                </div>

                <div class="ticket-status-list">

                    <?php if (!empty($ticketsParStatut)): ?>

                        <?php foreach (
                            $ticketsParStatut as $ticketStatut
                        ): ?>

                            <?php

                            $totalStatut =
                                (int)($ticketStatut['total'] ?? 0);

                            $percent = $totalTickets > 0
                                ? round(
                                    (
                                        $totalStatut /
                                        $totalTickets
                                    ) * 100,
                                    1
                                )
                                : 0;

                            $statutLabel =
                                dashboardStatusLabel(
                                    $ticketStatut['statut'] ?? ''
                                );

                            ?>

                            <div class="ticket-status-item">

                                <div class="ticket-status-top">

                                    <span>
                                        <?= htmlspecialchars(
                                            $statutLabel
                                        ); ?>
                                    </span>

                                    <strong>

                                        <?= $totalStatut; ?>
                                        /
                                        <?= $percent; ?>%

                                    </strong>

                                </div>

                                <div class="ticket-status-track">

                                    <div
                                        class="ticket-status-fill"
                                        style="width: <?= $percent; ?>%;">
                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="empty-box">

                            <?= htmlspecialchars(
                                dashboardT('no_ticket')
                            ); ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <div class="dash-panel">

                <div class="panel-header">

                    <div>

                        <h5>
                            <?= htmlspecialchars(
                                dashboardT('expired_licenses')
                            ); ?>
                        </h5>

                        <small>
                            <?= htmlspecialchars(
                                dashboardT(
                                    'licenses_requiring_action'
                                )
                            ); ?>
                        </small>

                    </div>

                    <a href="<?= BASE_URL ?>?page=licences">

                        <?= htmlspecialchars(
                            dashboardT('see_all')
                        ); ?>

                    </a>

                </div>

                <div class="expired-list">

                    <?php if (!empty($licencesExpirees)): ?>

                        <?php foreach (
                            $licencesExpirees as $licence
                        ): ?>

                            <div class="expired-item">

                                <div class="expired-icon">
                                    <i class="bi bi-key-fill"></i>
                                </div>

                                <div>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $licence['nom_logiciel']
                                            ?? dashboardT(
                                                'default_software'
                                            )
                                        ); ?>

                                    </strong>

                                    <small>

                                        <?= htmlspecialchars(
                                            $licence['editeur'] ?? ''
                                        ); ?>

                                    </small>

                                </div>

                                <span>

                                    <?= !empty($licence['date_fin'])
                                        ? date(
                                            'd/m/Y',
                                            strtotime(
                                                $licence['date_fin']
                                            )
                                        )
                                        : '-'; ?>

                                </span>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="success-empty">

                            <i class="bi bi-check-circle-fill"></i>

                            <p>
                                <?= htmlspecialchars(
                                    dashboardT(
                                        'no_expired_license'
                                    )
                                ); ?>
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <div class="dashboard-bottom-grid">

            <div class="dash-panel">

                <div class="panel-header">

                    <div>

                        <h5>
                            <?= htmlspecialchars(
                                dashboardT('latest_tickets')
                            ); ?>
                        </h5>

                        <small>
                            <?= htmlspecialchars(
                                dashboardT(
                                    'latest_support_requests'
                                )
                            ); ?>
                        </small>

                    </div>

                    <a href="<?= BASE_URL ?>?page=tickets">

                        <?= htmlspecialchars(
                            dashboardT('see_all_tickets')
                        ); ?>

                    </a>

                </div>

                <div class="table-responsive">

                    <table class="table modern-dashboard-table">

                        <thead>

                        <tr>
                            <th><?= dashboardT('id'); ?></th>
                            <th><?= dashboardT('title'); ?></th>
                            <th><?= dashboardT('requester'); ?></th>
                            <th><?= dashboardT('status'); ?></th>
                            <th><?= dashboardT('priority'); ?></th>
                            <th><?= dashboardT('created_at'); ?></th>
                        </tr>

                        </thead>

                        <tbody>

                        <?php if (!empty($derniersTickets)): ?>

                            <?php foreach (
                                $derniersTickets as $ticket
                            ): ?>

                                <?php

                                $statut =
                                    $ticket['statut'] ?? '';

                                $statutNormalized =
                                    dashboardNormalize($statut);

                                $priorite =
                                    $ticket['priorite'] ?? '';

                                $prioriteNormalized =
                                    dashboardNormalize($priorite);

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
                                            trim(
                                                ($ticket['prenom'] ?? '')
                                                . ' '
                                                . ($ticket['nom'] ?? '')
                                            )
                                        ); ?>

                                    </td>

                                    <td>

                                        <?php if (
                                            $statutNormalized === 'ouvert'
                                        ): ?>

                                            <span class="badge bg-warning">
                                                <?= dashboardT(
                                                    'status_open'
                                                ); ?>
                                            </span>

                                        <?php elseif (
                                            $statutNormalized === 'en cours'
                                        ): ?>

                                            <span class="badge bg-primary">
                                                <?= dashboardT(
                                                    'status_progress'
                                                ); ?>
                                            </span>

                                        <?php elseif (
                                            $statutNormalized === 'resolu'
                                        ): ?>

                                            <span class="badge bg-success">
                                                <?= dashboardT(
                                                    'status_resolved'
                                                ); ?>
                                            </span>

                                        <?php else: ?>

                                            <span class="badge bg-secondary">

                                                <?= htmlspecialchars(
                                                    dashboardStatusLabel(
                                                        $statut
                                                    )
                                                ); ?>

                                            </span>

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?php if (
                                            $prioriteNormalized === 'haute'
                                        ): ?>

                                            <span class="priority-dot red"></span>

                                        <?php elseif (
                                            $prioriteNormalized === 'moyenne'
                                        ): ?>

                                            <span class="priority-dot orange"></span>

                                        <?php else: ?>

                                            <span class="priority-dot green"></span>

                                        <?php endif; ?>

                                        <?= htmlspecialchars(
                                            dashboardPriorityLabel(
                                                $priorite
                                            )
                                        ); ?>

                                    </td>

                                    <td>

                                        <?= !empty(
                                            $ticket['date_creation']
                                        )
                                            ? date(
                                                'd/m/Y H:i',
                                                strtotime(
                                                    $ticket[
                                                        'date_creation'
                                                    ]
                                                )
                                            )
                                            : '-'; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-4">

                                    <?= htmlspecialchars(
                                        dashboardT('no_ticket')
                                    ); ?>

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

                        <h5>
                            <?= htmlspecialchars(
                                dashboardT('recent_activity')
                            ); ?>
                        </h5>

                        <small>
                            <?= htmlspecialchars(
                                dashboardT('latest_actions')
                            ); ?>
                        </small>

                    </div>

                    <a href="<?= BASE_URL ?>?page=historiques">

                        <?= htmlspecialchars(
                            dashboardT('see_all')
                        ); ?>

                    </a>

                </div>

                <div class="activity-list">

                    <?php if (!empty($activitesRecentes)): ?>

                        <?php foreach (
                            $activitesRecentes as $activite
                        ): ?>

                            <div class="activity-item">

                                <div class="activity-dot">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                                <div>

                                    <span>

                                        <?= !empty(
                                            $activite['date_action']
                                        )
                                            ? date(
                                                'd/m/Y H:i',
                                                strtotime(
                                                    $activite[
                                                        'date_action'
                                                    ]
                                                )
                                            )
                                            : '-'; ?>

                                    </span>

                                    <strong>

                                        <?= htmlspecialchars(
                                            dashboardActionLabel(
                                                $activite['action']
                                                ?? dashboardT(
                                                    'default_action'
                                                )
                                            )
                                        ); ?>

                                        -

                                        <?= htmlspecialchars(
                                            dashboardTableLabel(
                                                $activite[
                                                    'table_concernee'
                                                ] ?? ''
                                            )
                                        ); ?>

                                    </strong>

                                    <small>

                                        <?= htmlspecialchars(
                                            dashboardT('by')
                                        ); ?>

                                        <?= htmlspecialchars(
                                            trim(
                                                (
                                                    $activite['prenom']
                                                    ?? 'FSJES'
                                                )
                                                . ' '
                                                . (
                                                    $activite['nom']
                                                    ?? 'Admin'
                                                )
                                            )
                                        ); ?>

                                    </small>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="empty-box">

                            <?= htmlspecialchars(
                                dashboardT('no_activity')
                            ); ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    <?php elseif ($roleDashboard === 'technicien'): ?>

        <div class="dashboard-welcome-card technician">

            <div>

                <span class="dashboard-role-label">

                    <i class="bi bi-tools"></i>

                    <?= htmlspecialchars(
                        dashboardT('technician_space')
                    ); ?>

                </span>

                <h2>

                    <?= htmlspecialchars(
                        dashboardT(
                            'hello',
                            ['name' => $prenomConnecte]
                        )
                    ); ?>

                </h2>

                <p>
                    <?= htmlspecialchars(
                        dashboardT('technician_intro')
                    ); ?>
                </p>

            </div>

            <a
                href="<?= BASE_URL ?>?page=interventions"
                class="btn btn-primary">

                <i class="bi bi-tools"></i>

                <?= htmlspecialchars(
                    dashboardT('see_my_interventions')
                ); ?>

            </a>

        </div>

        <?php

        $technicianStats = [

            [
                'color' => 'purple',
                'icon' => 'bi-tools',
                'label' => dashboardT('my_interventions'),
                'value' => $stats['mes_interventions'] ?? 0,
                'small' => dashboardT('total_assigned')
            ],

            [
                'color' => 'blue',
                'icon' => 'bi-hourglass-split',
                'label' => dashboardT('status_progress'),
                'value' =>
                    $stats['interventions_en_cours'] ?? 0,
                'small' => dashboardT('to_process')
            ],

            [
                'color' => 'green',
                'icon' => 'bi-check-circle-fill',
                'label' => dashboardT('status_completed'),
                'value' =>
                    $stats['interventions_terminees'] ?? 0,
                'small' =>
                    dashboardT('completed_interventions')
            ],

            [
                'color' => 'orange',
                'icon' => 'bi-exclamation-triangle-fill',
                'label' =>
                    dashboardT('maintenance_equipment'),
                'value' =>
                    $stats['equipements_maintenance'] ?? 0,
                'small' => dashboardT('to_monitor')
            ]
        ];

        ?>

        <div class="role-dashboard-stats">

            <?php foreach ($technicianStats as $card): ?>

                <div class="role-stat-card">

                    <div class="role-stat-icon <?= $card['color']; ?>">

                        <i class="bi <?= $card['icon']; ?>"></i>

                    </div>

                    <div>

                        <span>
                            <?= htmlspecialchars($card['label']); ?>
                        </span>

                        <h3>
                            <?= (int)$card['value']; ?>
                        </h3>

                        <small>
                            <?= htmlspecialchars($card['small']); ?>
                        </small>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="dash-panel">

            <div class="panel-header">

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            dashboardT('latest_interventions')
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            dashboardT(
                                'currently_assigned_interventions'
                            )
                        ); ?>
                    </small>

                </div>

                <a href="<?= BASE_URL ?>?page=interventions">

                    <?= htmlspecialchars(
                        dashboardT('see_all_interventions')
                    ); ?>

                </a>

            </div>

            <div class="table-responsive">

                <table class="table modern-dashboard-table">

                    <thead>

                    <tr>
                        <th><?= dashboardT('id'); ?></th>
                        <th><?= dashboardT('ticket'); ?></th>
                        <th><?= dashboardT('title'); ?></th>
                        <th><?= dashboardT('priority'); ?></th>
                        <th><?= dashboardT('status'); ?></th>
                        <th><?= dashboardT('date'); ?></th>
                        <th><?= dashboardT('duration'); ?></th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php if (!empty($mesInterventions)): ?>

                        <?php foreach (
                            $mesInterventions as $intervention
                        ): ?>

                            <?php

                            $statut =
                                $intervention['statut'] ?? '';

                            $priorite =
                                $intervention['priorite'] ?? '';

                            ?>

                            <tr>

                                <td>

                                    #INT-<?= htmlspecialchars(
                                        $intervention[
                                            'id_intervention'
                                        ] ?? ''
                                    ); ?>

                                </td>

                                <td>

                                    #TKT-<?= htmlspecialchars(
                                        $intervention[
                                            'id_ticket'
                                        ] ?? ''
                                    ); ?>

                                </td>

                                <td>

                                    <?= htmlspecialchars(
                                        $intervention[
                                            'titre_ticket'
                                        ] ?? ''
                                    ); ?>

                                </td>

                                <td>

                                    <?php

                                    $priorityClass = match (
                                        dashboardNormalize($priorite)
                                    ) {
                                        'haute' => 'priority-high',
                                        'moyenne' => 'priority-medium',
                                        default => 'priority-low'
                                    };

                                    ?>

                                    <span class="badge <?= $priorityClass; ?>">

                                        <?= htmlspecialchars(
                                            dashboardPriorityLabel(
                                                $priorite
                                            )
                                        ); ?>

                                    </span>

                                </td>

                                <td>

                                    <?php

                                    $statusClass = match (
                                        dashboardNormalize($statut)
                                    ) {
                                        'termine',
                                        'terminee' =>
                                            'intervention-done',

                                        'en cours' =>
                                            'intervention-progress',

                                        default =>
                                            'intervention-waiting'
                                    };

                                    ?>

                                    <span class="badge <?= $statusClass; ?>">

                                        <?= htmlspecialchars(
                                            dashboardStatusLabel(
                                                $statut
                                            )
                                        ); ?>

                                    </span>

                                </td>

                                <td>

                                    <?= !empty(
                                        $intervention[
                                            'date_intervention'
                                        ]
                                    )
                                        ? date(
                                            'd/m/Y H:i',
                                            strtotime(
                                                $intervention[
                                                    'date_intervention'
                                                ]
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

                            <td
                                colspan="7"
                                class="text-center text-muted py-5">

                                <i class="bi bi-tools fs-1"></i>

                                <br><br>

                                <?= htmlspecialchars(
                                    dashboardT(
                                        'no_assigned_intervention'
                                    )
                                ); ?>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="dashboard-quick-actions">

            <a
                href="<?= BASE_URL ?>?page=interventions"
                class="quick-action-card">

                <div class="quick-action-icon purple">
                    <i class="bi bi-tools"></i>
                </div>

                <div>

                    <strong>
                        <?= dashboardT('my_interventions'); ?>
                    </strong>

                    <small>
                        <?= dashboardT(
                            'view_interventions_description'
                        ); ?>
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

            <a
                href="<?= BASE_URL ?>?page=tickets"
                class="quick-action-card">

                <div class="quick-action-icon orange">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>

                <div>

                    <strong>
                        <?= dashboardT('tickets'); ?>
                    </strong>

                    <small>
                        <?= dashboardT(
                            'view_tickets_description'
                        ); ?>
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

            <a
                href="<?= BASE_URL ?>?page=equipements"
                class="quick-action-card">

                <div class="quick-action-icon green">
                    <i class="bi bi-pc-display"></i>
                </div>

                <div>

                    <strong>
                        <?= dashboardT('equipment'); ?>
                    </strong>

                    <small>
                        <?= dashboardT(
                            'view_equipment_description'
                        ); ?>
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

        </div>

    <?php else: ?>

        <div class="dashboard-welcome-card employee">

            <div>

                <span class="dashboard-role-label">

                    <i class="bi bi-person-circle"></i>

                    <?= htmlspecialchars(
                        dashboardT('employee_space')
                    ); ?>

                </span>

                <h2>

                    <?= htmlspecialchars(
                        dashboardT(
                            'hello',
                            ['name' => $prenomConnecte]
                        )
                    ); ?>

                </h2>

                <p>
                    <?= htmlspecialchars(
                        dashboardT('employee_intro')
                    ); ?>
                </p>

            </div>

            <a
                href="<?= BASE_URL ?>?page=ajouter-ticket"
                class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                <?= htmlspecialchars(
                    dashboardT('create_ticket')
                ); ?>

            </a>

        </div>

        <?php

        $employeeStats = [

            [
                'color' => 'brown',
                'icon' => 'bi-ticket-detailed-fill',
                'label' => dashboardT('my_tickets'),
                'value' => $stats['mes_tickets'] ?? 0,
                'small' => dashboardT('total_requests')
            ],

            [
                'color' => 'orange',
                'icon' => 'bi-exclamation-circle-fill',
                'label' => dashboardT('open_tickets'),
                'value' => $stats['tickets_ouverts'] ?? 0,
                'small' => dashboardT('pending_processing')
            ],

            [
                'color' => 'blue',
                'icon' => 'bi-hourglass-split',
                'label' => dashboardT('status_progress'),
                'value' => $stats['tickets_en_cours'] ?? 0,
                'small' => dashboardT('requests_processing')
            ],

            [
                'color' => 'green',
                'icon' => 'bi-check-circle-fill',
                'label' => dashboardT('status_resolved'),
                'value' => $stats['tickets_resolus'] ?? 0,
                'small' => dashboardT('resolved_requests')
            ]
        ];

        ?>

        <div class="role-dashboard-stats">

            <?php foreach ($employeeStats as $card): ?>

                <div class="role-stat-card">

                    <div class="role-stat-icon <?= $card['color']; ?>">

                        <i class="bi <?= $card['icon']; ?>"></i>

                    </div>

                    <div>

                        <span>
                            <?= htmlspecialchars($card['label']); ?>
                        </span>

                        <h3>
                            <?= (int)$card['value']; ?>
                        </h3>

                        <small>
                            <?= htmlspecialchars($card['small']); ?>
                        </small>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <div class="dash-panel">

            <div class="panel-header">

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            dashboardT('latest_my_tickets')
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            dashboardT('track_my_requests')
                        ); ?>
                    </small>

                </div>

                <a href="<?= BASE_URL ?>?page=tickets">

                    <?= htmlspecialchars(
                        dashboardT('see_all_my_tickets')
                    ); ?>

                </a>

            </div>

            <div class="table-responsive">

                <table class="table modern-dashboard-table">

                    <thead>

                    <tr>
                        <th><?= dashboardT('id'); ?></th>
                        <th><?= dashboardT('title'); ?></th>
                        <th><?= dashboardT('description'); ?></th>
                        <th><?= dashboardT('priority'); ?></th>
                        <th><?= dashboardT('status'); ?></th>
                        <th><?= dashboardT('created_at'); ?></th>
                    </tr>

                    </thead>

                    <tbody>

                    <?php if (!empty($mesTickets)): ?>

                        <?php foreach ($mesTickets as $ticket): ?>

                            <?php

                            $statut =
                                $ticket['statut'] ?? '';

                            $priorite =
                                $ticket['priorite'] ?? '';

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

                                    <?php

                                    $priorityClass = match (
                                        dashboardNormalize($priorite)
                                    ) {
                                        'haute' => 'priority-high',
                                        'moyenne' => 'priority-medium',
                                        default => 'priority-low'
                                    };

                                    ?>

                                    <span class="badge <?= $priorityClass; ?>">

                                        <?= htmlspecialchars(
                                            dashboardPriorityLabel(
                                                $priorite
                                            )
                                        ); ?>

                                    </span>

                                </td>

                                <td>

                                    <?php

                                    $statusClass = match (
                                        dashboardNormalize($statut)
                                    ) {
                                        'ouvert' => 'ticket-open',
                                        'en cours' =>
                                            'ticket-progress',
                                        'resolu' => 'ticket-done',
                                        default => 'bg-secondary'
                                    };

                                    ?>

                                    <span class="badge <?= $statusClass; ?>">

                                        <?= htmlspecialchars(
                                            dashboardStatusLabel(
                                                $statut
                                            )
                                        ); ?>

                                    </span>

                                </td>

                                <td>

                                    <?= !empty(
                                        $ticket['date_creation']
                                    )
                                        ? date(
                                            'd/m/Y H:i',
                                            strtotime(
                                                $ticket[
                                                    'date_creation'
                                                ]
                                            )
                                        )
                                        : '-'; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-5">

                                <i class="bi bi-ticket-detailed fs-1"></i>

                                <br><br>

                                <?= htmlspecialchars(
                                    dashboardT(
                                        'no_personal_ticket'
                                    )
                                ); ?>

                                <br><br>

                                <a
                                    href="<?= BASE_URL ?>?page=ajouter-ticket"
                                    class="btn btn-primary">

                                    <i class="bi bi-plus-circle"></i>

                                    <?= htmlspecialchars(
                                        dashboardT(
                                            'create_first_ticket'
                                        )
                                    ); ?>

                                </a>

                            </td>

                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="dashboard-quick-actions">

            <a
                href="<?= BASE_URL ?>?page=ajouter-ticket"
                class="quick-action-card">

                <div class="quick-action-icon orange">
                    <i class="bi bi-plus-circle-fill"></i>
                </div>

                <div>

                    <strong>
                        <?= dashboardT('create_ticket'); ?>
                    </strong>

                    <small>
                        <?= dashboardT(
                            'create_ticket_description'
                        ); ?>
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

            <a
                href="<?= BASE_URL ?>?page=tickets"
                class="quick-action-card">

                <div class="quick-action-icon blue">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>

                <div>

                    <strong>
                        <?= dashboardT('my_tickets'); ?>
                    </strong>

                    <small>
                        <?= dashboardT(
                            'view_my_tickets_description'
                        ); ?>
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

            <a
                href="<?= BASE_URL ?>?page=profil"
                class="quick-action-card">

                <div class="quick-action-icon green">
                    <i class="bi bi-person-circle"></i>
                </div>

                <div>

                    <strong>
                        <?= dashboardT('my_profile'); ?>
                    </strong>

                    <small>
                        <?= dashboardT(
                            'view_profile_description'
                        ); ?>
                    </small>

                </div>

                <i class="bi bi-chevron-right"></i>

            </a>

        </div>

    <?php endif; ?>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>