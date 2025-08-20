<?php
    session_start();
    //mi collego al DB
	include ("connect.php");
    //recupero il nick dell'utente
    $utente = $_SESSION["nickname"];
    //controllo se ci sono blog creati dall'utente (cioe' di cui e' l'autore)
    $sql = "SELECT `blog_idC` FROM `creazione` WHERE `autoreC` = '".$utente."'";
    $resultControllo = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultControllo) {
        die ("Errore query controllo blog: " . mysqli_error($link));
    }
    $rows = mysqli_num_rows($resultControllo);
    //se ci sono blog creati dall'utente li elimino
    if ($rows > 0) {
        //per ogni blog creato dall'utente memorizzo nella sessione l'id del blog e includo eliminaBlog.php
        while ($fetchBlog = mysqli_fetch_assoc($resultControllo)) {
            $_SESSION["idBlog"] = $fetchBlog["blog_idC"];
            include ("eliminaBlog.php");
        }
    }
    //controllo se ci sono commenti legati all'utente
    $sql = "SELECT `commento_idD` FROM `discussione` WHERE `autoreD` = '".$utente."'";
    $resultControllo = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultControllo) {
        die ("Errore query controllo commenti: " . mysqli_error($link));
    }
    $rows = mysqli_num_rows($resultControlloCommenti);
    //se ci sono commenti li elimino...
    if ($rows > 0) {
        while ($fetchCommento = mysqli_fetch_assoc($resultControllo)) {
            $commento = $fetchCommento["commento_idD"];
            $sql = "DELETE FROM `commento` WHERE `commento_id` = '".$commento."'";
            $resultEliminaCommento = mysqli_query($link, $sql);
            //se si verifica un errore..
            if (!$resultEliminaCommento) {
                die ("Errore query eliminazione commento: " . mysqli_error($link));
            }
        }
    }
    //rimuovo il profilo dal DB
    $sql = "DELETE FROM `utenti` WHERE `nickname` = '".$utente."'";
    $resultEliminaProfilo = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultEliminaProfilo) {
        die ("Errore query eliminazione profilo: " . mysqli_error($link));
    }
    //effettuo il logout
    include ("logout.php");
?>