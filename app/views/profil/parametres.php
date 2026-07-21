<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$nom = $_SESSION['nom'] ?? 'Admin';
$prenom = $_SESSION['prenom'] ?? 'FSJES';
$email = $_SESSION['email'] ?? 'admin@fsjes.ma';
$tel = $_SESSION['tel'] ?? $_SESSION['telephone'] ?? '0600000000';
$role = $_SESSION['role'] ?? $_SESSION['nom_role'] ?? 'Administrateur';

$idUtilisateurConnecte = $_SESSION['id_utilisateur']
    ?? $_SESSION['ID_UTILISATEUR']
    ?? $_SESSION['user_id']
    ?? 1;
?>

<div class="wow-settings-page">

    <div class="wow-profile-actions-top">
        <a href="<?= BASE_URL ?>?page=profil" class="wow-return-btn">
            <i class="bi bi-arrow-left"></i>
            Retour au profil
        </a>
    </div>

    <div class="wow-settings-grid">

        <div class="wow-settings-card">

            <div class="wow-settings-card-header purple">
                <div class="wow-settings-icon">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <h4>Informations du compte</h4>
                    <small>Vos informations personnelles</small>
                </div>
            </div>

            <div class="wow-settings-list">

                <div class="wow-settings-row">
                    <i class="bi bi-person"></i>
                    <div>
                        <span>Nom</span>
                        <strong><?= htmlspecialchars($prenom . ' ' . $nom); ?></strong>
                    </div>
                </div>

                <div class="wow-settings-row">
                    <i class="bi bi-envelope"></i>
                    <div>
                        <span>Email</span>
                        <strong><?= htmlspecialchars($email); ?></strong>
                    </div>
                </div>

                <div class="wow-settings-row">
                    <i class="bi bi-telephone"></i>
                    <div>
                        <span>Téléphone</span>
                        <strong><?= htmlspecialchars($tel); ?></strong>
                    </div>
                </div>

                <div class="wow-settings-row">
                    <i class="bi bi-person-badge"></i>
                    <div>
                        <span>Rôle</span>
                        <strong><?= htmlspecialchars($role); ?></strong>
                    </div>
                </div>

            </div>

            <a href="<?= BASE_URL ?>?page=profil" class="wow-settings-btn purple">
                <i class="bi bi-person-circle"></i>
                Voir mon profil
            </a>

        </div>

        <div class="wow-settings-card">

            <div class="wow-settings-card-header green">
                <div class="wow-settings-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <h4>Sécurité</h4>
                    <small>Gérez la sécurité de votre compte</small>
                </div>
            </div>

            <div class="wow-settings-list">

                <a href="<?= BASE_URL ?>?page=modifier-utilisateur&id=<?= htmlspecialchars($idUtilisateurConnecte); ?>"
                   class="wow-settings-row arrow settings-link-row">
                    <i class="bi bi-lock"></i>
                    <div>
                        <span>Mot de passe</span>
                        <strong>Modifier le mot de passe</strong>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </a>

                <div class="wow-settings-row">
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <span>Authentification</span>
                        <strong>Session sécurisée active</strong>
                    </div>
                    <span class="wow-green-dot">
                        <i class="bi bi-check"></i>
                    </span>
                </div>

                <a href="<?= BASE_URL ?>?page=historiques"
                   class="wow-settings-row arrow settings-link-row">
                    <i class="bi bi-clock-history"></i>
                    <div>
                        <span>Activités récentes</span>
                        <strong>Voir l’historique des actions</strong>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </a>

            </div>

            <a href="<?= BASE_URL ?>?page=modifier-utilisateur&id=<?= htmlspecialchars($idUtilisateurConnecte); ?>"
               class="wow-settings-btn green">
                <i class="bi bi-pencil-square"></i>
                Changer le mot de passe
            </a>

        </div>

        <div class="wow-settings-card">

            <div class="wow-settings-card-header orange">
                <div class="wow-settings-icon">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div>
                    <h4>Préférences</h4>
                    <small>Personnalisez votre expérience</small>
                </div>
            </div>

            <div class="wow-settings-list">

                <div class="wow-settings-row">
                    <i class="bi bi-bell"></i>
                    <div>
                        <span>Notifications</span>
                        <strong>Activées</strong>
                    </div>
                </div>

                <div class="wow-settings-row">
                    <i class="bi bi-globe2"></i>
                    <div>
                        <span>Langue</span>
                        <strong>Français</strong>
                    </div>
                </div>

                <div class="wow-settings-row">
                    <i class="bi bi-brightness-high"></i>
                    <div>
                        <span>Thème</span>
                        <strong>Clair</strong>
                    </div>
                </div>

            </div>

            <button type="button"
                    class="wow-settings-btn orange"
                    data-bs-toggle="modal"
                    data-bs-target="#preferencesModal">
                <i class="bi bi-pencil-square"></i>
                Gérer les préférences
            </button>

        </div>

        <div class="wow-settings-card">

            <div class="wow-settings-card-header red">
                <div class="wow-settings-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <div>
                    <h4>Session</h4>
                    <small>Gérez votre session</small>
                </div>
            </div>

            <div class="wow-settings-list">

                <div class="wow-settings-row">
                    <i class="bi bi-toggle-on"></i>
                    <div>
                        <span>Statut de la session</span>
                        <strong>
                            <span class="wow-status-pill">Active</span>
                        </strong>
                    </div>
                </div>

                <div class="wow-settings-row">
                    <i class="bi bi-clock"></i>
                    <div>
                        <span>Connecté depuis</span>
                        <strong><?= date('d/m/Y'); ?> à 09:15</strong>
                    </div>
                </div>

                <div class="wow-settings-row">
                    <i class="bi bi-laptop"></i>
                    <div>
                        <span>Appareil</span>
                        <strong>Navigateur web</strong>
                    </div>
                </div>

            </div>

            <a href="<?= BASE_URL ?>?page=logout" class="wow-settings-btn red">
                <i class="bi bi-box-arrow-right"></i>
                Se déconnecter
            </a>

        </div>

    </div>

    <div class="wow-security-advice">

        <div class="wow-advice-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <div>
            <h5>Conseil de sécurité</h5>
            <p>
                Pour protéger votre compte, utilisez un mot de passe fort et ne le partagez jamais avec d’autres personnes.
            </p>
        </div>

        <button type="button"
                data-bs-toggle="modal"
                data-bs-target="#securityAdviceModal">
            En savoir plus
            <i class="bi bi-chevron-right"></i>
        </button>

    </div>

</div>

<div class="modal fade" id="preferencesModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content support-modal-content">

            <div class="modal-header support-modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-sliders"></i>
                    Préférences du compte
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="support-item">
                    <i class="bi bi-bell-fill"></i>
                    <div>
                        <strong>Notifications</strong>
                        <p>Les notifications sont activées pour cette version.</p>
                    </div>
                </div>

                <div class="support-item">
                    <i class="bi bi-globe2"></i>
                    <div>
                        <strong>Langue</strong>
                        <p>L’interface est configurée en français.</p>
                    </div>
                </div>

                <div class="support-item">
                    <i class="bi bi-brightness-high-fill"></i>
                    <div>
                        <strong>Thème</strong>
                        <p>Le thème clair est appliqué avec les couleurs de la FSJES Oujda.</p>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal">
                    D'accord
                </button>
            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="securityAdviceModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content support-modal-content">

            <div class="modal-header support-modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-shield-lock-fill"></i>
                    Conseil de sécurité
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="support-item">
                    <i class="bi bi-key-fill"></i>
                    <div>
                        <strong>Mot de passe fort</strong>
                        <p>Utilisez un mot de passe long avec des lettres, chiffres et symboles.</p>
                    </div>
                </div>

                <div class="support-item">
                    <i class="bi bi-person-lock"></i>
                    <div>
                        <strong>Confidentialité</strong>
                        <p>Ne partagez jamais vos identifiants avec une autre personne.</p>
                    </div>
                </div>

                <div class="support-item">
                    <i class="bi bi-box-arrow-right"></i>
                    <div>
                        <strong>Déconnexion</strong>
                        <p>Déconnectez-vous après utilisation, surtout sur un ordinateur partagé.</p>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-primary"
                        data-bs-dismiss="modal">
                    D'accord
                </button>
            </div>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>