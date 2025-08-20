<?php
    session_start();
    //mi collego al DB
    include ("connect.php");
    //memorizzo e converto eventuali elementi HMTL nel commento
    $commentoChecked = htmlspecialchars($_POST["commento"]);
    //protezione contro SQL Injection
    $commento = mysqli_real_escape_string($link, $commentoChecked);
    //ottengo data e ora del commento
    $dataCommento = date("Y-m-d");
    $oraCommento = date("H:i:s");
    //registro il commento nel DB
    $sql = "INSERT INTO `commento`(`dataCommento`, `oraCommento`, `testoCommento`) VALUES ('".$dataCommento."', '".$oraCommento."', '".$commento."')";
    $resultCommento = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultCommento) {
        die ("Errore query commento: " . mysqli_error($link));
    }
    //ottengo il nickname dell'utente
    $utente = $_SESSION["nickname"];
    //registro l'id del commento e il nickname dell'autore
    $sql = "INSERT INTO `discussione`(`commento_idD`, `autoreD`) VALUES ((SELECT `commento_id` FROM `commento` WHERE `dataCommento` = '".$dataCommento."' AND `oraCommento` = '".$oraCommento."' AND `testoCommento` = '".$commento."'), '".$utente."')";
    $resultDiscussione = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultDiscussione) {
        die ("Errore query discussione: " . mysqli_error($link));
    }
    //ottengo l'id del post
    $post = $_POST["id"];
    //registro l'id del commento e l'id del post
    $sql = "INSERT INTO `appunto`(`commento_idAp`, `post_idAp`) VALUES ((SELECT `commento_id` FROM `commento` WHERE `dataCommento` = '".$dataCommento."' AND `oraCommento` = '".$oraCommento."' AND `testoCommento` = '".$commento."'), '".$post."')";
    $resultAppunto = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultAppunto) {
        die ("Errore query appunto: " . mysqli_error($link));
    }
?>