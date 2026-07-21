<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$nom = $_SESSION['nom'] ?? 'Admin';
$prenom = $_SESSION['prenom'] ?? 'FSJES';
$email = $_SESSION['email'] ?? 'admin@fsjes.ma';
$tel = $_SESSION['tel'] ?? $_SESSION['telephone'] ?? '0600000000';
$role = $_SESSION['role'] ?? $_SESSION['nom_role'] ?? 'Administrateur';
$statut = $_SESSION['statut'] ?? 'Actif';
$idUtilisateurConnecte = $_SESSION['id_utilisateur'] 
    ?? $_SESSION['ID_UTILISATEUR'] 
    ?? $_SESSION['user_id'] 
    ?? 1;

$initiales = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
?>

<div class="wow-profile-page">

    <div class="wow-profile-actions-top">
        <a href="<?= BASE_URL ?>?page=dashboard" class="wow-return-btn">
            <i class="bi bi-arrow-left"></i>
            Retour au tableau de bord
        </a>
    </div>

    <div class="wow-profile-grid">

        <div class="wow-profile-card">

            <div class="wow-profile-cover"></div>

            <div class="wow-profile-avatar">
                <?= htmlspecialchars($initiales ?: 'FA'); ?>
                <span></span>
            </div>

            <div class="wow-profile-center">

                <h3><?= htmlspecialchars($prenom . ' ' . $nom); ?></h3>

                <p><?= htmlspecialchars($role); ?></p>

                <div class="wow-active-badge">
                    <i class="bi bi-check-circle-fill"></i>
                    Compte actif
                </div>

            </div>

            <div class="wow-profile-contact-list">

                <div class="wow-profile-contact-item">
                    <div class="wow-contact-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($email); ?></strong>
                        <small>Email</small>
                    </div>
                </div>

                <div class="wow-profile-contact-item">
                    <div class="wow-contact-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($tel); ?></strong>
                        <small>Téléphone</small>
                    </div>
                </div>

                <div class="wow-profile-contact-item">
                    <div class="wow-contact-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <strong>15/01/2024 à 10:30</strong>
                        <small>Membre depuis</small>
                    </div>
                </div>

            </div>

           <a href="<?= BASE_URL ?>?page=modifier-utilisateur&id=<?= htmlspecialchars($idUtilisateurConnecte); ?>" 
   class="wow-edit-profile-btn">
                <i class="bi bi-pencil-square"></i>
                Modifier mon profil
            </a>

        </div>

        <div class="wow-profile-details">

            <div class="wow-section-title">

                <div class="wow-section-icon">
                    <i class="bi bi-person-fill"></i>
                </div>

                <div>
                    <h4>Informations personnelles</h4>
                    <small>Détails du compte connecté</small>
                </div>

            </div>

            <div class="wow-info-box">

                <div class="wow-info-item">
                    <div class="wow-info-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <span>Nom complet</span>
                        <strong><?= htmlspecialchars($prenom . ' ' . $nom); ?></strong>
                    </div>
                </div>

                <div class="wow-info-item">
                    <div class="wow-info-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <span>Rôle</span>
                        <strong><?= htmlspecialchars($role); ?></strong>
                    </div>
                </div>

                <div class="wow-info-item">
                    <div class="wow-info-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <span>Email</span>
                        <strong><?= htmlspecialchars($email); ?></strong>
                    </div>
                </div>

                <div class="wow-info-item">
                    <div class="wow-info-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <span>Statut</span>
                        <strong>
                            <span class="wow-status-pill">
                                <?= htmlspecialchars($statut); ?>
                            </span>
                        </strong>
                    </div>
                </div>

                <div class="wow-info-item">
                    <div class="wow-info-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <span>Téléphone</span>
                        <strong><?= htmlspecialchars($tel); ?></strong>
                    </div>
                </div>

                <div class="wow-info-item">
                    <div class="wow-info-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <span>Dernière connexion</span>
                        <strong><?= date('d/m/Y'); ?> à 09:15</strong>
                    </div>
                </div>

            </div>

            <div class="wow-security-box">

                <div class="wow-section-title small">

                    <div class="wow-section-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>

                    <div>
                        <h4>Sécurité du compte</h4>
                        <small>Votre compte est sécurisé et protégé.</small>
                    </div>

                </div>

                <div class="wow-security-list">

                    <div class="wow-security-item">
                        <div class="wow-info-icon">
                            <i class="bi bi-lock-fill"></i>
                        </div>
                        <div>
                            <strong>Mot de passe</strong>
                            <small>************</small>
                        </div>
                        <a href="<?= BASE_URL ?>?page=parametres-compte" class="wow-small-action">
                            <i class="bi bi-lock-fill"></i>
                            Changer le mot de passe
                        </a>
                    </div>

                    <div class="wow-security-item">
                        <div class="wow-info-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <strong>Authentification</strong>
                            <small>Session sécurisée active</small>
                        </div>
                        <span class="wow-secured-badge">
                            <i class="bi bi-check"></i>
                            Sécurisé
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>