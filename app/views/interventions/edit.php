<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$intervention = $intervention ?? [];
$tickets = $tickets ?? [];
$techniciens = $techniciens ?? $utilisateurs ?? [];

$id = $intervention['id_intervention'] ?? $intervention['ID_INTERVENTION'] ?? '';
$idTicketIntervention = $intervention['id_ticket'] ?? $intervention['ID_TICKET'] ?? '';
$idTechnicienIntervention = $intervention['id_technicien'] ?? $intervention['ID_TECHNICIEN'] ?? $intervention['ID_UTILISATEUR'] ?? '';
$rapport = $intervention['rapport'] ?? $intervention['RAPPORT'] ?? '';
$duree = $intervention['duree'] ?? $intervention['DUREE'] ?? '';
$dateIntervention = $intervention['date_intervention'] ?? $intervention['DATE_INTERVENTION'] ?? '';
$statut = $intervention['statut'] ?? $intervention['STATUT'] ?? 'En cours';
$tempsReponse = $intervention['temps_reponse'] ?? '';
$tempsResolution = $intervention['temps_resolution'] ?? '';

$dateInterventionInput = '';

if (!empty($dateIntervention)) {
    $dateInterventionInput = date('Y-m-d\TH:i', strtotime($dateIntervention));
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier une intervention</h2>
            <p>Mettez à jour les informations de l’intervention technique.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=interventions" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-intervention&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_intervention" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations de l’intervention</h5>
                    <small>Modifiez le ticket, le technicien et le statut.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Ticket concerné</label>
                    <select name="id_ticket" class="form-select" required>

                        <option value="">Choisir un ticket</option>

                        <?php foreach ($tickets as $ticket): ?>

                            <?php
                            $idTicket = $ticket['id_ticket'] ?? $ticket['ID_TICKET'] ?? '';
                            $titre = $ticket['titre'] ?? $ticket['TITRE'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idTicket); ?>"
                                <?= ($idTicket == $idTicketIntervention) ? 'selected' : ''; ?>>
                                #TKT-<?= htmlspecialchars($idTicket); ?> - <?= htmlspecialchars($titre); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Technicien</label>
                    <select name="id_technicien" class="form-select">

                        <option value="">Choisir un technicien</option>

                        <?php foreach ($techniciens as $tech): ?>

                            <?php
                            $idTech = $tech['id_utilisateur'] ?? $tech['ID_UTILISATEUR'] ?? '';
                            $nom = $tech['nom'] ?? $tech['NOM'] ?? '';
                            $prenom = $tech['prenom'] ?? $tech['PRENOM'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idTech); ?>"
                                <?= ($idTech == $idTechnicienIntervention) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars(trim($prenom . ' ' . $nom)); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date intervention</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-check"></i>
                        <input type="datetime-local"
                               name="date_intervention"
                               class="form-control"
                               value="<?= htmlspecialchars($dateInterventionInput); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Durée</label>
                    <div class="input-with-icon">
                        <i class="bi bi-clock"></i>
                        <input type="text"
                               name="duree"
                               class="form-control"
                               value="<?= htmlspecialchars($duree); ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="En attente" <?= ($statut == 'En attente') ? 'selected' : ''; ?>>En attente</option>
                        <option value="En cours" <?= ($statut == 'En cours') ? 'selected' : ''; ?>>En cours</option>
                        <option value="Terminée" <?= ($statut == 'Terminée') ? 'selected' : ''; ?>>Terminée</option>
                    </select>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-stopwatch-fill"></i>
                </div>
                <div>
                    <h5>Délais de traitement</h5>
                    <small>Modifiez le temps de réponse et le temps de résolution.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Temps de réponse</label>
                    <div class="input-with-icon">
                        <i class="bi bi-stopwatch"></i>
                        <input type="text"
                               name="temps_reponse"
                               class="form-control"
                               value="<?= htmlspecialchars($tempsReponse); ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Temps de résolution</label>
                    <div class="input-with-icon">
                        <i class="bi bi-clock-history"></i>
                        <input type="text"
                               name="temps_resolution"
                               class="form-control"
                               value="<?= htmlspecialchars($tempsResolution); ?>">
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Rapport d’intervention</label>
                    <div class="textarea-with-icon">
                        <i class="bi bi-file-earmark-text"></i>
                        <textarea name="rapport"
                                  class="form-control"
                                  rows="5"><?= htmlspecialchars($rapport); ?></textarea>
                    </div>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=interventions" class="btn btn-light border">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Mettre à jour
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>