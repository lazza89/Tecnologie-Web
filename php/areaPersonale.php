<?php
require_once("DBConnection.php");
use DB\DBConnection;

if(!isset($_SESSION)) {
    session_start();
}

$HTMLPage = file_get_contents("../html/areaPersonale.html");

$queryUpdateResult = "";
$errorMSG = "";
$doneMSG = "";

$connection = new DBConnection();
$connectionOK = $connection->openDBConnection();

if(isset($_SESSION['login']) && $_SESSION['login'] == true){

    $mail = $_SESSION['email'];
    $username = $_SESSION['username'];
    $pw = "";
	$oldPw = "";
    $name = $_SESSION['name'];
    $surname = $_SESSION['surname'];
    $city = $_SESSION['city'];

    $HTMLPage = str_replace("{{logged}}", "Ciao $username! " , $HTMLPage);

    $HTMLPage = str_replace("{{mail}}", $mail, $HTMLPage);
    $HTMLPage = str_replace("{{username}}", $username, $HTMLPage);
    $HTMLPage = str_replace("{{name}}", $name, $HTMLPage);
    $HTMLPage = str_replace("{{surname}}", $surname, $HTMLPage);
    $HTMLPage = str_replace("{{city}}", $city, $HTMLPage);

    if(isset($_POST["submit"])){


        //regular expression generati con il sito regex101.com/r
        //deve essere in formato email, non può contenere caratteri speciali riferiti a linguaggi (html, sql)
        $mail = $_POST["PEmail"];
        if(!preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/",$mail)){
            $errorMSG .= "<li>Email non conforme</li>";
        }
        //deve essere almeno 3 caratteri e lunga 20, può contenere solo numeri e lettere
        $username = $_POST["PUsername"];
        if(!preg_match("/^[A-Z\d]{3,20}+$/i",$username)){
            $errorMSG .= "<li>Username non conforme</li>";
        }
        //PASSWORD lunga almeno 3 caratteri, deve contenere almeno un numero e una lettera, può contenere caratteri speciali ma non robe html e sql
        $pw = $_POST["PPassword"];
		if($pw != "" && !preg_match("/^[A-Z\d]{3,20}+$/i",$pw)){   
			$errorMSG .= "<li>Password non conforme</li>";
		}

		$repeatPw = $_POST["PRPassword"];
		if($pw != $repeatPw){   
			$errorMSG .= "<li>Password nuova non combacia con quella ripetuta</li>";
		}

		$oldPw = $_POST["POldPassword"];
		if(!preg_match("/^[A-Z\d]{3,20}+$/i",$oldPw)){   
			$errorMSG .= "<li>Password non conforme</li>";
		}else{
			if($connection->checkLoginCredentials($_SESSION['username'], $oldPw) == ""){
				$errorMSG .= "<li>Password attuale errata</li>";
			}
		}

        //deve essere almeno 3 caratteri e lunga 30, può contenere solo lettere
        $name = $_POST["PName"];
        if(!preg_match("/^[A-Z]{2,30}+$/i",$name)){
            $errorMSG .= "<li>Nome non conforme</li>";
        }
        //deve essere almeno 3 caratteri e lunga 30, può contenere solo lettere
        $surname = $_POST["PSurname"];
        if(!preg_match("/^[A-Z]{2,30}+$/i",$surname)){
            $errorMSG .= "<li>Cognome non conforme</li>";
        }
        //deve essere almeno 3 caratteri e lunga 40, può contenere solo lettere
        $city = $_POST["PCity"];
        if($city && !preg_match("/^[A-Z ùèàéòì]{2,40}+$/i",$city)){
            $errorMSG .= "<li>Città non conforme</li>";
        }

		if($errorMSG == ""){
			if($pw == $oldPw){
				$errorMSG .= "<li>Le password sono identiche</li>";
			}

			$checkChange = $connection->getUserDetailsById($_SESSION['id']);
			if($checkChange != "" && !$pw){
				if($checkChange['email'] == $mail && $checkChange['username'] == $username && $checkChange['name'] == $name && $checkChange['surname'] == $surname && $checkChange['city'] == $city){
					$errorMSG .= "<li>I dati inseriti sono identici a quelli nel database</li>";
				}
			}
		}

        if($errorMSG == ""){
            if($connectionOK){
                if($_SESSION["username"] != $username && $connection->checkUsernameOnDB($username)){
                    $errorMSG .= "<li>Email o Username già associato ad un account</li>";
                }
                
                if($errorMSG == ""){
					if($pw == ""){
						$pw = $oldPw;
					}
                    $queryUpdateResult = $connection->updateUser($_SESSION['id'], $mail, $username, $pw, $name, $surname, $city);
                    if($queryUpdateResult){

                        $_SESSION['email'] = $mail;
                        $_SESSION['username'] = $username;
                        $_SESSION['name'] = $name;
                        $_SESSION['surname'] = $surname;
                        $_SESSION['city'] = $city;

						$doneMSG = "Modifica avvenuta con successo";
                        
                    }else{
                        $errorMSG = "<li>Problemi di connessione, ci scusiamo per il disagio</li>";
                    }
                }
            }else{
                $errorMSG = "<li>Problemi di connessione, ci scusiamo per il disagio</li>";
            }
        }
    
    }
}else{
	header("Location: home.php");
}
$connection->closeDBConnection();

if($errorMSG){
	$openList = "<ul>";
	$closeList = "</ul>";
	$openList .= $errorMSG .= $closeList;
	$errorMSG = $openList;
    $HTMLPage = str_replace("{{errorMSG}}", $errorMSG, $HTMLPage);
}else{
    $HTMLPage = str_replace("{{errorMSG}}", "", $HTMLPage);
}
if($doneMSG){
	$openList = "<h3 id=\"doneChanges\">";
	$closeList = "</h3>";
	$openList .= $doneMSG .= $closeList;
	$doneMSG = $openList;
    $HTMLPage = str_replace("{{doneMSG}}", $doneMSG, $HTMLPage);
}else{
    $HTMLPage = str_replace("{{doneMSG}}", "", $HTMLPage);
}

$HTMLPage = str_replace("{{footer}}", file_get_contents("../html/components/footer.html") , $HTMLPage);

echo $HTMLPage;

?>