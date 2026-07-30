<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Changer le mot de passe - FSJES Oujda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="<?= BASE_URL ?>assets/css/style.css?v=11">

</head>

<body class="login-body">

<div class="login-page">

    <div class="login-left">

        <div class="login-logo-box">

            <img src="<?= BASE_URL ?>assets/images/logo-fsjes.png"
                 alt="Logo FSJES Oujda">

        </div>

        <h1>FSJES Oujda</h1>

        <h3>Sécurisation de votre compte</h3>

        <p>
            Pour protéger votre compte, vous devez remplacer
            le mot de passe temporaire attribué par l’administrateur.
        </p>

        <div class="login-features">

            <div>
                <i class="bi bi-shield-check"></i>
                <span>Mot de passe personnel</span>
            </div>

            <div>
                <i class="bi bi-lock-fill"></i>
                <span>Compte sécurisé</span>
            </div>

            <div>
                <i class="bi bi-person-check-fill"></i>
                <span>Accès confidentiel</span>
            </div>

        </div>

    </div>

    <div class="login-right">

        <div class="login-card">

            <div class="login-card-header">

                <div class="login-icon">
                    <i class="bi bi-key-fill"></i>
                </div>

                <h4>Créer votre mot de passe</h4>

                <p>
                    Cette opération est obligatoire lors de votre
                    première connexion.
                </p>

            </div>

            <?php if (isset($erreur)): ?>

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-triangle-fill me-2"></i>

                    <?= htmlspecialchars($erreur); ?>

                </div>

            <?php endif; ?>

            <form action="<?= BASE_URL ?>?page=changer-mot-de-passe-obligatoire"
                  method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Nouveau mot de passe
                    </label>

                    <div class="login-input">

                        <i class="bi bi-lock"></i>

                        <input type="password"
                               name="nouveau_mot_de_passe"
                               placeholder="Au moins 8 caractères"
                               minlength="8"
                               required>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Confirmer le mot de passe
                    </label>

                    <div class="login-input">

                        <i class="bi bi-lock-fill"></i>

                        <input type="password"
                               name="confirmation_mot_de_passe"
                               placeholder="Confirmez votre mot de passe"
                               minlength="8"
                               required>

                    </div>

                </div>

                <div class="alert alert-info small">

                    <i class="bi bi-info-circle-fill me-2"></i>

                    Le nouveau mot de passe doit contenir au moins
                    8 caractères et être différent du mot de passe
                    temporaire.

                </div>

                <button type="submit"
                        class="btn login-btn">

                    <i class="bi bi-check-circle-fill"></i>

                    Enregistrer le mot de passe

                </button>

            </form>

            <div class="login-footer-text">

                Connecté en tant que :

                <strong>
                    <?= htmlspecialchars(
                        trim(
                            ($_SESSION['prenom'] ?? '') .
                            ' ' .
                            ($_SESSION['nom'] ?? '')
                        )
                    ); ?>
                </strong>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>