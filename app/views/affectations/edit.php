<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$affectation =
    $affectation
    ?? $affectationEquipement
    ?? [];

$utilisateurs = $utilisateurs ?? [];
$equipements = $equipements ?? [];

$id =
    $affectation['id_affectation']
    ?? $affectation['ID_AFFECTATION_EQUIP']
    ?? '';

$idUtilisateurAff =
    $affectation['id_utilisateur']
    ?? $affectation['ID_UTILISATEUR']
    ?? '';

$idEquipementAff =
    $affectation['id_equipement']
    ?? $affectation['ID_EQUIPEMENT_']
    ?? '';

$dateAffectation =
    $affectation['date_affectation']
    ?? $affectation['DATE_AFFECTATION']
    ?? '';

$dateFinAffectation =
    $affectation['date_fin_affectation']
    ?? $affectation['DATE_FIN_AFFECTATION']
    ?? '';

if (!function_exists('affectationT')) {
    function affectationT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'affectations_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('affectationNormalize')) {
    function affectationNormalize(string $value): string
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

if (!function_exists('affectationRoleLabel')) {
    function affectationRoleLabel(string $value): string
    {
        return match (affectationNormalize($value)) {
            'administrateur' =>
                affectationT('role_admin'),
            'technicien' =>
                affectationT('role_technician'),
            'employe' =>
                affectationT('role_employee'),
            default => $value
        };
    }
}

if (!function_exists('affectationEquipmentStatusLabel')) {
    function affectationEquipmentStatusLabel(
        string $value
    ): string {
        return match (affectationNormalize($value)) {
            'disponible' =>
                affectationT('equipment_available'),
            'affecte' =>
                affectationT('equipment_assigned'),
            'maintenance',
            'en maintenance' =>
                affectationT('equipment_maintenance'),
            'en panne' =>
                affectationT('equipment_broken'),
            default => $value
        };
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    affectationT('edit_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    affectationT('edit_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=affectations"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>

            <?= htmlspecialchars(
                affectationT('back')
            ); ?>

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

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-affectation&id=<?= (int)$id; ?>"
              method="POST">

            <input type="hidden"
                   name="id_affectation"
                   value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            affectationT(
                                'assignment_information'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            affectationT(
                                'edit_information_help'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            affectationT('user')
                        ); ?>
                    </label>

                    <select name="id_utilisateur"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                affectationT('choose_user')
                            ); ?>
                        </option>

                        <?php foreach (
                            $utilisateurs as $user
                        ): ?>

                            <?php

                            $idUtilisateur =
                                $user['id_utilisateur']
                                ?? $user['ID_UTILISATEUR']
                                ?? '';

                            $nom =
                                $user['nom']
                                ?? $user['NOM']
                                ?? '';

                            $prenom =
                                $user['prenom']
                                ?? $user['PRENOM']
                                ?? '';

                            $role =
                                $user['nom_role']
                                ?? $user['NOM_ROLE']
                                ?? '';

                            ?>

                            <option value="<?= htmlspecialchars(
                                $idUtilisateur
                            ); ?>"
                                <?= $idUtilisateur == $idUtilisateurAff
                                    ? 'selected'
                                    : ''; ?>>

                                <?= htmlspecialchars(
                                    trim(
                                        $prenom
                                        . ' '
                                        . $nom
                                    )
                                ); ?>

                                <?php if (!empty($role)): ?>

                                    -
                                    <?= htmlspecialchars(
                                        affectationRoleLabel(
                                            (string)$role
                                        )
                                    ); ?>

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            affectationT('equipment')
                        ); ?>
                    </label>

                    <select name="id_equipement"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(
                                affectationT(
                                    'choose_equipment'
                                )
                            ); ?>
                        </option>

                        <?php foreach (
                            $equipements as $eq
                        ): ?>

                            <?php

                            $idEquipement =
                                $eq['id_equipement']
                                ?? $eq['ID_EQUIPEMENT_']
                                ?? '';

                            $numeroSerie =
                                $eq['numero_serie']
                                ?? $eq['NUMERO_SERIE']
                                ?? '';

                            $marque =
                                $eq['marque']
                                ?? $eq['MARQUE']
                                ?? '';

                            $modele =
                                $eq['modele']
                                ?? $eq['MODELE']
                                ?? '';

                            $statut =
                                $eq['statut']
                                ?? $eq['STATUT']
                                ?? '';

                            ?>

                            <option value="<?= htmlspecialchars(
                                $idEquipement
                            ); ?>"
                                <?= $idEquipement == $idEquipementAff
                                    ? 'selected'
                                    : ''; ?>>

                                <?= htmlspecialchars(
                                    $numeroSerie
                                    . ' - '
                                    . $marque
                                    . ' '
                                    . $modele
                                ); ?>

                                <?php if (!empty($statut)): ?>

                                    (
                                    <?= htmlspecialchars(
                                        affectationEquipmentStatusLabel(
                                            (string)$statut
                                        )
                                    ); ?>
                                    )

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

            <div class="form-section-title mt-5">

                <div class="form-section-icon">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>

                <div>

                    <h5>
                        <?= htmlspecialchars(
                            affectationT(
                                'assignment_period'
                            )
                        ); ?>
                    </h5>

                    <small>
                        <?= htmlspecialchars(
                            affectationT(
                                'edit_period_help'
                            )
                        ); ?>
                    </small>

                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            affectationT(
                                'assignment_date'
                            )
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-calendar-check"></i>

                        <input type="date"
                               name="date_affectation"
                               class="form-control"
                               value="<?= htmlspecialchars(
                                   $dateAffectation
                               ); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            affectationT('end_date')
                        ); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-calendar-x"></i>

                        <input type="date"
                               name="date_fin_affectation"
                               class="form-control"
                               value="<?= htmlspecialchars(
                                   $dateFinAffectation
                               ); ?>">

                    </div>

                </div>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=affectations"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>

                    <?= htmlspecialchars(
                        affectationT('cancel')
                    ); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>

                    <?= htmlspecialchars(
                        affectationT('update')
                    ); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>