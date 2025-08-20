<?php
    session_start();
    //mi collego al DB
	include ("connect.php");
    //recupero il nick dell'utente
    $utente = $_SESSION["nickname"];
    //registro in sessione l'id del post
    $post = $_POST["idPost"];
    $_SESSION["idPost"] = $post;
    //controllo che l'utente non abbia gia' messo dislike
    $sql = "SELECT `nicknameDL` FROM `nonmipiace` WHERE `nicknameDL` = '".$utente."' AND `post_idDL` = '".$post."'";
    $resultControllo = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultControllo) {
        die ("Errore query controllo dislike: " . mysqli_error($link));
    }
    $rowsDislike = mysqli_num_rows($resultControllo);
    //controllo anche che l'utente non abbia gia' messo like
    $sql = "SELECT `nicknameL` FROM `mipiace` WHERE `nicknameL` = '".$utente."' AND `post_idL` = '".$post."'";
    $resultControllo2 = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$resultControllo2) {
        die ("Errore query controllo like: " . mysqli_error($link));
    }
    $rowsLike = mysqli_num_rows($resultControllo2);
    //se ha gia' messo dislike glielo revoco...
    if ($rowsDislike > 0) {
        //tolgo 1 dislike dal post
        $sql = "UPDATE `post` SET `nDislike` = `nDislike` - 1 WHERE `post_id` = '".$post."'";
        $resultRevoca = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultRevoca) {
            die ("Errore query revoca dislike: " . mysqli_error($link));
        }
        //cancello il dislike dal DB
        $sql = "DELETE FROM `nonmipiace` WHERE `nicknameDL` = '".$utente."' AND `post_idDL` = '".$post."'";
        $resultCancellaDislike = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultCancellaDislike) {
            die ("Errore query cancella dislike: " . mysqli_error($link));
        }
    }
    //...altrimenti se ha gia' messo like tolgo il like e metto il dislike...
    elseif ($rowsLike > 0) {
        //tolgo 1 like dal post
        $sql = "UPDATE `post` SET `nLike` = `nLike` - 1 WHERE `post_id` = '".$post."'";
        $resultRevoca = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultRevoca) {
            die ("Errore query revoca like: " . mysqli_error($link));
        }
        //cancello il like dal DB
        $sql = "DELETE FROM `mipiace` WHERE `nicknameL` = '".$utente."' AND `post_idL` = '".$post."'";
        $resultCancellaLike = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultCancellaLike) {
            die ("Errore query cancella like: " . mysqli_error($link));
        }
        //aggiungo 1 dislike al post
        $sql = "UPDATE `post` SET `nDislike` = `nDislike` + 1 WHERE `post_id` = '".$post."'";
        $resultDislike = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultDislike) {
            die ("Errore query update post: " . mysqli_error($link));
        }
        //inserisco il dislike nel DB
        $sql = "INSERT INTO `nonmipiace`(`nicknameDL`, `post_idDL`) VALUES ('".$utente."', '".$post."')";
        $resultNonmipiace = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultNonmipiace) {
            die ("Errore query insert nonmipiace: " . mysqli_error($link));
        }
    }
    //...altrimenti metto semplicemente il dislike
    else {
        //aggiungo 1 dislike al post
        $sql = "UPDATE `post` SET `nDislike` = `nDislike` + 1 WHERE `post_id` = '".$post."'";
        $resultDislike = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultDislike) {
            die ("Errore query update post: " . mysqli_error($link));
        }
        //inserisco il dislike nel DB
        $sql = "INSERT INTO `nonmipiace`(`nicknameDL`, `post_idDL`) VALUES ('".$utente."', '".$post."')";
        $resultNonmipiace = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultNonmipiace) {
            die ("Errore query insert nonmipiace: " . mysqli_error($link));
        }
    }
?>