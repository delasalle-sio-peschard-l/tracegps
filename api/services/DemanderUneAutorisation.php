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
$mdp = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$pseudoDestinataire = ( empty($this->request['pseudoDestinataire'])) ? "" : $this->request['pseudoDestinataire'];
$texteMessage = ( empty($this->request['texteMessage'])) ? "" : $this->request['texteMessage'];
$nomPrenom = ( empty($this->request['nomPrenom'])) ? "" : $this->request['nomPrenom'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

// "xml" par défaut si le paramètre lang est absent ou incorrect
if ($lang != "json") $lang = "xml";

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	$msg = "Erreur : méthode HTTP incorrecte.";
    $code_reponse = 406;
}
else {
    if ( $pseudo == "" || $mdp == "" || $pseudoDestinataire == "" || $texteMessage == "" || $nomPrenom == "") {
        $msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else{
        if ( $dao->getNiveauConnexion($pseudo, $mdp) == 0 ) {
            $msg = "Erreur : authentification incorrecte.";
            $code_reponse = 401;
        }
        else{
            // contrôle d'existence de pseudo demandé
            if (!$dao->existePseudoUtilisateur($pseudoDestinataire))
        	{  $msg = "Erreur : pseudo destinataire inexistant.";
        	   $code_reponse = 400;
        	}

    	    else {
    	        $utilisateur = $dao->getUnUtilisateur($pseudo);
    	        $utilisateurDestinataire = $dao->getUnUtilisateur($pseudoDestinataire);
    	        
    	        // envoi d'un mail de confirmation de l'enregistrement
    	        $sujet = "Demande d'autorisation de la part d'un utilisateur du sustème TraceGPS";
    	        $contenuMail = "Cher ou chère ". $pseudoDestinataire ."\n\n";
    	        $contenuMail .= "Un utilisateur du système TraceGPS vous demande l'autorisation de suivre vos parcours.\n\n";
    	        $contenuMail .= "Voici les données le concernant :\n\n";
    	        
    	        $contenuMail .= "Son pseudo : " . $pseudo . "\n";
    	        $contenuMail .= "Son adresse mail : " . $utilisateur->getAdrMail() . "\n";
    	        $contenuMail .= "Son numéro de téléphone : " . $utilisateur->getNumTel() . "\n";
    	        $contenuMail .= "Son nom et prénom : " . $nomPrenom . "\n";
    	        $contenuMail .= "Son message : " . $texteMessage . "\n\n";
    	        
    	        $contenuMail .= "Pour accepter la demande, cliquez sur ce lien :\n";
    	        $contenuMail .= "http://localhost/ws-php-peschard/tracegps/api/ValiderDemandeAutorisation?a=".$mdp."&b=".$pseudo."&c=".$pseudoDestinataire."&d=1\n\n";
    	        
    	        $contenuMail .= "Pour refuser la demande, cliquez sur ce lien :\n";
    	        $contenuMail .= "http://localhost/ws-php-peschard/tracegps/api/ValiderDemandeAutorisation?a=".$mdp."&b=".$pseudo."&c=".$pseudoDestinataire."&d=0\n\n";
    	        
    	        // cette variable globale est définie dans le fichier modele/parametres.php
    	        global $ADR_MAIL_EMETTEUR;
    	        $adrMail = $utilisateurDestinataire->getAdrMail();
    	        $ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
    	        if ( ! $ok ) {
    	            // l'envoi de mail a échoué
    	            $msg = "Erreur : : l'envoi du courriel de demande d'autorisation a rencontré un problème.";
    	            $code_reponse = 500;
    	        }
    	        else {
    	            // tout a bien fonctionné
    	            $msg = $pseudoDestinataire." va recevoir un courriel avec votre demande";
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
