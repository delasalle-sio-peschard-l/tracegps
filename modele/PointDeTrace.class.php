<?php
// Projet TraceGPS
// fichier : modele/PointDeTrace.class.php
// Rôle : la classe PointDeTrace représente un point de passage sur un parcours
// Dernière mise à jour : 9/9/2019 par JM CARTRON

include_once ('Point.class.php');

class PointDeTrace extends Point
{
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------- Attributs privés de la classe -------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    private $idTrace;		// identifiant de la trace
    private $id;			// identifiant relatif du point dans la trace
    private $dateHeure;		// date et heure du passage au point
    private $rythmeCardio;	// rythme cardiaque (en bpm : battements par minute)
    private $tempsCumule;	// temps cumulé depuis le départ (en secondes)
    private $distanceCumulee;	// distance cumulée depuis le départ (en Km)
    private $vitesse;		// vitesse instantanée au point de passage (en Km/h)
    
    
    // ------------------------------------------------------------------------------------------------------
    // ----------------------------------------- Constructeur -----------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // Constructeur avec 10 paramètres :
    // $unIdTrace  : identifiant de la trace
    // $unId  : identifiant relatif du point dans la trace
    // $uneLatitude  : latitude du point (en degrés décimaux)
    // $uneLongitude : longitude du point (en degrés décimaux)
    // $uneAltitude  : altitude du point (en mètres)
    // $uneDateHeure : heure de passage au point
    // $unRythmeCardio : rythme cardiaque au passage au point
    // $unTempsCumule : temps cumulé depuis le départ(en secondes)
    // $uneDistanceCumulee : distance cumulée depuis le départ (en Km)
    // $uneVitesse : vitesse instantanée, calculée entre le point précédent et le point suivant (en Km/h)
    public function __construct($unIdTrace, $unID, $uneLatitude, $uneLongitude, $uneAltitude,
        $uneDateHeure, $unRythmeCardio, $unTempsCumule, $uneDistanceCumulee, $uneVitesse) {
            // appelle le constructeur de la classe mère avec 3 paramètres
            parent::__construct($uneLatitude, $uneLongitude, $uneAltitude);
            // initialise les nouveaux attributs
            
            $this -> idTrace = $unIdTrace;
            $this -> id = $unID;
            $this -> dateHeure = $uneDateHeure;
            $this -> rythmeCardio = $unRythmeCardio;
            $this -> tempsCumule = $unTempsCumule;
            $this -> distanceCumulee = $uneDistanceCumulee;
            $this -> vitesse = $uneVitesse;
    }
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Getters et Setters ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function getIdTrace()	{return $this->idTrace;}
    public function setIdTrace($unIdTrace) {$this->idTrace = $unIdTrace;}
    
    public function getId() {return $this->id;}
    public function setId($unId) {$this->id = $unId;}
    
    public function getDateHeure() {return $this->dateHeure;}
    public function setDateHeure($uneDateHeure) {$this->dateHeure = $uneDateHeure;}
    
    public function getRythmeCardio() {return $this->rythmeCardio;}
    public function setRythmeCardio($unRythmeCardio) {$this->rythmeCardio = $unRythmeCardio;}
    
    public function getTempsCumule() {return $this->tempsCumule;}
    public function setTempsCumule($unTempsCumule) {$this->tempsCumule = $unTempsCumule;}
    
    public function getDistanceCumulee() {return $this->distanceCumulee;}
    public function setDistanceCumulee($uneDistanceCumulee) {$this->distanceCumulee = $uneDistanceCumulee;}
    
    public function getVitesse() {return $this->vitesse;}
    public function setVitesse($uneVitesse) {$this->vitesse = $uneVitesse;}
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Méthodes d'instances ----------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // Fournit une chaine contenant toutes les données de l'objet
    public function toString() {
        $msg = "IdTrace : " . $this->getIdTrace() . "<br>";
        $msg .= "Id : " . $this->getId() . "<br>";
        $msg .= parent::toString();
        if ($this->dateHeure != null) {
            $msg .= "Heure de passage : " . $this->dateHeure . "<br>";
        }
        $msg .= "Rythme cardiaque : " . $this->rythmeCardio . "<br>";
        $msg .= "Temps cumule (s) : " . $this->tempsCumule . "<br>";
        $msg .= "Temps cumule (hh:mm:ss) : " . $this->getTempsCumuleEnChaine() . "<br>";
        $msg .= "Distance cumulée (Km) : " . $this->distanceCumulee . "<br>";
        $msg .= "Vitesse (Km/h) : " . $this->vitesse . "<br>";
        return $msg;
    }
    
    // Méthode fournissant le temps cumulé depuis le départ (sous la forme d'une chaine "hh:mm:ss")
    public function getTempsCumuleEnChaine()
    {
        $heures = ($this -> getTempsCumule()) / 3600;
        $reste = ($this -> getTempsCumule()) % 3600;
        $minutes = $reste / 60;
        $secondes = $reste % 60;
        
        return sprintf("%02d",$heures) . ":" . sprintf("%02d",$minutes) . ":" . sprintf("%02d",$secondes);
    }
    
} // fin de la classe PointDeTrace

// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!
