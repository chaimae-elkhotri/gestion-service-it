<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$utilisateurs = $utilisateurs ?? [];
$equipements = $equipements ?? [];
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Ajouter une affectation</h2>
            <p>Affectez un équipement à un utilisateur de la FSJES Oujda.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=affectations" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-affectation" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div>
                    <h5>Informations de l’affectation</h5>
                    <small>Choisissez l’utilisateur et l’équipement à affecter.</small>
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
                            $role = $user['nom_role'] ?? $user['NOM_ROLE'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idUtilisateur); ?>">
                                <?= htmlspecialchars(trim($prenom . ' ' . $nom)); ?>
                                <?= !empty($role) ? ' - ' . htmlspecialchars($role) : ''; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Équipement</label>
                    <select name="id_equipement" class="form-select" required>

                        <option value="">Choisir un équipement</option>

                        <?php foreach ($equipements as $eq): ?>

                            <?php
                            $idEquipement = $eq['id_equipement'] ?? $eq['ID_EQUIPEMENT_'] ?? '';
                            $numeroSerie = $eq['numero_serie'] ?? $eq['NUMERO_SERIE'] ?? '';
                            $marque = $eq['marque'] ?? $eq['MARQUE'] ?? '';
                            $modele = $eq['modele'] ?? $eq['MODELE'] ?? '';
                            $statut = $eq['statut'] ?? $eq['STATUT'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idEquipement); ?>">
                                <?= htmlspecialchars($numeroSerie . ' - ' . $marque . ' ' . $modele); ?>
                                <?= !empty($statut) ? ' (' . htmlspecialchars($statut) . ')' : ''; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>
                <div>
                    <h5>Période d’affectation</h5>
                    <small>Indiquez la date de début et éventuellement la date de fin.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Date d’affectation</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-check"></i>
                        <input type="date"
                               name="date_affectation"
                               class="form-control"
                               value="<?= date('Y-m-d'); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date fin affectation</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-x"></i>
                        <input type="date"
                               name="date_fin_affectation"
                               class="form-control">
                    </div>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=affectations" class="btn btn-light border">
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