<?php

require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/DashboardController.php';
require_once '../app/controllers/UtilisateurController.php';
require_once '../app/controllers/CategorieController.php';
require_once '../app/controllers/EquipementController.php';
require_once '../app/controllers/LocalController.php';
require_once '../app/controllers/OccupationLocalController.php';
require_once '../app/controllers/AffectationController.php';
require_once '../app/controllers/TicketController.php';
require_once '../app/controllers/LicenceController.php';
require_once '../app/controllers/InterventionController.php';
require_once '../app/controllers/EvaluationController.php';
require_once '../app/controllers/LogicielController.php';
require_once '../app/controllers/HistoriqueController.php';

class Router
{
    public function dispatch()
    {
        $page = $_GET['page'] ?? 'login';

        switch ($page) {

            /*
             * Authentification
             */

            case 'login':
                (new AuthController())->login();
                break;

            case 'changer-mot-de-passe-obligatoire':
                (new AuthController())
                    ->changerMotDePasseObligatoire();
                break;

            case 'logout':
                (new AuthController())->logout();
                break;

            /*
             * Tableau de bord
             */

            case 'dashboard':
                (new DashboardController())->index();
                break;

            /*
             * Utilisateurs
             */

            case 'utilisateurs':
                (new UtilisateurController())->index();
                break;

            case 'ajouter-utilisateur':
                (new UtilisateurController())->create();
                break;

            case 'enregistrer-utilisateur':
                (new UtilisateurController())->store();
                break;

            case 'importer-utilisateurs':
                (new UtilisateurController())->importCsv();
                break;

            case 'modele-import-utilisateurs':
                (new UtilisateurController())
                    ->modeleImportCsv();
                break;

            case 'modifier-utilisateur':
                (new UtilisateurController())->edit();
                break;

            case 'mettre-a-jour-utilisateur':
                (new UtilisateurController())->update();
                break;

            case 'supprimer-utilisateur':
                (new UtilisateurController())->delete();
                break;

            /*
             * Catégories
             */

            case 'categories':
                (new CategorieController())->index();
                break;

            case 'ajouter-categorie':
                (new CategorieController())->create();
                break;

            case 'enregistrer-categorie':
                (new CategorieController())->store();
                break;

            case 'modifier-categorie':
                (new CategorieController())->edit();
                break;

            case 'mettre-a-jour-categorie':
                (new CategorieController())->update();
                break;

            case 'supprimer-categorie':
                (new CategorieController())->delete();
                break;

            /*
             * Équipements
             */

            case 'equipements':
                (new EquipementController())->index();
                break;

            case 'ajouter-equipement':
                (new EquipementController())->create();
                break;

            case 'enregistrer-equipement':
                (new EquipementController())->store();
                break;

            case 'importer-equipements':
                (new EquipementController())->importCsv();
                break;

            case 'modele-import-equipements':
                (new EquipementController())
                    ->modeleImportCsv();
                break;

            case 'modifier-equipement':
            case 'edit-equipement':
                (new EquipementController())->edit();
                break;

            case 'mettre-a-jour-equipement':
                (new EquipementController())->update();
                break;

            case 'supprimer-equipement':
                (new EquipementController())->delete();
                break;

            /*
             * Locaux
             */

            case 'locals':
                (new LocalController())->index();
                break;

            case 'ajouter-local':
                (new LocalController())->create();
                break;

            case 'enregistrer-local':
                (new LocalController())->store();
                break;

            case 'modifier-local':
                (new LocalController())->edit();
                break;

            case 'mettre-a-jour-local':
                (new LocalController())->update();
                break;

            case 'supprimer-local':
                (new LocalController())->delete();
                break;

            /*
             * Occupations et disponibilité des locaux
             */

            case 'occupations-locaux':
                (new OccupationLocalController())->index();
                break;

            case 'ajouter-occupation-local':
                (new OccupationLocalController())->create();
                break;

            case 'enregistrer-occupation-local':
                (new OccupationLocalController())->store();
                break;

            case 'modifier-occupation-local':
                (new OccupationLocalController())->edit();
                break;

            case 'mettre-a-jour-occupation-local':
                (new OccupationLocalController())->update();
                break;

            case 'annuler-occupation-local':
                (new OccupationLocalController())->cancel();
                break;

            /*
             * Affectations
             */

            case 'affectations':
                (new AffectationController())->index();
                break;

            case 'ajouter-affectation':
                (new AffectationController())->create();
                break;

            case 'enregistrer-affectation':
                (new AffectationController())->store();
                break;

            case 'modifier-affectation':
                (new AffectationController())->edit();
                break;

            case 'mettre-a-jour-affectation':
                (new AffectationController())->update();
                break;

            case 'supprimer-affectation':
                (new AffectationController())->delete();
                break;

            /*
             * Tickets
             */

            case 'tickets':
                (new TicketController())->index();
                break;

            case 'ajouter-ticket':
                (new TicketController())->create();
                break;

            case 'enregistrer-ticket':
                (new TicketController())->store();
                break;

            case 'modifier-ticket':
                (new TicketController())->edit();
                break;

            case 'mettre-a-jour-ticket':
                (new TicketController())->update();
                break;

            case 'supprimer-ticket':
                (new TicketController())->delete();
                break;

            /*
             * Interventions
             */

            case 'interventions':
                (new InterventionController())->index();
                break;

            case 'ajouter-intervention':
                (new InterventionController())->create();
                break;

            case 'enregistrer-intervention':
                (new InterventionController())->store();
                break;

            case 'modifier-intervention':
                (new InterventionController())->edit();
                break;

            case 'mettre-a-jour-intervention':
                (new InterventionController())->update();
                break;

            case 'supprimer-intervention':
                (new InterventionController())->delete();
                break;

            /*
             * Évaluations
             */

            case 'evaluations':
                (new EvaluationController())->index();
                break;

            case 'ajouter-evaluation':
                (new EvaluationController())->create();
                break;

            case 'enregistrer-evaluation':
                (new EvaluationController())->store();
                break;

            case 'modifier-evaluation':
                (new EvaluationController())->edit();
                break;

            case 'mettre-a-jour-evaluation':
                (new EvaluationController())->update();
                break;

            case 'supprimer-evaluation':
                (new EvaluationController())->delete();
                break;

            /*
             * Logiciels
             */

            case 'logiciels':
                (new LogicielController())->index();
                break;

            case 'ajouter-logiciel':
                (new LogicielController())->create();
                break;

            case 'enregistrer-logiciel':
                (new LogicielController())->store();
                break;

            case 'importer-logiciels':
                (new LogicielController())->importCsv();
                break;

            case 'modele-import-logiciels':
                (new LogicielController())
                    ->modeleImportCsv();
                break;

            case 'modifier-logiciel':
                (new LogicielController())->edit();
                break;

            case 'mettre-a-jour-logiciel':
                (new LogicielController())->update();
                break;

            case 'supprimer-logiciel':
                (new LogicielController())->delete();
                break;

            /*
             * Licences
             */

            case 'licences':
                (new LicenceController())->index();
                break;

            case 'ajouter-licence':
                (new LicenceController())->create();
                break;

            case 'enregistrer-licence':
                (new LicenceController())->store();
                break;

            case 'modifier-licence':
                (new LicenceController())->edit();
                break;

            case 'mettre-a-jour-licence':
                (new LicenceController())->update();
                break;

            case 'supprimer-licence':
                (new LicenceController())->delete();
                break;

            /*
             * Historique
             */

            case 'historiques':
                (new HistoriqueController())->index();
                break;

            case 'supprimer-historique':
                (new HistoriqueController())->delete();
                break;

            /*
             * Profil et paramètres
             */

            case 'profil':
                require_once '../app/controllers/ProfilController.php';

                $controller = new ProfilController();
                $controller->index();
                break;

            case 'parametres-compte':
                require_once '../app/controllers/ProfilController.php';

                $controller = new ProfilController();
                $controller->parametres();
                break;

            /*
             * Accès refusé
             */

            case 'acces-refuse':
                require_once '../app/views/errors/acces-refuse.php';
                break;

            /*
             * Page inexistante
             */

            default:
                header(
                    'Location: ' .
                    BASE_URL .
                    '?page=login'
                );
                exit;
        }
    }
}