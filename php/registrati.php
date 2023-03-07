<?php
require_once("DBConnection.php");
use DB\DBConnection;

if(!isset($_SESSION)) {
    session_start();
}
$HTMLPage = file_get_contents("../html/registrati.html");


$mail = "";
$username = "";
$pw = "";
$repeatPw = "";
$name = "";
$surname = "";
$city = "";

$errorMSG = "";
$queryResult = false;

if(!isset($_SESSION["login"])){
	if(isset($_POST["submit"])){

		//regular expression generati con il sito regex101.com/r
		//deve essere in formato email, non può contenere caratteri speciali riferiti a linguaggi (html, sql)
		$mail = $_POST["REmail"];
		if(!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$mail)){
			$errorMSG .= "<li>Email non conforme, la mail deve essere in formato: testo@dominio.nomedominio</li>";
		}
		//deve essere almeno 3 caratteri e lunga 20, può contenere solo numeri e lettere
		$username = $_POST["RUsername"];
		if(!preg_match("/^[A-Z\d]{3,20}+$/i",$username)){
			$errorMSG .= "<li>Username non conforme, l'username deve contenere solo caratteri alfanumerici senza spaziature, minimo 3 caratteri massimo 20</li>";
		}
		//deve essere almeno 3 caratteri e lunga 30, può contenere solo lettere
		$name = $_POST["RName"];
		if(!preg_match("/^[A-Z ]{2,30}+$/i",$name)){
			$errorMSG .= "<li>Nome non conforme, il nome può contenere solo lettere, minimo 2 caratteri massimo 30</li>";
		}
		//deve essere almeno 3 caratteri e lunga 30, può contenere solo lettere
		$surname = $_POST["RSurname"];
		if(!preg_match("/^[A-Z ]{2,30}+$/i",$surname)){
			$errorMSG .= "<li>Cognome non conforme, il cognome può contenere solo lettere, minimo 2 caratteri massimo 30</li>";
		}
		//deve essere almeno 3 caratteri e lunga 40, può contenere solo lettere
		$city = $_POST["RCity"];
		if($city && !preg_match("/^[A-Z ùèàéòì]{2,40}+$/i",$city)){
			$errorMSG .= "<li>Città non conforme, la città può contenere solo lettere, minimo 2 caratteri massimo 40</li>";
		}
		//PASSWORD lunga almeno 6 caratteri, deve contenere almeno un numero e una lettera, può contenere caratteri speciali ma non robe html e sql
		$pw = $_POST["RPassword"];
		if(!preg_match("/^[A-Z\d]{3,20}+$/i",$pw)){   
			$errorMSG .= "<li>Password non conforme, la password deve contenere solo caratteri alfanumerici senza spaziature, minimo 3 caratteri massimo 20</li>";
		}

		$repeatPw = $_POST["RPasswordRepeat"];
		if($pw != $repeatPw){   
			$errorMSG .= "<li>Le password non coincidono</li>";
		}

		if($errorMSG == ""){
			$connection = new DBConnection();
			$connectionOK = $connection->openDBConnection();

			if($connectionOK){
				if($connection->checkUsernameOnDB($username)){
					$errorMSG .= "<li>Username già associato ad un account</li>";
				}

				if($errorMSG == ""){
					$queryResult = $connection->createNewUser($mail, $username, $pw, $name, $surname, $city);
					if($queryResult){

                        $mail = "";
                        $username = "";
                        $pw = "";
                        $repeatPw = "";
                        $name = "";
                        $surname = "";
                        $city = "";

                        $HTMLPage = str_replace("registerDoneHidden", "registerDone", $HTMLPage);

                    }else{
						$errorMSG = "<li>Problemi di connessione, ci scusiamo per il disagio</li>";
					}
				}
			}else{
				$errorMSG = "<li>Problemi di connessione, ci scusiamo per il disagio</li>";
			}
			$connection->closeDBConnection();
		}

	}
}else{
	header("Location: home.php");
}

if($errorMSG){
	$openList = "<ul>";
	$closeList = "</ul>";
	$openList .= $errorMSG .= $closeList;
	$errorMSG = $openList;
    $HTMLPage = str_replace("{{errorMSG}}", $errorMSG, $HTMLPage);
}else{
    $HTMLPage = str_replace("{{errorMSG}}", "", $HTMLPage);
}


$HTMLPage = str_replace("{{mail}}", $mail, $HTMLPage);
$HTMLPage = str_replace("{{username}}", $username, $HTMLPage);
$HTMLPage = str_replace("{{name}}", $name, $HTMLPage);
$HTMLPage = str_replace("{{surname}}", $surname, $HTMLPage);
$HTMLPage = str_replace("{{city}}", $city, $HTMLPage);

$HTMLPage = str_replace("{{footer}}", file_get_contents("../html/components/footer.html") , $HTMLPage);

echo $HTMLPage;

?>
