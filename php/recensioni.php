<?php
require_once("DBConnection.php");
use DB\DBConnection;

if(!isset($_SESSION)) {
    session_start();
}

$HTMLPage = file_get_contents("../html/recensioni.html");

$connection = new DBConnection();
$connectionOK = $connection->openDBConnection();

$errorMSG = "";
$connectionERR = "";
$commentsQuery = "";

if(isset($_POST["DComment"])){
	if($connectionOK){
		$query = $connection->deleteComment($_POST["DComment"]);
		if($query){
			header( "refresh:0; url=recensioni.php" ); 
		}else{
			$connectionERR = "<li>Problemi di connessione, Commento non cancellato</li>";
		}
	}else{
		$connectionERR = "<li>Problemi di connessione, ci scusiamo per il disagio</li>";
	}
}


if(isset($_POST["submit"])){
    if(isset($_SESSION['login'])){
        $comment = $_POST["commentBox"];
        if(!preg_match("/^[A-Z\d\r\n,.èéì'% àòù!?() ]{1,300}+$/i", $comment)){
            $errorMSG .= "<li>Commento non valido</li>";
            $errorMSG .= "<li>Il commento non può contenere caratteri inerenti a linguaggi di programmazione</li>";
            $errorMSG .= "<li>Il commento può essere lungo massimo 300 caratteri</li>";
        }

        $stars = $_POST["starsQuantity"];
        if($stars > 5 or $stars < 0){
            $errorMSG .= "<li>Stelle non valide</li>";
        }

        if($errorMSG == ""){
            if($connectionOK){
                $query = $connection->insertComment($_SESSION["id"], $comment, $stars);
                if($query){
                    header( "refresh:0; url=recensioni.php" ); 
                }else{
                    $errorMSG = "<li>Problemi di connessione, ci scusiamo per il disagio</li>"; 
                }
            }else{
                $errorMSG = "<li>Problemi di connessione, ci scusiamo per il disagio</li>";
            }
        }
    }else{
        $errorMSG= "<p>Problemi di connessione, ci scusiamo per il disagio</p>";
    }
    if($errorMSG){
        $openList = "<ul>";
        $closeList = "</ul>";
        $openList .= $errorMSG .= $closeList;
        $errorMSG = $openList;
    }
}


if($connectionOK){
    $commentsQuery = $connection->getComments();
    if($commentsQuery != ""){
        while($row = mysqli_fetch_assoc($commentsQuery)){
            $comment = file_get_contents("../html/components/comment.html");
            $comment = str_replace("{{username}}", mysqli_fetch_assoc($connection->getUsernameById($row["userId"]))["username"] , $comment);
            $comment = str_replace("{{NStars}}", $row['stars'], $comment);
            $NStars = "";
            for ($i = 0; $i < 5; $i++) { 
                if($row['stars'] > $i){
                    $NStars .= "<div class=\"star gold\"></div>";
                }else{
                    $NStars .= "<div class=\"star\"></div>";
                }
            }
            $comment = str_replace("{{classStar}}", $NStars, $comment);
            $comment = str_replace("{{date}}", $row["date"], $comment);
            $comment = str_replace("{{comment}}", $row["comment"], $comment);
            
            if((isset($_SESSION['login']) && $_SESSION['id'] == $row["userId"]) || (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'])){ 
                $pre = "<div class = \"commentsHeader\"><form action=\"recensioni.php\" method=\"post\">";
                $post = "</form></div>";
                $tmp = "<button class=\"DComment\" name=\"DComment\" value=".$row["id"].">Cancella</button>";
                $tmp = $pre .= $tmp .= $post;
                $comment = str_replace("{{userId}}", $tmp, $comment);
            }else{
                $comment = str_replace("{{userId}}", "", $comment);
            }
            $HTMLPage = str_replace("{{comments}}", $comment, $HTMLPage);
        } 
    }else{
        $connectionERR = "<p>Problemi di connessione, ci scusiamo per il disagio</p>"; 
    }
}else{
    $connectionERR = "<p>Problemi di connessione, ci scusiamo per il disagio</p>";
}


if (isset($_SESSION['login']) && $_SESSION['login']) { 
    $username = $_SESSION['username'];
    $HTMLPage = str_replace("{{logged}}", "Ciao $username! " , $HTMLPage);
    $HTMLPage = str_replace("{{loginPage}}", "logout.php" , $HTMLPage);
    $HTMLPage = str_replace("{{login}}", "Logout" , $HTMLPage);
    $HTMLPage = str_replace("{{areaPersonale}}", "<li><a href=\"areaPersonale.php\">Profilo</a></li>" , $HTMLPage);

    $HTMLPage = str_replace("writeACommentHidden", "writeAComment" , $HTMLPage);
    $HTMLPage = str_replace("warning", "warningHidden" , $HTMLPage);

}else{
    $HTMLPage = str_replace("{{logged}}", "" , $HTMLPage);
    $HTMLPage = str_replace("{{loginPage}}", "login.php" , $HTMLPage);
    $HTMLPage = str_replace("{{login}}", "Login" , $HTMLPage);
    $HTMLPage = str_replace("{{areaPersonale}}", "" , $HTMLPage);
}

$HTMLPage = str_replace("{{connectionERR}}", $connectionERR, $HTMLPage);
$HTMLPage = str_replace("{{errorMSG}}", $errorMSG, $HTMLPage);

$HTMLPage = str_replace("{{comments}}", "", $HTMLPage);

$HTMLPage = str_replace("{{footer}}", file_get_contents("../html/components/footer.html") , $HTMLPage);

$connection->closeDBConnection();

echo $HTMLPage;

?>
