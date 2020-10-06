<?php
// Projet TraceGPS
// fichier : modele/Point.test.php
// Rôle : test de la classe Point.class.php
// Dernière mise à jour : 18/12/2017 par JM CARTRON

// inclusion de la classe Point
include_once ('Point.class.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Test de la classe Point</title>
	<style type="text/css">body {font-family: Arial, Helvetica, sans-serif; font-size: small;}</style>
</head>
<body>

<?php
echo "<h3>Test de la classe Point</h3>";

// appel du constructeur et tests des accesseurs (get)
$uneLatitude = 48.5;
$uneLongitude = -1.6;
$uneAltitude = 50;
$unPoint1 = new Point($uneLatitude, $uneLongitude, $uneAltitude);
 
echo "<h4>objet unPoint1 : </h4>";
echo ("latitude : " . $unPoint1->getLatitude() . "<br>");
echo ("longitude : " . $unPoint1->getLongitude() . "<br>");
echo ("altitude : " . $unPoint1->getAltitude() . "<br>");
echo ('<br>');

// tests des mutateurs (set)
$unPoint2 = new Point(0, 0, 0);
$unPoint2->setLatitude(48.51);
$unPoint2->setLongitude(-1.6);
$unPoint2->setAltitude(200);

echo "<h4>objet unPoint2 : </h4>";
echo ("latitude : " . $unPoint2->getLatitude() . "<br>");
echo ("longitude : " . $unPoint2->getLongitude() . "<br>");
echo ("altitude : " . $unPoint2->getAltitude() . "<br>");
echo ('<br>');

// test de la méthode toString
echo "<h4>méthode toString sur objet unPoint2 : </h4>";
echo ($unPoint2->toString());
echo ('<br>');

// test de la méthode statique getDistance
$distance = Point::getDistance($unPoint1, $unPoint2);
echo "<h4>test de la méthode statique getDistance entre unPoint1 et unPoint2 : </h4>";
echo ("distance : " . $distance . "<br>");
echo ('<br>');

?>

</body>
</html>