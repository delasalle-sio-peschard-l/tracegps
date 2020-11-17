<?php
// Projet TraceGPS - services web
// fichier :  api/services/DemanderMdp.php
// Dernière mise à jour : 3/7/2019 par Jim

// Les paramètres doivent être passés par la méthode GET :
//     http://<hébergeur>/tracegps/api/ChangerDeMdppseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&nouveauMdp=123&confirmationMdp=123&lang=xml

// connexion du serveur web à la base MySQL
$dao = new DAO();
	
// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

// "xml" par défaut si le paramètre lang est absent ou incorrect
if ($lang != "json") $lang = "xml";

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	$msg = "Erreur : méthode HTTP incorrecte.";
    $code_reponse = 406;
}
else {
    if ( $pseudo == "") {
        $msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else{
    // contrôle d'existence de pseudo
        if (!$dao->existePseudoUtilisateur($pseudo))
    	{  $msg = "Erreur : pseudo inexistant.";
    	   $code_reponse = 400;
    	}
    	else {
    	    // création d'un mot de passe aléatoire de 8 caractères
    	    $password = Outils::creerMdp();
    	    // enregistrement du mdp dans la BDD
    	    $ok = $dao->modifierMdpUtilisateur($pseudo, $password);
    	    if ( ! $ok ) {
    	        $msg = "Erreur : problème lors de l'enregistrement.";
    	        $code_reponse = 500;
    	    }
    	    else {
    	        // envoi d'un mail de confirmation de l'enregistrement
    	        $sujet = "Demande mot de passe";
    	        $contenuMail = "Vous venez de demander un nouveau mot de passe.\n\n";
    	        $contenuMail .= "Les données enregistrées sont :\n\n";
    	        $contenuMail .= "Votre pseudo : " . $pseudo . "\n";
    	        $contenuMail .= "Votre mot de passe : " . $password . " (nous vous conseillons de le changer lors de la prochaine connexion)\n";
    	        
    	        // cette variable globale est définie dans le fichier modele/parametres.php
    	        global $ADR_MAIL_EMETTEUR;
    	        $adrMail = $dao->getUnUtilisateur($pseudo)->getAdrMail();
    	        $ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
    	        if ( ! $ok ) {
    	            // l'envoi de mail a échoué
    	            $msg = "Enregistrement effectué ; l'envoi du courriel à l'utilisateur a rencontré un problème.";
    	            $code_reponse = 500;
    	        }
    	        else {
    	            // tout a bien fonctionné
    	            $msg = "Enregistrement effectué ; vous allez recevoir un courriel avec votre mot de passe.";
    	            $code_reponse = 201;
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
    $donnees = creerFluxXML ($msg);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON ($msg);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);

// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================

// création du flux XML en sortie
function creerFluxXML($msg)
{	
    /* Exemple de code XML
        <?xml version="1.0" encoding="UTF-8"?>
        <!--Service web ChangerDeMdp - BTS SIO - Lycée De La Salle - Rennes-->
        <data>
            <reponse>Erreur : authentification incorrecte.</reponse>
        </data>
     */
    
    // crée une instance de DOMdocument (DOM : Document Object Model)
	$doc = new DOMDocument();
	
	// specifie la version et le type d'encodage
	$doc->version = '1.0';
	$doc->encoding = 'UTF-8';
	
	// crée un commentaire et l'encode en UTF-8
	$elt_commentaire = $doc->createComment('Service web ChangerDeMdp - BTS SIO - Lycée De La Salle - Rennes');
	// place ce commentaire à la racine du document XML
	$doc->appendChild($elt_commentaire);
	
	// crée l'élément 'data' à la racine du document XML
	$elt_data = $doc->createElement('data');
	$doc->appendChild($elt_data);
	
	// place l'élément 'reponse' juste après l'élément 'data'
	$elt_reponse = $doc->createElement('reponse', $msg);
	$elt_data->appendChild($elt_reponse);
	
	// Mise en forme finale
	$doc->formatOutput = true;
	
	// renvoie le contenu XML
	return $doc->saveXML();
}

// ================================================================================================

// création du flux JSON en sortie
function creerFluxJSON($msg)
{
    /* Exemple de code JSON
         {
             "data": {
                "reponse": "Erreur : authentification incorrecte."
             }
         }
     */
    
    // construction de l'élément "data"
    $elt_data = ["reponse" => $msg];
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_PRETTY_PRINT);
}

// ================================================================================================
?>
