<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<div class="modern-form-card text-center py-5">

    <div class="form-section-icon mx-auto mb-3">
        <i class="bi bi-shield-x"></i>
    </div>

    <h2>Accès refusé</h2>

    <p class="text-muted mt-2">
        Vous n’avez pas les autorisations nécessaires pour accéder à cette page.
    </p>

    <a href="<?= BASE_URL ?>?page=dashboard"
       class="btn btn-primary mt-3">

        <i class="bi bi-house-door"></i>
        Retour au tableau de bord

    </a>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>