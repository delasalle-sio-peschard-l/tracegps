<?php
// Projet TraceGPS
// fichier : modele/Outils.class.php
// Rôle : boite à outils de fonctions courantes proposées sous forme de méthodes statiques
// Dernière mise à jour : 6/11/2018 par JM CARTRON

// liste des méthodes statiques de cette classe (dans l'ordre alphabétique) :

// convertirEnDateFR($laDate) : reçoit une date US (aaaa-mm-jj) et fournit sa conversion au format Français (jj/mm/aaaa)
// convertirEnDateUS($laDate) : reçoit une date française (jj/mm/aaaa) et fournit sa conversion au format US (aaaa-mm-jj)
// corrigerDate($laDate) : reçoit une date et fournit cette date en remplaçant les "-" par des "/"
// corrigerPrenom($lePrenom) : reçoit un prénom et fournit ce prénom corrigé en mettant en majuscules le premier caractère, 
//    et le caractère qui suit un éventuel tiret, et en mettant en minuscules les autres caractères
// corrigerTelephone($leNumero) : reçoit un numéro et fournit ce numéro corrigé en le mettant sous la forme de 
//    5 groupes de 2 chiffres séparés par des points
// corrigerVille($laVille) : reçoit un nom de ville et fournit ce nom corrigé en le mettant en majuscules 
//    et en remplaçant les "SAINT" par "St"
// creerMdp() : génère et fournit un mot de passe aléatoire de 8 caractères (4 syllabes avec 1 consonne et 1 voyelle)
// envoyerMail ($adresseDestinataire, $sujet, $message, $adresseEmetteur) : envoie un mail à un destinataire
// estUnCodePostalValide($codePostalAvalider) : fournit true si le code postal reçu en paramètre est correct 
//    (il doit comporter 5 chiffres), false sinon
// estUneAdrMailValide($adrMailAvalider) : fournit true si l'adresse mail reçue en paramètre est correcte, false sinon
// estUneDateValide($laDateAvalider) : fournit true si la date reçue en paramètre est correcte 
//    (format jj/mm/aaaa ou bien jj-mm-aaaa), false sinon
// estUnNumTelValide($numTelAvalider) : fournit true si le n° de téléphone reçu en paramètre est correct 
//    (5 groupes de 2 chiffres éventuellement séparés), false sinon

// ce fichier est destiné à être inclus dans les pages PHP qui ont besoin des fonctions qu'il contient, avec l'instruction :
//     include_once ('Outils.class.php');               (si la page et la classe Outils sont dans le même dossier)
//     include_once ('../modele/Outils.class.php');     (si la page et la classe Outils sont dans des dossiers différents)

// ces méthodes statiques sont appelées avec la notation suivante :
//     Outils::methode(parametres);

// début de la classe Outils
class Outils
{

    // La fonction convertirEnDateFR($laDate) reçoit une date US (aaaa-mm-jj) et fournit sa conversion au format Français (jj/mm/aaaa)
	// par exemple, le paramètre '2007-05-16' donnera '16/05/2007'
    // Dernière mise à jour : 13/7/2018 par JM CARTRON
	public static function convertirEnDateFR ($laDate)
	{	$tableau = explode ("-", $laDate);           // on extrait les segments de la chaine $laDate séparés par des "-"
		$an = $tableau[0];
		$mois = $tableau[1];
		$jour = $tableau[2];
		return ($jour . "/" . $mois . "/" . $an);     // on les reconcatène dans un ordre différent et avec des "/"
	}
			
	// La fonction convertirEnDateUS($laDate) reçoit une date française (jj/mm/aaaa) et fournit sa conversion au format US (aaaa-mm-jj)
	// par exemple, le paramètre '2007-05-16' donnera '16/05/2007'
	// Dernière mise à jour : 13/7/2018 par JM CARTRON
	public static function convertirEnDateUS ($laDate)
	{	$tableau = explode ("/", $laDate);           // on extrait les segments de la chaine $laDate séparés par des "/"
		$jour = $tableau[0];
		$mois = $tableau[1];
		$an = $tableau[2];
		return ($an . "-" . $mois . "-" . $jour);     // on les reconcatène dans un ordre différent et avec des "-"
	}

	// La fonction corrigerDate($laDate) reçoit une date et fournit cette date en remplaçant les "-" par des "/"
	// par exemple, le paramètre '16-05-2007' donnera '16/05/2007'
	// Dernière mise à jour : 13/7/2018 par JM CARTRON
	public static function corrigerDate ($laDate)
	{
		$temporaire = str_replace ("-", "/", $laDate);
		return $temporaire;
	}
	
	// La fonction corrigerPrenom($lePrenom) reçoit un prénom et fournit ce prénom corrigé en mettant en majuscules le premier 
	// caractère, et le caractère qui suit un éventuel tiret, et en mettant en minuscules les autres caractères
	// par exemple, le paramètre 'charles' donnera 'Charles'
	// par exemple, le paramètre 'charles-edouard' donnera 'Charles-Edouard'
	// Dernière mise à jour : 13/7/2018 par JM CARTRON
	public static function corrigerPrenom ($lePrenom)
	{	if ($lePrenom != "")
		{	$longueur = strlen($lePrenom);
			$position = strpos($lePrenom, "-");
			if ($position == 0)
			{	$partie1 = substr ($lePrenom, 0 , 1);
				$partie2 = substr ($lePrenom, 1 , $longueur-1);
				$lePrenom = strtoupper($partie1) . strtolower($partie2);
			}
			else
			{	$partie1 = substr ($lePrenom, 0 , 1);
				$partie2 = substr ($lePrenom, 1 , $position-1);
				$partie3 = substr ($lePrenom, $position + 1, 1);
				$partie4 = substr ($lePrenom, $position + 2, $longueur-$position-2);
				$lePrenom = strtoupper($partie1) . strtolower($partie2) . "-" . strtoupper($partie3) . strtolower($partie4);
			}
		}
		return $lePrenom;
	}
	
	// La fonction corrigerTelephone($leNumero) reçoit un numéro et fournit ce numéro corrigé en le mettant sous la forme 
	// de 5 groupes de 2 chiffres séparés par des points
	// par exemple, le paramètre '1122334455' donnera '11.22.33.4.55'
	// par exemple, le paramètre '11 22 33 44 55' donnera '11.22.33.4.55'
	// Dernière mise à jour : 13/7/2018 par JM CARTRON
	public static function corrigerTelephone ($leNumero)
	{	$temporaire = $leNumero;
		$temporaire = str_replace (" ", "", $temporaire);	// supprime les espaces
		$temporaire = str_replace (".", "", $temporaire);	// supprime les points
		$temporaire = str_replace (",", "", $temporaire);	// supprime les virgules
		$temporaire = str_replace ("-", "", $temporaire);	// supprime les tirets
		$temporaire = str_replace ("_", "", $temporaire);	// supprime les underscore
		$temporaire = str_replace ("/", "", $temporaire);	// supprime les slash
		
		if (strlen($temporaire) == 10 )		// il ne doit rester que les 10 chiffres...
		{   $resultat =             substr ($temporaire, 0, 2) . ".";
			$resultat = $resultat . substr ($temporaire, 2, 2) . ".";
			$resultat = $resultat . substr ($temporaire, 4, 2) . ".";
			$resultat = $resultat . substr ($temporaire, 6, 2) . ".";
			$resultat = $resultat . substr ($temporaire, 8, 2);
			return $resultat;
		}
		else
		{   return $leNumero;
		}
	}
	
	// La fonction corrigerVille($laVille) reçoit un nom de ville et fournit ce nom corrigé en le mettant en majuscules 
	// et en remplaçant les "SAINT" par "St" (et avec un espace à la place du tiret)
	// par exemple, le paramètre 'rennes' donnera 'RENNES'
	// par exemple, le paramètre 'Saint Malo' donnera 'St MALO'
	// Dernière mise à jour : 13/7/2018 par JM CARTRON
	public static function corrigerVille ($laVille)
	{	$temporaire = $laVille;
	    // on supprime les accents
		$temporaire = str_replace ("é", "e", $temporaire);
		$temporaire = str_replace ("è", "e", $temporaire);
		$temporaire = str_replace ("ê", "e", $temporaire);
		$temporaire = str_replace ("à", "a", $temporaire);
		$temporaire = str_replace ("ô", "o", $temporaire);
		$temporaire = str_replace ("î", "i", $temporaire);
		// on met le nom en majuscules
		$temporaire = strtoupper ($temporaire);
		// et on gère les "SAINT" et "SAINTE"
		$temporaire = str_replace ("SAINTE ", "Ste ", $temporaire);
		$temporaire = str_replace ("SAINTE-", "Ste ", $temporaire);
		$temporaire = str_replace ("SAINT ", "St ", $temporaire);
		$temporaire = str_replace ("SAINT-", "St ", $temporaire);
		return $temporaire;
	}

	// La fonction creerMdp() génère et fournit un mot de passe aléatoire de 8 caractères (4 syllabes avec 1 consonne et 1 voyelle)
	// Dernière mise à jour : 13/7/2018 par JM CARTRON
	public static function creerMdp ()
	{   $consonnes = "bcdfghjklmnpqrstvwxz";
		$voyelles = "aeiouy";
		$mdp = "";
		// on construit 4 syllabes de 2 caractères
		for ($i = 1 ; $i <= 4 ; $i++)
		{   // on tire d'abord une consonne (position aléatoire entre 0 et le nombre de consonnes - 1)
			$position = rand (0, strlen($consonnes)-1);
			// on récupère la consonne correspondant à la position dans $consonnes
			$unCaract = substr ($consonnes, $position, 1);
			// on ajoute cette consonne au mot de passe
			$mdp = $mdp . $unCaract;
			// puis on tire une voyelle (position aléatoire entre 0 et le nombre de voyelles - 1)
			$position = rand (0, strlen($voyelles)-1);
			// on récupère la voyelle correspondant à la position dans $voyelles
			$unCaract = substr ($voyelles, $position, 1);
			// on ajoute cette voyelle au mot de passe
			$mdp = $mdp . $unCaract;
		}
		return $mdp;
	}

// 	// La fonction envoyerMail ($adresseDestinataire, $sujet, $message, $adresseEmetteur) envoie un mail à un destinataire
// 	// elle utilise le serveur localhost si celui-ci est bien configuré pour envoyer des mails (ce qui n'est pas le cas en salle I12)
// 	// retourne true si envoi correct, false en cas de problème d'envoi
// 	// Dernière mise à jour : 13/7/2018 par JM CARTRON
// 	public static function  envoyerMail ($adresseDestinataire, $sujet, $message, $adresseEmetteur)
// 	{	// la fonction mail fonctionne aléatoirement avec les adresses Gmail comportant plusieurs points dans la partie gauche
// 	    // comme dans l'adresse "delasalle.sio.fonfec.s@gmail.com"
// 	    // Heureusement, Gmail ne tient pas compte des points dans la partie gauche, on peut donc les enlever
// 	    // Utilisation d'une expression régulière pour vérifier si c'est une adresse Gmail :
// 		if ( preg_match ( "#^.+@gmail\.com$#" , $adresseDestinataire) == true) {
// 			// on commence par enlever les points dans l'adresse gmail car ils ne sont pas pris en compte
// 			$adresseDestinataire = str_replace(".", "", $adresseDestinataire);
// 			// puis on remet le point de "@gmail.com" (celui-là, il faut le conserver)
// 			$adresseDestinataire = str_replace("@gmailcom", "@gmail.com", $adresseDestinataire);
// 		}
// 		// envoi du mail avec la fonction mail de PHP
// 		try {
// 			$ok = mail($adresseDestinataire , $sujet , $message, "From: " . $adresseEmetteur);
// 			return $ok;
// 		}
// 		catch (Exception $ex) {
// 			return false;
// 		}
// 	}

	// La fonction envoyerMail ($adresseDestinataire, $sujet, $message, $adresseEmetteur) envoie un mail à un destinataire
	// elle utilise le serveur le serveur OVH (sio.lyceedelassale.fr) pour envoyer le mail, en passant par un service web
	// retourne true si envoi correct, false en cas de problème d'envoi
	// Dernière mise à jour : 6/11/2018 par JM CARTRON
	public static function  envoyerMail ($adresseDestinataire, $sujet, $message, $adresseEmetteur)
	{
	    // transformation du message s'il contient des "&"
	    $message = str_replace("&", "$$", $message);
	    
	    // préparation de l'URL du service web avec ses paramètres
	    $urlService = "http://sio.lyceedelasalle.fr/tracegps/services/ServiceEnvoyerMail.php";
	    $urlService .= "?adresseDestinataire=" . $adresseDestinataire;
	    $urlService .= "&sujet=" . $sujet;
	    $urlService .= "&message=" . $message;
	    $urlService .= "&adresseEmetteur=" . $adresseEmetteur;
	    
	    // création d'un objet DOMDocument pour traiter un flux de données XML
	    $dom = new DOMDocument();
	    // chargement des données à partir de l'url du service web
	    if ( ! $dom->load($urlService )) return false;
	    // récupération du noeud XML correspondant à la balise <reponse>
	    $lesNoeuds = $dom->getElementsByTagName("reponse");
	    foreach ($lesNoeuds as $unNoeud)
	    {
	        if ($unNoeud->nodeValue == "true") return true;
            else return false;
	    }
	}
	
	// La fonction estUnCodePostalValide($codePostalAvalider) fournit true si le code postal reçu en paramètre est correct 
	// (il doit comporter 5 chiffres), false sinon
	// Dernière mise à jour : 11/12/2017 par JM CARTRON
	public static function estUnCodePostalValide($codePostalAvalider)
	{	// utilisation d'une expression régulière pour vérifier un code postal :
		$EXPRESSION = "#^[0-9]{5,5}$#";
		// on retourne true si le code est bon, mais aussi si le code est vide :
		if ( preg_match ( $EXPRESSION , $codePostalAvalider ) == true || $codePostalAvalider == "" ) return true; else return false;
	}	

	// La fonction estUneAdrMailValide($adrMailAvalider) fournit true si l'adresse mail reçue en paramètre est correcte, false sinon
	// Dernière mise à jour : 11/12/2017 par JM CARTRON
	public static function  estUneAdrMailValide ($adrMailAvalider)
	{	// utilisation d'une expression régulière pour vérifier une adresse mail :
		$EXPRESSION = "#^.+@.+\..+$#";
		// on retourne true si l'adresse est bonne, mais aussi si l'adresse est vide :
		if ( preg_match ( $EXPRESSION , $adrMailAvalider) == true || $adrMailAvalider == "" ) return true; else return false;
	}
	
	// La fonction estUneDateValide($laDateAvalider) fournit true si la date reçue en paramètre est correcte 
	// (format jj/mm/aaaa ou bien jj-mm-aaaa), false sinon
	// Dernière mise à jour : 11/12/2017 par JM CARTRON
	public static function estUneDateValide ($laDateAvalider)
	{	// on retourne true si la date est vide :
		if ( $laDateAvalider == "" ) return true;
		
		// utilisation d'une expression régulière pour vérifier le format de la date :
		$EXPRESSION = "#^[0-9]{2,2}(/|-)[0-9]{2,2}(/|-)[0-9]{4,4}$#";
		if ( preg_match ( $EXPRESSION , $laDateAvalider) == false) return false;
		
		// jusque là, tout va bien ! on extrait les 3 sous-chaines et on les convertit en 3 entiers :
		$chaine1 = substr ($laDateAvalider, 0, 2);
		$chaine2 = substr ($laDateAvalider, 3, 2);
		$chaine3 = substr ($laDateAvalider, 6, 4);
		$jour = (int)($chaine1);
		$mois = (int)($chaine2);
		$an = (int)($chaine3);
	
		// test des valeurs
		if ( $mois < 0 || $mois > 12 || $jour < 0 || $jour > 31 )
			return false;
		else
		{   // cas des mois de 30 jours
			if ( ( $mois == 4 || $mois == 6 || $mois == 9 || $mois == 11 ) && ( $jour > 30 ) )
				return false;
			else
			{   // cas du mois de février
				// % est l'opérateur modulo ; il permet de tester si $an est multiple de 4, de 100 ou de 400
				if ((($an % 4) == 0 && ($an % 100) != 0) || ($an % 400) == 0)
					$bissextile = true;
				else
					$bissextile = false;
				if ( $mois == 2 && $bissextile == false && $jour > 28 )
					return false;
				else
				{   if ( $mois == 2 && $bissextile == true && $jour > 29 )
					{   return false;
					}
				}
			}
		}
		// si on arrive ici, c'est que la date est bonne :
		return true;
	}
	
	// La fonction estUnNumTelValide($numTelAvalider) fournit true si le n° de téléphone reçu en paramètre est correct 
	// (5 groupes de 2 chiffres éventuellement séparés), false sinon
	// Dernière mise à jour : 11/12/2017 par JM CARTRON
	public static function  estUnNumTelValide ($numTelAvalider)
	{	// utilisation d'une expression régulière pour vérifier un numéro de téléphone
		// les séparateurs entre les groupes de 2 chiffres sont facultatifs
		// les seuls séparateurs autorisés sont l'espace, le point, le tiret, le tiret bas (underscore) et le slash (/)
		$EXPRESSION = "#^([0-9]{2,2}( |\.|-|_|/)?){4,4}[0-9]{2,2}$#";
		// on retourne true si le numéro est bon, mais aussi si le numéro est vide :
		if ( preg_match ( $EXPRESSION , $numTelAvalider) == true || $numTelAvalider == "" ) return true; else return false;
	}

} // fin de la classe Outils

// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!
