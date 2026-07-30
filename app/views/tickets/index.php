<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$tickets = $tickets ?? [];

if (!function_exists('ticketT')) {
    function ticketT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'tickets_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('ticketNormalize')) {
    function ticketNormalize(string $value): string
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

if (!function_exists('ticketPriorityLabel')) {
    function ticketPriorityLabel(string $value): string
    {
        return match (ticketNormalize($value)) {
            'basse' => ticketT('priority_low'),
            'moyenne' => ticketT('priority_medium'),
            'haute' => ticketT('priority_high'),
            default => $value !== ''
                ? $value
                : ticketT('undefined')
        };
    }
}

if (!function_exists('ticketStatusLabel')) {
    function ticketStatusLabel(string $value): string
    {
        return match (ticketNormalize($value)) {
            'ouvert' => ticketT('status_open'),
            'en cours' => ticketT('status_in_progress'),
            'en attente' => ticketT('status_waiting'),
            'resolu' => ticketT('status_resolved'),
            'annule' => ticketT('status_cancelled'),
            default => $value !== ''
                ? $value
                : ticketT('undefined')
        };
    }
}

if (!function_exists('ticketCommunicationLabel')) {
    function ticketCommunicationLabel(string $value): string
    {
        return match (ticketNormalize($value)) {
            'email', 'e-mail' => ticketT('communication_email'),
            'telephone' => ticketT('communication_phone'),
            'presentiel' => ticketT('communication_in_person'),
            default => $value !== ''
                ? $value
                : '-'
        };
    }
}

$filtrePriorite = ticketNormalize(
    (string)($_GET['priorite'] ?? '')
);

$filtreStatut = ticketNormalize(
    (string)($_GET['statut'] ?? '')
);

if (
    $filtrePriorite !== ''
    || $filtreStatut !== ''
) {
    $tickets = array_values(
        array_filter(
            $tickets,
            function (array $ticket) use (
                $filtrePriorite,
                $filtreStatut
            ): bool {
                $prioriteTicket = ticketNormalize(
                    (string)(
                        $ticket['priorite']
                        ?? $ticket['PRIORITE']
                        ?? ''
                    )
                );

                $statutTicket = ticketNormalize(
                    (string)(
                        $ticket['statut']
                        ?? $ticket['STATUT']
                        ?? ''
                    )
                );

                $prioriteCorrespond =
                    $filtrePriorite === ''
                    || $prioriteTicket ===
                        $filtrePriorite;

                $statutCorrespond =
                    $filtreStatut === ''
                    || $statutTicket ===
                        $filtreStatut;

                return $prioriteCorrespond
                    && $statutCorrespond;
            }
        )
    );
}

$totalTickets = count($tickets);
$totalOuverts = 0;
$totalEnCours = 0;
$totalEnAttente = 0;
$totalResolus = 0;
$totalAnnules = 0;

foreach ($tickets as $ticket) {
    $statut = ticketNormalize(
        (string)(
            $ticket['statut']
            ?? $ticket['STATUT']
            ?? ''
        )
    );

    if ($statut === 'ouvert') {
        $totalOuverts++;
    } elseif ($statut === 'en cours') {
        $totalEnCours++;
    } elseif ($statut === 'en attente') {
        $totalEnAttente++;
    } elseif ($statut === 'resolu') {
        $totalResolus++;
    } elseif ($statut === 'annule') {
        $totalAnnules++;
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2><?= htmlspecialchars(ticketT('management_title')); ?></h2>

            <p>
                <?= htmlspecialchars(ticketT('management_subtitle')); ?>
            </p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-ticket"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            <?= htmlspecialchars(ticketT('new_ticket')); ?>

        </a>

    </div>

    <?php if (($_GET['message'] ?? '') === 'ticket-annule'): ?>

        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <?= htmlspecialchars(ticketT('ticket_cancelled_success')); ?>
        </div>

    <?php elseif (($_GET['message'] ?? '') === 'ticket-deja-annule'): ?>

        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?= htmlspecialchars(ticketT('ticket_already_cancelled')); ?>
        </div>

    <?php elseif (
        ($_GET['message'] ?? '') ===
        'ticket-annule-non-modifiable'
    ): ?>

        <div class="alert alert-warning">
            <i class="bi bi-lock-fill"></i>
            <?= htmlspecialchars(ticketT('cancelled_ticket_locked')); ?>
        </div>

    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>

        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['success']); ?>
        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['error']); ?>
        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <div class="module-stats-grid">

        <div class="module-stat-card">

            <div class="module-stat-icon brown">
                <i class="bi bi-ticket-detailed-fill"></i>
            </div>

            <div>
                <span><?= htmlspecialchars(ticketT('total_tickets')); ?></span>
                <h3><?= $totalTickets; ?></h3>
                <small><?= htmlspecialchars(ticketT('registered_requests')); ?></small>
            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon orange">
                <i class="bi bi-exclamation-circle-fill"></i>
            </div>

            <div>
                <span><?= htmlspecialchars(ticketT('open_tickets')); ?></span>
                <h3><?= $totalOuverts; ?></h3>
                <small><?= htmlspecialchars(ticketT('to_process')); ?></small>
            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon blue">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div>
                <span><?= htmlspecialchars(ticketT('in_progress')); ?></span>
                <h3><?= $totalEnCours; ?></h3>
                <small><?= htmlspecialchars(ticketT('active_processing')); ?></small>
            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon green">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>
                <span><?= htmlspecialchars(ticketT('resolved')); ?></span>
                <h3><?= $totalResolus; ?></h3>
                <small><?= htmlspecialchars(ticketT('closed_tickets')); ?></small>
            </div>

        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="tickets">

            <div class="row g-3 align-items-end">

                <div class="col-lg-5 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(ticketT('search')); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(ticketT('search_placeholder')); ?>"
                               value="<?= isset($_GET['search'])
                                   ? htmlspecialchars($_GET['search'])
                                   : ''; ?>">

                    </div>

                </div>

                <div class="col-lg-2 col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(ticketT('priority')); ?>
                    </label>

                    <select class="form-select"
                            name="priorite"
                            onchange="this.form.submit()">

                        <option value=""
                            <?= $filtrePriorite === ''
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('all_feminine')
                            ); ?>

                        </option>

                        <option value="haute"
                            <?= $filtrePriorite === 'haute'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('priority_high')
                            ); ?>

                        </option>

                        <option value="moyenne"
                            <?= $filtrePriorite === 'moyenne'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('priority_medium')
                            ); ?>

                        </option>

                        <option value="basse"
                            <?= $filtrePriorite === 'basse'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('priority_low')
                            ); ?>

                        </option>

                    </select>

                </div>

                <div class="col-lg-2 col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(ticketT('status')); ?>
                    </label>

                    <select class="form-select"
                            name="statut"
                            onchange="this.form.submit()">

                        <option value=""
                            <?= $filtreStatut === ''
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('all_masculine')
                            ); ?>

                        </option>

                        <option value="ouvert"
                            <?= $filtreStatut === 'ouvert'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('status_open')
                            ); ?>

                        </option>

                        <option value="en cours"
                            <?= $filtreStatut === 'en cours'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('status_in_progress')
                            ); ?>

                        </option>

                        <option value="en attente"
                            <?= $filtreStatut === 'en attente'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('status_waiting')
                            ); ?>

                        </option>

                        <option value="resolu"
                            <?= $filtreStatut === 'resolu'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('status_resolved')
                            ); ?>

                        </option>

                        <option value="annule"
                            <?= $filtreStatut === 'annule'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                ticketT('status_cancelled')
                            ); ?>

                        </option>

                    </select>

                </div>

                <div class="col-lg-3 col-md-12 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-fill">

                        <i class="bi bi-search"></i>
                        <?= htmlspecialchars(ticketT('search_button')); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=tickets"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(ticketT('reset')); ?>">

                        <i class="bi bi-arrow-clockwise"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5><?= htmlspecialchars(ticketT('ticket_list')); ?></h5>

                <small>
                    <?= htmlspecialchars(
                        ticketT(
                            'tickets_found_cancelled',
                            [
                                'count' => $totalTickets,
                                'cancelled' => $totalAnnules
                            ]
                        )
                    ); ?>
                </small>
            </div>

            <span class="module-chip">
                <i class="bi bi-headset"></i>
                <?= htmlspecialchars(ticketT('it_support')); ?>
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(ticketT('id')); ?></th>
                    <th><?= htmlspecialchars(ticketT('ticket')); ?></th>
                    <th><?= htmlspecialchars(ticketT('requester')); ?></th>
                    <th><?= htmlspecialchars(ticketT('priority')); ?></th>
                    <th><?= htmlspecialchars(ticketT('status')); ?></th>
                    <th><?= htmlspecialchars(ticketT('communication_method_short')); ?></th>
                    <th><?= htmlspecialchars(ticketT('creation_date')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(ticketT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($tickets)): ?>

                    <?php foreach ($tickets as $ticket): ?>

                        <?php

                        $id =
                            $ticket['id_ticket']
                            ?? $ticket['ID_TICKET']
                            ?? '';

                        $titre =
                            $ticket['titre']
                            ?? $ticket['TITRE']
                            ?? '';

                        $description =
                            $ticket['description']
                            ?? $ticket['DESCRIPTION']
                            ?? '';

                        $priorite =
                            $ticket['priorite']
                            ?? $ticket['PRIORITE']
                            ?? '';

                        $statut =
                            $ticket['statut']
                            ?? $ticket['STATUT']
                            ?? '';

                        $dateCreation =
                            $ticket['date_creation']
                            ?? $ticket['DATE_CREATION']
                            ?? '';

                        $nom =
                            $ticket['nom']
                            ?? $ticket['NOM']
                            ?? '';

                        $prenom =
                            $ticket['prenom']
                            ?? $ticket['PRENOM']
                            ?? '';

                        $moyen =
                            $ticket['moyen']
                            ?? $ticket['LIBELLE']
                            ?? '-';

                        $prioriteLower =
                            ticketNormalize((string)$priorite);

                        $statutLower =
                            ticketNormalize((string)$statut);

                        $ticketAnnule =
                            $statutLower === 'annule';

                        $initiales = mb_strtoupper(
                            mb_substr($prenom, 0, 1, 'UTF-8') .
                            mb_substr($nom, 0, 1, 'UTF-8'),
                            'UTF-8'
                        );

                        ?>

                        <tr class="<?= $ticketAnnule
                            ? 'ticket-row-cancelled'
                            : ''; ?>">

                            <td>

                                <span class="table-id">
                                    #TKT-<?= htmlspecialchars($id); ?>
                                </span>

                            </td>

                            <td>

                                <div class="ticket-cell">

                                    <div class="ticket-icon">
                                        <i class="bi bi-ticket-detailed-fill"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars($titre); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                mb_strimwidth(
                                                    $description,
                                                    0,
                                                    55,
                                                    '...',
                                                    'UTF-8'
                                                )
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <div class="user-cell">

                                    <div class="table-avatar">

                                        <?= htmlspecialchars(
                                            $initiales ?: 'U'
                                        ); ?>

                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                trim(
                                                    $prenom .
                                                    ' ' .
                                                    $nom
                                                )
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                ticketT('requester')
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <?php if ($prioriteLower === 'haute'): ?>

                                    <span class="badge priority-high">
                                        <i class="bi bi-arrow-up-circle-fill"></i>
                                        <?= htmlspecialchars(ticketT('priority_high')); ?>
                                    </span>

                                <?php elseif ($prioriteLower === 'moyenne'): ?>

                                    <span class="badge priority-medium">
                                        <i class="bi bi-dash-circle-fill"></i>
                                        <?= htmlspecialchars(ticketT('priority_medium')); ?>
                                    </span>

                                <?php else: ?>

                                    <span class="badge priority-low">
                                        <i class="bi bi-arrow-down-circle-fill"></i>

                                        <?= htmlspecialchars(
                                            ticketPriorityLabel(
                                                (string)$priorite
                                            )
                                        ); ?>

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php if ($statutLower === 'ouvert'): ?>

                                    <span class="badge ticket-open">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                        <?= htmlspecialchars(ticketT('status_open')); ?>
                                    </span>

                                <?php elseif ($statutLower === 'en cours'): ?>

                                    <span class="badge ticket-progress">
                                        <i class="bi bi-hourglass-split"></i>
                                        <?= htmlspecialchars(ticketT('status_in_progress')); ?>
                                    </span>

                                <?php elseif ($statutLower === 'en attente'): ?>

                                    <span class="badge ticket-waiting">
                                        <i class="bi bi-clock-fill"></i>
                                        <?= htmlspecialchars(ticketT('status_waiting')); ?>
                                    </span>

                                <?php elseif ($statutLower === 'resolu'): ?>

                                    <span class="badge ticket-done">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <?= htmlspecialchars(ticketT('status_resolved')); ?>
                                    </span>

                                <?php elseif ($ticketAnnule): ?>

                                    <span class="badge ticket-cancelled">
                                        <i class="bi bi-x-circle-fill"></i>
                                        <?= htmlspecialchars(ticketT('status_cancelled')); ?>
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">

                                        <?= htmlspecialchars(
                                            ticketStatusLabel(
                                                (string)$statut
                                            )
                                        ); ?>

                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <span class="moyen-badge">
                                    <i class="bi bi-chat-dots-fill"></i>

                                    <?= htmlspecialchars(
                                        ticketCommunicationLabel(
                                            (string)$moyen
                                        )
                                    ); ?>
                                </span>

                            </td>

                            <td>

                                <?= !empty($dateCreation)
                                    ? date(
                                        'd/m/Y H:i',
                                        strtotime($dateCreation)
                                    )
                                    : '-'; ?>

                            </td>

                            <td class="text-center">

                                <?php if (!$ticketAnnule): ?>

                                    <a href="<?= BASE_URL ?>?page=modifier-ticket&id=<?= (int)$id; ?>"
                                       class="btn btn-warning btn-sm"
                                       title="<?= htmlspecialchars(ticketT('edit')); ?>">

                                        <i class="bi bi-pencil-square"></i>

                                    </a>

                                    <?php if (
                                        (int)($_SESSION['id_role'] ?? 0) === 1
                                    ): ?>

                                        <a href="<?= BASE_URL ?>?page=supprimer-ticket&id=<?= (int)$id; ?>"
                                           class="btn btn-danger btn-sm"
                                           title="<?= htmlspecialchars(ticketT('cancel_ticket')); ?>"
                                           onclick="return confirm('<?= htmlspecialchars(
                                               ticketT(
                                                   'cancel_ticket_confirmation'
                                               ),
                                               ENT_QUOTES,
                                               'UTF-8'
                                           ); ?>');">

                                            <i class="bi bi-x-circle"></i>

                                        </a>

                                    <?php endif; ?>

                                <?php else: ?>

                                    <span class="text-muted small">
                                        <i class="bi bi-lock-fill"></i>
                                        <?= htmlspecialchars(ticketT('cancelled_ticket')); ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="8"
                            class="text-center py-5 text-muted">

                            <i class="bi bi-ticket-detailed fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(ticketT('no_ticket')); ?>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>