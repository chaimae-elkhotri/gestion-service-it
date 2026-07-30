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

if (!function_exists('ticketT')) {
    function ticketT(
        string $key,
        array $replacements = []
    ): string {
        return t(
            'tickets_module.' . $key,
            $replacements
        );
    }
}

if (!function_exists('ticketNormalize')) {
    function ticketNormalize(string $value): string
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

if (!function_exists('ticketCommunicationLabel')) {
    function ticketCommunicationLabel(string $value): string
    {
        return match (ticketNormalize($value)) {
            'email', 'e-mail' => ticketT('communication_email'),
            'telephone' => ticketT('communication_phone'),
            'presentiel' => ticketT('communication_in_person'),
            default => $value
        };
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>
            <h2><?= htmlspecialchars(ticketT('create_title')); ?></h2>

            <p>
                <?= htmlspecialchars(ticketT('create_subtitle')); ?>
            </p>
        </div>

        <a href="<?= BASE_URL ?>?page=tickets"
           class="btn btn-light border">

            <i class="bi bi-arrow-left"></i>
            <?= htmlspecialchars(ticketT('back')); ?>

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

        <form action="<?= BASE_URL ?>?page=enregistrer-ticket"
              method="POST">

            <div class="form-section-title">

                <div class="form-section-icon">
                    <i class="bi bi-ticket-detailed-fill"></i>
                </div>

                <div>
                    <h5><?= htmlspecialchars(ticketT('ticket_information')); ?></h5>

                    <small>
                        <?= htmlspecialchars(ticketT('describe_problem')); ?>
                    </small>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(ticketT('ticket_title')); ?>
                    </label>

                    <div class="input-with-icon">

                        <i class="bi bi-type"></i>

                        <input type="text"
                               name="titre"
                               class="form-control"
                               placeholder="<?= htmlspecialchars(ticketT('title_placeholder')); ?>"
                               required>

                    </div>

                </div>

                <div class="col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(ticketT('description')); ?>
                    </label>

                    <textarea name="description"
                              class="form-control"
                              rows="5"
                              placeholder="<?= htmlspecialchars(ticketT('description_placeholder')); ?>"
                              required></textarea>

                </div>

            </div>

            <div class="form-section-title mt-5">

                <div class="form-section-icon">
                    <i class="bi bi-pc-display"></i>
                </div>

                <div>
                    <h5><?= htmlspecialchars(ticketT('concerned_equipment')); ?></h5>

                    <small>
                        <?= htmlspecialchars(ticketT('concerned_equipment_help')); ?>
                    </small>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(ticketT('equipment')); ?>
                    </label>

                    <select name="id_equipement"
                            id="id_equipement"
                            class="form-select">

                        <option value=""
                                data-local="">

                            <?= htmlspecialchars(ticketT('no_specific_equipment')); ?>

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
                                ?? ticketT('undefined_local');

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
                        <?= htmlspecialchars(ticketT('equipment_local')); ?>
                    </label>

                    <input type="text"
                           id="local_equipement"
                           class="form-control"
                           placeholder="<?= htmlspecialchars(ticketT('local_auto_placeholder')); ?>"
                           readonly>

                    <small class="text-muted">
                        <?= htmlspecialchars(ticketT('local_depends_equipment')); ?>
                    </small>

                </div>

            </div>

            <div class="form-section-title mt-5">

                <div class="form-section-icon">
                    <i class="bi bi-sliders"></i>
                </div>

                <div>
                    <h5><?= htmlspecialchars(ticketT('ticket_classification')); ?></h5>

                    <small>
                        <?= htmlspecialchars(ticketT('classification_help')); ?>
                    </small>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(ticketT('priority')); ?>
                    </label>

                    <select name="priorite"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(ticketT('choose_priority')); ?>
                        </option>

                        <option value="Basse">
                            <?= htmlspecialchars(ticketT('priority_low')); ?>
                        </option>

                        <option value="Moyenne">
                            <?= htmlspecialchars(ticketT('priority_medium')); ?>
                        </option>

                        <option value="Haute">
                            <?= htmlspecialchars(ticketT('priority_high')); ?>
                        </option>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(ticketT('communication_method')); ?>
                    </label>

                    <select name="id_moyen"
                            class="form-select"
                            required>

                        <option value="">
                            <?= htmlspecialchars(ticketT('choose_method')); ?>
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
                                ?? $moyen['nom_moyen']
                                ?? $moyen['NOM_MOYEN']
                                ?? '';
                            ?>

                            <option value="<?= htmlspecialchars($idMoyen); ?>">

                                <?= htmlspecialchars(
                                    ticketCommunicationLabel(
                                        (string)$libelle
                                    )
                                ); ?>

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
                            <?= htmlspecialchars(ticketT('requester')); ?>
                        </label>

                        <select name="id_utilisateur"
                                class="form-select"
                                required>

                            <option value="">
                                <?= htmlspecialchars(ticketT('choose_user')); ?>
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
                            <?= htmlspecialchars(ticketT('status')); ?>
                        </label>

                        <select name="statut"
                                class="form-select"
                                required>

                            <option value="Ouvert">
                                <?= htmlspecialchars(ticketT('status_open')); ?>
                            </option>

                            <option value="En cours">
                                <?= htmlspecialchars(ticketT('status_in_progress')); ?>
                            </option>

                            <option value="En attente">
                                <?= htmlspecialchars(ticketT('status_waiting')); ?>
                            </option>

                            <option value="Résolu">
                                <?= htmlspecialchars(ticketT('status_resolved')); ?>
                            </option>

                        </select>

                    </div>

                <?php endif; ?>

            </div>

            <div class="form-actions">

                <a href="<?= BASE_URL ?>?page=tickets"
                   class="btn btn-light border">

                    <i class="bi bi-x-circle"></i>
                    <?= htmlspecialchars(ticketT('cancel')); ?>

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>
                    <?= htmlspecialchars(ticketT('create_ticket')); ?>

                </button>

            </div>

        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const equipementSelect =
        document.getElementById('id_equipement');

    const localInput =
        document.getElementById('local_equipement');

    if (!equipementSelect || !localInput) {
        return;
    }

    function afficherLocal() {
        const optionSelectionnee =
            equipementSelect.options[
                equipementSelect.selectedIndex
            ];

        localInput.value =
            optionSelectionnee.getAttribute(
                'data-local'
            ) || '';
    }

    equipementSelect.addEventListener(
        'change',
        afficherLocal
    );

    afficherLocal();
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>