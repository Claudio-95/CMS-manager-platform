<?php
    session_start();
    function check_auth($var1, $var2) {
        //mi collego al DB
        include ("connect.php");
        //cerco il nickname inserito
        $sql = "SELECT `nickname`, `password` FROM `utenti` WHERE `nickname` = '".$var1."'";
        $result = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result) {
            die (json_encode("Errore query : " . mysqli_error($link)));
        }
        $rows = mysqli_num_rows($result);
        //se ho trovato il nickname controllo che la password sia corretta...
        if ($rows == 1) {
            //memorizzo la password del DB
            $passwordDB = mysqli_fetch_assoc($result);
            //confronto le password
            if (password_verify($var2, $passwordDB["password"])) {
                return 1;
            }
            //se la password e' errata stampo l'array di stato di errori
            else {
                echo json_encode(array("0", "1"));
                return 0;
            }
        }
        //...altrimenti stampo l'array di stato di errori
        else {
            echo json_encode(array("1", "0"));
            return 0;
        }
    }
    //mi collego al DB
    include ("connect.php");
    //recupero i dati inseriti
    $nick = $_POST["nickname"];
    //(aggiungo salt alla password per poterla confrontare con DB)
    $pass = "s4lt3d".$_POST["pass"]."p4ssw0rd";
    //dichiaro $error
    $error = "";
    //controllo che i campi siano stati compilati
    if (!$nick || !$_POST["pass"]) {
        echo "Tutti i campi sono obbligatori!";
        exit();
    }
    //se il nickname contiene caratteri nocivi (non sono ammessi spazi, caratteri accentati e caratteri speciali eccetto '-_) stampo l'errore
    if (!preg_match("/^[A-Za-z0-9\'\-\_]+$/i", $nick)) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e0 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e0 = "0";
    }
    //se la password contiene caratteri nocivi (non sono ammessi spazi, caratteri accentati e caratteri speciali eccetto '-_) stampo l'errore
    if (!preg_match("/^[A-Za-z0-9\'\-\_]+$/i", $pass)) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e1 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e1 = "0";
    }
    //se tutto e' ok controllo che esista un tale profilo nel DB e registro i dati in sessione...
    if (!$error) {
        //protezione contro SQL Injection
        $nickEscaped = mysqli_real_escape_string($link, $nick);
        if (check_auth($nickEscaped, $pass)) {
            //memorizzo l'utente e lo status del login nella sessione
            $_SESSION["nickname"] = $nickEscaped;
            $_SESSION["login"] = "OK";
            //stampo OK nel caso in cui tutto venga effettuato correttamente
            echo "OK";
        }
    }
    //...altrimenti stampo l'array di stato di errori
    else {
        echo json_encode(array($e0, $e1));
    }
?>