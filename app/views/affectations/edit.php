<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$affectation = $affectation ?? $affectationEquipement ?? [];
$utilisateurs = $utilisateurs ?? [];
$equipements = $equipements ?? [];

$id = $affectation['id_affectation'] ?? $affectation['ID_AFFECTATION_EQUIP'] ?? '';
$idUtilisateurAff = $affectation['id_utilisateur'] ?? $affectation['ID_UTILISATEUR'] ?? '';
$idEquipementAff = $affectation['id_equipement'] ?? $affectation['ID_EQUIPEMENT_'] ?? '';
$dateAffectation = $affectation['date_affectation'] ?? $affectation['DATE_AFFECTATION'] ?? '';
$dateFinAffectation = $affectation['date_fin_affectation'] ?? $affectation['DATE_FIN_AFFECTATION'] ?? '';
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier une affectation</h2>
            <p>Mettez à jour l’affectation d’un équipement à un utilisateur.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=affectations" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-affectation&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_affectation" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations de l’affectation</h5>
                    <small>Modifiez l’utilisateur ou l’équipement affecté.</small>
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

                            <option value="<?= htmlspecialchars($idUtilisateur); ?>"
                                <?= ($idUtilisateur == $idUtilisateurAff) ? 'selected' : ''; ?>>
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

                            <option value="<?= htmlspecialchars($idEquipement); ?>"
                                <?= ($idEquipement == $idEquipementAff) ? 'selected' : ''; ?>>
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
                    <small>Modifiez la date de début ou la date de fin.</small>
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
                               value="<?= htmlspecialchars($dateAffectation); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date fin affectation</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-x"></i>
                        <input type="date"
                               name="date_fin_affectation"
                               class="form-control"
                               value="<?= htmlspecialchars($dateFinAffectation); ?>">
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
                    Mettre à jour
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>