<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$roles = $roles ?? [];

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
            <h2><?= htmlspecialchars(usersT('add_title')); ?></h2>
            <p><?= htmlspecialchars(usersT('add_intro')); ?></p>
        </div>

        <a href="<?= BASE_URL ?>?page=utilisateurs" class="btn btn-light border">
            <i class="bi <?= Language::isRtl() ? 'bi-arrow-right' : 'bi-arrow-left'; ?>"></i>
            <?= htmlspecialchars(usersT('back')); ?>
        </a>

    </div>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['error']); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= htmlspecialchars($_SESSION['success']); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-utilisateur" method="POST">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <h5><?= htmlspecialchars(usersT('personal_info')); ?></h5>
                    <small><?= htmlspecialchars(usersT('personal_info_help')); ?></small>
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
                               placeholder="<?= htmlspecialchars(usersT('last_name_placeholder')); ?>"
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
                               placeholder="<?= htmlspecialchars(usersT('first_name_placeholder')); ?>"
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
                               placeholder="<?= htmlspecialchars(usersT('email_placeholder')); ?>"
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
                               placeholder="<?= htmlspecialchars(usersT('phone_placeholder')); ?>">
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <h5><?= htmlspecialchars(usersT('system_access')); ?></h5>
                    <small><?= htmlspecialchars(usersT('system_access_help')); ?></small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <label class="form-label"><?= htmlspecialchars(usersT('role')); ?></label>
                    <select name="id_role" class="form-select" required>

                        <option value=""><?= htmlspecialchars(usersT('choose_role')); ?></option>

                        <?php if (!empty($roles)): ?>

                            <?php foreach ($roles as $role): ?>

                                <?php
                                $idRole = $role['id_role'] ?? $role['ID_ROLE'] ?? '';
                                $nomRole = $role['nom_role'] ?? $role['NOM_ROLE'] ?? '';
                                ?>

                                <option value="<?= htmlspecialchars($idRole); ?>">
                                    <?= htmlspecialchars(userRoleLabel($nomRole, $idRole)); ?>
                                </option>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <option value="1"><?= htmlspecialchars(t('role.admin')); ?></option>
                            <option value="2"><?= htmlspecialchars(t('role.technician')); ?></option>
                            <option value="3"><?= htmlspecialchars(t('role.employee')); ?></option>

                        <?php endif; ?>

                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"><?= htmlspecialchars(usersT('status')); ?></label>
                    <select name="statut" class="form-select" required>
                        <option value="Actif"><?= htmlspecialchars(usersT('active')); ?></option>
                        <option value="Inactif"><?= htmlspecialchars(usersT('inactive')); ?></option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"><?= htmlspecialchars(usersT('password')); ?></label>
                    <div class="input-with-icon">
                        <i class="bi bi-lock"></i>
                        <input type="password"
                               name="mot_de_passe"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(usersT('password_placeholder')); ?>"
                               minlength="8"
                               autocomplete="new-password"
                               required>
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
                    <?= htmlspecialchars(usersT('save')); ?>
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>