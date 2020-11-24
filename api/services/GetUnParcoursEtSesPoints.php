<?php
// connexion du serveur web à la base MySQL
$dao = new DAO();

// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdpSha1 = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];
$idTrace = ( empty($this->request['idTrace'])) ? "" : $this->request['idTrace'];

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
    if ( $pseudo == "" || $mdpSha1 == ""  || $lang == "" || $idTrace == "" ) {
        $msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else
    {	if ( $dao->getNiveauConnexion($pseudo, $mdpSha1) == 0 ) {
            $msg = "Erreur : authentification incorrecte.";
            $code_reponse = 401;
        }
        else
        {	
            $uneTrace = $dao->getUneTrace($idTrace);
            if ( $uneTrace == null ) {
                $msg = "Erreur : parcours inexistant.";
                $code_reponse = 404;
            }
            else
            {	$utilisateurAutorise= $dao->getUnUtilisateur($pseudo);
                $idUtilisateurAutorisant = $uneTrace->getIdUtilisateur();
                if ($dao->autoriseAConsulter($idUtilisateurAutorisant, $utilisateurAutorise->getId()) == false) {
                    $msg = "Erreur : vous n'êtes pas autorisé par le propriétaire du parcours.";
                    $code_reponse = 403;
                    $uneTrace = null;
                }
                else
                {	
                    $msg = "Données de la trace demandée.";
                    $code_reponse = 300;
                    $uneTrace = $dao->getUneTrace($idTrace);
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
    <!--Service web GetUnParcoursEtSesPoints - BTS SIO - Lycée De La Salle - Rennes-->
    <data>
      <reponse>Données de la trace demandée.</reponse>
      <donnees>
        <trace>
          <id>2</id>
          <dateHeureDebut>2018-01-19 13:08:48</dateHeureDebut>
          <terminee>1</terminee>
          <dateHeureFin>2018-01-19 13:11:48</dateHeureFin>
          <idUtilisateur>2</idUtilisateur>
        </trace>
        <lesPoints>
          <point>
            <id>1</id>
            <latitude>48.2109</latitude>
            <longitude>-1.5535</longitude>
            <altitude>60</altitude>
            <dateHeure>2018-01-19 13:08:48</dateHeure>
            <rythmeCardio>81</rythmeCardio>
          </point>
           .....................................................................................................
          <point>
            <id>10</id>
            <latitude>48.2199</latitude>
            <longitude>-1.5445</longitude>
            <altitude>150</altitude>
            <dateHeure>2018-01-19 13:11:48</dateHeure>
            <rythmeCardio>90</rythmeCardio>
          </point>
        </lesPoints>
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
        $elt_dateHeureFin = $doc->createElement('dateHeureFin', $uneTrace->getDateHeureFin());
        $elt_trace->appendChild($elt_dateHeureFin);
        $elt_idUtilisateur = $doc->createElement('idUtilisateur', $uneTrace->getIdUtilisateur());
        $elt_trace->appendChild($elt_idUtilisateur );

        $lesPoints = $uneTrace->getLesPointsDeTrace();
        $elt_LesPoints = $doc->createElement('lesPoints');
        $elt_donnees->appendChild($elt_LesPoints);
        foreach($lesPoints as $unPoint) {
            //Creation d'un point
            $elt_point = $doc->createElement('point');
            $elt_LesPoints->appendChild($elt_point);
            
            //id du point
            $elt_idPoint = $doc->createElement('id', $unPoint->getId());
            $elt_point->appendChild($elt_idPoint);
            //latitude du point
            $elt_latitudePoint = $doc->createElement('Latitude', $unPoint->getLatitude());
            $elt_point->appendChild($elt_latitudePoint);
            //longitude du point
            $elt_longitudePoint = $doc->createElement('Longitude', $unPoint->getLongitude());
            $elt_point->appendChild($elt_longitudePoint);
            //altitude du point
            $elt_altitudePoint = $doc->createElement('Altitude', $unPoint->getAltitude());
            $elt_point->appendChild($elt_altitudePoint);
            //dateHeure du point
            $elt_DateHeurePoint = $doc->createElement('DateHeure', $unPoint->getDateHeure());
            $elt_point->appendChild($elt_DateHeurePoint);
            //rythme cardiaque du point
            $elt_RythmeCardioPoint = $doc->createElement('RythmeCardio', $unPoint->getRythmeCardio());
            $elt_point->appendChild($elt_RythmeCardioPoint);
        } 
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
                "reponse": "Données de la trace demandée.",
                "donnees": {
                    "trace": {
                        "id": "2",
                        "dateHeureDebut": "2018-01-19 13:08:48",
                        "terminee: "1",
                        "dateHeureFin: "2018-01-19 13:11:48",
                        "idUtilisateur: "2"
                    }
                    "lesPoints": [
                        {
                            "id": "1",
                            "latitude": "48.2109",
                            "longitude": "-1.5535",
                            "altitude": "60",
                            "dateHeure": "2018-01-19 13:08:48",
                            "rythmeCardio": "81"
                        },
                        ..................................
                        {
                            "id"10</id>,
                            "latitude": "48.2199",
                            "longitude": "-1.5445",
                            "altitude": "150",
                            "dateHeure": "2018-01-19 13:11:48",
                            "rythmeCardio": "90"
                        }
                    ]
                }
            }
        }

     */
    //PAS FINIS
    if ($uneTrace == null) {
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg];
    }
    else {
        // construction d'un tableau contenant les utilisateurs
        $lesPoints = $uneTrace->getLesPointsDeTrace();
        $lesObjetsDuTableau = array();
        
        $laTrace = array();
        $laTrace["id"] = $uneTrace->getId();
        $laTrace["dateHeureDebut"] = $uneTrace->getDateHeureDebut();
        $laTrace["terminee"] = $uneTrace->getTerminee();
        $laTrace["dateHeureFin"] = $uneTrace->getDateHeureFin();
        $laTrace["idUtilisateur"] = $uneTrace->getIdUtilisateur();
        $elt_trace = ["trace" => $laTrace];
        
        foreach ($lesPoints as $unPoint)
        {	// crée une ligne dans le tableau
            $unObjetPoint = array();
            $unObjetPoint["id"] = $unPoint->getId();
            $unObjetPoint["Latitude"] = $unPoint->getLatitude();
            $unObjetPoint["Longitude"] = $unPoint->getLongitude();
            $unObjetPoint["Altitude"] = $unPoint->getAltitude();
            $unObjetPoint["DateHeure"] = $unPoint->getDateHeure();
            $unObjetPoint["RythmeCardio"] = $unPoint->getRythmeCardio();
            $lesObjetsDuTableau[] = $unObjetPoint;
        }
        // construction de l'élément "lesUtilisateurs"
        $elt_utilisateur = ["LesPoints" => $lesObjetsDuTableau];
        
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg, "donnees" => $elt_trace + $elt_utilisateur];
    }
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_PRETTY_PRINT);
}

// ================================================================================================

