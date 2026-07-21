<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$roles = $roles ?? [];
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Ajouter un utilisateur</h2>
            <p>Créez un nouveau compte utilisateur pour accéder au système.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=utilisateurs" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-utilisateur" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <h5>Informations personnelles</h5>
                    <small>Renseignez les informations de base de l’utilisateur.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <div class="input-with-icon">
                        <i class="bi bi-person"></i>
                        <input type="text"
                               name="nom"
                               class="form-control"
                               placeholder="Ex : El Khotri"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <div class="input-with-icon">
                        <i class="bi bi-person"></i>
                        <input type="text"
                               name="prenom"
                               class="form-control"
                               placeholder="Ex : Chaimae"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <div class="input-with-icon">
                        <i class="bi bi-envelope"></i>
                        <input type="email"
                               name="email"
                               class="form-control"
                               placeholder="exemple@fsjes.ma"
                               required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <div class="input-with-icon">
                        <i class="bi bi-telephone"></i>
                        <input type="text"
                               name="tel"
                               class="form-control"
                               placeholder="06 00 00 00 00">
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <h5>Accès au système</h5>
                    <small>Définissez le rôle, le statut et le mot de passe.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Rôle</label>
                    <select name="id_role" class="form-select" required>

                        <option value="">Choisir un rôle</option>

                        <?php if (!empty($roles)): ?>

                            <?php foreach ($roles as $role): ?>

                                <?php
                                $idRole = $role['id_role'] ?? $role['ID_ROLE'] ?? '';
                                $nomRole = $role['nom_role'] ?? $role['NOM_ROLE'] ?? '';
                                ?>

                                <option value="<?= htmlspecialchars($idRole); ?>">
                                    <?= htmlspecialchars($nomRole); ?>
                                </option>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <option value="1">Administrateur</option>
                            <option value="2">Technicien</option>
                            <option value="3">Employé</option>

                        <?php endif; ?>

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="Actif">Actif</option>
                        <option value="Inactif">Inactif</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-with-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password"
                               name="mot_de_passe"
                               class="form-control"
                               placeholder="Mot de passe"
                               required>
                    </div>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=utilisateurs" class="btn btn-light border">
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