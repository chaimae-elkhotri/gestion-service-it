<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$utilisateurs = $utilisateurs ?? [];

$totalUtilisateurs = count($utilisateurs);
$totalAdmins = 0;
$totalTechniciens = 0;
$totalEmployes = 0;
$totalActifs = 0;

foreach ($utilisateurs as $u) {
    $role = strtolower($u['nom_role'] ?? $u['NOM_ROLE'] ?? $u['role'] ?? $u['ROLE'] ?? $u['id_role'] ?? $u['ID_ROLE'] ?? '');
    $statut = strtolower($u['statut'] ?? $u['STATUT'] ?? '');

    if (strpos($role, 'admin') !== false || $role == '1') {
        $totalAdmins++;
    } elseif (strpos($role, 'technicien') !== false || $role == '2') {
        $totalTechniciens++;
    } else {
        $totalEmployes++;
    }

    if ($statut == 'actif') {
        $totalActifs++;
    }
}
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Gestion des utilisateurs</h2>
            <p>Gérez les comptes, les rôles et les accès au système.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=ajouter-utilisateur" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Ajouter un utilisateur
        </a>

    </div>

    <div class="module-stats-grid">

        <div class="module-stat-card">
            <div class="module-stat-icon brown">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <span>Total utilisateurs</span>
                <h3><?= $totalUtilisateurs; ?></h3>
                <small>Tous les comptes</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon orange">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <span>Administrateurs</span>
                <h3><?= $totalAdmins; ?></h3>
                <small>Gestion du système</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon blue">
                <i class="bi bi-tools"></i>
            </div>
            <div>
                <span>Techniciens</span>
                <h3><?= $totalTechniciens; ?></h3>
                <small>Interventions IT</small>
            </div>
        </div>

        <div class="module-stat-card">
            <div class="module-stat-icon green">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <span>Comptes actifs</span>
                <h3><?= $totalActifs; ?></h3>
                <small>Utilisateurs actifs</small>
            </div>
        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden" name="page" value="utilisateurs">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">
                    <label class="form-label">Recherche</label>
                    <div class="modern-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text"
                               name="search"
                               placeholder="Rechercher par nom, email, téléphone..."
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label">Rôle</label>
                    <select class="form-select" disabled>
                        <option>Tous les rôles</option>
                        <option>Administrateur</option>
                        <option>Technicien</option>
                        <option>Employé</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i>
                        Rechercher
                    </button>

                    <a href="<?= BASE_URL ?>?page=utilisateurs" class="btn btn-light border">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>
                <h5>Liste des utilisateurs</h5>
                <small><?= $totalUtilisateurs; ?> utilisateur(s) trouvé(s)</small>
            </div>

            <span class="module-chip">
                <i class="bi bi-person-badge"></i>
                Comptes FSJES
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($utilisateurs)): ?>

                    <?php foreach ($utilisateurs as $user): ?>

                        <?php
                        $id = $user['id_utilisateur'] ?? $user['ID_UTILISATEUR'] ?? '';
                        $nom = $user['nom'] ?? $user['NOM'] ?? '';
                        $prenom = $user['prenom'] ?? $user['PRENOM'] ?? '';
                        $email = $user['email'] ?? $user['EMAIL'] ?? '';
                        $telephone = $user['telephone'] ?? $user['tel'] ?? $user['TELEPHONE'] ?? $user['TEL'] ?? '';
                        $statut = $user['statut'] ?? $user['STATUT'] ?? '';
                        $role = $user['nom_role'] ?? $user['NOM_ROLE'] ?? $user['role'] ?? $user['ROLE'] ?? $user['id_role'] ?? $user['ID_ROLE'] ?? '';

                        $initiales = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
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
                                        <strong><?= htmlspecialchars($prenom . ' ' . $nom); ?></strong>
                                        <small>Utilisateur système</small>
                                    </div>
                                </div>
                            </td>

                            <td><?= htmlspecialchars($email); ?></td>

                            <td><?= htmlspecialchars($telephone); ?></td>

                            <td>
                                <?php
                                $roleLower = strtolower($role);

                                if (strpos($roleLower, 'admin') !== false || $role == '1') {
                                    echo '<span class="badge role-admin">Administrateur</span>';
                                } elseif (strpos($roleLower, 'technicien') !== false || $role == '2') {
                                    echo '<span class="badge role-tech">Technicien</span>';
                                } else {
                                    echo '<span class="badge role-user">' . htmlspecialchars($role ?: 'Employé') . '</span>';
                                }
                                ?>
                            </td>

                            <td>
                                <?php if ($statut == 'Actif'): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Actif
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle-fill"></i>
                                        <?= htmlspecialchars($statut ?: 'Inactif'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=modifier-utilisateur&id=<?= $id; ?>"
                                   class="btn btn-warning btn-sm"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?page=supprimer-utilisateur&id=<?= $id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="Supprimer"
                                   onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
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
                            Aucun utilisateur trouvé.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>