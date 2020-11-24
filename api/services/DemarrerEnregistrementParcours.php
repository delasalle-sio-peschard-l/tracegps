<?php
// connexion du serveur web à la base MySQL
$dao = new DAO();
define('TIMEZONE', 'Europe/Paris');
date_default_timezone_set(TIMEZONE);


// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdpSha1 = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

// "xml" par défaut si le paramètre lang est absent ou incorrect
if ($lang != "json") $lang = "xml";

$uneTrace = null;
// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	
    $msg = "Erreur : méthode HTTP incorrecte.";
    $code_reponse = 406;
}
else {
    // Les paramètres doivent être présents
    if ( $pseudo == "" || $mdpSha1 == ""  || $lang == "") {
        $msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else
    {	
        if ( $dao->getNiveauConnexion($pseudo, $mdpSha1) <= 0) {
            $msg = "Erreur : authentification incorrecte.";
            $code_reponse = 401;
        }
        else
        {	
            $utilisateur = $dao->getUnUtilisateur($pseudo);
            $msg = "Trace créée.";
            $code_reponse = 300;
            $uneTrace = new Trace($utilisateur->getNbTraces(), strftime('%Y-%m-%d %H:%M:%S'), null, 0, $utilisateur->getId());	
        }
    }
}
// ferme la connexion à MySQL :
unset($dao);
// création du flux en sortie
if ($lang == "xml") {
    $content_type = "application/xml; charset=utf-8";      // indique le format XML pour la réponse
    $donnees = creerFluxXML($msg, $uneTrace);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON($msg, $uneTrace);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);

// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================

// création du flux XML en sortie
function creerFluxXML($msg, $uneTrace)
{
    /* Exemple de code XML
    <?xml version="1.0" encoding="UTF-8"?>
    <!--Service web DemarrerEnregistrementParcours - BTS SIO - Lycée De La Salle - Rennes-->
    <data>
      <reponse>Trace créée.</reponse>
      <donnees>
        <trace>
          <id>23</id>
          <dateHeureDebut>2018-11-15 16:15:54</dateHeureDebut>
          <terminee>0</terminee>
          <idUtilisateur>3</idUtilisateur>
        </trace>
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
    if($uneTrace != null) {
            // place l'élément 'donnees' dans l'élément 'data'
        $elt_donnees = $doc->createElement('donnees');
        $elt_data->appendChild($elt_donnees);
        
        // place l'élément 'lesUtilisateurs' dans l'élément 'donnees'
        $elt_trace = $doc->createElement('trace');
        $elt_donnees->appendChild($elt_trace);
        
        $elt_id = $doc->createElement('id', $uneTrace->getId());
        $elt_trace->appendChild($elt_id);
        
        $elt_dateHeureDebut = $doc->createElement('dateHeureDebut', $uneTrace->getDateHeureDebut());
        $elt_trace->appendChild($elt_dateHeureDebut);
        $elt_terminee = $doc->createElement('terminee', $uneTrace->getTerminee());
        $elt_trace->appendChild($elt_terminee);
        $elt_idUtilisateur = $doc->createElement('idUtilisateur', $uneTrace->getIdUtilisateur());
        $elt_trace->appendChild($elt_idUtilisateur);
    }
    // Mise en forme finale
    $doc->formatOutput = true;
    
    // renvoie le contenu XML
    return $doc->saveXML();
}

// ================================================================================================

// création du flux JSON en sortie
function creerFluxJSON($msg, $uneTrace)
{
    /* Exemple de code JSON
     {
    "data": {
        "reponse": "Trace créée.",
        "donnees": {
            "trace": {
                "id": "23",
                "dateHeureDebut": "2018-11-15 16:15:54",
                "terminee: "0",
                "idUtilisateur: "3"
                }
            }
        }
    }

     */
    if ($uneTrace == null) {
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg];
    }
    else {
        $laTrace = array();
        $laTrace["id"] = $uneTrace->getId();
        $laTrace["dateHeureDebut"] = $uneTrace->getDateHeureDebut();
        $laTrace["terminee"] = $uneTrace->getTerminee();
        $laTrace["idUtilisateur"] = $uneTrace->getIdUtilisateur();
        $elt_trace = ["trace" => $laTrace];
        
        
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg, "donnees" => $elt_trace];
    }
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_PRETTY_PRINT);
}

// ================================================================================================

