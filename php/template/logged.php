<?php

function isLogged($page){
    $HTMLPage = file_get_contents($page);

    if (isset($_SESSION['login']) && $_SESSION['login']) { 
    $username = $_SESSION['username'];
    $HTMLPage = str_replace("{{logged}}", "Ciao $username! " , $HTMLPage);
    $HTMLPage = str_replace("{{loginPage}}", "logout.php" , $HTMLPage);
    $HTMLPage = str_replace("{{login}}", "Logout" , $HTMLPage);
    $HTMLPage = str_replace("{{areaPersonale}}", "<li><a href=\"areaPersonale.php\">Profilo</a></li>" , $HTMLPage);

    }else{
    $HTMLPage = str_replace("{{logged}}", "" , $HTMLPage);
    $HTMLPage = str_replace("{{loginPage}}", "login.php" , $HTMLPage);
    $HTMLPage = str_replace("{{login}}", "Login" , $HTMLPage);
    $HTMLPage = str_replace("{{areaPersonale}}", "" , $HTMLPage);
    }

$HTMLPage = str_replace("{{footer}}", file_get_contents("../html/components/footer.html") , $HTMLPage);

return $HTMLPage;
}

?>