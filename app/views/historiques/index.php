<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$historiques = $historiques ?? [];

$totalHistoriques = count($historiques);
$totalAjouts = 0;
$totalModifications = 0;
$totalSuppressions = 0;

foreach ($historiques as $h) {
    $action = strtolower($h['action'] ?? $h['ACTION'] ?? '');

    if ($action == 'ajout') {
        $totalAjouts++;
    } elseif ($action == 'modification') {
        $totalModifications++;
    } elseif ($action == 'suppression') {
        $totalSuppressions++;
    }
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Historique / Traçabilité</h2>
            <p>Consultez les actions réalisées dans l’application.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=dashboard" class="btn btn-primary">
            <i class="bi bi-speedometer2"></i>
            Retour dashboard
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <span>Total actions</span>
                <h3><?= $totalHistoriques; ?></h3>
                <small>Actions enregistrées</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-plus-circle-fill"></i>
            </div>
            <div>
                <span>Ajouts</span>
                <h3><?= $totalAjouts; ?></h3>
                <small>Nouveaux éléments</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <span>Modifications</span>
                <h3><?= $totalModifications; ?></h3>
                <small>Mises à jour</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon red">
                <i class="bi bi-trash-fill"></i>
            </div>
            <div>
                <span>Suppressions</span>
                <h3><?= $totalSuppressions; ?></h3>
                <small>Éléments supprimés</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="historiques">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par action, table, utilisateur..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Action</label>
                    <select class="form-select" disabled>
                        <option>Toutes les actions</option>
                        <option>Ajout</option>
                        <option>Modification</option>
                        <option>Suppression</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=historiques" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Journal des actions</h5>
                <small><?= $totalHistoriques; ?> action(s) trouvée(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-shield-check"></i>
                Traçabilité système
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Action</th>
                    <th>Table concernée</th>
                    <th>Élément</th>
                    <th>Utilisateur</th>
                    <th>Ancienne valeur</th>
                    <th>Nouvelle valeur</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($historiques)): ?>

                    <?php foreach ($historiques as $h): ?>

                        <?php
                        $id = $h['id_historique'] ?? $h['ID_HISTORIQUE'] ?? '';
                        $table = $h['table_concernee'] ?? $h['TABLE_CONCERNEE'] ?? '';
                        $idElement = $h['id_element'] ?? $h['ID_ELEMENT'] ?? '';
                        $action = $h['action'] ?? $h['ACTION'] ?? '';
                        $ancienneValeur = $h['ancienne_valeur'] ?? $h['ANCIENNE_VALEUR'] ?? '';
                        $nouvelleValeur = $h['nouvelle_valeur'] ?? $h['NOUVELLE_VALEUR'] ?? '';
                        $dateAction = $h['date_action'] ?? $h['DATE_ACTION'] ?? '';

                        $nomUtilisateur = $h['nom_utilisateur'] ?? $h['NOM_UTILISATEUR'] ?? $h['nom'] ?? $h['NOM'] ?? '';
                        $prenomUtilisateur = $h['prenom_utilisateur'] ?? $h['PRENOM_UTILISATEUR'] ?? $h['prenom'] ?? $h['PRENOM'] ?? '';

                        $actionLower = strtolower($action);

                        if ($actionLower == 'ajout') {
                            $actionClass = 'history-add';
                            $actionIcon = 'bi-plus-circle-fill';
                        } elseif ($actionLower == 'modification') {
                            $actionClass = 'history-edit';
                            $actionIcon = 'bi-pencil-square';
                        } elseif ($actionLower == 'suppression') {
                            $actionClass = 'history-delete';
                            $actionIcon = 'bi-trash-fill';
                        } else {
                            $actionClass = 'history-default';
                            $actionIcon = 'bi-clock-history';
                        }

                        $initiales = strtoupper(substr($prenomUtilisateur, 0, 1) . substr($nomUtilisateur, 0, 1));

                        $ancienneAffichee = '-';
                        $nouvelleAffichee = '-';

                        if ($actionLower === 'modification') {
                            $ancienneData = json_decode($ancienneValeur, true);
                            $nouvelleData = json_decode($nouvelleValeur, true);

                            if (is_array($ancienneData) && is_array($nouvelleData)) {
                                $anciennesModifications = [];
                                $nouvellesModifications = [];

                                foreach ($nouvelleData as $champ => $nouvelleDonnee) {
                                    $ancienneDonnee = $ancienneData[$champ] ?? null;

                                    if ((string) $ancienneDonnee !== (string) $nouvelleDonnee) {
                                        $nomChamp = ucfirst(str_replace('_', ' ', $champ));

                                        $anciennesModifications[] =
                                            htmlspecialchars($nomChamp) . ' : ' .
                                            htmlspecialchars(
                                                $ancienneDonnee !== null && $ancienneDonnee !== ''
                                                    ? (string) $ancienneDonnee
                                                    : 'Vide'
                                            );

                                        $nouvellesModifications[] =
                                            htmlspecialchars($nomChamp) . ' : ' .
                                            htmlspecialchars(
                                                $nouvelleDonnee !== null && $nouvelleDonnee !== ''
                                                    ? (string) $nouvelleDonnee
                                                    : 'Vide'
                                            );
                                    }
                                }

                                if (!empty($anciennesModifications)) {
                                    $ancienneAffichee = implode('<br>', $anciennesModifications);
                                    $nouvelleAffichee = implode('<br>', $nouvellesModifications);
                                } else {
                                    $ancienneAffichee = 'Aucun changement détecté';
                                    $nouvelleAffichee = 'Aucun changement détecté';
                                }
                            }
                        } elseif ($actionLower === 'ajout') {
                            $nouvelleAffichee = 'Nouvel élément ajouté';
                        } elseif ($actionLower === 'suppression') {
                            $ancienneAffichee = 'Élément supprimé';
                        }
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#HIS-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <span class="badge <?= $actionClass; ?>">
                                    <i class="bi <?= $actionIcon; ?>"></i>
                                    <?= htmlspecialchars($action); ?>
                                </span>
                            </td>

                            <td>
                                <span class="history-table-badge">
                                    <i class="bi bi-table"></i>
                                    <?= htmlspecialchars($table); ?>
                                </span>
                            </td>

                            <td>
                                <span class="history-element-badge">
                                    #<?= htmlspecialchars($idElement); ?>
                                </span>
                            </td>

                            <td>
                                <div class="user-cell">
                                    <div class="table-avatar">
                                        <?= htmlspecialchars($initiales ?: 'A'); ?>
                                    </div>
                                    <div>
                                        <strong>
                                            <?= htmlspecialchars(trim($prenomUtilisateur . ' ' . $nomUtilisateur) ?: 'Utilisateur inconnu'); ?>
                                        </strong>
                                        <small>Action système</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="history-value old">
                                    <?= $ancienneAffichee; ?>
                                </span>
                            </td>

                            <td>
                                <span class="history-value new">
                                    <?= $nouvelleAffichee; ?>
                                </span>
                            </td>

                            <td>
                                <span class="date-badge">
                                    <i class="bi bi-calendar-check"></i>
                                    <?= !empty($dateAction) ? date('d/m/Y H:i', strtotime($dateAction)) : '-'; ?>
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=supprimer-historique&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette ligne historique ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-1"></i>
                            <br><br>
                            Aucun historique trouvé.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>