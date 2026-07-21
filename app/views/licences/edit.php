<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
$licence = $licence ?? [];
$logiciels = $logiciels ?? [];

$id = $licence['id_licence'] ?? $licence['ID_LICENCE'] ?? '';
$idLogicielLicence = $licence['id_logiciel'] ?? $licence['ID_LOGICIEL'] ?? '';
$cleLicence = $licence['cle_licence'] ?? $licence['CLE_LICENCE'] ?? '';
$dateDebut = $licence['date_debut'] ?? $licence['DATE_DEBUT'] ?? '';
$dateFin = $licence['date_fin'] ?? $licence['DATE_FIN'] ?? '';
$nombrePostes = $licence['nombre_postes'] ?? $licence['NOMBRE_POSTES'] ?? '';
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Modifier une licence</h2>
            <p>Mettez à jour les informations de la licence sélectionnée.</p>
        </div>

        <a href="<?= BASE_URL ?>?page=licences" class="btn btn-light border">
            <i class="bi bi-arrow-left"></i>
            Retour
        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=mettre-a-jour-licence&id=<?= htmlspecialchars($id); ?>" method="POST">

            <input type="hidden" name="id_licence" value="<?= htmlspecialchars($id); ?>">

            <div class="form-section-title">
                <div class="form-section-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5>Informations de la licence</h5>
                    <small>Modifiez la clé, les dates et le nombre de postes.</small>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Logiciel</label>
                    <select name="id_logiciel" class="form-select" required>

                        <option value="">Choisir un logiciel</option>

                        <?php foreach ($logiciels as $logiciel): ?>

                            <?php
                            $idLogiciel = $logiciel['id_logiciel'] ?? $logiciel['ID_LOGICIEL'] ?? '';
                            $nomLogiciel = $logiciel['nom_logiciel'] ?? $logiciel['NOM_LOGICIEL'] ?? '';
                            $version = $logiciel['version'] ?? $logiciel['VERSION'] ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idLogiciel); ?>"
                                <?= ($idLogiciel == $idLogicielLicence) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($nomLogiciel); ?>
                                <?= !empty($version) ? ' - v' . htmlspecialchars($version) : ''; ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Clé licence</label>
                    <div class="input-with-icon">
                        <i class="bi bi-key"></i>
                        <input type="text"
                               name="cle_licence"
                               class="form-control"
                               value="<?= htmlspecialchars($cleLicence); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date début</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-check"></i>
                        <input type="date"
                               name="date_debut"
                               class="form-control"
                               value="<?= htmlspecialchars($dateDebut); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date fin</label>
                    <div class="input-with-icon">
                        <i class="bi bi-calendar-x"></i>
                        <input type="date"
                               name="date_fin"
                               class="form-control"
                               value="<?= htmlspecialchars($dateFin); ?>"
                               required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nombre de postes</label>
                    <div class="input-with-icon">
                        <i class="bi bi-pc-display"></i>
                        <input type="number"
                               name="nombre_postes"
                               class="form-control"
                               value="<?= htmlspecialchars($nombrePostes); ?>"
                               min="1"
                               required>
                    </div>
                </div>

            </div>

            <div class="form-section-title mt-5">
                <div class="form-section-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div>
                    <h5>Remarque</h5>
                    <small>Vérifiez bien la date d’expiration pour éviter les licences expirées.</small>
                </div>
            </div>

            <div class="info-form-box">
                <i class="bi bi-lightbulb-fill"></i>
                <div>
                    <strong>Conseil :</strong>
                    gardez une clé licence claire et une date de fin correcte pour faciliter le suivi.
                </div>
            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=licences" class="btn btn-light border">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    Mettre à jour
                </button>

            </div>

        </form>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>