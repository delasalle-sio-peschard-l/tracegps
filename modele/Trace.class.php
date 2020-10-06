<?php
// Projet TraceGPS
// fichier : modele/Trace.class.php
// Rôle : la classe Trace représente une trace ou un parcours
// Dernière mise à jour : 9/9/2019 par JM CARTRON

include_once ('PointDeTrace.class.php');

class Trace
{
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------- Attributs privés de la classe -------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    private $id;				// identifiant de la trace
    private $dateHeureDebut;		// date et heure de début
    private $dateHeureFin;		// date et heure de fin
    private $terminee;			// true si la trace est terminée, false sinon
    private $idUtilisateur;		// identifiant de l'utilisateur ayant créé la trace
    private $lesPointsDeTrace;		// la collection (array) des objets PointDeTrace formant la trace
    
    
    // ------------------------------------------------------------------------------------------------------
    // ----------------------------------------- Constructeur -----------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function __construct($unId, $uneDateHeureDebut, $uneDateHeureFin, $terminee, $unIdUtilisateur) {

        $this -> lesPointsDeTrace=array();
        $this -> id = $unId;
        $this -> dateHeureDebut = $uneDateHeureDebut;
        $this -> dateHeureFin = $uneDateHeureFin;
        $this -> terminee = $terminee;
        $this -> idUtilisateur = $unIdUtilisateur;

    }
    
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Getters et Setters ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function getId() {return $this->id;}
    public function setId($unId) {$this->id = $unId;}
    
    public function getDateHeureDebut() {return $this->dateHeureDebut;}
    public function setDateHeureDebut($uneDateHeureDebut) {$this->dateHeureDebut = $uneDateHeureDebut;}
    
    public function getDateHeureFin() {return $this->dateHeureFin;}
    public function setDateHeureFin($uneDateHeureFin) {$this->dateHeureFin= $uneDateHeureFin;}
    
    public function getTerminee() {return $this->terminee;}
    public function setTerminee($terminee) {$this->terminee = $terminee;}
    
    public function getIdUtilisateur() {return $this->idUtilisateur;}
    public function setIdUtilisateur($unIdUtilisateur) {$this->idUtilisateur = $unIdUtilisateur;}
    
    public function getLesPointsDeTrace() {return $this->lesPointsDeTrace;}
    public function setLesPointsDeTrace($lesPointsDeTrace) {$this->lesPointsDeTrace = $lesPointsDeTrace;}
    
        
    // Fournit une chaine contenant toutes les données de l'objet
    public function toString() {
        $msg = "Id : " . $this->getId() . "<br>";
        $msg .= "Utilisateur : " . $this->getIdUtilisateur() . "<br>";
        if ($this->getDateHeureDebut() != null) {
            $msg .= "Heure de début : " . $this->getDateHeureDebut() . "<br>";
        }
        if ($this->getTerminee()) {
            $msg .= "Terminée : Oui  <br>";
        }
        else {
            $msg .= "Terminée : Non  <br>";
        }
        $msg .= "Nombre de points : " . $this->getNombrePoints() . "<br>";
        if ($this->getNombrePoints() > 0) {
            if ($this->getDateHeureFin() != null) {
                $msg .= "Heure de fin : " . $this->getDateHeureFin() . "<br>";
            }
            $msg .= "Durée en secondes : " . $this->getDureeEnSecondes() . "<br>";
            $msg .= "Durée totale : " . $this->getDureeTotale() . "<br>";
            $msg .= "Distance totale en Km : " . $this->getDistanceTotale() . "<br>";
            $msg .= "Dénivelé en m : " . $this->getDenivele() . "<br>";
            $msg .= "Dénivelé positif en m : " . $this->getDenivelePositif() . "<br>";
            $msg .= "Dénivelé négatif en m : " . $this->getDeniveleNegatif() . "<br>";
            $msg .= "Vitesse moyenne en Km/h : " . $this->getVitesseMoyenne() . "<br>";
            $msg .= "Centre du parcours : " . "<br>";
            $msg .= "   - Latitude : " . $this->getCentre()->getLatitude() . "<br>";
            $msg .= "   - Longitude : "  . $this->getCentre()->getLongitude() . "<br>";
            $msg .= "   - Altitude : " . $this->getCentre()->getAltitude() . "<br>";
        }
        return $msg;
    }
    
    public function getNombrePoints(){
        $nombre = sizeof($this->lesPointsDeTrace);
        return $nombre;    
    }
    
    public function getCentre() {
        if($this->getNombrePoints() == 0) return null;
        
        else{
            $lePremierPoint = $this->lesPointsDeTrace[0];
            $latitudeMini = $lePremierPoint->getLatitude();
            $latitudeMaxi = $lePremierPoint->getLatitude();
            $longitudeMini = $lePremierPoint->getLongitude();
            $longitudeMaxi = $lePremierPoint->getLongitude();
            
            for($i = 1; $i < $this->getNombrePoints() ; $i++){
                
                $lePoint = $this->lesPointsDeTrace[$i];
                if ($lePoint->getLatitude() < $latitudeMini) $latitudeMini = $lePoint->getLatitude();
                if ($lePoint->getLatitude() > $latitudeMaxi) $latitudeMaxi = $lePoint->getLatitude();
                if ($lePoint->getLongitude() < $longitudeMini) $longitudeMini = $lePoint->getLongitude();
                if ($lePoint->getLongitude() > $longitudeMaxi) $longitudeMaxi = $lePoint->getLongitude();  
            }
            $latitudeCentre = ($latitudeMini + $latitudeMaxi) / 2;
            $longitudeCentre = ($longitudeMini + $longitudeMaxi) / 2;
            $leCentre = new Point($latitudeCentre, $longitudeCentre, 0);
            return $leCentre;     
        }
    }
    
    public function getDenivele()
    {
        if($this->getNombrePoints() == 0) return 0;
            else
            {
                
                $lePremierPoint = $this->lesPointsDeTrace[0];
                $altitudeMini = $lePremierPoint->getAltitude();
                $altitudeMaxi = $lePremierPoint->getAltitude();
                
                for ($i = 1; $i < $this->getNombrePoints() ; $i++)
                {
                    $lePoint = $this->lesPointsDeTrace[$i];
                    if ($lePoint->getAltitude() < $altitudeMini) $altitudeMini = $lePoint->getAltitude();
                    if ($lePoint->getAltitude() > $altitudeMaxi) $altitudeMaxi = $lePoint->getAltitude();
                }
                $denivele = $altitudeMaxi - $altitudeMini;
                return $denivele;
            }
    }
    
    public function getDureeEnSecondes()
    {
        if($this->getNombrePoints() == 0) return 0;
        
        $duree = strtotime($this->getDateHeureFin()) - strtotime($this->getDateHeureDebut());
        return $duree;
    }
    
    public function getDureeTotale()
    {
        $heuredebut = date('H',strtotime($this->getDateHeureDebut()));
        $heurefin = date('H',strtotime($this->getDateHeureFin()));
        
        $minutedebut = date('i',strtotime($this->getDateHeureDebut()));
        $minutefin = date('i',strtotime($this->getDateHeureFin()));
        
        $secondesdebut = date('s',strtotime($this->getDateHeureDebut()));
        $secondesfin = date('s',strtotime($this->getDateHeureFin()));
        
        $heuretotal = $heurefin - $heuredebut;
        $minutetotal = $minutefin - $minutedebut;
        $secondestotal = $secondesfin - $secondesdebut;
        
        return $heuretotal.":".$minutetotal.":".$secondestotal;
        
    }
    
    public function getDistanceTotale()
    {
        if($this->getNombrePoints() == 0) return 0;
        
        $dernierPoint = $this->lesPointsDeTrace[$this->getNombrePoints() - 1];
        $distance = $dernierPoint->getDistanceCumulee();
        return $distance;
    }
    
    public function getDenivelePositif()
    {
        if($this->getNombrePoints() == 0) return 0;
        
        else
        {
            $denivele = 0;
            
            for($i = 1; $i < $this->getNombrePoints() ; $i++){
                $lePoint1 = $this->lesPointsDeTrace[$i - 1];
                $lePoint2 = $this->lesPointsDeTrace[$i];
                
                if ($lePoint2->getAltitude() > $lePoint1->getAltitude())
                    $denivele = $denivele + ($lePoint2->getAltitude() - $lePoint1->getAltitude());
            }
            return $denivele;
        }
    }
    
    public function getDeniveleNegatif()
    {
        
        if($this->getNombrePoints() == 0) return 0;
        
        else
        {
           $denivele = 0;
            
           for($i = 1; $i < $this->getNombrePoints() ; $i++){
               $lePoint1 = $this->lesPointsDeTrace[$i - 1];
               $lePoint2 = $this->lesPointsDeTrace[$i];
                
                if ($lePoint2->getAltitude() < $lePoint1->getAltitude())
                    $denivele = $denivele + ($lePoint1->getAltitude() - $lePoint2->getAltitude());
            }
            return $denivele;
        }
    }
    
    public function getVitesseMoyenne()
    {
        if($this->getNombrePoints() == 0) return 0;
        
        $distance = $this->getDistanceTotale();
        $temps = $this->getDureeEnSecondes() / 3600;
        return $distance / $temps;
    }
    
    public function ajouterPoint($leNouveauPoint)
    {
        
        if($this->getNombrePoints() == 0)
        {
            $leNouveauPoint->setDistanceCumulee(0);
            $leNouveauPoint->setTempsCumule(0);
            $leNouveauPoint->setVitesse(0);
        }
        else
        {
            $leDernierPoint = $this->lesPointsDeTrace[$this->getNombrePoints() - 1];
            
            $tps = strtotime($leNouveauPoint->getDateHeure()) - strtotime($leDernierPoint->getDateHeure());
            
            
            $leNouveauPoint->setDistanceCumulee($leDernierPoint->getDistanceCumulee() + Point::getDistance($leDernierPoint, $leNouveauPoint));
            $leNouveauPoint->setTempsCumule($leDernierPoint->getTempsCumule() + $tps);
            $leNouveauPoint->setVitesse(Point::getDistance($leDernierPoint, $leNouveauPoint) / ($tps/3600));
            
            
        }
        $this->lesPointsDeTrace[] = $leNouveauPoint;
    }
    
    public function viderListePoints()
    {
        $this->lesPointsDeTrace[] = null;
    }
    
} // fin de la classe Trace

// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!
?>