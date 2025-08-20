<?php
    session_start();
    //mi collego al DB
	include ("connect.php");
    //ottengo il nick del coautore e l'id del blog
    $utente = $_SESSION["nickname"];
    $idBlog = $_SESSION["idBlog"];
    //ottengo il nick autore blog
    $autore = $_POST["autore"];
    //rimuovo il coautore dal DB
    $sql = "UPDATE `creazione` SET `coautoreC` = NULL WHERE `blog_idC` = '".$idBlog."' AND `coautoreC` = '".$utente."' AND `autoreC` = '".$autore."'";
    $resultRimuovi = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultRimuovi) {
        die ("Errore query rimozione coautore: " . mysqli_error($link));
    }
?>