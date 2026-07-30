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

if (!function_exists('profileStatusLabel')) {
    function profileStatusLabel(string $value): string
    {
        return match (profileNormalize($value)) {
            'actif', 'active' => profileT('status_active'),
            'inactif', 'inactive' => profileT('status_inactive'),
            'suspendu', 'suspended' => profileT('status_suspended'),
            default => $value !== ''
                ? $value
                : profileT('status_active')
        };
    }
}

$nom = $_SESSION['nom'] ?? 'Admin';
$prenom = $_SESSION['prenom'] ?? 'FSJES';
$email = $_SESSION['email'] ?? 'admin@fsjes.ma';

$tel =
    $_SESSION['tel']
    ?? $_SESSION['telephone']
    ?? '0600000000';

$role =
    $_SESSION['role']
    ?? $_SESSION['nom_role']
    ?? 'Administrateur';

$statut = $_SESSION['statut'] ?? 'Actif';

$idUtilisateurConnecte =
    $_SESSION['id_utilisateur']
    ?? $_SESSION['ID_UTILISATEUR']
    ?? $_SESSION['user_id']
    ?? 1;

$dateCreation =
    $_SESSION['date_creation']
    ?? $_SESSION['DATE_CREATION']
    ?? '2024-01-15 10:30:00';

$derniereConnexion =
    $_SESSION['derniere_connexion']
    ?? $_SESSION['DERNIERE_CONNEXION']
    ?? date('Y-m-d') . ' 09:15:00';

$initiales = mb_strtoupper(
    mb_substr($prenom, 0, 1, 'UTF-8')
    . mb_substr($nom, 0, 1, 'UTF-8'),
    'UTF-8'
);

$roleAffiche = profileRoleLabel((string)$role);
$statutAffiche = profileStatusLabel((string)$statut);

$dateCreationAffiche = !empty($dateCreation)
    ? date('d/m/Y', strtotime($dateCreation))
        . ' '
        . profileT('at')
        . ' '
        . date('H:i', strtotime($dateCreation))
    : '-';

$derniereConnexionAffiche =
    !empty($derniereConnexion)
        ? date(
            'd/m/Y',
            strtotime($derniereConnexion)
        )
        . ' '
        . profileT('at')
        . ' '
        . date(
            'H:i',
            strtotime($derniereConnexion)
        )
        : '-';

?>

<div class="wow-profile-page">

    <div class="wow-profile-actions-top">

        <a href="<?= BASE_URL ?>?page=dashboard"
           class="wow-return-btn">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                profileT('back_dashboard')
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

    <div class="wow-profile-grid">

        <div class="wow-profile-card">

            <div class="wow-profile-cover"></div>

            <div class="wow-profile-avatar">

                <?= htmlspecialchars(
                    $initiales ?: 'FA'
                ); ?>

                <span></span>

            </div>

            <div class="wow-profile-center">

                <h3>
                    <?= htmlspecialchars(
                        trim($prenom . ' ' . $nom)
                    ); ?>
                </h3>

                <p>
                    <?= htmlspecialchars($roleAffiche); ?>
                </p>

                <div class="wow-active-badge">

                    <i class="bi bi-check-circle-fill"></i>

                    <?= htmlspecialchars(
                        profileT('active_account')
                    ); ?>

                </div>

            </div>

            <div class="wow-profile-contact-list">

                <div class="wow-profile-contact-item">

                    <div class="wow-contact-icon">
                        <i class="bi bi-envelope"></i>
                    </div>

                    <div>

                        <strong>
                            <?= htmlspecialchars($email); ?>
                        </strong>

                        <small>
                            <?= htmlspecialchars(
                                profileT('email')
                            ); ?>
                        </small>

                    </div>

                </div>

                <div class="wow-profile-contact-item">

                    <div class="wow-contact-icon">
                        <i class="bi bi-telephone"></i>
                    </div>

                    <div>

                        <strong>
                            <?= htmlspecialchars($tel); ?>
                        </strong>

                        <small>
                            <?= htmlspecialchars(
                                profileT('phone')
                            ); ?>
                        </small>

                    </div>

                </div>

                <div class="wow-profile-contact-item">

                    <div class="wow-contact-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>

                    <div>

                        <strong>
                            <?= htmlspecialchars(
                                $dateCreationAffiche
                            ); ?>
                        </strong>

                        <small>
                            <?= htmlspecialchars(
                                profileT('member_since')
                            ); ?>
                        </small>

                    </div>

                </div>

            </div>

            <a href="<?= BASE_URL ?>?page=modifier-utilisateur&id=<?= (int)$idUtilisateurConnecte; ?>"
               class="wow-edit-profile-btn">

                <i class="bi bi-pencil-square"></i>

                <?= htmlspecialchars(
                    profileT('edit_profile')
                ); ?>

            </a>

        </div>

        <div class="wow-profile-details">

            <div class="wow-section-title">

                <div class="wow-section-icon">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div>

                    <h4>
                        <?= htmlspecialchars(
                            profileT(
                                'personal_information'
                            )
                        ); ?>
                    </h4>

                    <small>
                        <?= htmlspecialchars(
                            profileT(
                                'connected_account_details'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="wow-info-box">

                <div class="wow-info-item">

                    <div class="wow-info-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('full_name')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                trim($prenom . ' ' . $nom)
                            ); ?>
                        </strong>

                    </div>

                </div>

                <div class="wow-info-item">

                    <div class="wow-info-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>

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

                <div class="wow-info-item">

                    <div class="wow-info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>

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

                <div class="wow-info-item">

                    <div class="wow-info-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('status')
                            ); ?>
                        </span>

                        <strong>

                            <span class="wow-status-pill">

                                <?= htmlspecialchars(
                                    $statutAffiche
                                ); ?>

                            </span>

                        </strong>

                    </div>

                </div>

                <div class="wow-info-item">

                    <div class="wow-info-icon">
                        <i class="bi bi-telephone"></i>
                    </div>

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

                <div class="wow-info-item">

                    <div class="wow-info-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>

                        <span>
                            <?= htmlspecialchars(
                                profileT('last_login')
                            ); ?>
                        </span>

                        <strong>
                            <?= htmlspecialchars(
                                $derniereConnexionAffiche
                            ); ?>
                        </strong>

                    </div>

                </div>

            </div>

            <div class="wow-security-box">

                <div class="wow-section-title small">

                    <div class="wow-section-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>

                    <div>

                        <h4>
                            <?= htmlspecialchars(
                                profileT(
                                    'account_security'
                                )
                            ); ?>
                        </h4>

                        <small>
                            <?= htmlspecialchars(
                                profileT(
                                    'account_protected'
                                )
                            ); ?>
                        </small>

                    </div>

                </div>

                <div class="wow-security-list">

                    <div class="wow-security-item">

                        <div class="wow-info-icon">
                            <i class="bi bi-lock-fill"></i>
                        </div>

                        <div>

                            <strong>
                                <?= htmlspecialchars(
                                    profileT('password')
                                ); ?>
                            </strong>

                            <small>************</small>

                        </div>

                        <a href="<?= BASE_URL ?>?page=parametres-compte"
                           class="wow-small-action">

                            <i class="bi bi-lock-fill"></i>

                            <?= htmlspecialchars(
                                profileT('change_password')
                            ); ?>

                        </a>

                    </div>

                    <div class="wow-security-item">

                        <div class="wow-info-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <div>

                            <strong>
                                <?= htmlspecialchars(
                                    profileT('authentication')
                                ); ?>
                            </strong>

                            <small>
                                <?= htmlspecialchars(
                                    profileT(
                                        'secure_session_active'
                                    )
                                ); ?>
                            </small>

                        </div>

                        <span class="wow-secured-badge">

                            <i class="bi bi-check"></i>

                            <?= htmlspecialchars(
                                profileT('secured')
                            ); ?>

                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>