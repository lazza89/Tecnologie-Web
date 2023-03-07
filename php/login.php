<?php
require_once("DBConnection.php");
use DB\DBConnection;

if(!isset($_SESSION)) {
    session_start();
}
$HTMLPage = file_get_contents("../html/login.html");

$errorMSG = "";

if(!isset($_SESSION["login"])){
	if(isset($_POST["submit"])){

		$username = $_POST["LUsername"];
		if(!preg_match("/^[A-Z\d]{1,20}+$/i",$username)){
			$errorMSG = "<li>username non conforme</li>";
		}
		$password = $_POST["LPassword"];
		if(!preg_match("/^[A-Z\d]{1,20}+$/i",$password)){   
			$errorMSG .= "<li>password non conforme</li>";
		}

		if($errorMSG == ""){
			$connection = new DBConnection();
			$connectionOK = $connection->openDBConnection();

			if($connectionOK){
				$query = $connection->checkLoginCredentials($username, $password);
				if($query != ""){
					if(!isset($_SESSION['login'])) {
						
						session_start();
						$_SESSION['login'] = true;
						
						$_SESSION['id'] = $query['id'];
						$_SESSION['email'] = $query['email'];
						$_SESSION['username'] = $query['username'];
						$_SESSION['password'] = $query['password'];
						$_SESSION['name'] = $query['name'];
						$_SESSION['surname'] = $query['surname'];
						$_SESSION['city'] = $query['city'];
						$_SESSION['isAdmin'] = $query['isAdmin'];
			
						header("Location: home.php");
					}else{
						$errorMSG = "<li>Login error, Riprova</li>";
						session_destroy();
					} 
				}else{
					$errorMSG = "<li>Username e Password non corretti</li>";
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

$HTMLPage = str_replace("{{footer}}", file_get_contents("../html/components/footer.html") , $HTMLPage);

echo $HTMLPage;

?>