<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$utilisateur = $utilisateur ?? $user ?? [];
$roles = $roles ?? [];

$id = $utilisateur['id_utilisateur'] ?? $utilisateur['ID_UTILISATEUR'] ?? '';
$nom = $utilisateur['nom'] ?? $utilisateur['NOM'] ?? '';
$prenom = $utilisateur['prenom'] ?? $utilisateur['PRENOM'] ?? '';
$email = $utilisateur['email'] ?? $utilisateur['EMAIL'] ?? '';
$tel = $utilisateur['tel'] ?? $utilisateur['telephone'] ?? $utilisateur['TEL'] ?? $utilisateur['TELEPHONE'] ?? '';
$statut = $utilisateur['statut'] ?? $utilisateur['STATUT'] ?? 'Actif';
$idRoleUser = $utilisateur['id_role'] ?? $utilisateur['ID_ROLE'] ?? '';
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier un utilisateur</h2>
            <p>Mettez à jour les informations du compte utilisateur.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=utilisateurs" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-utilisateur&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_utilisateur" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations personnelles</h5>
                    <small>Modifiez les informations de base de l’utilisateur.</small>
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
                               value="<?= htmlspecialchars($nom); ?>"
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
                               value="<?= htmlspecialchars($prenom); ?>"
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
                               value="<?= htmlspecialchars($email); ?>"
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
                               value="<?= htmlspecialchars($tel); ?>">
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <h5>Accès au système</h5>
                    <small>Modifiez le rôle, le statut ou le mot de passe.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label">Rôle</label>
                    <select name="id_role" class="form-select" required>

                        <?php if (!empty($roles)): ?>

                            <?php foreach ($roles as $role): ?>

                                <?php
                                $idRole = $role['id_role'] ?? $role['ID_ROLE'] ?? '';
                                $nomRole = $role['nom_role'] ?? $role['NOM_ROLE'] ?? '';
                                ?>

                                <option value="<?= htmlspecialchars($idRole); ?>"
                                    <?= ($idRole == $idRoleUser) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($nomRole); ?>
                                </option>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <option value="1" <?= ($idRoleUser == 1) ? 'selected' : ''; ?>>Administrateur</option>
                            <option value="2" <?= ($idRoleUser == 2) ? 'selected' : ''; ?>>Technicien</option>
                            <option value="3" <?= ($idRoleUser == 3) ? 'selected' : ''; ?>>Employé</option>

                        <?php endif; ?>

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select" required>
                        <option value="Actif" <?= ($statut == 'Actif') ? 'selected' : ''; ?>>Actif</option>
                        <option value="Inactif" <?= ($statut == 'Inactif') ? 'selected' : ''; ?>>Inactif</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nouveau mot de passe</label>
                    <div class="input-with-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password"
                               name="mot_de_passe"
                               class="form-control"
                               placeholder="Laisser vide si inchangé">
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
                    Mettre à jour
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>