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
$idTrace = ( empty($this->request['idTrace'])) ? "" : $this->request['idTrace'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

// "xml" par défaut si le paramètre lang est absent ou incorrect
if ($lang != "json") $lang = "xml";

$id = 0;

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	$msg = "Erreur : méthode HTTP incorrecte.";
    $code_reponse = 406;
}
else {
    // Les paramètres doivent être présents
    if ( $pseudo == "" || $mdpSha1 == "" || $idTrace == "")
    {	$msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else
    {	if ( $dao->getNiveauConnexion($pseudo, $mdpSha1) == 0 ) {
    		$msg = "Erreur : authentification incorrecte.";
    		$code_reponse = 401;
        }
    	else 
    	{	$idutilisateur = $dao->getUnUtilisateur($pseudo)->getId();
    	    $uneTrace = $dao->getUneTrace($idTrace);
    	
    	    if ($dao->getUneTrace($idTrace) == null) {
    			$msg = "Parcours inexistant.";
    			$code_reponse = 401;
    	    }
    	    elseif ( $uneTrace->getIdUtilisateur() != $idutilisateur ) 
    	    {
    	        $msg = "Vous n'êtes pas le propriétaire de ce parcours";
    	        $code_reponse = 401;
    	    }
    	    else
    	    {           	        
    	        
    	        $ok = $dao->supprimerUneTrace($idTrace);
    	        if($ok == $idTrace)
    	        {
    	            $msg = "Point supprimé.";
    	            $code_reponse = 200;
    	        }
    	        else
    	        {
    	            $msg = "Erreur : problème lors de la suppression du parcours";
    	            $code_reponse = 401;
    	        }
    	    }
    	}
    }
}
// ferme la connexion à MySQL :
unset($dao);

// création du flux en sortie
if ($lang == "xml") {
    $content_type = "application/xml; charset=utf-8";      // indique le format XML pour la réponse
    $donnees = creerFluxXML($msg,$id);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON($msg,$id);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);

// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================
 
// création du flux XML en sortie
function creerFluxXML($msg,$id)
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
	$elt_commentaire = $doc->createComment('Service web EnvoyerPosition - BTS SIO - Lycée De La Salle - Rennes');
	// place ce commentaire à la racine du document XML
	$doc->appendChild($elt_commentaire);
	
	// crée l'élément 'data' à la racine du document XML
	$elt_data = $doc->createElement('data');
	$doc->appendChild($elt_data);
	
	// place l'élément 'reponse' dans l'élément 'data'
	$elt_reponse = $doc->createElement('reponse', $msg);
	$elt_data->appendChild($elt_reponse);
	
	    

	// Mise en forme finale
	$doc->formatOutput = true;
	
	// renvoie le contenu XML
	return $doc->saveXML();
}

// ================================================================================================

// création du flux JSON en sortie
function creerFluxJSON($msg,$id)
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
    

        $elt_data = ["reponse" => $msg];

    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_PRETTY_PRINT);
}

// ================================================================================================
?>
