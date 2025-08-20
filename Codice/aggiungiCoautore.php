<?php
    session_start();
    //mi collego al DB
	include ("connect.php");
    //ottengo nick da ricercare (protezione contro SQL Injection)
    $ricerca = mysqli_real_escape_string($link, $_POST["searchNick"]);
    //memorizzo il nick dell'utente loggato in modo da escluderlo dalla ricerca
    $utente = $_SESSION["nickname"];
    //ottengo l'id del blog
    $idBlog = $_SESSION["idBlog"];
    //cerco il nick nel DB
    $sql = "SELECT `nickname` FROM `utenti` WHERE `nickname` = '".$ricerca."' AND `nickname` != '".$utente."'";
    $resultRicerca = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultRicerca) {
        die ("Errore query ricerca nick: " . mysqli_error($link));
    }
    $rows = mysqli_num_rows($resultRicerca);
    //se trovo l'utente lo memorizzo e lo registro nel DB...
    if ($rows > 0) {
        $trovato = mysqli_fetch_assoc($resultRicerca);
        //aggiorno il DB inserendo il nick del coautore
        $sql = "UPDATE `creazione` SET `coautoreC` = '".$trovato["nickname"]."' WHERE `blog_idC` = '".$idBlog."'";
        $resultCoautore = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultCoautore) {
            die ("Errore query coautore: " . mysqli_error($link));
        }
        //stampo 1 (serve per AJAX)
        echo "1";
    }
    //...altrimenti stampo 0 (serve per AJAX)
    else {
        echo "0";
    }
?>