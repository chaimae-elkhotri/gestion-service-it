<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

if (!function_exists('profileT')) {
    function profileT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'profile_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('profileNormalize')) {
    function profileNormalize(string $value): string
    {
        $value = mb_strtolower(
            trim($value),
            'UTF-8'
        );

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

if (!function_exists('profileRoleLabel')) {
    function profileRoleLabel(string $value): string
    {
        return match (profileNormalize($value)) {
            'administrateur' => profileT('role_admin'),
            'technicien' => profileT('role_technician'),
            'employe' => profileT('role_employee'),
            default => $value !== ''
                ? $value
                : profileT('role_user')
        };
    }
}

$nom = $_SESSION['nom'] ?? 'Utilisateur';
$prenom = $_SESSION['prenom'] ?? '';
$email = $_SESSION['email'] ?? '';

$tel =
    $_SESSION['tel']
    ?? $_SESSION['telephone']
    ?? '-';

$role =
    $_SESSION['role']
    ?? $_SESSION['nom_role']
    ?? 'Utilisateur';

$roleAffiche = profileRoleLabel((string)$role);

$langueCourante =
    $_SESSION['language']
    ?? $_SESSION['lang']
    ?? $_COOKIE['language']
    ?? (
        defined('DEFAULT_LANGUAGE')
            ? DEFAULT_LANGUAGE
            : 'fr'
    );

$langueAffichee =
    str_starts_with(
        strtolower((string)$langueCourante),
        'ar'
    )
        ? profileT('language_arabic')
        : profileT('language_french');

?>

<div class="wow-settings-page">

    <div class="wow-profile-actions-top">

        <a href="<?= BASE_URL ?>?page=profil"
           class="wow-return-btn">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                profileT('back_profile')
            ); ?>

        </a>

    </div>

    <?php if (isset($_SESSION['success'])): ?>

        <div class="alert alert-success">

            <i class="bi bi-check-circle-fill me-2"></i>

            <?= htmlspecialchars($_SESSION['success']); ?>

        </div>

        <?php unset($_SESSION['success']); ?>

    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <?= htmlspecialchars($_SESSION['error']); ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <div class="wow-settings-grid">

        <div class="wow-settings-card">

            <div class="wow-settings-card-header purple">

                <div class="wow-settings-icon">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div>

                    <h4>
                        <?= htmlspecialchars(
                            profileT(
                                'account_information'
                            )
                        ); ?>
                    </h4>

                    <small>
                        <?= htmlspecialchars(
                            profileT(
                                'personal_information_short'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="wow-settings-list">

                <div class="wow-settings-row">

                    <i class="bi bi-person"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('name')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                trim($prenom . ' ' . $nom)
                            ); ?>
                        </strong>

                    </div>

                </div>

                <div class="wow-settings-row">

                    <i class="bi bi-envelope"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('email')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars($email); ?>
                        </strong>

                    </div>

                </div>

                <div class="wow-settings-row">

                    <i class="bi bi-telephone"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('phone')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars($tel); ?>
                        </strong>

                    </div>

                </div>

                <div class="wow-settings-row">

                    <i class="bi bi-person-badge"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('role')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $roleAffiche
                            ); ?>
                        </strong>

                    </div>

                </div>

            </div>

            <a href="<?= BASE_URL ?>?page=profil"
               class="wow-settings-btn purple">

                <i class="bi bi-person-circle"></i>

                <?= htmlspecialchars(
                    profileT('view_profile')
                ); ?>

            </a>

        </div>

        <div class="wow-settings-card">

            <div class="wow-settings-card-header green">

                <div class="wow-settings-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <div>

                    <h4>
                        <?= htmlspecialchars(
                            profileT('security')
                        ); ?>
                    </h4>

                    <small>
                        <?= htmlspecialchars(
                            profileT(
                                'manage_account_security'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="wow-settings-list">

                <button type="button"
                        class="wow-settings-row arrow settings-link-row border-0 bg-transparent w-100 text-start"
                        data-bs-toggle="modal"
                        data-bs-target="#changePasswordModal">

                    <i class="bi bi-lock"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('password')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                profileT(
                                    'edit_my_password'
                                )
                            ); ?>
                        </strong>

                    </div>

                    <i class="bi bi-chevron-right"></i>

                </button>

                <div class="wow-settings-row">

                    <i class="bi bi-shield-check"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('authentication')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                profileT(
                                    'secure_session_active'
                                )
                            ); ?>
                        </strong>

                    </div>

                    <span class="wow-green-dot">
                        <i class="bi bi-check"></i>
                    </span>

                </div>

                <?php if (
                    (int)($_SESSION['id_role'] ?? 0) === 1
                ): ?>

                    <a href="<?= BASE_URL ?>?page=historiques"
                       class="wow-settings-row arrow settings-link-row">

                        <i class="bi bi-clock-history"></i>

                        <div>

                            <span>
                                <?= htmlspecialchars(
                                    profileT(
                                        'recent_activities'
                                    )
                                ); ?>
                            </span>

                            <strong>
                                <?= htmlspecialchars(
                                    profileT(
                                        'view_action_history'
                                    )
                                ); ?>
                            </strong>

                        </div>

                        <i class="bi bi-chevron-right"></i>

                    </a>

                <?php endif; ?>

            </div>

            <button type="button"
                    class="wow-settings-btn green border-0"
                    data-bs-toggle="modal"
                    data-bs-target="#changePasswordModal">

                <i class="bi bi-pencil-square"></i>

                <?= htmlspecialchars(
                    profileT('change_password')
                ); ?>

            </button>

        </div>

        <div class="wow-settings-card">

            <div class="wow-settings-card-header orange">

                <div class="wow-settings-icon">
                    <i class="bi bi-bell-fill"></i>
                </div>

                <div>

                    <h4>
                        <?= htmlspecialchars(
                            profileT('preferences')
                        ); ?>
                    </h4>

                    <small>
                        <?= htmlspecialchars(
                            profileT(
                                'customize_experience'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="wow-settings-list">

                <div class="wow-settings-row">

                    <i class="bi bi-bell"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('notifications')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('enabled')
                            ); ?>
                        </strong>

                    </div>

                </div>

                <div class="wow-settings-row">

                    <i class="bi bi-globe2"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('language')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $langueAffichee
                            ); ?>
                        </strong>

                    </div>

                </div>

                <div class="wow-settings-row">

                    <i class="bi bi-brightness-high"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('theme')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('light')
                            ); ?>
                        </strong>

                    </div>

                </div>

            </div>

            <button type="button"
                    class="wow-settings-btn orange border-0"
                    data-bs-toggle="modal"
                    data-bs-target="#preferencesModal">

                <i class="bi bi-pencil-square"></i>

                <?= htmlspecialchars(
                    profileT('manage_preferences')
                ); ?>

            </button>

        </div>

        <div class="wow-settings-card">

            <div class="wow-settings-card-header red">

                <div class="wow-settings-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </div>

                <div>

                    <h4>
                        <?= htmlspecialchars(
                            profileT('session')
                        ); ?>
                    </h4>

                    <small>
                        <?= htmlspecialchars(
                            profileT('manage_session')
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="wow-settings-list">

                <div class="wow-settings-row">

                    <i class="bi bi-toggle-on"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT(
                                    'session_status'
                                )
                            ); ?>
                        </span>

                        <strong>

                            <span class="wow-status-pill">

                                <?= htmlspecialchars(
                                    profileT(
                                        'status_active_feminine'
                                    )
                                ); ?>

                            </span>

                        </strong>

                    </div>

                </div>

                <div class="wow-settings-row">

                    <i class="bi bi-clock"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('login_date')
                            ); ?>
                        </span>

                        <strong>
                            <?= date('d/m/Y'); ?>
                        </strong>

                    </div>

                </div>

                <div class="wow-settings-row">

                    <i class="bi bi-laptop"></i>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('device')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('web_browser')
                            ); ?>
                        </strong>

                    </div>

                </div>

            </div>

            <a href="<?= BASE_URL ?>?page=logout"
               class="wow-settings-btn red">

                <i class="bi bi-box-arrow-right"></i>

                <?= htmlspecialchars(
                    profileT('logout')
                ); ?>

            </a>

        </div>

    </div>

    <div class="wow-security-advice">

        <div class="wow-advice-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <div>

            <h5>
                <?= htmlspecialchars(
                    profileT('security_advice')
                ); ?>
            </h5>

            <p>
                <?= htmlspecialchars(
                    profileT(
                        'security_advice_text'
                    )
                ); ?>
            </p>

        </div>

        <button type="button"
                data-bs-toggle="modal"
                data-bs-target="#securityAdviceModal">

            <?= htmlspecialchars(
                profileT('learn_more')
            ); ?>

            <i class="bi bi-chevron-right"></i>

        </button>

    </div>

</div>

<div class="modal fade"
     id="changePasswordModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content support-modal-content">

            <div class="modal-header support-modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-key-fill"></i>

                    <?= htmlspecialchars(
                        profileT(
                            'change_my_password'
                        )
                    ); ?>

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="<?= htmlspecialchars(
                            profileT('close')
                        ); ?>">
                </button>

            </div>

            <form action="<?= BASE_URL ?>?page=parametres-compte"
                  method="POST">

                <div class="modal-body">

                    <input type="hidden"
                           name="action"
                           value="changer_mot_de_passe">

                    <div class="mb-3">

                        <label class="form-label">
                            <?= htmlspecialchars(
                                profileT(
                                    'current_password'
                                )
                            ); ?>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input type="password"
                                   name="mot_de_passe_actuel"
                                   class="form-control"
                                   placeholder="<?= htmlspecialchars(
                                       profileT(
                                           'current_password_placeholder'
                                       )
                                   ); ?>"
                                   autocomplete="current-password"
                                   required>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            <?= htmlspecialchars(
                                profileT('new_password')
                            ); ?>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-key"></i>
                            </span>

                            <input type="password"
                                   name="nouveau_mot_de_passe"
                                   class="form-control"
                                   placeholder="<?= htmlspecialchars(
                                       profileT(
                                           'minimum_characters'
                                       )
                                   ); ?>"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            <?= htmlspecialchars(
                                profileT(
                                    'confirm_new_password'
                                )
                            ); ?>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-check-circle"></i>
                            </span>

                            <input type="password"
                                   name="confirmation_mot_de_passe"
                                   class="form-control"
                                   placeholder="<?= htmlspecialchars(
                                       profileT(
                                           'confirm_password_placeholder'
                                       )
                                   ); ?>"
                                   minlength="8"
                                   autocomplete="new-password"
                                   required>

                        </div>

                    </div>

                    <div class="alert alert-info mb-0">

                        <i class="bi bi-info-circle-fill me-2"></i>

                        <?= htmlspecialchars(
                            profileT(
                                'password_requirement'
                            )
                        ); ?>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light border"
                            data-bs-dismiss="modal">

                        <?= htmlspecialchars(
                            profileT('cancel')
                        ); ?>

                    </button>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle-fill"></i>

                        <?= htmlspecialchars(
                            profileT('save')
                        ); ?>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="modal fade"
     id="preferencesModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content support-modal-content">

            <div class="modal-header support-modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-sliders"></i>

                    <?= htmlspecialchars(
                        profileT(
                            'account_preferences'
                        )
                    ); ?>

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="<?= htmlspecialchars(
                            profileT('close')
                        ); ?>">
                </button>

            </div>

            <div class="modal-body">

                <div class="support-item">

                    <i class="bi bi-bell-fill"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('notifications')
                            ); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars(
                                profileT(
                                    'notifications_enabled_text'
                                )
                            ); ?>
                        </p>

                    </div>

                </div>

                <div class="support-item">

                    <i class="bi bi-globe2"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('language')
                            ); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars(
                                profileT(
                                    'language_configured_text',
                                    [
                                        'language' =>
                                            $langueAffichee
                                    ]
                                )
                            ); ?>
                        </p>

                    </div>

                </div>

                <div class="support-item">

                    <i class="bi bi-brightness-high-fill"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('theme')
                            ); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars(
                                profileT(
                                    'light_theme_text'
                                )
                            ); ?>
                        </p>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal">

                    <?= htmlspecialchars(
                        profileT('understood')
                    ); ?>

                </button>

            </div>

        </div>

    </div>

</div>

<div class="modal fade"
     id="securityAdviceModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content support-modal-content">

            <div class="modal-header support-modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-shield-lock-fill"></i>

                    <?= htmlspecialchars(
                        profileT('security_advice')
                    ); ?>

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="<?= htmlspecialchars(
                            profileT('close')
                        ); ?>">
                </button>

            </div>

            <div class="modal-body">

                <div class="support-item">

                    <i class="bi bi-key-fill"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('strong_password')
                            ); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars(
                                profileT(
                                    'strong_password_text'
                                )
                            ); ?>
                        </p>

                    </div>

                </div>

                <div class="support-item">

                    <i class="bi bi-person-lock"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('privacy')
                            ); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars(
                                profileT('privacy_text')
                            ); ?>
                        </p>

                    </div>

                </div>

                <div class="support-item">

                    <i class="bi bi-box-arrow-right"></i>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                profileT('logout')
                            ); ?>
                        </strong>

                        <p>
                            <?= htmlspecialchars(
                                profileT('logout_text')
                            ); ?>
                        </p>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal">

                    <?= htmlspecialchars(
                        profileT('understood')
                    ); ?>

                </button>

            </div>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>