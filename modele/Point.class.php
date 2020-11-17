<?php
// Projet TraceGPS
// fichier : modele/Point.class.php
// Rôle : la classe Point représente un point géographique
// Dernière mise à jour : 9/9/2019 par JM CARTRON

class Point
{// ------------------------------------------------------------------------------------------------------
    // ---------------------------------- Attributs protégés de la classe -----------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // protected au lieu de private car cette classe fera l'objet d'un héritage
    protected $latitude;			// latitude
    protected $longitude;		// longitude
    protected $altitude;			// altitude


    // ------------------------------------------------------------------------------------------------------
    // ----------------------------------------- Constructeur -----------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function __construct($uneLatitude, $uneLongitude, $uneAltitude) {
        $this->latitude = $uneLatitude;
        $this->longitude = $uneLongitude;
        $this->altitude = $uneAltitude;
    }
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Getters et Setters ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    public function getLatitude()	{return $this->latitude;}
    public function setLatitude($uneLatitude) {$this->latitude = $uneLatitude;}
    
    public function getLongitude()	{return $this->longitude;}
    public function setLongitude($uneLongitude) {$this->longitude = $uneLongitude;}
    
    public function getAltitude()	{return $this->altitude;}
    public function setAltitude($uneAltitude) {$this->altitude = $uneAltitude;} 
        
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Méthodes d'instances ----------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // Fournit une chaine contenant toutes les données de l'objet
    public function toString() {
        $msg = "latitude : " . $this->latitude . "<br>";
        $msg .= "longitude : " . $this->longitude . "<br>";
        $msg .= "altitude : " . $this->altitude . "<br>";
        return $msg;
    }
    
    // ------------------------------------------------------------------------------------------------------
    // ---------------------------------------- Méthodes statiques ------------------------------------------
    // ------------------------------------------------------------------------------------------------------
    
    // Méthode statique privée
    // calcule la distance (en Km) entre 2 points géographiques passés avec 4 paramètres :
    // $latitude1  : latitude point 1 (en degrés décimaux)
    // $longitude1 : longitude point 1 (en degrés décimaux)
    // $latitude2  : latitude point 2 (en degrés décimaux)
    // $longitude2 : longitude point 2 (en degrés décimaux)
    // fournit     : la distance (en Km) entre les 2 points
    private static function getDistanceBetween ($latitude1, $longitude1, $latitude2, $longitude2) {
        if (abs($latitude1 - $latitude2) < 0.000001 && abs($longitude1 - $longitude2) < 0.000001) return 0;
        try
        {   $a = pi() / 180;
        $latitude1 = $latitude1 * $a;
        $latitude2 = $latitude2 * $a;
        $longitude1 = $longitude1 * $a;
        
        $longitude2 = $longitude2 * $a;
        $t1 = sin($latitude1) * sin($latitude2);
        $t2 = cos($latitude1) * cos($latitude2);
        $t3 = cos($longitude1 - $longitude2);
        $t4 = $t2 * $t3;
        $t5 = $t1 + $t4;
        $rad_dist = atan(-$t5 / sqrt(-$t5 * $t5 + 1)) + 2 * atan(1);
        return ($rad_dist * 3437.74677 * 1.1508) * 1.6093470878864446;
        }
        catch (Exception $ex)
        {	return 0;
        }
    }
    
    // Méthode statique publique
    // calcule la distance (en Km) entre 2 points géographiques passés en paramètres :
    // point1  : le premier point
    // point2  : le second point
    // fournit : la distance (en Km) entre les 2 points
    public static function getDistance (Point $point1, Point $point2) {
        $lat1 = $point1->getLatitude();
        $long1 = $point1->getLongitude();
        $lat2 = $point2->getLatitude();
        $long2 = $point2->getLongitude();
        $distance = Point::getDistanceBetween($lat1, $long1, $lat2, $long2);
        return $distance;
        
        
        
        
        // (il faut appeler la méthode précédente)
    }
    
    
} // fin de la classe Point

// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!