<?php
// Projet TraceGPS
// fichier : modele/Outils.test.php
// Rôle : test de la classe Outils.class.php (il suffit d'afficher cette page dans un navigateur web)
// Dernière mise à jour : 13/7/2018 par JM CARTRON

// inclusion de la classe Outils
include_once ('Outils.class.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Test de la classe Outils</title>
	<style type="text/css">body {font-family: Arial, Helvetica, sans-serif; font-size: small;}</style>
</head>
<body>

<?php
// test de la fonction PHP sha1 avec les 2 mots de passe utilisés pour les tests
echo ('<b>Test de la fonction PHP sha1 : </b><br>');
echo ('mdpadmin  : ' . sha1('mdpadmin') . '<br>');
echo ('mdputilisateur : ' . sha1('mdputilisateur') . '<br>');
echo ('<br>');

// test de la méthode convertirEnDateFR------------------------------------------------------------
$uneDateUS = '2007-05-16';
$uneDateFR = Outils::convertirEnDateFR($uneDateUS);
echo ('<b>Test de la méthode convertirEnDateFR : </b><br>');
echo ('$uneDateUS : ' . $uneDateUS . '<br>');
echo ('$uneDateFR : ' . $uneDateFR . '<br>');
echo ('<br>');

// test de la méthode convertirEnDateUS------------------------------------------------------------
$uneDateFR = '16/05/2007';
$uneDateUS = Outils::convertirEnDateUS($uneDateFR);
echo ('<b>Test de la méthode convertirEnDateUS : </b><br>');
echo ('$uneDateFR : ' . $uneDateFR . '<br>');
echo ('$uneDateUS : ' . $uneDateUS . '<br>');
echo ('<br>');

// test de la méthode corrigerDate-----------------------------------------------------------------
$uneDateAvant = '16-05-2007';
$uneDateApres = Outils::corrigerDate($uneDateAvant);
echo ('<b>Test de la méthode corrigerDate : </b><br>');
echo ('$uneDateAvant : ' . $uneDateAvant . '<br>');
echo ('$uneDateApres : ' . $uneDateApres . '<br>');
echo ('<br>');

// test de la méthode corrigerPrenom---------------------------------------------------------------
$unPrenomAvant = 'charles';
$unPrenomApres = Outils::corrigerPrenom($unPrenomAvant);
echo ('<b>Test de la méthode corrigerPrenom : </b><br>');
echo ('$unPrenomAvant : ' . $unPrenomAvant . '<br>');
echo ('$unPrenomApres : ' . $unPrenomApres . '<br>');
echo ('<br>');

$unPrenomAvant = 'charles-edouard';
$unPrenomApres = Outils::corrigerPrenom($unPrenomAvant);
echo ('$unPrenomAvant : ' . $unPrenomAvant . '<br>');
echo ('$unPrenomApres : ' . $unPrenomApres . '<br>');
echo ('<br>');

// test de la méthode corrigerTelephone------------------------------------------------------------
$unNumeroAvant = '1122334455';
$unNumeroApres = Outils::corrigerTelephone($unNumeroAvant);
echo ('<b>Test de la méthode corrigerTelephone : </b><br>');
echo ('$unNumeroAvant : ' . $unNumeroAvant . '<br>');
echo ('$unNumeroApres : ' . $unNumeroApres . '<br>');
echo ('<br>');

$unNumeroAvant = '11 22 33 44 55';
$unNumeroApres = Outils::corrigerTelephone($unNumeroAvant);
echo ('$unNumeroAvant : ' . $unNumeroAvant . '<br>');
echo ('$unNumeroApres : ' . $unNumeroApres . '<br>');
echo ('<br>');

// test de la méthode corrigerVille----------------------------------------------------------------
$uneVilleAvant = 'rennes';
$uneVilleApres = Outils::corrigerVille($uneVilleAvant);
echo ('<b>Test de la méthode corrigerVille : </b><br>');
echo ('$uneVilleAvant : ' . $uneVilleAvant . '<br>');
echo ('$uneVilleApres : ' . $uneVilleApres . '<br>');
echo ('<br>');

$uneVilleAvant = 'saint malo';
$uneVilleApres = Outils::corrigerVille($uneVilleAvant);
echo ('$uneVilleAvant : ' . $uneVilleAvant . '<br>');
echo ('$uneVilleApres : ' . $uneVilleApres . '<br>');
echo ('<br>');

$uneVilleAvant = 'saint-malo';
$uneVilleApres = Outils::corrigerVille($uneVilleAvant);
echo ('$uneVilleAvant : ' . $uneVilleAvant . '<br>');
echo ('$uneVilleApres : ' . $uneVilleApres . '<br>');
echo ('<br>');

// test de la méthode creerMdp---------------------------------------------------------------------
echo ('<b>Test de la méthode creerMdp : </b><br>');
echo ('Un mot de passe : ' . Outils::creerMdp() . '<br>');
echo ('Un autre mot de passe : ' . Outils::creerMdp() . '<br>');
echo ('Et encore un autre mot de passe : ' . Outils::creerMdp() . '<br>');
echo ('<br>');

// test de la méthode envoyerMail------------------------------------------------------------------
// ATTENTION : remplacez l'adr destinataire par votre adresse pour pouvoir vérifier la réception du mail
$adresseDestinataire = "xxxxxxxxxxxxxxxxxx";
$sujet = "sujet du test";
$message = "corps du message";
$adresseEmetteur = "delasalle.sio.crib@gmail.com";
$ok = false;
$ok = Outils::envoyerMail ($adresseDestinataire, $sujet, $message, $adresseEmetteur);
echo ('<b>Test de la méthode envoyerMail : </b><br>');
if ($ok == true) 
    echo ('Un mail vient d\'être envoyé !<br>');
else 
    echo ('L\'envoi du mail a rencontré un problème !<br>');
echo ('<br>');

// test de la méthode estUnCodePostalValide -------------------------------------------------------
$unCP = '35000';
if ( Outils::estUnCodePostalValide($unCP) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('<b>Test de la méthode estUnCodePostalValide : </b><br>');
echo ('$unCP : ' . $unCP . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$unCP = '3500';
if ( Outils::estUnCodePostalValide($unCP) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$unCP : ' . $unCP . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

// test de la méthode estUneAdrMailValide ---------------------------------------------------------
$uneAdrMail = 'sophie.fonfec@gmail.com';
if ( Outils::estUneAdrMailValide($uneAdrMail) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('<b>Test de la méthode estUneAdrMailValide : </b><br>');
echo ('$uneAdrMail : ' . $uneAdrMail . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$uneAdrMail = 'sophie.fonfec@gmailcom';
if ( Outils::estUneAdrMailValide($uneAdrMail) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$uneAdrMail : ' . $uneAdrMail . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$uneAdrMail = 'sophie.fonfecgmail.com';
if ( Outils::estUneAdrMailValide($uneAdrMail) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$uneAdrMail : ' . $uneAdrMail . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

// test de la méthode estUneDateValide ---------------------------------------------------------
$uneDate = '31/13/2016';
if ( Outils::estUneDateValide($uneDate) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('<b>Test de la méthode estUneDateValide : </b><br>');
echo ('$uneDate : ' . $uneDate . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$uneDate = '31/12/2016';
if ( Outils::estUneDateValide($uneDate) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$uneDate : ' . $uneDate . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$uneDate = '29/02/2015';
if ( Outils::estUneDateValide($uneDate) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$uneDate : ' . $uneDate . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$uneDate = '29/02/2016';
if ( Outils::estUneDateValide($uneDate) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$uneDate : ' . $uneDate . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

// test de la méthode estUnNumTelValide -----------------------------------------------------------
$unNumero = '1122334455';
if ( Outils::estUnNumTelValide($unNumero) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('<b>Test de la méthode estUnNumTelValide : </b><br>');
echo ('$unNumero : ' . $unNumero . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$unNumero = '112233445';
if ( Outils::estUnNumTelValide($unNumero) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$unNumero : ' . $unNumero . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$unNumero = '11.22.33.44.55';
if ( Outils::estUnNumTelValide($unNumero) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$unNumero : ' . $unNumero . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$unNumero = '11,22,33,44,55';
if ( Outils::estUnNumTelValide($unNumero) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$unNumero : ' . $unNumero . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$unNumero = '11-22-33-44-55';
if ( Outils::estUnNumTelValide($unNumero) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$unNumero : ' . $unNumero . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');

$unNumero = '11/22/33/44/55';
if ( Outils::estUnNumTelValide($unNumero) ) $resultat = 'vrai'; else $resultat = 'faux';
echo ('$unNumero : ' . $unNumero . '<br>');
echo ('$resultat : ' . $resultat . '<br>');
echo ('<br>');
?>

</body>
</html>