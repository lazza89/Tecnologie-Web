<?php
include 'template/logged.php';
if(!isset($_SESSION)) {
    session_start();
}

$HTMLPage = isLogged("../html/mappa.html");

echo $HTMLPage;
?>