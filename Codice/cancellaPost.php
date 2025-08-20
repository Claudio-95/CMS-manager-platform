<?php
    session_start();
    //mi collego al DB
    include ("connect.php");
    //ottengo l'id del post
    $idPost = $_POST["id"];
    //controllo se c'è un'immagine allegata al post
    $sql = "SELECT `img_idAl` FROM `allegato` WHERE `post_idAl` = '".$idPost."'";
    $resultControlloImmagine = mysqli_query($link, $sql);
    //se ci sono errori..
    if (!$resultControlloImmagine) {
        die ("Errore query controllo immagine: " . mysqli_error($link));
    }
    $rows = mysqli_num_rows($resultControlloImmagine);
    //se c'e' un'immagine la elimino
    if ($rows > 0) {
        //memorizzo l'id dell'immagine
        $fetchImg = mysqli_fetch_assoc($resultControlloImmagine);
        $immagine = $fetchImg["img_idAl"];
        //query per eliminare l'immagine
        $sql = "DELETE FROM `immagine` WHERE `img_id` = '".$immagine."'";
        $resultEliminaImmagine = mysqli_query($link, $sql);
        //se ci sono errori..
        if (!$resultEliminaImmagine) {
            die ("Errore query eliminazione immagine: " . mysqli_error($link));
        }
    }
    //controllo se ci sono commenti relativi al post
    $sql = "SELECT `commento_idAp` FROM `appunto` WHERE `post_idAp` = '".$idPost."'";
    $resultControlloCommenti = mysqli_query($link, $sql);
    //se ci sono errori..
    if (!$resultControlloCommenti) {
        die ("Errore query controllo commenti: " . mysqli_error($link));
    }
    $rows = mysqli_num_rows($resultControlloCommenti);
    //se ci sono commenti li elimino tutti
    if ($rows > 0) {
        while ($fetchCommento = mysqli_fetch_assoc($resultControlloCommenti)) {
            //memorizzo il commento
            $commento = $fetchCommento["commento_idAp"];
            //query per eliminare il commento
            $sql = "DELETE FROM `commento` WHERE `commento_id` = '".$commento."'";
            $resultEliminaCommento = mysqli_query($link, $sql);
            //se ci sono errori..
            if (!$resultEliminaCommento) {
                die ("Errore query eliminazione commento: " . mysqli_error($link));
            }
        }
    }
    //elimino il post
    $sql = "DELETE FROM `post` WHERE `post_id` = '".$idPost."'";
    $resultEliminaPost = mysqli_query($link, $sql);
    //se ci sono errori..
    if (!$resultEliminaPost) {
        die ("Errore query eliminazione post: " . mysqli_error($link));
    }
?>