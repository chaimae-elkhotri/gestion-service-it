<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$utilisateurs = $utilisateurs ?? [];
$interventions = $interventions ?? [];
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Ajouter une évaluation</h2>
            <p>Ajoutez une note et un commentaire pour une intervention réalisée.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=evaluations" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-evaluation" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div>
                    <h5>Informations de l’évaluation</h5>
                    <small>Choisissez l’utilisateur, l’intervention et la note.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Utilisateur</label>
                    <select name="id_utilisateur" class="form-select" required>

                        <option value="">Choisir un utilisateur</option>

                        <?php foreach ($utilisateurs as $user): ?>

                            <?php
                            $idUtilisateur = $user['id_utilisateur'] ?? $user['ID_UTILISATEUR'] ?? '';
                            $nom = $user['nom'] ?? $user['NOM'] ?? '';
                            $prenom = $user['prenom'] ?? $user['PRENOM'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idUtilisateur); ?>">
                                <?= htmlspecialchars(trim($prenom . ' ' . $nom)); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Intervention</label>
                    <select name="id_intervention" class="form-select" required>

                        <option value="">Choisir une intervention</option>

                        <?php foreach ($interventions as $intervention): ?>

                            <?php
                            $idIntervention = $intervention['id_intervention'] ?? $intervention['ID_INTERVENTION'] ?? '';
                            $idTicket = $intervention['id_ticket'] ?? $intervention['ID_TICKET'] ?? '';
                            $rapport = $intervention['rapport'] ?? $intervention['RAPPORT'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idIntervention); ?>">
                                #INT-<?= htmlspecialchars($idIntervention); ?>
                                <?= !empty($idTicket) ? ' - Ticket #' . htmlspecialchars($idTicket) : ''; ?>
                                <?= !empty($rapport) ? ' - ' . htmlspecialchars(substr($rapport, 0, 35)) : ''; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Note</label>
                    <select name="note" class="form-select" required>
                        <option value="">Choisir une note</option>
                        <option value="5">★★★★★ - Excellent</option>
                        <option value="4">★★★★☆ - Très bien</option>
                        <option value="3">★★★☆☆ - Moyen</option>
                        <option value="2">★★☆☆☆ - Faible</option>
                        <option value="1">★☆☆☆☆ - Mauvais</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d’évaluation</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-check"></i>
                        <input type="date"
                               name="date_evaluation"
                               class="form-control"
                               value="<?= date('Y-m-d'); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Commentaire</label>
                    <div class="textarea-with-icon">
                        <i class="bi bi-chat-left-text"></i>
                        <textarea name="commentaire"
                                  class="form-control"
                                  rows="5"
                                  placeholder="Ajouter un commentaire sur l’intervention..."></textarea>
                    </div>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=evaluations" class="btn btn-light border">
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