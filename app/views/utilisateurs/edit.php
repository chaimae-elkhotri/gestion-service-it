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
$tel = $utilisateur['tel']
    ?? $utilisateur['telephone']
    ?? $utilisateur['TEL']
    ?? $utilisateur['TELEPHONE']
    ?? '';
$statut = $utilisateur['statut'] ?? $utilisateur['STATUT'] ?? 'Actif';
$idRoleUser = $utilisateur['id_role'] ?? $utilisateur['ID_ROLE'] ?? '';

if (!function_exists('usersT')) {
    function usersT(string $key, array $replacements = []): string
    {
        return t('users_module.' . $key, $replacements);
    }
}

if (!function_exists('userNormalizeValue')) {
    function userNormalizeValue(string $value): string
    {
        $value = mb_strtolower(trim($value), 'UTF-8');

        return strtr($value, [
            'é' => 'e',
            'è' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'à' => 'a',
            'â' => 'a',
            'î' => 'i',
            'ï' => 'i',
            'ô' => 'o',
            'ù' => 'u',
            'û' => 'u',
            'ç' => 'c'
        ]);
    }
}

if (!function_exists('userRoleLabel')) {
    function userRoleLabel($role, $idRole = null): string
    {
        $roleText = userNormalizeValue((string)$role);
        $id = (string)($idRole ?? $role);

        if ($id === '1' || str_contains($roleText, 'admin')) {
            return t('role.admin');
        }

        if ($id === '2' || str_contains($roleText, 'technicien')) {
            return t('role.technician');
        }

        if ($id === '3' || str_contains($roleText, 'employe')) {
            return t('role.employee');
        }

        return (string)$role;
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2><?= htmlspecialchars(usersT('edit_title')); ?></h2>
            <p><?= htmlspecialchars(usersT('edit_intro')); ?></p>
        </div>

        <a href="<?= BASE_URL ?>?page=utilisateurs" class="btn btn-light border">
            <i class="bi <?= Language::isRtl() ? 'bi-arrow-right' : 'bi-arrow-left'; ?>"></i>
            <?= htmlspecialchars(usersT('back')); ?>
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
                    <h5><?= htmlspecialchars(usersT('personal_info')); ?></h5>
                    <small><?= htmlspecialchars(usersT('edit_personal_help')); ?></small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label"><?= htmlspecialchars(usersT('last_name')); ?></label>
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
                    <label class="form-label"><?= htmlspecialchars(usersT('first_name')); ?></label>
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
                    <label class="form-label"><?= htmlspecialchars(usersT('email')); ?></label>
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
                    <label class="form-label"><?= htmlspecialchars(usersT('phone')); ?></label>
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
                    <h5><?= htmlspecialchars(usersT('system_access')); ?></h5>
                    <small><?= htmlspecialchars(usersT('edit_access_help')); ?></small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label"><?= htmlspecialchars(usersT('role')); ?></label>
                    <select name="id_role" class="form-select" required>

                        <?php if (!empty($roles)): ?>

                            <?php foreach ($roles as $role): ?>

                                <?php
                                $idRole = $role['id_role'] ?? $role['ID_ROLE'] ?? '';
                                $nomRole = $role['nom_role'] ?? $role['NOM_ROLE'] ?? '';
                                ?>

                                <option value="<?= htmlspecialchars($idRole); ?>"
                                    <?= ((string)$idRole === (string)$idRoleUser) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars(userRoleLabel($nomRole, $idRole)); ?>
                                </option>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <option value="1" <?= ((string)$idRoleUser === '1') ? 'selected' : ''; ?>>
                                <?= htmlspecialchars(t('role.admin')); ?>
                            </option>
                            <option value="2" <?= ((string)$idRoleUser === '2') ? 'selected' : ''; ?>>
                                <?= htmlspecialchars(t('role.technician')); ?>
                            </option>
                            <option value="3" <?= ((string)$idRoleUser === '3') ? 'selected' : ''; ?>>
                                <?= htmlspecialchars(t('role.employee')); ?>
                            </option>

                        <?php endif; ?>

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"><?= htmlspecialchars(usersT('status')); ?></label>
                    <select name="statut" class="form-select" required>
                        <option value="Actif" <?= (userNormalizeValue((string)$statut) === 'actif') ? 'selected' : ''; ?>>
                            <?= htmlspecialchars(usersT('active')); ?>
                        </option>
                        <option value="Inactif" <?= (userNormalizeValue((string)$statut) === 'inactif') ? 'selected' : ''; ?>>
                            <?= htmlspecialchars(usersT('inactive')); ?>
                        </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"><?= htmlspecialchars(usersT('new_password')); ?></label>
                    <div class="input-with-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password"
                               name="mot_de_passe"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(usersT('unchanged_password')); ?>">
                    </div>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=utilisateurs" class="btn btn-light border">
                    <i class="bi bi-x-circle"></i>
                    <?= htmlspecialchars(usersT('cancel')); ?>
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    <?= htmlspecialchars(usersT('update')); ?>
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>