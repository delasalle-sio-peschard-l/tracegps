<?php
// Projet TraceGPS
// fichier : modele/Utilisateur.test.php
// Rôle : test de la classe Utilisateur.class.php
// Dernière mise à jour : 18/7/2018 par JM CARTRON

include_once ('Utilisateur.class.php');

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Test de la classe Utilisateur</title>
	<style type="text/css">body {font-family: Arial, Helvetica, sans-serif; font-size: small;}</style>
</head>
<body>

<?php
echo "<h3>Test de la classe Utilisateur</h3>";
 
// tests du constructeur et des accesseurs (get)
$utilisateur1 = new Utilisateur(1, "Tess Tuniter", sha1("mdputilisateur"), "tess.tuniter@gmail.com", "1122334455", 1, date('Y-m-d H:i:s', time()), 0, null);

echo "<h4>objet utilisateur1 : </h4>";
echo ('id : ' . $utilisateur1->getId() . '<br>');
echo ('pseudo : ' . $utilisateur1->getPseudo() . '<br>');
echo ('mdpSha1 : ' . $utilisateur1->getMdpSha1() . '<br>');
echo ('adrMail : ' . $utilisateur1->getAdrMail() . '<br>');
echo ('numTel : ' . $utilisateur1->getNumTel() . '<br>');
echo ('niveau : ' . $utilisateur1->getNiveau() . '<br>');
echo ('dateCreation : ' . $utilisateur1->getDateCreation() . '<br>');
echo ("nbtraces : " . $utilisateur1->getNbTraces() . '<br>');
echo ('dateDerniereTrace : ' . $utilisateur1->getDateDerniereTrace() . '<br>');

echo ('<br>');

// test de la méthode toString
echo "<h4>méthode toString sur objet utilisateur1 : </h4>";
echo ($utilisateur1->toString());
echo ('<br>');

// tests des mutateurs (set)
$utilisateur1->setId(4);
$utilisateur1->setPseudo("Sophie Fonfec");
$utilisateur1->setMdpSha1(sha1("mdpadmin"));
$utilisateur1->setAdrMail("sophie.fonfec@gmail.com");
$utilisateur1->setNumTel("4455667788");
$utilisateur1->setNiveau(2);
$utilisateur1->setDateCreation(date('Y-m-d H:i:s', time() - 7200));
$utilisateur1->setNbTraces(2);
$utilisateur1->setDateDerniereTrace(date('Y-m-d H:i:s', time() - 3600));

echo "<h4>objet utilisateur1 : </h4>";
echo ('id : ' . $utilisateur1->getId() . '<br>');
echo ('pseudo : ' . $utilisateur1->getPseudo() . '<br>');
echo ('mdpSha1 : ' . $utilisateur1->getMdpSha1() . '<br>');
echo ('adrMail : ' . $utilisateur1->getAdrMail() . '<br>');
echo ('numTel : ' . $utilisateur1->getNumTel() . '<br>');
echo ('niveau : ' . $utilisateur1->getNiveau() . '<br>');
echo ('dateCreation : ' . $utilisateur1->getDateCreation() . '<br>');
echo ("nbtraces : " . $utilisateur1->getNbTraces() . '<br>');
echo ('dateDerniereTrace : ' . $utilisateur1->getDateDerniereTrace() . '<br>');
echo ('<br>');
?>

</body>
</html>