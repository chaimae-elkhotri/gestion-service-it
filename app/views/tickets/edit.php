<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$ticket = $ticket ?? [];
$utilisateurs = $utilisateurs ?? [];
$moyens = $moyens ?? [];

$id = $ticket['id_ticket'] ?? $ticket['ID_TICKET'] ?? '';
$titre = $ticket['titre'] ?? $ticket['TITRE'] ?? '';
$description = $ticket['description'] ?? $ticket['DESCRIPTION'] ?? '';
$priorite = $ticket['priorite'] ?? $ticket['PRIORITE'] ?? 'Moyenne';
$statut = $ticket['statut'] ?? $ticket['STATUT'] ?? 'Ouvert';
$idUtilisateurTicket = $ticket['id_utilisateur'] ?? $ticket['ID_UTILISATEUR'] ?? '';
$idMoyenTicket = $ticket['id_moyen'] ?? $ticket['ID_MOYEN'] ?? '';
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier un ticket</h2>
            <p>Mettez à jour les informations de la demande d’assistance.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=tickets" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-ticket&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_ticket" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations du ticket</h5>
                    <small>Modifiez le titre, la description, la priorité et le statut.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Titre</label>
                    <div class="input-with-icon">
                        <i class="bi bi-pencil-square"></i>
                        <input type="text"
                               name="titre"
                               class="form-control"
                               value="<?= htmlspecialchars($titre); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Priorité</label>
                    <select name="priorite" class="form-select" required>
                        <option value="Basse" <?= ($priorite == 'Basse') ? 'selected' : ''; ?>>Basse</option>
                        <option value="Moyenne" <?= ($priorite == 'Moyenne') ? 'selected' : ''; ?>>Moyenne</option>
                        <option value="Haute" <?= ($priorite == 'Haute') ? 'selected' : ''; ?>>Haute</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="Ouvert" <?= ($statut == 'Ouvert') ? 'selected' : ''; ?>>Ouvert</option>
                        <option value="En cours" <?= ($statut == 'En cours') ? 'selected' : ''; ?>>En cours</option>
                        <option value="En attente" <?= ($statut == 'En attente') ? 'selected' : ''; ?>>En attente</option>
                        <option value="Résolu" <?= ($statut == 'Résolu') ? 'selected' : ''; ?>>Résolu</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <div class="textarea-with-icon">
                        <i class="bi bi-card-text"></i>
                        <textarea name="description"
                                  class="form-control"
                                  rows="5"
                                  required><?= htmlspecialchars($description); ?></textarea>
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
                <div>
                    <h5>Demandeur et moyen de communication</h5>
                    <small>Modifiez l’utilisateur demandeur et le canal utilisé.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Utilisateur demandeur</label>
                    <select name="id_utilisateur" class="form-select" required>

                        <option value="">Choisir un utilisateur</option>

                        <?php foreach ($utilisateurs as $user): ?>

                            <?php
                            $idUtilisateur = $user['id_utilisateur'] ?? $user['ID_UTILISATEUR'] ?? '';
                            $nom = $user['nom'] ?? $user['NOM'] ?? '';
                            $prenom = $user['prenom'] ?? $user['PRENOM'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idUtilisateur); ?>"
                                <?= ($idUtilisateur == $idUtilisateurTicket) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars(trim($prenom . ' ' . $nom)); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Moyen de communication</label>
                    <select name="id_moyen" class="form-select" required>

                        <option value="">Choisir un moyen</option>

                        <?php if (!empty($moyens)): ?>

                            <?php foreach ($moyens as $moyen): ?>

                                <?php
                                $idMoyen = $moyen['id_moyen'] ?? $moyen['ID_MOYEN'] ?? '';
                                $libelle = $moyen['libelle'] ?? $moyen['LIBELLE'] ?? $moyen['nom_moyen'] ?? $moyen['NOM_MOYEN'] ?? '';
                                ?>

                                <option value="<?= htmlspecialchars($idMoyen); ?>"
                                    <?= ($idMoyen == $idMoyenTicket) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($libelle); ?>
                                </option>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <option value="1" <?= ($idMoyenTicket == 1) ? 'selected' : ''; ?>>Email</option>
                            <option value="2" <?= ($idMoyenTicket == 2) ? 'selected' : ''; ?>>Téléphone</option>
                            <option value="3" <?= ($idMoyenTicket == 3) ? 'selected' : ''; ?>>Présentiel</option>

                        <?php endif; ?>

                    </select>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=tickets" class="btn btn-light border">
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