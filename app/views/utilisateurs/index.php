<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$utilisateurs = $utilisateurs ?? [];

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
    function userRoleLabel($role): string
    {
        $normalized = userNormalizeValue((string)$role);

        if ((string)$role === '1' || str_contains($normalized, 'admin')) {
            return t('role.admin');
        }

        if ((string)$role === '2' || str_contains($normalized, 'technicien')) {
            return t('role.technician');
        }

        if ((string)$role === '3' || str_contains($normalized, 'employe')) {
            return t('role.employee');
        }

        return (string)$role;
    }
}

if (!function_exists('userStatusLabel')) {
    function userStatusLabel($status): string
    {
        return userNormalizeValue((string)$status) === 'actif'
            ? usersT('active')
            : usersT('inactive');
    }
}

$totalUtilisateurs = count($utilisateurs);
$totalAdmins = 0;
$totalTechniciens = 0;
$totalEmployes = 0;
$totalActifs = 0;

foreach ($utilisateurs as $utilisateur) {
    $role = userNormalizeValue(
        (string)(
            $utilisateur['nom_role']
            ?? $utilisateur['NOM_ROLE']
            ?? $utilisateur['role']
            ?? $utilisateur['ROLE']
            ?? $utilisateur['id_role']
            ?? $utilisateur['ID_ROLE']
            ?? ''
        )
    );

    $statut = userNormalizeValue(
        (string)(
            $utilisateur['statut']
            ?? $utilisateur['STATUT']
            ?? ''
        )
    );

    if (str_contains($role, 'admin') || $role === '1') {
        $totalAdmins++;
    } elseif (str_contains($role, 'technicien') || $role === '2') {
        $totalTechniciens++;
    } else {
        $totalEmployes++;
    }

    if ($statut === 'actif') {
        $totalActifs++;
    }
}

$resultatImport = $_SESSION['resultat_import_utilisateurs'] ?? null;
$deleteConfirmation = json_encode(
    usersT('delete_confirm'),
    JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT
);

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2><?= htmlspecialchars(usersT('management_title')); ?></h2>
            <p><?= htmlspecialchars(usersT('management_intro')); ?></p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <button type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#importUtilisateursModal">

                <i class="bi bi-file-earmark-spreadsheet"></i>
                <?= htmlspecialchars(usersT('import_csv')); ?>

            </button>

            <a href="<?= BASE_URL ?>?page=ajouter-utilisateur"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                <?= htmlspecialchars(usersT('add_user')); ?>

            </a>

        </div>

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

    <?php if ($resultatImport !== null): ?>

        <?php
        $nombreAjoutes = (int)($resultatImport['ajoutes'] ?? 0);
        $nombreDoublons = (int)($resultatImport['doublons'] ?? 0);
        $erreursImport = $resultatImport['erreurs'] ?? [];
        $nombreErreurs = count($erreursImport);
        ?>

        <div class="alert alert-info">

            <h5 class="alert-heading">
                <i class="bi bi-file-earmark-check-fill me-2"></i>
                <?= htmlspecialchars(usersT('import_result')); ?>
            </h5>

            <div class="row g-3 mt-2">

                <div class="col-md-4">
                    <div class="border rounded bg-white p-3">
                        <strong class="text-success fs-4"><?= $nombreAjoutes; ?></strong>
                        <div><?= htmlspecialchars(usersT('users_added')); ?></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded bg-white p-3">
                        <strong class="text-warning fs-4"><?= $nombreDoublons; ?></strong>
                        <div><?= htmlspecialchars(usersT('existing_emails')); ?></div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded bg-white p-3">
                        <strong class="text-danger fs-4"><?= $nombreErreurs; ?></strong>
                        <div><?= htmlspecialchars(usersT('errors')); ?></div>
                    </div>
                </div>

            </div>

            <?php if ($nombreErreurs > 0): ?>

                <hr>

                <button type="button"
                        class="btn btn-sm btn-outline-danger"
                        data-bs-toggle="collapse"
                        data-bs-target="#detailsErreursImport"
                        aria-expanded="false">

                    <i class="bi bi-exclamation-triangle"></i>
                    <?= htmlspecialchars(usersT('view_errors')); ?>

                </button>

                <div class="collapse mt-3" id="detailsErreursImport">
                    <div class="bg-white border rounded p-3">
                        <ul class="mb-0">
                            <?php foreach ($erreursImport as $erreur): ?>
                                <li class="mb-1"><?= htmlspecialchars($erreur); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            <?php endif; ?>

        </div>

        <?php unset($_SESSION['resultat_import_utilisateurs']); ?>

    <?php endif; ?>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span><?= htmlspecialchars(usersT('total_users')); ?></span>
                <h3><?= $totalUtilisateurs; ?></h3>
                <small><?= htmlspecialchars(usersT('all_accounts')); ?></small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span><?= htmlspecialchars(usersT('administrators')); ?></span>
                <h3><?= $totalAdmins; ?></h3>
                <small><?= htmlspecialchars(usersT('system_management')); ?></small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-tools"></i>
            </div>
            <div>
                <span><?= htmlspecialchars(usersT('technicians')); ?></span>
                <h3><?= $totalTechniciens; ?></h3>
                <small><?= htmlspecialchars(usersT('it_interventions')); ?></small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <span><?= htmlspecialchars(usersT('active_accounts')); ?></span>
                <h3><?= $totalActifs; ?></h3>
                <small><?= htmlspecialchars(usersT('active_users')); ?></small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="utilisateurs">

            <div class="row g-3 align-items-end">

                <div class="col-lg-8 col-md-12">
                    <label class="form-label"><?= htmlspecialchars(usersT('search')); ?></label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(usersT('search_placeholder')); ?>"
                               value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        <?= htmlspecialchars(usersT('search_button')); ?>
                    </button>

                    <a href="<?= BASE_URL ?>?page=utilisateurs"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(usersT('reset')); ?>">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5><?= htmlspecialchars(usersT('users_list')); ?></h5>
                <small>
                    <?= htmlspecialchars(usersT('users_found', ['count' => $totalUtilisateurs])); ?>
                </small>
            </div>

            <span class="module-chip">
                <i class="bi bi-person-badge"></i>
                <?= htmlspecialchars(usersT('fsjes_accounts')); ?>
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th><?= htmlspecialchars(usersT('id')); ?></th>
                    <th><?= htmlspecialchars(usersT('user')); ?></th>
                    <th><?= htmlspecialchars(usersT('email')); ?></th>
                    <th><?= htmlspecialchars(usersT('phone')); ?></th>
                    <th><?= htmlspecialchars(usersT('role')); ?></th>
                    <th><?= htmlspecialchars(usersT('status')); ?></th>
                    <th class="text-center"><?= htmlspecialchars(usersT('actions')); ?></th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($utilisateurs)): ?>

                    <?php foreach ($utilisateurs as $utilisateur): ?>

                        <?php
                        $id = $utilisateur['id_utilisateur'] ?? $utilisateur['ID_UTILISATEUR'] ?? '';
                        $nom = $utilisateur['nom'] ?? $utilisateur['NOM'] ?? '';
                        $prenom = $utilisateur['prenom'] ?? $utilisateur['PRENOM'] ?? '';
                        $email = $utilisateur['email'] ?? $utilisateur['EMAIL'] ?? '';
                        $telephone = $utilisateur['telephone']
                            ?? $utilisateur['tel']
                            ?? $utilisateur['TELEPHONE']
                            ?? $utilisateur['TEL']
                            ?? '';
                        $statut = $utilisateur['statut'] ?? $utilisateur['STATUT'] ?? '';
                        $role = $utilisateur['nom_role']
                            ?? $utilisateur['NOM_ROLE']
                            ?? $utilisateur['role']
                            ?? $utilisateur['ROLE']
                            ?? $utilisateur['id_role']
                            ?? $utilisateur['ID_ROLE']
                            ?? '';

                        $initiales = mb_strtoupper(
                            mb_substr($prenom, 0, 1, 'UTF-8')
                            . mb_substr($nom, 0, 1, 'UTF-8'),
                            'UTF-8'
                        );

                        $roleLower = userNormalizeValue((string)$role);
                        ?>

                        <tr>

                            <td>
                                <span class="table-id">#USR-<?= htmlspecialchars($id); ?></span>
                            </td>

                            <td>
                                <div class="user-cell">
                                    <div class="table-avatar">
                                        <?= htmlspecialchars($initiales ?: 'U'); ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars(trim($prenom . ' ' . $nom)); ?></strong>
                                        <small><?= htmlspecialchars(usersT('system_user')); ?></small>
                                    </div>
                                </div>
                            </td>

                            <td><?= htmlspecialchars($email); ?></td>

                            <td><?= htmlspecialchars($telephone ?: '-'); ?></td>

                            <td>
                                <?php if (str_contains($roleLower, 'admin') || (string)$role === '1'): ?>
                                    <span class="badge role-admin"><?= htmlspecialchars(t('role.admin')); ?></span>
                                <?php elseif (str_contains($roleLower, 'technicien') || (string)$role === '2'): ?>
                                    <span class="badge role-tech"><?= htmlspecialchars(t('role.technician')); ?></span>
                                <?php else: ?>
                                    <span class="badge role-user"><?= htmlspecialchars(userRoleLabel($role) ?: t('role.employee')); ?></span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (userNormalizeValue((string)$statut) === 'actif'): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <?= htmlspecialchars(usersT('active')); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle-fill"></i>
                                        <?= htmlspecialchars(userStatusLabel($statut)); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-utilisateur&id=<?= (int)$id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="<?= htmlspecialchars(usersT('edit')); ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-utilisateur&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(usersT('delete')); ?>"
                                   onclick='return confirm(<?= $deleteConfirmation; ?>);'>
                                    <i class="bi bi-trash"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1"></i>
                            <br><br>
                            <?= htmlspecialchars(usersT('no_user')); ?>
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<style>
    .simple-import-modal .modal-content {
        border: 0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 22px 60px rgba(67, 40, 23, 0.22);
    }

    .simple-import-modal .modal-header {
        padding: 1rem 1.25rem;
        color: #fff;
        border: 0;
        background: #7b4727;
    }

    .simple-import-modal .modal-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin: 0;
        color: #fff;
        font-weight: 800;
    }

    .simple-import-modal .modal-header .btn-close {
        margin: 0;
        filter: brightness(0) invert(1);
        opacity: 0.9;
    }

    .simple-import-modal .modal-body {
        padding: 1.35rem;
    }

    .simple-import-format {
        margin-bottom: 1rem;
        padding: 0.85rem 1rem;
        border: 1px solid #eadfd7;
        border-radius: 12px;
        background: #faf7f4;
    }

    .simple-import-format small {
        display: block;
        margin-bottom: 0.4rem;
        color: #6b7280;
        font-weight: 700;
    }

    .simple-import-format code {
        color: #a12d62;
        font-size: 0.8rem;
        overflow-wrap: anywhere;
        white-space: normal;
    }

    .simple-import-file {
        padding: 1rem;
        border: 2px dashed #d9c6b8;
        border-radius: 14px;
        text-align: center;
        background: #fffdfb;
    }

    .simple-import-file > i {
        display: block;
        margin-bottom: 0.55rem;
        color: #198754;
        font-size: 2rem;
    }

    .simple-import-file label {
        display: block;
        margin-bottom: 0.65rem;
        color: #243247;
        font-weight: 800;
    }

    .simple-import-file .form-control {
        max-width: 560px;
        margin-inline: auto;
        border-radius: 10px;
    }

    .simple-import-file small {
        display: block;
        margin-top: 0.55rem;
        color: #7b8493;
    }

    .simple-import-template {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-top: 0.9rem;
        font-weight: 700;
    }

    .simple-import-modal .modal-footer {
        gap: 0.6rem;
        padding: 0.85rem 1.25rem;
        border-top: 1px solid #eee7e2;
        background: #fbfaf9;
    }

    .simple-import-modal .modal-footer .btn {
        min-height: 42px;
        border-radius: 10px;
        font-weight: 700;
    }

    @media (max-width: 575.98px) {
        .simple-import-modal .modal-dialog {
            margin: 0.75rem;
        }

        .simple-import-modal .modal-footer {
            flex-direction: column-reverse;
        }

        .simple-import-modal .modal-footer .btn {
            width: 100%;
        }
    }
</style>


<div class="modal fade simple-import-modal"
     id="importUtilisateursModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    <?= htmlspecialchars(usersT('import_users_title')); ?>
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <form action="<?= BASE_URL ?>?page=importer-utilisateurs"
                  method="POST"
                  enctype="multipart/form-data">

                <div class="modal-body">

                    <div class="simple-import-format">

                        <small>
                            <?= htmlspecialchars(usersT('required_columns')); ?>
                        </small>

                        <code>
                            nom;prenom;email;telephone;statut;id_role;mot_de_passe_temporaire
                        </code>

                    </div>

                    <div class="simple-import-file">

                        <i class="bi bi-cloud-arrow-up-fill"></i>

                        <label for="usersCsvFile">
                            <?= htmlspecialchars(usersT('csv_file')); ?>
                        </label>

                        <input type="file"
                               id="usersCsvFile"
                               name="fichier_csv"
                               class="form-control"
                               accept=".csv,text/csv"
                               required>

                        <small>
                            <?= htmlspecialchars(usersT('file_help')); ?>
                        </small>

                        <a href="<?= BASE_URL ?>?page=modele-import-utilisateurs"
                           class="btn btn-outline-primary simple-import-template">

                            <i class="bi bi-download"></i>
                            <?= htmlspecialchars(usersT('download_template')); ?>

                        </a>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light border"
                            data-bs-dismiss="modal">
                        <?= htmlspecialchars(usersT('cancel')); ?>
                    </button>

                    <button type="submit"
                            class="btn btn-success">
                        <i class="bi bi-upload"></i>
                        <?= htmlspecialchars(usersT('start_import')); ?>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<?php require_once '../app/views/layouts/footer.php'; ?>