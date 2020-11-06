<?php
// Projet TraceGPS - services web
// Fichier : api/api.php
// La classe Api hérite de la classe Rest (fichier api/rest.php)
// Dernière mise à jour : 3/7/2018 par Jim

include_once ("rest.php");
include_once ('../modele/DAO.class.php');

class Api extends Rest
{   
    // Le constructeur
    public function __construct()
    {   parent::__construct();      // appel du constructeur de la classe parente
    }
    
    
    // Cette méthode traite l'action demandée dans l'URI
    public function traiterRequete()
    {   // récupère le contenu du paramètre action et supprime les "/"
        $action = ( empty($this->request['action'])) ? "" : $this->request['action'];
        $action = strtolower(trim(str_replace("/", "", $action)));
        
        switch ($action) {
            // services web fournis
            case "connecter" : {$this->Connecter(); break;}
            case "changerdemdp" : {$this->ChangerDeMdp(); break;}
            case "creerunutilisateur" : {$this->CreerUnUtilisateur(); break;}
            case "gettouslesutilisateurs" : {$this->GetTousLesUtilisateurs(); break;}
            case "supprimerunutilisateur" : {$this->SupprimerUnUtilisateur(); break;}
            case "validerdemandeautorisation" : {$this->ValiderDemandeAutorisation(); break;}
            
            // services web restant à développer
            case "demandermdp" : {$this->DemanderMdp(); break;}
            case "getlesparcoursdunutilisateur" : {$this->GetLesParcoursDunUtilisateur(); break;}
            case "getlesutilisateursquejautorise" : {$this->GetLesUtilisateursQueJautorise(); break;}
            case "getlesutilisateursquimautorisent" : {$this->GetLesUtilisateursQuiMautorisent(); break;}
            case "getunparcoursetsespoints" : {$this->GetUnParcoursEtSesPoints(); break;}
            case "retireruneautorisation" : {$this->RetirerUneAutorisation(); break;}
            case "supprimerunparcours" : {$this->SupprimerUnParcours(); break;}
            case "demanderuneautorisation" : {$this->DemanderUneAutorisation(); break;}
            case "demarrerenregistrementparcours" : {$this->DemarrerEnregistrementParcours(); break;}
            case "envoyerposition" : {$this->EnvoyerPosition(); break;}
            case "arreterenregistrementparcours" : {$this->ArreterEnregistrementParcours(); break;}
            
            // l'action demandée n'existe pas, la réponse est 404 ("Page not found") et aucune donnée n'est envoyée
            default : {
                $code_reponse = 404;            
                $donnees = '';
                $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
                $this->envoyerReponse($code_reponse, $content_type, $donnees);    // envoi de la réponse HTTP
                break;
            }  
        } 
    } // fin de la fonction traiterRequete
    
    // services web fournis ===========================================================================================
    
    // Ce service permet permet à un utilisateur de s'authentifier
    private function Connecter()
    {   include_once ("services/Connecter.php");
    }
    
    // Ce service permet permet à un utilisateur de changer son mot de passe
    private function ChangerDeMdp()
    {   include_once ("services/ChangerDeMdp.php");
    }
    
    // Ce service permet permet à un utilisateur de se créer un compte
    private function CreerUnUtilisateur()
    {   include_once ("services/CreerUnUtilisateur.php");
    }
    
    // Ce service permet à un utilisateur authentifié d'obtenir la liste de tous les utilisateurs (de niveau 1)
    private function GetTousLesUtilisateurs()
    {   include_once ("services/GetTousLesUtilisateurs.php");
    }
    
    // Ce service permet à un administrateur de supprimer un utilisateur (à condition qu'il ne possède aucune trace enregistrée)
    private function SupprimerUnUtilisateur()
    {   include_once ("services/SupprimerUnUtilisateur.php");
    }
    
    // Ce service permet à un utilisateur destinataire d'accepter ou de rejeter une demande d'autorisation provenant d'un utilisateur demandeur
    private function ValiderDemandeAutorisation()
    {   include_once ("services/ValiderDemandeAutorisation.php");
    }
    
    // services web restant à développer ==============================================================================
    
    // Ce service génère un nouveau mot de passe, l'enregistre en sha1 et l'envoie par mail à l'utilisateur
    private function DemanderMdp()
    {   include_once ("services/DemanderMdp.php");
    }
    
    // Ce service permet à un utilisateur d'obtenir la liste de ses parcours ou la liste des parcours d'un utilisateur qui l'autorise
    private function GetLesParcoursDunUtilisateur()
    {   include_once ("services/GetLesParcoursDunUtilisateur.php");
    }
    
    // Ce service permet à un utilisateur d'obtenir la liste des utilisateurs qu'il autorise à consulter ses parcours
    private function GetLesUtilisateursQueJautorise()
    {   include_once ("services/GetLesUtilisateursQueJautorise.php");
    }
    
    // Ce service permet à un utilisateur d'obtenir la liste des utilisateurs qui l'autorisent à consulter leurs parcours
    private function GetLesUtilisateursQuiMautorisent()
    {   include_once ("services/GetLesUtilisateursQuiMautorisent.php");
    }
    
    // Ce service permet à un utilisateur d'obtenir le détail d'un de ses parcours ou d'un parcours d'un membre qui l'autorise
    private function getunparcoursetsespoints()
    {   include_once ("services/getunparcoursetsespoints.php");
    }
    
    // Ce service permet à un utilisateur de supprimer une autorisation qu'il avait accordée à un autre utilisateur
    private function RetirerUneAutorisation()
    {   include_once ("services/RetirerUneAutorisation.php");
    }
    
    // Ce service permet à un utilisateur de supprimer un de ses parcours 
    private function SupprimerUnParcours()
    {   include_once ("services/SupprimerUnParcours.php");
    }
    
    // Ce service permet à un utilisateur de demander une autorisation à un autre utilisateur
    private function DemanderUneAutorisation()
    {   include_once ("services/DemanderUneAutorisation.php");
    }
    
    // Ce service permet à un utilisateur de démarrer l'enregistrement d'un parcours
    private function DemarrerEnregistrementParcours()
    {   include_once ("services/DemarrerEnregistrementParcours.php");
    }
    
    // Ce service permet à un utilisateur authentifié d'envoyer sa position
    private function EnvoyerPosition()
    {   include_once ("services/EnvoyerPosition.php");
    }
    
    // Ce service permet à un utilisateur de terminer l'enregistrement de son parcours
    private function ArreterEnregistrementParcours()
    {   include_once ("services/ArreterEnregistrementParcours.php");
    }
} // fin de la classe Api

// Traitement de la requête HTTP
$api = new Api;
$api->traiterRequete();