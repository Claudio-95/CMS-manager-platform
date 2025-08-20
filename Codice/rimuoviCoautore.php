<?php
    session_start();
    //mi collego al DB
	include ("connect.php");
    //ottengo il nick dell'autore e l'id del blog
    $utente = $_SESSION["nickname"];
    $idBlog = $_SESSION["idBlog"];
    //ottengo il nick coautore da rimuovere
    $coautore = $_POST["coautore"];
    //rimuovo il coautore dal DB
    $sql = "UPDATE `creazione` SET `coautoreC` = NULL WHERE `autoreC` = '".$utente."' AND `blog_idC` = '".$idBlog."' AND `coautoreC` = '".$coautore."'";
    $resultRimuovi = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultRimuovi) {
        die ("Errore query rimozione coautore: " . mysqli_error($link));
    }
?>