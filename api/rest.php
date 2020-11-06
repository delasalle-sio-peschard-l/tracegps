<?php
// Projet TraceGPS - services web
// Fichier : api/rest.php
// La classe Rest est la classe mère de la classe Api (fichier api/api.php)
// Dernière mise à jour : 5/7/2018 par Jim

class Rest {
    protected $codeReponse;        // statut de la réponse HTTP (code numérique à 3 chiffres)
    protected $contentType;        // indique le format de la réponse HTTP : "application/xml; charset=utf-8" ou "application/json; charset=utf-8"
	protected $request = array();  // tableau contenant les données envoyées avec la requête
		
	// le constructeur
	public function __construct() {
		$this->recupererLesDonneesEnvoyees();
	}
	
	// Cette méthode envoie la réponse HTTP ; elle reçoit 3 paramètres :
	//    $code_reponse : le code numérique à 3 chiffres indiquant l'état de la réponse
	//    $content_type : le format de la réponse ("application/json; charset=utf-8" ou "application/xml; charset=utf-8")
	//    $donnees : les données encodées (formatées en Json ou en XML)
	protected function envoyerReponse($code_reponse, $content_type, $donnees) {
        $this->codeReponse = $code_reponse;       // mémorise le code de la réponse HTTP
        $this->contentType = $content_type;       // mémorise le le format de la réponse HTTP
        $this->preparerEntetes();                 // prépare les entêtes HTTP de la réponse HTTP
        echo $donnees;                            // envoie les données dans le corps de la réponse HTTP
        exit;                                     // fin de l'exécution
	}		
	
	// Cette méthode prépare les entêtes HTTP de la réponse
	protected function preparerEntetes() {
	    header("HTTP/1.1 " . $this->codeReponse . " " . $this->getLibelleMessage());
	    header("Content-Type:" . $this->contentType);
	}
	
	// Cette méthode fournit le libellé correspondant au code de la réponse HTTP
	protected function getLibelleMessage() {
		$status = array(
					100 => 'Continue',  
					101 => 'Switching Protocols',  
					200 => 'OK',
					201 => 'Created',  
					202 => 'Accepted',  
					203 => 'Non-Authoritative Information',  
					204 => 'No Content',  
					205 => 'Reset Content',  
					206 => 'Partial Content',  
					300 => 'Multiple Choices',  
					301 => 'Moved Permanently',  
					302 => 'Found',  
					303 => 'See Other',  
					304 => 'Not Modified',  
					305 => 'Use Proxy',  
					306 => '(Unused)',  
					307 => 'Temporary Redirect',  
					400 => 'Bad Request',  
					401 => 'Unauthorized',  
					402 => 'Payment Required',  
					403 => 'Forbidden',  
					404 => 'Not Found',  
					405 => 'Method Not Allowed',  
					406 => 'Not Acceptable',  
					407 => 'Proxy Authentication Required',  
					408 => 'Request Timeout',  
					409 => 'Conflict',  
					410 => 'Gone',  
					411 => 'Length Required',  
					412 => 'Precondition Failed',  
					413 => 'Request Entity Too Large',  
					414 => 'Request-URI Too Long',  
					415 => 'Unsupported Media Type',  
					416 => 'Requested Range Not Satisfiable',  
					417 => 'Expectation Failed',  
					500 => 'Internal Server Error',  
					501 => 'Not Implemented',  
					502 => 'Bad Gateway',  
					503 => 'Service Unavailable',  
					504 => 'Gateway Timeout',  
					505 => 'HTTP Version Not Supported');
		// si le code n'est pas dans le tableau, on retourne le libellé du code 500 ('Internal Server Error')
		return ($status[$this->codeReponse]) ? $status[$this->codeReponse] : $status[500];
	}
	
	// Cette méthode retourne la méthode utilisée par la requête HTTP (GET, POST, PUT ou DELETE)
	protected function getMethodeRequete() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	// Cette méthode récupère les données envoyées avec la requête et les stocke dans le tableau $this->request
	protected function recupererLesDonneesEnvoyees() {
		switch($this->getMethodeRequete()) {
			case "POST":
				$this->request = $this->remettreEnFormeLesEntrees($_POST);
				break;
			case "GET":
			case "DELETE":
				$this->request = $this->remettreEnFormeLesEntrees($_GET);
				break;
			case "PUT":
				parse_str(file_get_contents("php://input"), $this->request);
				$this->request = $this->remettreEnFormeLesEntrees($this->request);
				break;
			default:
				$this->envoyerReponse('',406);
				break;
		}
	}		
	
	// Cette méthode nettoie les données envoyées avec la requête
	protected function remettreEnFormeLesEntrees($data) {
		$lesEntrees = array();
		if (is_array($data)) {
			foreach ($data as $cle => $valeur){
				$lesEntrees[$cle] = $this->remettreEnFormeLesEntrees($valeur);
			}
		} else {
			if (get_magic_quotes_gpc()) {
				$data = trim(stripslashes($data));
			}
			$data = strip_tags($data);
			$lesEntrees = trim($data);
		}
		return $lesEntrees;
	}
} // fin de la classe Rest
?>