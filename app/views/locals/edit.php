<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$local = $local ?? [];

$id =
    $local['id_local']
    ?? $local['ID_LOCAL']
    ?? '';

$nomLocal =
    $local['nom_local']
    ?? $local['NOM_LOCAL']
    ?? '';

$typeLocal =
    $local['type_local']
    ?? $local['TYPE_LOCAL']
    ?? '';

$statutGeneral =
    $local['statut_general']
    ?? $local['STATUT_GENERAL']
    ?? 'Actif';

if (!function_exists('localT')) {
    function localT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'locals_module.' . $key,
            $replacements
        );
    }
}

$typesLocaux = [
    'Bureau' => localT('type_office'),
    'Salle' => localT('type_room'),
    'Salle informatique' => localT('type_computer_room'),
    'Amphithéâtre' => localT('type_amphitheater'),
    'Laboratoire' => localT('type_laboratory'),
    'Service' => localT('type_service'),
    'Autre' => localT('type_other')
];

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2><?= htmlspecialchars(localT('edit_title')); ?></h2>

            <p>
                <?= htmlspecialchars(localT('edit_subtitle')); ?>
            </p>
        </div>

        <a href="<?= BASE_URL ?>?page=locals"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>
            <?= htmlspecialchars(localT('back')); ?>

        </a>

    </div>

    <?php if (isset($_SESSION['error'])): ?>

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <?= htmlspecialchars($_SESSION['error']); ?>

        </div>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-local"
              method="POST">

            <input type="hidden"
                   name="id_local"
                   value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h5><?= htmlspecialchars(localT('local_information')); ?></h5>

                    <small>
                        <?= htmlspecialchars(localT('edit_information_help')); ?>
                    </small>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(localT('local_name')); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-door-open"></i>

                        <input type="text"
                               name="nom_local"
                               class="form-control"
                               value="<?= htmlspecialchars($nomLocal); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(localT('local_type')); ?>
                    </label>

                    <select name="type_local"
                            class="form-select"
                            required>

                        <?php foreach ($typesLocaux as $value => $label): ?>

                            <option value="<?= htmlspecialchars($value); ?>"
                                <?= $typeLocal === $value ? 'selected' : ''; ?>>

                                <?= htmlspecialchars($label); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-4">

                    <label class="form-label">
                        <?= htmlspecialchars(localT('general_status')); ?>
                    </label>

                    <select name="statut_general"
                            class="form-select"
                            required>

                        <option value="Actif"
                            <?= $statutGeneral === 'Actif' ? 'selected' : ''; ?>>

                            <?= htmlspecialchars(localT('status_active')); ?>

                        </option>

                        <option value="Maintenance"
                            <?= $statutGeneral === 'Maintenance' ? 'selected' : ''; ?>>

                            <?= htmlspecialchars(localT('status_maintenance')); ?>

                        </option>

                        <option value="Indisponible"
                            <?= $statutGeneral === 'Indisponible' ? 'selected' : ''; ?>>

                            <?= htmlspecialchars(localT('status_unavailable')); ?>

                        </option>

                    </select>

                </div>

            </div>

            <div class="info-form-box mt-4">

                <i class="bi bi-info-circle-fill"></i>

                <div>
                    <?= htmlspecialchars(localT('edit_status_explanation')); ?>
                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=locals"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>
                    <?= htmlspecialchars(localT('cancel')); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>
                    <?= htmlspecialchars(localT('update')); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>