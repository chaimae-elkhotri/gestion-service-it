<?php require_once '../app/views/layouts/header.php'; ?>
<?php require_once '../app/views/layouts/sidebar.php'; ?>
<?php require_once '../app/views/layouts/navbar.php'; ?>

<?php

$historiques = $historiques ?? [];

if (!function_exists('historiqueT')) {
    function historiqueT(
        string $key,
        array $replacements = []
    ): string {
        $fullKey = 'historiques_module.' . $key;

        $translated = t(
            $fullKey,
            $replacements
        );

        /*
         * Si le bloc historiques_module n'a pas été chargé
         * correctement dans fr.php/ar.php, t() renvoie la clé.
         * On utilise alors ce dictionnaire local de secours.
         */
        if ($translated !== $fullKey) {
            return $translated;
        }

        $currentLanguage =
            $_SESSION['language']
            ?? $_SESSION['lang']
            ?? $_COOKIE['language']
            ?? (defined('DEFAULT_LANGUAGE')
                ? DEFAULT_LANGUAGE
                : 'fr');

        $currentLanguage =
            strtolower((string)$currentLanguage);

        $translations = [
            'fr' => [
                'management_title' => 'Historique / Traçabilité',
                'management_subtitle' => 'Consultez les actions réalisées dans l’application.',
                'back_dashboard' => 'Retour au tableau de bord',
                'total_actions' => 'Total actions',
                'registered_actions' => 'Actions enregistrées',
                'adds' => 'Ajouts',
                'new_elements' => 'Nouveaux éléments',
                'modifications' => 'Modifications',
                'updates' => 'Mises à jour',
                'deletions' => 'Suppressions',
                'deleted_elements' => 'Éléments supprimés',
                'search' => 'Recherche',
                'search_placeholder' => 'Rechercher par action, table ou utilisateur...',
                'action' => 'Action',
                'all_actions' => 'Toutes les actions',
                'search_button' => 'Rechercher',
                'reset' => 'Réinitialiser',
                'action_add' => 'Ajout',
                'action_edit' => 'Modification',
                'action_delete' => 'Suppression',
                'action_log' => 'Journal des actions',
                'actions_found' => ':count action(s) trouvée(s)',
                'system_traceability' => 'Traçabilité système',
                'id' => 'ID',
                'concerned_table' => 'Table concernée',
                'element' => 'Élément',
                'user' => 'Utilisateur',
                'old_value' => 'Ancienne valeur',
                'new_value' => 'Nouvelle valeur',
                'date' => 'Date',
                'actions' => 'Actions',
                'unknown_user' => 'Utilisateur inconnu',
                'system_action' => 'Action système',
                'empty' => 'Vide',
                'no_change_detected' => 'Aucun changement détecté',
                'new_element_added' => 'Nouvel élément ajouté',
                'element_deleted' => 'Élément supprimé',
                'undefined' => 'Non défini',
                'delete' => 'Supprimer',
                'delete_confirmation' => 'Voulez-vous vraiment supprimer cette ligne historique ?',
                'no_history' => 'Aucun historique trouvé.',
                'table_users' => 'Utilisateurs',
                'table_equipment' => 'Équipements',
                'table_tickets' => 'Tickets',
                'table_interventions' => 'Interventions',
                'table_assignments' => 'Affectations',
                'table_software' => 'Logiciels',
                'table_licences' => 'Licences',
                'table_categories' => 'Catégories',
                'table_locations' => 'Locaux',
                'field_name' => 'Nom',
                'field_first_name' => 'Prénom',
                'field_email' => 'E-mail',
                'field_phone' => 'Téléphone',
                'field_status' => 'Statut',
                'field_priority' => 'Priorité',
                'field_title' => 'Titre',
                'field_description' => 'Description',
                'field_brand' => 'Marque',
                'field_model' => 'Modèle',
                'field_serial_number' => 'Numéro de série',
                'field_version' => 'Version',
                'field_publisher' => 'Éditeur',
                'field_purchase_date' => 'Date d’achat',
                'field_installation_date' => 'Date d’installation',
                'field_start_date' => 'Date de début',
                'field_end_date' => 'Date de fin',
                'field_assignment_date' => 'Date d’affectation',
                'field_assignment_end_date' => 'Date de fin d’affectation',
                'field_report' => 'Rapport',
                'field_duration' => 'Durée',
                'field_response_time' => 'Temps de réponse',
                'field_resolution_time' => 'Temps de résolution'
            ],
            'ar' => [
                'management_title' => 'السجل / التتبع',
                'management_subtitle' => 'اطّلع على الإجراءات المنجزة داخل التطبيق.',
                'back_dashboard' => 'العودة إلى لوحة التحكم',
                'total_actions' => 'مجموع الإجراءات',
                'registered_actions' => 'الإجراءات المسجلة',
                'adds' => 'الإضافات',
                'new_elements' => 'العناصر الجديدة',
                'modifications' => 'التعديلات',
                'updates' => 'عمليات التحديث',
                'deletions' => 'عمليات الحذف',
                'deleted_elements' => 'العناصر المحذوفة',
                'search' => 'البحث',
                'search_placeholder' => 'البحث بالإجراء أو الجدول أو المستخدم...',
                'action' => 'الإجراء',
                'all_actions' => 'جميع الإجراءات',
                'search_button' => 'بحث',
                'reset' => 'إعادة الضبط',
                'action_add' => 'إضافة',
                'action_edit' => 'تعديل',
                'action_delete' => 'حذف',
                'action_log' => 'سجل الإجراءات',
                'actions_found' => 'تم العثور على :count إجراء',
                'system_traceability' => 'تتبع النظام',
                'id' => 'المعرف',
                'concerned_table' => 'الجدول المعني',
                'element' => 'العنصر',
                'user' => 'المستخدم',
                'old_value' => 'القيمة القديمة',
                'new_value' => 'القيمة الجديدة',
                'date' => 'التاريخ',
                'actions' => 'الإجراءات',
                'unknown_user' => 'مستخدم غير معروف',
                'system_action' => 'إجراء نظامي',
                'empty' => 'فارغ',
                'no_change_detected' => 'لم يتم اكتشاف أي تغيير',
                'new_element_added' => 'تمت إضافة عنصر جديد',
                'element_deleted' => 'تم حذف العنصر',
                'undefined' => 'غير محدد',
                'delete' => 'حذف',
                'delete_confirmation' => 'هل تريد فعلًا حذف هذا السطر من السجل؟',
                'no_history' => 'لم يتم العثور على أي سجل.',
                'table_users' => 'المستخدمون',
                'table_equipment' => 'المعدات',
                'table_tickets' => 'التذاكر',
                'table_interventions' => 'التدخلات',
                'table_assignments' => 'التعيينات',
                'table_software' => 'البرامج',
                'table_licences' => 'التراخيص',
                'table_categories' => 'الفئات',
                'table_locations' => 'المرافق',
                'field_name' => 'الاسم العائلي',
                'field_first_name' => 'الاسم الشخصي',
                'field_email' => 'البريد الإلكتروني',
                'field_phone' => 'الهاتف',
                'field_status' => 'الحالة',
                'field_priority' => 'الأولوية',
                'field_title' => 'العنوان',
                'field_description' => 'الوصف',
                'field_brand' => 'العلامة التجارية',
                'field_model' => 'الطراز',
                'field_serial_number' => 'الرقم التسلسلي',
                'field_version' => 'الإصدار',
                'field_publisher' => 'الناشر',
                'field_purchase_date' => 'تاريخ الشراء',
                'field_installation_date' => 'تاريخ التثبيت',
                'field_start_date' => 'تاريخ البداية',
                'field_end_date' => 'تاريخ النهاية',
                'field_assignment_date' => 'تاريخ التعيين',
                'field_assignment_end_date' => 'تاريخ نهاية التعيين',
                'field_report' => 'التقرير',
                'field_duration' => 'المدة',
                'field_response_time' => 'زمن الاستجابة',
                'field_resolution_time' => 'زمن الحل'
            ]
        ];

        $language = str_starts_with(
            $currentLanguage,
            'ar'
        ) ? 'ar' : 'fr';

        $value =
            $translations[$language][$key]
            ?? $translations['fr'][$key]
            ?? $key;

        foreach ($replacements as $name => $replacement) {
            $value = str_replace(
                ':' . $name,
                (string)$replacement,
                $value
            );
        }

        return $value;
    }
}

if (!function_exists('historiqueNormalize')) {
    function historiqueNormalize(string $value): string
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

if (!function_exists('historiqueActionLabel')) {
    function historiqueActionLabel(string $value): string
    {
        return match (historiqueNormalize($value)) {
            'ajout' => historiqueT('action_add'),
            'modification' => historiqueT('action_edit'),
            'suppression' => historiqueT('action_delete'),
            default => $value !== ''
                ? $value
                : historiqueT('undefined')
        };
    }
}

if (!function_exists('historiqueTableLabel')) {
    function historiqueTableLabel(string $value): string
    {
        return match (historiqueNormalize($value)) {
            'utilisateur',
            'utilisateurs' =>
                historiqueT('table_users'),

            'equipement',
            'equipements' =>
                historiqueT('table_equipment'),

            'ticket',
            'tickets' =>
                historiqueT('table_tickets'),

            'intervention',
            'interventions' =>
                historiqueT('table_interventions'),

            'affectation',
            'affectation_equipement',
            'affectations' =>
                historiqueT('table_assignments'),

            'logiciel',
            'logiciels' =>
                historiqueT('table_software'),

            'licence',
            'licences' =>
                historiqueT('table_licences'),

            'categorie',
            'categories' =>
                historiqueT('table_categories'),

            'local',
            'locaux' =>
                historiqueT('table_locations'),

            default => $value !== ''
                ? $value
                : historiqueT('undefined')
        };
    }
}

if (!function_exists('historiqueFieldLabel')) {
    function historiqueFieldLabel(string $field): string
    {
        $normalized = historiqueNormalize($field);

        $map = [
            'nom' => 'field_name',
            'prenom' => 'field_first_name',
            'email' => 'field_email',
            'tel' => 'field_phone',
            'telephone' => 'field_phone',
            'statut' => 'field_status',
            'priorite' => 'field_priority',
            'titre' => 'field_title',
            'description' => 'field_description',
            'marque' => 'field_brand',
            'modele' => 'field_model',
            'numero serie' => 'field_serial_number',
            'version' => 'field_version',
            'editeur' => 'field_publisher',
            'date achat' => 'field_purchase_date',
            'date installation' => 'field_installation_date',
            'date debut' => 'field_start_date',
            'date fin' => 'field_end_date',
            'date affectation' => 'field_assignment_date',
            'date fin affectation' => 'field_assignment_end_date',
            'rapport' => 'field_report',
            'duree' => 'field_duration',
            'temps reponse' => 'field_response_time',
            'temps resolution' => 'field_resolution_time'
        ];

        if (isset($map[$normalized])) {
            return historiqueT($map[$normalized]);
        }

        return ucfirst(
            str_replace('_', ' ', $field)
        );
    }
}

$filtreAction = historiqueNormalize(
    (string)($_GET['action'] ?? '')
);

if ($filtreAction !== '') {
    $historiques = array_values(
        array_filter(
            $historiques,
            function (array $historique) use (
                $filtreAction
            ): bool {
                $action = historiqueNormalize(
                    (string)(
                        $historique['action']
                        ?? $historique['ACTION']
                        ?? ''
                    )
                );

                return $action === $filtreAction;
            }
        )
    );
}

$totalHistoriques = count($historiques);
$totalAjouts = 0;
$totalModifications = 0;
$totalSuppressions = 0;

foreach ($historiques as $h) {
    $action = historiqueNormalize(
        (string)(
            $h['action']
            ?? $h['ACTION']
            ?? ''
        )
    );

    if ($action === 'ajout') {
        $totalAjouts++;
    } elseif ($action === 'modification') {
        $totalModifications++;
    } elseif ($action === 'suppression') {
        $totalSuppressions++;
    }
}

?>

<div class="module-page">

    <div class="module-header">

        <div>

            <h2>
                <?= htmlspecialchars(
                    historiqueT('management_title')
                ); ?>
            </h2>

            <p>
                <?= htmlspecialchars(
                    historiqueT('management_subtitle')
                ); ?>
            </p>

        </div>

        <a href="<?= BASE_URL ?>?page=dashboard"
           class="btn btn-primary">

            <i class="bi bi-speedometer2"></i>

            <?= htmlspecialchars(
                historiqueT('back_dashboard')
            ); ?>

        </a>

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

    <div class="module-stats-grid">

        <div class="module-stat-card">

            <div class="module-stat-icon brown">
                <i class="bi bi-clock-history"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        historiqueT('total_actions')
                    ); ?>
                </span>

                <h3><?= $totalHistoriques; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        historiqueT(
                            'registered_actions'
                        )
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon green">
                <i class="bi bi-plus-circle-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        historiqueT('adds')
                    ); ?>
                </span>

                <h3><?= $totalAjouts; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        historiqueT('new_elements')
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon blue">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        historiqueT('modifications')
                    ); ?>
                </span>

                <h3><?= $totalModifications; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        historiqueT('updates')
                    ); ?>
                </small>

            </div>

        </div>

        <div class="module-stat-card">

            <div class="module-stat-icon red">
                <i class="bi bi-trash-fill"></i>
            </div>

            <div>

                <span>
                    <?= htmlspecialchars(
                        historiqueT('deletions')
                    ); ?>
                </span>

                <h3><?= $totalSuppressions; ?></h3>

                <small>
                    <?= htmlspecialchars(
                        historiqueT('deleted_elements')
                    ); ?>
                </small>

            </div>

        </div>

    </div>

    <div class="module-filter-card">

        <form action="<?= BASE_URL ?>" method="GET">

            <input type="hidden"
                   name="page"
                   value="historiques">

            <div class="row g-3 align-items-end">

                <div class="col-lg-6 col-md-12">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            historiqueT('search')
                        ); ?>
                    </label>

                    <div class="modern-search-input">

                        <i class="bi bi-search"></i>

                        <input type="text"
                               name="search"
                               placeholder="<?= htmlspecialchars(
                                   historiqueT(
                                       'search_placeholder'
                                   )
                               ); ?>"
                               value="<?= htmlspecialchars(
                                   $_GET['search'] ?? ''
                               ); ?>">

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <label class="form-label">
                        <?= htmlspecialchars(
                            historiqueT('action')
                        ); ?>
                    </label>

                    <select name="action"
                            class="form-select"
                            onchange="this.form.submit()">

                        <option value=""
                            <?= $filtreAction === ''
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                historiqueT(
                                    'all_actions'
                                )
                            ); ?>

                        </option>

                        <option value="ajout"
                            <?= $filtreAction === 'ajout'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                historiqueT('action_add')
                            ); ?>

                        </option>

                        <option value="modification"
                            <?= $filtreAction === 'modification'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                historiqueT('action_edit')
                            ); ?>

                        </option>

                        <option value="suppression"
                            <?= $filtreAction === 'suppression'
                                ? 'selected'
                                : ''; ?>>

                            <?= htmlspecialchars(
                                historiqueT(
                                    'action_delete'
                                )
                            ); ?>

                        </option>

                    </select>

                </div>

                <div class="col-lg-3 col-md-6 d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary flex-fill">

                        <i class="bi bi-search"></i>

                        <?= htmlspecialchars(
                            historiqueT('search_button')
                        ); ?>

                    </button>

                    <a href="<?= BASE_URL ?>?page=historiques"
                       class="btn btn-light border"
                       title="<?= htmlspecialchars(
                           historiqueT('reset')
                       ); ?>">

                        <i class="bi bi-arrow-clockwise"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>

    <div class="module-table-card">

        <div class="module-table-header">

            <div>

                <h5>
                    <?= htmlspecialchars(
                        historiqueT('action_log')
                    ); ?>
                </h5>

                <small>
                    <?= htmlspecialchars(
                        historiqueT(
                            'actions_found',
                            ['count' => $totalHistoriques]
                        )
                    ); ?>
                </small>

            </div>

            <span class="module-chip">

                <i class="bi bi-shield-check"></i>

                <?= htmlspecialchars(
                    historiqueT(
                        'system_traceability'
                    )
                ); ?>

            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle modern-table">

                <thead>

                <tr>
                    <th><?= htmlspecialchars(historiqueT('id')); ?></th>
                    <th><?= htmlspecialchars(historiqueT('action')); ?></th>
                    <th><?= htmlspecialchars(historiqueT('concerned_table')); ?></th>
                    <th><?= htmlspecialchars(historiqueT('element')); ?></th>
                    <th><?= htmlspecialchars(historiqueT('user')); ?></th>
                    <th><?= htmlspecialchars(historiqueT('old_value')); ?></th>
                    <th><?= htmlspecialchars(historiqueT('new_value')); ?></th>
                    <th><?= htmlspecialchars(historiqueT('date')); ?></th>
                    <th class="text-center">
                        <?= htmlspecialchars(historiqueT('actions')); ?>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php if (!empty($historiques)): ?>

                    <?php foreach (
                        $historiques as $h
                    ): ?>

                        <?php

                        $id =
                            $h['id_historique']
                            ?? $h['ID_HISTORIQUE']
                            ?? '';

                        $table =
                            $h['table_concernee']
                            ?? $h['TABLE_CONCERNEE']
                            ?? '';

                        $idElement =
                            $h['id_element']
                            ?? $h['ID_ELEMENT']
                            ?? '';

                        $action =
                            $h['action']
                            ?? $h['ACTION']
                            ?? '';

                        $ancienneValeur =
                            $h['ancienne_valeur']
                            ?? $h['ANCIENNE_VALEUR']
                            ?? '';

                        $nouvelleValeur =
                            $h['nouvelle_valeur']
                            ?? $h['NOUVELLE_VALEUR']
                            ?? '';

                        $dateAction =
                            $h['date_action']
                            ?? $h['DATE_ACTION']
                            ?? '';

                        $nomUtilisateur =
                            $h['nom_utilisateur']
                            ?? $h['NOM_UTILISATEUR']
                            ?? $h['nom']
                            ?? $h['NOM']
                            ?? '';

                        $prenomUtilisateur =
                            $h['prenom_utilisateur']
                            ?? $h['PRENOM_UTILISATEUR']
                            ?? $h['prenom']
                            ?? $h['PRENOM']
                            ?? '';

                        $actionLower =
                            historiqueNormalize(
                                (string)$action
                            );

                        if ($actionLower === 'ajout') {
                            $actionClass = 'history-add';
                            $actionIcon =
                                'bi-plus-circle-fill';

                        } elseif (
                            $actionLower === 'modification'
                        ) {
                            $actionClass = 'history-edit';
                            $actionIcon =
                                'bi-pencil-square';

                        } elseif (
                            $actionLower === 'suppression'
                        ) {
                            $actionClass = 'history-delete';
                            $actionIcon =
                                'bi-trash-fill';

                        } else {
                            $actionClass = 'history-default';
                            $actionIcon =
                                'bi-clock-history';
                        }

                        $initiales = mb_strtoupper(
                            mb_substr(
                                $prenomUtilisateur,
                                0,
                                1,
                                'UTF-8'
                            )
                            .
                            mb_substr(
                                $nomUtilisateur,
                                0,
                                1,
                                'UTF-8'
                            ),
                            'UTF-8'
                        );

                        $ancienneAffichee = '-';
                        $nouvelleAffichee = '-';

                        if (
                            $actionLower === 'modification'
                        ) {
                            $ancienneData = json_decode(
                                $ancienneValeur,
                                true
                            );

                            $nouvelleData = json_decode(
                                $nouvelleValeur,
                                true
                            );

                            if (
                                is_array($ancienneData)
                                && is_array($nouvelleData)
                            ) {
                                $anciennesModifications = [];
                                $nouvellesModifications = [];

                                foreach (
                                    $nouvelleData
                                    as $champ => $nouvelleDonnee
                                ) {
                                    $ancienneDonnee =
                                        $ancienneData[$champ]
                                        ?? null;

                                    if (
                                        (string)$ancienneDonnee
                                        !==
                                        (string)$nouvelleDonnee
                                    ) {
                                        $nomChamp =
                                            historiqueFieldLabel(
                                                (string)$champ
                                            );

                                        $ancienneTexte =
                                            $ancienneDonnee !== null
                                            && $ancienneDonnee !== ''
                                                ? (string)$ancienneDonnee
                                                : historiqueT('empty');

                                        $nouvelleTexte =
                                            $nouvelleDonnee !== null
                                            && $nouvelleDonnee !== ''
                                                ? (string)$nouvelleDonnee
                                                : historiqueT('empty');

                                        $anciennesModifications[] =
                                            htmlspecialchars(
                                                $nomChamp
                                            )
                                            . ' : '
                                            . htmlspecialchars(
                                                $ancienneTexte
                                            );

                                        $nouvellesModifications[] =
                                            htmlspecialchars(
                                                $nomChamp
                                            )
                                            . ' : '
                                            . htmlspecialchars(
                                                $nouvelleTexte
                                            );
                                    }
                                }

                                if (
                                    !empty(
                                        $anciennesModifications
                                    )
                                ) {
                                    $ancienneAffichee =
                                        implode(
                                            '<br>',
                                            $anciennesModifications
                                        );

                                    $nouvelleAffichee =
                                        implode(
                                            '<br>',
                                            $nouvellesModifications
                                        );

                                } else {
                                    $ancienneAffichee =
                                        htmlspecialchars(
                                            historiqueT(
                                                'no_change_detected'
                                            )
                                        );

                                    $nouvelleAffichee =
                                        htmlspecialchars(
                                            historiqueT(
                                                'no_change_detected'
                                            )
                                        );
                                }
                            }

                        } elseif ($actionLower === 'ajout') {
                            $nouvelleAffichee =
                                htmlspecialchars(
                                    historiqueT(
                                        'new_element_added'
                                    )
                                );

                        } elseif (
                            $actionLower === 'suppression'
                        ) {
                            $ancienneAffichee =
                                htmlspecialchars(
                                    historiqueT(
                                        'element_deleted'
                                    )
                                );
                        }

                        ?>

                        <tr>

                            <td>

                                <span class="table-id">
                                    #HIS-<?= htmlspecialchars($id); ?>
                                </span>

                            </td>

                            <td>

                                <span class="badge <?= $actionClass; ?>">

                                    <i class="bi <?= $actionIcon; ?>"></i>

                                    <?= htmlspecialchars(
                                        historiqueActionLabel(
                                            (string)$action
                                        )
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="history-table-badge">

                                    <i class="bi bi-table"></i>

                                    <?= htmlspecialchars(
                                        historiqueTableLabel(
                                            (string)$table
                                        )
                                    ); ?>

                                </span>

                            </td>

                            <td>

                                <span class="history-element-badge">
                                    #<?= htmlspecialchars($idElement); ?>
                                </span>

                            </td>

                            <td>

                                <div class="user-cell">

                                    <div class="table-avatar">

                                        <?= htmlspecialchars(
                                            $initiales ?: 'A'
                                        ); ?>

                                    </div>

                                    <div>

                                        <strong>
                                            <?= htmlspecialchars(
                                                trim(
                                                    $prenomUtilisateur
                                                    . ' '
                                                    . $nomUtilisateur
                                                )
                                                ?: historiqueT(
                                                    'unknown_user'
                                                )
                                            ); ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars(
                                                historiqueT(
                                                    'system_action'
                                                )
                                            ); ?>
                                        </small>

                                    </div>

                                </div>

                            </td>

                            <td>

                                <span class="history-value old">
                                    <?= $ancienneAffichee; ?>
                                </span>

                            </td>

                            <td>

                                <span class="history-value new">
                                    <?= $nouvelleAffichee; ?>
                                </span>

                            </td>

                            <td>

                                <span class="date-badge">

                                    <i class="bi bi-calendar-check"></i>

                                    <?= !empty($dateAction)
                                        ? date(
                                            'd/m/Y H:i',
                                            strtotime(
                                                $dateAction
                                            )
                                        )
                                        : '-'; ?>

                                </span>

                            </td>

                            <td class="text-center">

                                <a href="<?= BASE_URL ?>?page=supprimer-historique&id=<?= (int)$id; ?>"
                                   class="btn btn-danger btn-sm"
                                   title="<?= htmlspecialchars(
                                       historiqueT('delete')
                                   ); ?>"
                                   onclick="return confirm('<?= htmlspecialchars(
                                       historiqueT(
                                           'delete_confirmation'
                                       ),
                                       ENT_QUOTES,
                                       'UTF-8'
                                   ); ?>');">

                                    <i class="bi bi-trash"></i>

                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="9"
                            class="text-center py-5 text-muted">

                            <i class="bi bi-clock-history fs-1"></i>

                            <br><br>

                            <?= htmlspecialchars(
                                historiqueT(
                                    'no_history'
                                )
                            ); ?>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>