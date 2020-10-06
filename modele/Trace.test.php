<?php
// Projet TraceGPS
// fichier : modele/Trace.test.php
// Rôle : test de la classe Trace.class.php
// Dernière mise à jour : 15/12/2017 par JM CARTRON

// inclusion de la classe Trace
include_once ('Trace.class.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Test de la classe Trace</title>
	<style type="text/css">body {font-family: Arial, Helvetica, sans-serif; font-size: small;}</style>
</head>
<body>

<?php
echo "<h3>Test de la classe Trace</h3>";
 
// création de 2 traces
$trace1 = new Trace(1, "2017-12-11 14:00:00", "2017-12-11 14:10:00", true, 2);		// cette trace va comporter 5 points
$trace2 = new Trace(2, "2017-12-11 16:00:00", null, false, 3);		                // cette trace restera vide

// création de 5 points de trace et ajout à $trace1
$uneDate1 = "2017-12-11 14:00:00";
$point1 = new PointDeTrace(1, 1, 48.500, -1.600, 50, $uneDate1, 80, 0, 0, 0);
$trace1->ajouterPoint($point1);

$uneDate2 = "2017-12-11 14:03:40";
$point2 = new PointDeTrace(1, 2, 48.510, -1.600, 200, $uneDate2, 100, 0, 0, 0);
$trace1->ajouterPoint($point2);

$uneDate3 = "2017-12-11 14:05:20";
$point3 = new PointDeTrace(1, 3, 48.510, -1.610, 150, $uneDate3, 110, 0, 0, 0);
$trace1->ajouterPoint($point3);

$uneDate4 = "2017-12-11 14:08:40";
$point4 = new PointDeTrace(1, 4, 48.500, -1.610, 200, $uneDate4, 120, 0, 0, 0);
$trace1->ajouterPoint($point4);

$uneDate5 = "2017-12-11 14:10:00";
$point5 = new PointDeTrace(1, 5, 48.500, -1.600, 50, $uneDate5, 130, 0, 0, 0);
$trace1->ajouterPoint($point5);


// test de la méthode toString
echo "<h4>méthode toString sur objet trace1 : </h4>";
echo ($trace1->toString());
echo ('<br>');

echo "<h4>méthode toString sur objet trace2 : </h4>";
echo ($trace2->toString());
echo ('<br>');

?>

</body>
</html>