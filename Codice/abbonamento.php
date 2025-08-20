<?php
    session_start();
    //mi collego al DB
    include ("connect.php");
    //recupero il nickname dell'utente
    $utente = $_SESSION["nickname"];
    //ottengo la data odierna e la preparo per registrarla nel DB
    $date = getdate(date("U"));
    $abb_start = "$date[year]-$date[mon]-$date[mday]";
    //registro la data nel DB
    $sql = "UPDATE `utenti` SET `inizioAbb` = '".$abb_start."' WHERE `nickname` = '".$utente."'";
    $result0 = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$result0) {
        die ("Errore query 0: " . mysqli_error($link));
    }
    //inserisco la data di scadenza (1 mese dalla data di inizio)
    $sql = "UPDATE `utenti` SET `fineAbb` = `inizioAbb` + INTERVAL 1 MONTH WHERE `nickname` = '".$utente."'";
    $result1 = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$result1) {
        die ("Errore query 1: " . mysqli_error($link));
    }
?>