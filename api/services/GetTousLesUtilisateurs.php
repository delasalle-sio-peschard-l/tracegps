<?php
// Projet TraceGPS - services web
// fichier : api/services/GetTousLesUtilisateurs.php
// Dernière mise à jour : 3/7/2019 par Jim

// Rôle : ce service permet à un utilisateur authentifié d'obtenir la liste de tous les utilisateurs (de niveau 1)
// Le service web doit recevoir 3 paramètres :
//     pseudo : le pseudo de l'utilisateur
//     mdp : le mot de passe de l'utilisateur hashé en sha1
//     lang : le langage du flux de données retourné ("xml" ou "json") ; "xml" par défaut si le paramètre est absent ou incorrect
// Le service retourne un flux de données XML ou JSON contenant un compte-rendu d'exécution

// Les paramètres doivent être passés par la méthode GET :
//     http://<hébergeur>/tracegps/api/GetTousLesUtilisateurs?pseudo=callisto&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=xml

// connexion du serveur web à la base MySQL
$dao = new DAO();
	
// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdpSha1 = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

// "xml" par défaut si le paramètre lang est absent ou incorrect
if ($lang != "json") $lang = "xml";

// initialisation du nombre de réponses
$nbReponses = 0;
$lesUtilisateurs = array();

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	$msg = "Erreur : méthode HTTP incorrecte.";
    $code_reponse = 406;
}
else {
    // Les paramètres doivent être présents
    if ( $pseudo == "" || $mdpSha1 == "" )
    {	$msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else
    {	if ( $dao->getNiveauConnexion($pseudo, $mdpSha1) == 0 ) {
    		$msg = "Erreur : authentification incorrecte.";
    		$code_reponse = 401;
        }
    	else 
    	{	// récupération de la liste des utilisateurs à l'aide de la méthode getTousLesUtilisateurs de la classe DAO
    	    $lesUtilisateurs = $dao->getTousLesUtilisateurs();
    	    
    	    // mémorisation du nombre d'utilisateurs
    	    $nbReponses = sizeof($lesUtilisateurs);
    	
    	    if ($nbReponses == 0) {
    			$msg = "Aucun utilisateur.";
    			$code_reponse = 200;
    	    }
    	    else {
    			$msg = $nbReponses . " utilisateur(s).";
    			$code_reponse = 200;
    	    }
    	}
    }
}
// ferme la connexion à MySQL :
unset($dao);

// création du flux en sortie
if ($lang == "xml") {
    $content_type = "application/xml; charset=utf-8";      // indique le format XML pour la réponse
    $donnees = creerFluxXML($msg, $lesUtilisateurs);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON($msg, $lesUtilisateurs);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);

// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================
 
// création du flux XML en sortie
function creerFluxXML($msg, $lesUtilisateurs)
{	
    /* Exemple de code XML
        <?xml version="1.0" encoding="UTF-8"?>
        <!--Service web GetTousLesUtilisateurs - BTS SIO - Lycée De La Salle - Rennes-->
        <data>
          <reponse>2 utilisateur(s).</reponse>
          <donnees>
             <lesUtilisateurs>
                <utilisateur>
                  <id>2</id>
                  <pseudo>callisto</pseudo>
                  <adrMail>delasalle.sio.eleves@gmail.com</adrMail>
                  <numTel>22.33.44.55.66</numTel>
                  <niveau>1</niveau>
                  <dateCreation>2018-08-12 19:45:23</dateCreation>
                  <nbTraces>2</nbTraces>
                  <dateDerniereTrace>2018-01-19 13:08:48</dateDerniereTrace>
                </utilisateur>
                <utilisateur>
                  <id>3</id>
                  <pseudo>europa</pseudo>
                  <adrMail>delasalle.sio.eleves@gmail.com</adrMail>
                  <numTel>22.33.44.55.66</numTel>
                  <niveau>1</niveau>
                  <dateCreation>2018-08-12 19:45:23</dateCreation>
                  <nbTraces>0</nbTraces>
                </utilisateur>
             </lesUtilisateurs>
          </donnees>
        </data>
     */
    
    // crée une instance de DOMdocument (DOM : Document Object Model)
	$doc = new DOMDocument();
	
	// specifie la version et le type d'encodage
	$doc->version = '1.0';
	$doc->encoding = 'UTF-8';
	
	// crée un commentaire et l'encode en UTF-8
	$elt_commentaire = $doc->createComment('Service web GetTousLesUtilisateurs - BTS SIO - Lycée De La Salle - Rennes');
	// place ce commentaire à la racine du document XML
	$doc->appendChild($elt_commentaire);
	
	// crée l'élément 'data' à la racine du document XML
	$elt_data = $doc->createElement('data');
	$doc->appendChild($elt_data);
	
	// place l'élément 'reponse' dans l'élément 'data'
	$elt_reponse = $doc->createElement('reponse', $msg);
	$elt_data->appendChild($elt_reponse);
	
	// traitement des utilisateurs
	if (sizeof($lesUtilisateurs) > 0) {
	    // place l'élément 'donnees' dans l'élément 'data'
	    $elt_donnees = $doc->createElement('donnees');
	    $elt_data->appendChild($elt_donnees);
	    
	    // place l'élément 'lesUtilisateurs' dans l'élément 'donnees'
	    $elt_lesUtilisateurs = $doc->createElement('lesUtilisateurs');
	    $elt_donnees->appendChild($elt_lesUtilisateurs);
	    
	    foreach ($lesUtilisateurs as $unUtilisateur)
		{
		    // crée un élément vide 'utilisateur'
		    $elt_utilisateur = $doc->createElement('utilisateur');	    
		    // place l'élément 'utilisateur' dans l'élément 'lesUtilisateurs'
		    $elt_lesUtilisateurs->appendChild($elt_utilisateur);
		
		    // crée les éléments enfants de l'élément 'utilisateur'
		    $elt_id         = $doc->createElement('id', $unUtilisateur->getId());
		    $elt_utilisateur->appendChild($elt_id);
		    
		    $elt_pseudo     = $doc->createElement('pseudo', $unUtilisateur->getPseudo());
		    $elt_utilisateur->appendChild($elt_pseudo);
		    
		    $elt_adrMail    = $doc->createElement('adrMail', $unUtilisateur->getAdrMail());
		    $elt_utilisateur->appendChild($elt_adrMail);
		    
		    $elt_numTel     = $doc->createElement('numTel', $unUtilisateur->getNumTel());
		    $elt_utilisateur->appendChild($elt_numTel);
		    
		    $elt_niveau     = $doc->createElement('niveau', $unUtilisateur->getNiveau());
		    $elt_utilisateur->appendChild($elt_niveau);
		    
		    $elt_dateCreation = $doc->createElement('dateCreation', $unUtilisateur->getDateCreation());
		    $elt_utilisateur->appendChild($elt_dateCreation);
		    
		    $elt_nbTraces   = $doc->createElement('nbTraces', $unUtilisateur->getNbTraces());
		    $elt_utilisateur->appendChild($elt_nbTraces);
		    
		    if ($unUtilisateur->getNbTraces() > 0)
		    {   $elt_dateDerniereTrace = $doc->createElement('dateDerniereTrace', $unUtilisateur->getDateDerniereTrace());
		        $elt_utilisateur->appendChild($elt_dateDerniereTrace);
		    }
		}
	}	
	// Mise en forme finale
	$doc->formatOutput = true;
	
	// renvoie le contenu XML
	return $doc->saveXML();
}

// ================================================================================================

// création du flux JSON en sortie
function creerFluxJSON($msg, $lesUtilisateurs)
{
    /* Exemple de code JSON
        {
            "data": {
                "reponse": "2 utilisateur(s).",
                "donnees": {
                    "lesUtilisateurs": [
                        {
                            "id": "2",
                            "pseudo": "callisto",
                            "adrMail": "delasalle.sio.eleves@gmail.com",
                            "numTel": "22.33.44.55.66",
                            "niveau": "1",
                            "dateCreation": "2018-08-12 19:45:23",
                            "nbTraces": "2",
                            "dateDerniereTrace": "2018-01-19 13:08:48"
                        },
                        {
                            "id": "3",
                            "pseudo": "europa",
                            "adrMail": "delasalle.sio.eleves@gmail.com",
                            "numTel": "22.33.44.55.66",
                            "niveau": "1",
                            "dateCreation": "2018-08-12 19:45:23",
                            "nbTraces": "0"
                        }
                    ]
                }
            }
        }
     */
    

    if (sizeof($lesUtilisateurs) == 0) {
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg];
    }
    else {
        // construction d'un tableau contenant les utilisateurs
        $lesObjetsDuTableau = array();
        foreach ($lesUtilisateurs as $unUtilisateur)
        {	// crée une ligne dans le tableau
            $unObjetUtilisateur = array();
            $unObjetUtilisateur["id"] = $unUtilisateur->getId();
            $unObjetUtilisateur["pseudo"] = $unUtilisateur->getPseudo();
            $unObjetUtilisateur["adrMail"] = $unUtilisateur->getAdrMail();
            $unObjetUtilisateur["numTel"] = $unUtilisateur->getNumTel();
            $unObjetUtilisateur["niveau"] = $unUtilisateur->getNiveau();
            $unObjetUtilisateur["dateCreation"] = $unUtilisateur->getDateCreation();
            $unObjetUtilisateur["nbTraces"] = $unUtilisateur->getNbTraces();
            if ($unUtilisateur->getNbTraces() > 0)
            {   $unObjetUtilisateur["dateDerniereTrace"] = $unUtilisateur->getDateDerniereTrace();
            }
            $lesObjetsDuTableau[] = $unObjetUtilisateur;
        }
        // construction de l'élément "lesUtilisateurs"
        $elt_utilisateur = ["lesUtilisateurs" => $lesObjetsDuTableau];
        
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg, "donnees" => $elt_utilisateur];
    }
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_PRETTY_PRINT);
}

// ================================================================================================
?>
