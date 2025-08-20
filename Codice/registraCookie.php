<?php
    session_start();
    //memorizzo l'utente in un cookie (valido 30 giorni)
    setcookie("Login", $_SESSION["nickname"], time()+2592000);
    //reindirizzo alla userpage
    header("Location: userpage.php");
?>