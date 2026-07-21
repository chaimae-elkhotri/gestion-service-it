<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion - FSJES Oujda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css?v=10">

</head>

<body class="login-body">

<div class="login-page">

    <div class="login-left">

        <div class="login-logo-box">
            <img src="<?= BASE_URL ?>assets/images/logo-fsjes.png" alt="Logo FSJES Oujda">
        </div>

        <h1>FSJES Oujda</h1>

        <h3>Système de Gestion du Parc Informatique</h3>

        <p>
            Plateforme interne dédiée au suivi des équipements, tickets,
            interventions, licences et affectations du service informatique.
        </p>

        <div class="login-features">

            <div>
                <i class="bi bi-pc-display"></i>
                <span>Gestion du parc IT</span>
            </div>

            <div>
                <i class="bi bi-ticket-detailed-fill"></i>
                <span>Suivi des tickets</span>
            </div>

            <div>
                <i class="bi bi-clock-history"></i>
                <span>Traçabilité des actions</span>
            </div>

        </div>

    </div>

    <div class="login-right">

        <div class="login-card">

            <div class="login-card-header">

                <div class="login-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <h4>Connexion</h4>

                <p>Accédez à votre espace de gestion.</p>

            </div>

            <?php if (isset($_SESSION['error'])): ?>

                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $_SESSION['error']; ?>
                </div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <?php if (isset($error)): ?>

                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $error; ?>
                </div>

            <?php endif; ?>

            <form action="<?= BASE_URL ?>?page=login" method="POST">

                <div class="mb-3">

                    <label class="form-label">Adresse email</label>

                    <div class="login-input">
                        <i class="bi bi-envelope"></i>
                        <input type="email"
                               name="email"
                               placeholder="admin@fsjes.ma"
                               required>
                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">Mot de passe</label>

                    <div class="login-input">
                        <i class="bi bi-lock"></i>
                        <input type="password"
                               name="mot_de_passe"
                               placeholder="Votre mot de passe"
                               required>
                    </div>

                </div>

                <button type="submit" class="btn login-btn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Se connecter
                </button>

            </form>

            <div class="login-footer-text">
                © <?= date('Y'); ?> FSJES Oujda - Service Informatique
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>