<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$evaluations = $evaluations ?? [];

$totalEvaluations = count($evaluations);
$sommeNotes = 0;
$totalNotes5 = 0;
$totalNotes4Plus = 0;
$totalCommentaires = 0;

foreach ($evaluations as $evaluation) {
    $note = (int)($evaluation['note'] ?? $evaluation['NOTE'] ?? 0);
    $commentaire = $evaluation['commentaire'] ?? $evaluation['COMMENTAIRE'] ?? '';

    $sommeNotes += $note;

    if ($note == 5) {
        $totalNotes5++;
    }

    if ($note >= 4) {
        $totalNotes4Plus++;
    }

    if (!empty($commentaire)) {
        $totalCommentaires++;
    }
}

$moyenneNote = $totalEvaluations > 0 ? round($sommeNotes / $totalEvaluations, 1) : 0;
$tauxSatisfaction = $totalEvaluations > 0 ? round(($totalNotes4Plus / $totalEvaluations) * 100) : 0;
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des évaluations</h2>
            <p>Suivez les notes et commentaires liés aux interventions techniques.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-evaluation" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Ajouter une évaluation
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-star-fill"></i>
            </div>
            <div>
                <span>Total évaluations</span>
                <h3><?= $totalEvaluations; ?></h3>
                <small>Évaluations enregistrées</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-star-half"></i>
            </div>
            <div>
                <span>Note moyenne</span>
                <h3><?= $moyenneNote; ?>/5</h3>
                <small>Moyenne globale</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-emoji-smile-fill"></i>
            </div>
            <div>
                <span>Satisfaction</span>
                <h3><?= $tauxSatisfaction; ?>%</h3>
                <small>Notes 4 ou 5 étoiles</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-chat-left-text-fill"></i>
            </div>
            <div>
                <span>Commentaires</span>
                <h3><?= $totalCommentaires; ?></h3>
                <small>Retours utilisateurs</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="evaluations">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par utilisateur, technicien, commentaire..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Note</label>
                    <select class="form-select" disabled>
                        <option>Toutes les notes</option>
                        <option>5 étoiles</option>
                        <option>4 étoiles</option>
                        <option>3 étoiles</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=evaluations" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des évaluations</h5>
                <small><?= $totalEvaluations; ?> évaluation(s) trouvée(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-stars"></i>
                Satisfaction utilisateurs
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Intervention</th>
                    <th>Technicien</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($evaluations)): ?>

                    <?php foreach ($evaluations as $evaluation): ?>

                        <?php
                        $id = $evaluation['id_evaluation'] ?? $evaluation['ID_EVALUATION'] ?? '';
                        $idIntervention = $evaluation['id_intervention'] ?? $evaluation['ID_INTERVENTION'] ?? '';
                        $note = (int)($evaluation['note'] ?? $evaluation['NOTE'] ?? 0);
                        $commentaire = $evaluation['commentaire'] ?? $evaluation['COMMENTAIRE'] ?? '';
                        $dateEvaluation = $evaluation['date_evaluation'] ?? $evaluation['DATE_EVALUATION'] ?? '';

                        $nomUtilisateur = $evaluation['nom_utilisateur'] ?? $evaluation['NOM_UTILISATEUR'] ?? $evaluation['nom'] ?? $evaluation['NOM'] ?? '';
                        $prenomUtilisateur = $evaluation['prenom_utilisateur'] ?? $evaluation['PRENOM_UTILISATEUR'] ?? $evaluation['prenom'] ?? $evaluation['PRENOM'] ?? '';

                        $nomTechnicien = $evaluation['nom_technicien'] ?? $evaluation['NOM_TECHNICIEN'] ?? '';
                        $prenomTechnicien = $evaluation['prenom_technicien'] ?? $evaluation['PRENOM_TECHNICIEN'] ?? '';

                        $titreTicket = $evaluation['titre_ticket'] ?? $evaluation['TITRE'] ?? 'Intervention technique';

                        $initialesUtilisateur = strtoupper(substr($prenomUtilisateur, 0, 1) . substr($nomUtilisateur, 0, 1));
                        $initialesTechnicien = strtoupper(substr($prenomTechnicien, 0, 1) . substr($nomTechnicien, 0, 1));
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#EVA-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="user-cell">
                                    <div class="table-avatar">
                                        <?= htmlspecialchars($initialesUtilisateur ?: 'U'); ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars(trim($prenomUtilisateur . ' ' . $nomUtilisateur) ?: 'Utilisateur'); ?></strong>
                                        <small>Évaluateur</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="evaluation-intervention-cell">
                                    <div class="evaluation-intervention-icon">
                                        <i class="bi bi-tools"></i>
                                    </div>
                                    <div>
                                        <strong>#INT-<?= htmlspecialchars($idIntervention); ?></strong>
                                        <small><?= htmlspecialchars($titreTicket); ?></small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="user-cell">
                                    <div class="table-avatar tech-avatar">
                                        <?= htmlspecialchars($initialesTechnicien ?: 'T'); ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars(trim($prenomTechnicien . ' ' . $nomTechnicien) ?: 'Technicien'); ?></strong>
                                        <small>Technicien IT</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="stars-cell">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $note): ?>
                                            <i class="bi bi-star-fill"></i>
                                        <?php else: ?>
                                            <i class="bi bi-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span><?= $note; ?>/5</span>
                                </div>
                            </td>

                            <td>
                                <span class="comment-badge"
                                      title="<?= htmlspecialchars($commentaire); ?>">
                                    <i class="bi bi-chat-left-text-fill"></i>
                                    <?= htmlspecialchars(!empty($commentaire) ? mb_strimwidth($commentaire, 0, 35, '...') : 'Aucun commentaire'); ?>
                                </span>
                            </td>

                            <td>
                                <span class="date-badge">
                                    <i class="bi bi-calendar-check"></i>
                                    <?= !empty($dateEvaluation) ? date('d/m/Y', strtotime($dateEvaluation)) : '-'; ?>
                                </span>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-evaluation&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-evaluation&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cette évaluation ?');">
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-star fs-1"></i>
                            <br><br>
                            Aucune évaluation trouvée.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>