<?php
    session_start();
    //cancello tutta la sessione
    session_destroy();
    //se risulta un cookie lo distruggo
    if (isset($_COOKIE["Login"])) {
        setcookie("Login", "", time()-1);
    }
    //reindirizzo alla pagina accedi.php
    header("Location: accedi.php");
?>