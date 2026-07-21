<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php
require_once '../app/core/Auth.php';

$utilisateurs = $utilisateurs ?? [];
$moyens = $moyens ?? [];
$equipements = $equipements ?? [];

$estEmploye = Auth::estEmploye();
$idUtilisateurConnecte = Auth::idUtilisateur();
?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2>Créer un ticket</h2>

            <p>
                Envoyez une nouvelle demande d’assistance au service informatique.
            </p>
        </div>

        <a href="<?= BASE_URL ?>?page=tickets"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>
            Retour

        </a>

    </div>

    <div class="modern-form-card">

        <form action="<?= BASE_URL ?>?page=enregistrer-ticket"
              method="POST">

            <!-- Informations du ticket -->

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>

                <div>
                    <h5>Informations du ticket</h5>

                    <small>
                        Décrivez clairement le problème rencontré.
                    </small>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-12">

                    <label class="form-label">
                        Titre du ticket
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-type"></i>

                        <input type="text"
                               name="titre"
                               class="form-control"
                               placeholder="Exemple : Ordinateur en panne"
                               required>

                    </div>

                </div>

                <div class="col-md-12">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea name="description"
                              class="form-control"
                              rows="5"
                              placeholder="Décrivez le problème rencontré..."
                              required></textarea>

                </div>

            </div>

            <!-- Équipement concerné -->

            <div class="form-section-title mt-5">

                <div class="form-section-icon">
                    <i class="bi bi-pc-display"></i>
                </div>

                <div>
                    <h5>Équipement concerné</h5>

                    <small>
                        Sélectionnez le matériel concerné si le problème est lié
                        à un équipement.
                    </small>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        Équipement
                    </label>

                    <select name="id_equipement"
                            id="id_equipement"
                            class="form-select">

                        <option value=""
                                data-local="">

                            Aucun équipement précis

                        </option>

                        <?php foreach ($equipements as $equipement): ?>

                            <?php
                            $idEquipement =
                                $equipement['id_equipement']
                                ?? $equipement['ID_EQUIPEMENT_']
                                ?? '';

                            $numeroSerie =
                                $equipement['numero_serie']
                                ?? $equipement['NUMERO_SERIE']
                                ?? '';

                            $marque =
                                $equipement['marque']
                                ?? $equipement['MARQUE']
                                ?? '';

                            $modele =
                                $equipement['modele']
                                ?? $equipement['MODELE']
                                ?? '';

                            $nomLocal =
                                $equipement['nom_local']
                                ?? $equipement['NOM_LOCAL']
                                ?? 'Local non défini';

                            $nomEquipement = trim(
                                $marque . ' ' .
                                $modele . ' - ' .
                                $numeroSerie
                            );
                            ?>

                            <option
                                value="<?= htmlspecialchars($idEquipement); ?>"
                                data-local="<?= htmlspecialchars($nomLocal); ?>">

                                <?= htmlspecialchars($nomEquipement); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Local de l’équipement
                    </label>

                    <input type="text"
                           id="local_equipement"
                           class="form-control"
                           placeholder="Le local s’affichera automatiquement"
                           readonly>

                    <small class="text-muted">
                        Le local dépend de l’équipement sélectionné.
                    </small>

                </div>

            </div>

            <!-- Classification -->

            <div class="form-section-title mt-5">

                <div class="form-section-icon">
                    <i class="bi bi-sliders"></i>
                </div>

                <div>
                    <h5>Classification du ticket</h5>

                    <small>
                        Choisissez la priorité et le moyen de communication.
                    </small>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        Priorité
                    </label>

                    <select name="priorite"
                            class="form-select"
                            required>

                        <option value="">
                            Choisir une priorité
                        </option>

                        <option value="Basse">
                            Basse
                        </option>

                        <option value="Moyenne">
                            Moyenne
                        </option>

                        <option value="Haute">
                            Haute
                        </option>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Moyen de communication
                    </label>

                    <select name="id_moyen"
                            class="form-select"
                            required>

                        <option value="">
                            Choisir un moyen
                        </option>

                        <?php foreach ($moyens as $moyen): ?>

                            <?php
                            $idMoyen =
                                $moyen['id_moyen']
                                ?? $moyen['ID_MOYEN']
                                ?? '';

                            $libelle =
                                $moyen['libelle']
                                ?? $moyen['LIBELLE']
                                ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idMoyen); ?>">

                                <?= htmlspecialchars($libelle); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <?php if ($estEmploye): ?>

                    <input type="hidden"
                           name="id_utilisateur"
                           value="<?= htmlspecialchars($idUtilisateurConnecte); ?>">

                    <input type="hidden"
                           name="statut"
                           value="Ouvert">

                <?php else: ?>

                    <div class="col-md-6">

                        <label class="form-label">
                            Demandeur
                        </label>

                        <select name="id_utilisateur"
                                class="form-select"
                                required>

                            <option value="">
                                Choisir un utilisateur
                            </option>

                            <?php foreach ($utilisateurs as $utilisateur): ?>

                                <?php
                                $idUtilisateur =
                                    $utilisateur['id_utilisateur']
                                    ?? $utilisateur['ID_UTILISATEUR']
                                    ?? '';

                                $nom =
                                    $utilisateur['nom']
                                    ?? $utilisateur['NOM']
                                    ?? '';

                                $prenom =
                                    $utilisateur['prenom']
                                    ?? $utilisateur['PRENOM']
                                    ?? '';
                                ?>

                                <option value="<?= htmlspecialchars($idUtilisateur); ?>">

                                    <?= htmlspecialchars(
                                        trim($prenom . ' ' . $nom)
                                    ); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Statut
                        </label>

                        <select name="statut"
                                class="form-select"
                                required>

                            <option value="Ouvert">
                                Ouvert
                            </option>

                            <option value="En cours">
                                En cours
                            </option>

                            <option value="En attente">
                                En attente
                            </option>

                            <option value="Résolu">
                                Résolu
                            </option>

                        </select>

                    </div>

                <?php endif; ?>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=tickets"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>
                    Annuler

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>
                    Créer le ticket

                </button>

            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const equipementSelect = document.getElementById('id_equipement');
    const localInput = document.getElementById('local_equipement');

    if (!equipementSelect || !localInput) {
        return;
    }

    function afficherLocal() {
        const optionSelectionnee =
            equipementSelect.options[equipementSelect.selectedIndex];

        localInput.value =
            optionSelectionnee.getAttribute('data-local') || '';
    }

    equipementSelect.addEventListener('change', afficherLocal);

    afficherLocal();
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>