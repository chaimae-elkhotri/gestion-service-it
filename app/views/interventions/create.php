<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$tickets = $tickets ?? [];
$techniciens = $techniciens ?? $utilisateurs ?? [];
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Ajouter une intervention</h2>
            <p>Créez une intervention technique liée à un ticket.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=interventions" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-intervention" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <h5>Informations de l’intervention</h5>
                    <small>Associez l’intervention à un ticket et indiquez son état.</small>
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

                            <option value="<?= htmlspecialchars($idTicket); ?>">
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

                            <option value="<?= htmlspecialchars($idTech); ?>">
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
                               placeholder="Ex : 30 min / 1h">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="En attente">En attente</option>
                        <option value="En cours" selected>En cours</option>
                        <option value="Terminée">Terminée</option>
                    </select>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-stopwatch-fill"></i>
                </div>
                <div>
                    <h5>Délais de traitement</h5>
                    <small>Ajoutez le temps de réponse et le temps de résolution.</small>
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
                               placeholder="Ex : 15 min">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Temps de résolution</label>
                    <div class="input-with-icon">
                        <i class="bi bi-clock-history"></i>
                        <input type="text"
                               name="temps_resolution"
                               class="form-control"
                               placeholder="Ex : 1h">
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Rapport d’intervention</label>
                    <div class="textarea-with-icon">
                        <i class="bi bi-file-earmark-text"></i>
                        <textarea name="rapport"
                                  class="form-control"
                                  rows="5"
                                  placeholder="Décrivez l’intervention réalisée..."></textarea>
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
                    Enregistrer
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>