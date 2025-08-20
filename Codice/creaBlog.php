<?php
    session_start();
    //mi collego al DB
    include ("connect.php");
    //recupero i dati inseriti
    $titolo = $_POST["titleBlog"];
    $topic = $_POST["topicBlog"];
    $nick = $_SESSION["nickname"];
    //dichiaro $error
    $error = "";
    //controllo che i campi siano stati compilati
    if (!$titolo || !$topic) {
        $error = "Tutti i campi sono obbligatori!";
    }
    //controllo che il titolo non contenga caratteri nocivi (non sono ammessi caratteri accentati e speciali eccetto '-)
    elseif (!preg_match("/^[A-Za-z0-9 \'\-]+$/i", $titolo)) {
        $error = "Il titolo contiene caratteri non ammessi!";
    }
    //controllo che topic non contenga caratteri nocivi (non sono ammessi numeri, caratteri accentati e speciali eccetto '-)
    elseif (!preg_match("/^[A-Za-z \'\-]+$/i", $topic)) {
        $error = "Il tema contiene caratteri non ammessi!";
    }
    //se tutto e' ok...
    if (!$error) {
        //protezione contro SQL Injection
        $titoloEscaped = mysqli_real_escape_string($link, $titolo);
        $topicEscaped = mysqli_real_escape_string($link, $topic);
        //controllo che l'utente sia abbonato
        $sql = "SELECT `inizioAbb` FROM `utenti` WHERE `nickname` = '".$nick."'";
        $result0 = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result0) {
            die ("Errore query 0: " . mysqli_error($link));
        }
        $rows = mysqli_fetch_assoc($result0);
        //se non lo e' stampo l'errore
        if ($rows["inizioAbb"] == NULL) {
            die ("Non puoi creare blog, devi prima abbonarti.");
        }
        //controllo che non esista gia' un blog con lo stesso nome
        $sql = "SELECT `nomeBlog` FROM `blog` WHERE `nomeBlog` = '".$titoloEscaped."'";
        $resultControllo = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$resultControllo) {
            die ("Errore query controllo: " . mysqli_error($link));
        }
        $rows = mysqli_num_rows($resultControllo);
        //se esiste interrompo l'esecuzione e stampo l'errore
        if ($rows > 0) {
            die ("Esiste già un blog con lo stesso nome.");
        }
        //inserisco il titolo del blog nel db
        $sql = "INSERT INTO `blog`(`nomeBlog`) VALUES ('".$titoloEscaped."')";
        $result1 = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result1) {
            die ("Errore query 1: " . mysqli_error($link));
        }
        //controllo che il tema non sia gia' stato inserito nel db
        $sql = "SELECT `nome` FROM `tema` WHERE `nome` = '".$topicEscaped."'";
        $result2 = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result2) {
            die ("Errore query 2: " . mysqli_error($link));
        }
        $rows = mysqli_num_rows($result2);
        //se trovo il tema incremento di 1 la sua popolarita'...
        if ($rows == 1) {
            $sql = "UPDATE `tema` SET `popolarita` = `popolarita` + 1 WHERE `nome` = '".$topicEscaped."'";
            $result3 = mysqli_query($link, $sql);
            //se si verifica un errore..
            if (!$result3) {
                die ("Errore query 3: " . mysqli_error($link));
            }
        }
        //...altrimenti lo registro nel DB e setto la popolarita' a 1
        else {
            $sql = "INSERT INTO `tema`(`nome`, `popolarita`) VALUES ('".$topicEscaped."', 1)";
            $result4 = mysqli_query($link, $sql);
            //se si verifica un errore..
            if (!$result4) {
                die ("Errore query 4: " . mysqli_error($link));
            }
        }
        //inserisco nella tabella "argomento" il tema e l'id del blog
        $sql = "INSERT INTO `argomento`(`nomeTema`, `blog_idArg`) VALUES ('".$topicEscaped."', (SELECT `blog_id` FROM `blog` WHERE `nomeBlog` = '".$titoloEscaped."'))";
        $result5 = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result5) {
            die ("Errore query 5: " . mysqli_error($link));
        }
        //inserisco nella tabella "creazione" l'id del blog e l'autore
        $sql = "INSERT INTO `creazione`(`blog_idC`, `autoreC`) VALUES ((SELECT `blog_id` FROM `blog` WHERE `nomeBlog` = '".$titoloEscaped."'), '".$nick."')";
        $result6 = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result6) {
            die ("Errore query 6: " . mysqli_error($link));
        }
        //stampo l'esito dell'operazione
        echo "OK";
    }
    //...altrimenti stampo l'errore
    else {
        echo $error;
    }
?>