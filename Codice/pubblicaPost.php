<?php
    session_start();
    //mi collego al DB
    include ("connect.php");
    //converto eventuali elementi HMTL nei dati inseriti
    $titoloChecked = htmlspecialchars($_POST["titoloPost"]);
    $testoChecked = htmlspecialchars($_POST["testoPost"]);
    //memorizzo i dati del form (protezione contro SQL Injection)
    $titolo = mysqli_real_escape_string($link, $titoloChecked);
    $testo = mysqli_real_escape_string($link, $testoChecked);
    //ottengo data e ora corrente
    $data = date("Y-m-d");
    $ora = date("H:i:s");
    //dichiaro $error
    $error = "";
    //controllo che titolo e testo siano stati inseriti
    if (strlen($titolo) < 1 ) {
        $error = "Il titolo è obbligatorio!";
    }
    elseif (strlen($testo) < 1) {
        $error = "Il testo è obbligatorio!";
    }
    //controllo che le informazioni inserite non superino la lunghezza massima
    elseif (strlen($titolo) > 40) {
        $error = "Il titolo non deve superare i 40 caratteri!";
    }
    elseif (strlen($testo) > 400) {
        $error = "Il testo non deve superare i 400 caratteri!";
    }
    //se non ci sono errori registro i dati nel DB...
    if (!$error) {
        //ottengo dalla sessione l'id del blog e il nick dell'utente
        $idBlog = $_SESSION["idBlog"];
        $nick = $_SESSION["nickname"];
        //controllo che sia stata caricata un'immagine...
        if ($_FILES["input_img"]["tmp_name"] != "") {
            //ottengo informazioni immagine
            $filetmp = $_FILES["input_img"]["tmp_name"];
            $filename = $_FILES["input_img"]["name"];
            $filesize = $_FILES["input_img"]["size"];
            //controllo che la grandezza non superi 1 MB
            $imgSize = filesize($filetmp);
            $imgSizeMB = ($imgSize / 1024) / 1024;
            $sizeMax = 1;
            //se lo supera stampo l'errore...
            if (!$imgSize || $imgSizeMB > $sizeMax) {
                die ("Errore immagine. Il peso non deve superare 1 MB.");
            }
            else {
                //...altrimenti la inserisco nel DB
                if (is_uploaded_file($filetmp) == True) {
                    //registro i dati del post
                    $sql = "INSERT INTO `post`(`dataPost`, `oraPost`, `titoloPost`, `testoPost`) VALUES ('".$data."', '".$ora."', '".$titolo."', '".$testo."')";
                    $result0 = mysqli_query($link, $sql);
                    //se si verifica un errore..
                    if (!$result0) {
                        die ("Errore query 0: " . mysqli_error($link));
                    }
                    //ottengo l'id del post
                    $sql = "SELECT `post_id` FROM `post` WHERE `dataPost` = '".$data."' AND `oraPost` = '".$ora."' AND `titoloPost` = '".$titolo."' AND `testoPost` = '".$testo."'";
                    $result1 = mysqli_query($link, $sql);
                    //se si verifica un errore..
                    if (!$result1) {
                        die ("Errore query 1: " . mysqli_error($link));
                    }
                    $idPost = mysqli_fetch_assoc($result1);
                    //memorizzo l'immagine nella cartella...
                    $filepath = "photo/".$idPost["post_id"].".".$filename;
                    move_uploaded_file($filetmp, $filepath);
                    //...e nel DB
                    $sql = "INSERT INTO `immagine`(`dataImg`, `oraImg`, `pathImg`) VALUES ('".$data."', '".$ora."', '".$filepath."')";
                    $result2 = mysqli_query($link, $sql);
                    //se si verifica un errore..
                    if (!$result2) {
                        die ("Errore query 2: " . mysqli_error($link));
                    }
                    //inserisco l'id del post e dell'immagine nella tabella "allegato"
                    $sql3 = "INSERT INTO `allegato`(`post_idAl`, `img_idAl`) VALUES ('".$idPost["post_id"]."', (SELECT `img_id` FROM `immagine` WHERE `dataImg` = '".$data."' AND `oraImg` = '".$ora."' AND `pathImg` = '".$filepath."'))";
                    $result3 = mysqli_query($link, $sql3);
                    //se si verifica un errore..
                    if (!$result3) {
                        die ("Errore query 3: " . mysqli_error($link));
                    }
                    //inserisco gli id di blog e post in "articolazione"
                    $sql4 = "INSERT INTO `articolazione`(`blog_idAr`, `post_idAr`) VALUES ('".$idBlog."', '".$idPost["post_id"]."')";
                    $result4 = mysqli_query($link, $sql4);
                    //se si verifica un errore..
                    if (!$result4) {
                        die ("Errore query 4: " . mysqli_error($link));
                    }
                    //libero la memoria temporanea del file
                    unset ($_FILES["input_img"]);
                    //memorizzo l'id del post nella sessione
                    $_SESSION["idPost"] = $idPost["post_id"];
                    //stampo l'esito dell'operazione
                    echo "OK";
                }
            }
        }
        //...altrimenti registro solo il post
        else {
            $sql = "INSERT INTO `post`(`dataPost`, `oraPost`, `titoloPost`, `testoPost`) VALUES ('".$data."', '".$ora."', '".$titolo."', '".$testo."')";
            $result0 = mysqli_query($link, $sql);
            //se si verifica un errore..
            if (!$result0) {
                die ("Errore query 0: " . mysqli_error($link));
            }
            //ottengo l'id del post
            $sql = "SELECT `post_id` FROM `post` WHERE `dataPost` = '".$data."' AND `oraPost` = '".$ora."' AND `titoloPost` = '".$titolo."' AND `testoPost` = '".$testo."'";
            $result1 = mysqli_query($link, $sql);
            //se si verifica un errore..
            if (!$result1) {
                die ("Errore query 1: " . mysqli_error($link));
            }
            $idPost = mysqli_fetch_assoc($result1);
            //inserisco gli id in "articolazione"
            $sql = "INSERT INTO `articolazione`(`blog_idAr`, `post_idAr`) VALUES ('".$idBlog."', '".$idPost["post_id"]."')";
            $result4 = mysqli_query($link, $sql);
            //se si verifica un errore..
            if (!$result4) {
                die ("Errore query 4: " . mysqli_error($link));
            }
            //memorizzo l'id del post nella sessione
            $_SESSION["idPost"] = $idPost["post_id"];
            //stampo l'esito dell'operazione
            echo "OK";
        }
    //...altrimenti stampo l'errore
    }
    else {
        echo $error;
    }
?>