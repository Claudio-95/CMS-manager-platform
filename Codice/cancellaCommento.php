<?php
    session_start();
    //mi collego al DB
    include ("connect.php");
    //ottengo l'id del commento
    $idCommento = $_POST["id"];
    //elimino il commento
    $sql = "DELETE FROM `commento` WHERE `commento_id` = '".$idCommento."'";
    $resultEliminaCommento = mysqli_query($link, $sql);
    //se ci sono errori..
    if (!$resultEliminaCommento) {
        die ("Errore query eliminazione commento: " . mysqli_error($link));
    }
?>