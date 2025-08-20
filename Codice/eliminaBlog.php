<?php
    session_start();
    //mi collego al DB
	include ("connect.php");
    //ottengo l'id del blog
    $idBlog = $_SESSION["idBlog"];
    //controllo che l'utente sia l'autore del blog (per evitare esecuzioni malevoli)
    $sql = "SELECT `autoreC` FROM `creazione` WHERE `blog_idC` = '".$idBlog."'";
    $resultControlloUtente = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultControlloUtente) {
        die ("Errore query controllo utente: " . mysqli_error($link));
    }
    $rows = mysqli_num_rows($resultControlloUtente);
    //se l'utente non e' l'autore del blog blocco l'esecuzione del codice e stampo l'errore
    if (!isset($_SESSION["nickname"]) || $rows["autoreC"] != $_SESSION["nickname"]) {
        die ("STAI TENTANDO DI CANCELLARE IL BLOG DI UN ALTRO UTENTE. NON SI FA!");
    }
    //controllo se ci sono post nel blog
    $sql = "SELECT `post_idAr` FROM `articolazione` WHERE `blog_idAr` = '".$idBlog."'";
    $resultControlloPost = mysqli_query($link, $sql);
    $rows = mysqli_num_rows($resultControlloPost);
    //se ci sono post li elimino
    if ($rows > 0) {
        //per ogni post memorizzo nella variabile $_POST l'id del post e includo cancellaPost.php
        while ($fetchPost = mysqli_fetch_assoc($resultControlloPost)) {
            $_POST["id"] = $fetchPost["post_idAr"];
            include ("cancellaPost.php");
        }
    }
    //rimuovo il blog dal DB
    $sql = "DELETE FROM `blog` WHERE `blog_id` = '".$idBlog."'";
    $resultEliminaBlog = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultEliminaBlog) {
        die ("Errore query eliminazione blog: " . mysqli_error($link));
    }
?>