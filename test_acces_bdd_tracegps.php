<?php
// Projet tracegps
// fichier : test_acces_bdd_tracegps.php
// Rôle : test de l'accès à la base de données tracegps sur le serveur MySql en localhost
// Dernière mise à jour : 12/7/2018 par JM CARTRON
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Test de l'accès à la base de données tracegps sur le serveur MySql en localhost</title>
	<style type="text/css">body {font-family: Arial, Helvetica, sans-serif; font-size: small;}</style>
</head>
<body>

<?php
$PARAM_HOTE = "localhost";		// si le sgbd est sur la même machine que le serveur php
$PARAM_PORT = "3306";			// le port utilisé par le serveur MySql
$PARAM_BDD = "tracegps_lmm";		// nom de la base de données
$PARAM_USER = "tracegps";		// nom de l'utilisateur
$PARAM_PWD = "spgecart";		// son mot de passe

try
{	$cnx = new PDO ("mysql:host=" . $PARAM_HOTE . ";port=" . $PARAM_PORT . ";dbname=" . $PARAM_BDD,
    $PARAM_USER,
    $PARAM_PWD);
echo ("Connexion réussie à la base de données tracegps <br>");
}
catch (Exception $ex)
{	echo ("Echec de la connexion à la base de données tracegps <br>");
    echo ("Erreur numero : " . $ex->getCode() . "<br />" . "Description : " . utf8_encode($ex->getMessage()) . "<br>");
}
unset($cnx);
?>

</body>
</html>