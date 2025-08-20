<?php
    session_start();
    //recupero i dati inseriti
    $nick = $_POST["nickname"];
    //(aggiungo salt alla password per aumentarne la sicurezza)
    $saltedPass = "s4lt3d".$_POST["pass"]."p4ssw0rd";
    $email = $_POST["email"];
    $tel = $_POST["telefono"];
    $nDoc = $_POST["numeroDoc"];
    $dataDoc = $_POST["dataDoc"];
    $luogoDoc = $_POST["luogoDoc"];
    $enteDoc = $_POST["enteDoc"];
    //metto in sicurezza la password
    $password = password_hash($saltedPass, PASSWORD_DEFAULT);
    //converto la data nel formato accettato dal DB
    $dataDocConverted = date("Y-m-d", strtotime($dataDoc));
    //ottengo la data corrente
    $date = getdate(date("U"));
    $current_timeStr = "$date[year]-$date[mon]-$date[mday]";
    $current_date = date("Y-m-d", strtotime($current_timeStr));
    //dichiaro $error
    $error = "";
    //se i campi non sono stati compilati stampo l'errore ed esco dal codice
    if (!$nick || !$_POST["pass"] || !$email || !$tel || !$nDoc || !$dataDoc || !$luogoDoc || !$enteDoc) {
        echo "Tutti i campi sono obbligatori!";
        exit();
    }
    //controllo che il nickname non contenga caratteri nocivi (non sono ammessi spazi, caratteri accentati e caratteri speciali eccetto '-_)
    if (!preg_match("/^[A-Za-z0-9\'\-\_]+$/i", $nick)) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e0 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e0 = "0";
    }
    //controllo che la password non contenga caratteri nocivi (non sono ammessi spazi, caratteri accentati e caratteri speciali eccetto '-_) e che sia di almeno 4 caratteri e non superi i 16
    if (!preg_match("/^[A-Za-z0-9\'\-\_]+$/i", $_POST["pass"]) || strlen($_POST["pass"]) < 4 || strlen($_POST["pass"]) > 16) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e1 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e1 = "0";
    }
    //controllo che nell'email ci sia la @
    if (!preg_match("/^[A-Za-z0-9\.\-\_]+@[A-Za-z\.]+$/i", $email)) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e2 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e2 = "0";
    }
    //controllo che il telefono/cellulare sia un numero e che contenga tra gli 6 e i 12 caratteri
    if (!preg_match("/^[0-9]{6,12}$/", $tel)) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e3 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e3 = "0";
    }
    //controllo che il numero di documento sia almeno di 6 caratteri
    if (strlen($nDoc) < 6)  {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e4 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e4 = "0";
    }
    //controllo che la data inserita non superi quella attuale
    if ($dataDocConverted > $current_date) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e5 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e5 = "0";
    }
    //controllo che luogo di rilascio sia scritto correttamente (ammessi solo lettere maiuscole e minuscole e spazi)
    if (!preg_match("/^[A-Za-z ]+$/i", $luogoDoc)) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e6 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e6 = "0";
    }
    //controllo che ente di rilascio sia scritto correttamente (ammessi solo lettere maiuscole e minuscole e spazi)
    if (!preg_match("/^[A-Za-z ]+$/i", $enteDoc)) {
        $error = "error";
        //1 significa che e' questo l'errore e serve per identificarlo al jQuery
        $e7 = "1";
    }
    else {
        //0 significa che non ci sono errori e serve per il jQuery
        $e7 = "0";
    }
    //se tutto e' ok...
    if (!$error) {
        //mi collego al DB
        include ("connect.php");
        //protezione contro SQL Injection
        $nickEscaped = mysqli_real_escape_string($link, $nick);
        $passwordEscaped = mysqli_real_escape_string($link, $password);
        $emailEscaped = mysqli_real_escape_string($link, $email);
        $telEscaped = mysqli_real_escape_string($link, $tel);
        $nDocEscaped = mysqli_real_escape_string($link, $nDoc);
        $luogoDocEscaped = mysqli_real_escape_string($link, $luogoDoc);
        $enteDocEscaped = mysqli_real_escape_string($link, $enteDoc);
        //registro i dati inseriti nel DB
        $sql = "INSERT INTO `utenti`(`nickname`, `password`, `email`, `telefono`, `nDocumento`, `dataDocumento`, `luogoDocumento`, `enteDocumento`) VALUES ('".$nickEscaped."', '".$passwordEscaped."', '".$emailEscaped."', '".$telEscaped."', '".$nDocEscaped."', '".$dataDocConverted."', '".$luogoDocEscaped."', '".$enteDocEscaped."')";
        $result = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result) {
            die (json_encode("Errore query registrazione: " . mysqli_error($link)));
        }
        //memorizzo l'utente e lo status del login nella sessione
        $_SESSION["login"] = "OK";
        $_SESSION["nickname"] = $nickEscaped;
        //stampo l'esito dell'operazione
        echo "OK";
    }
    //...altrimenti stampo stampo l'array di stato di errori
    else {
        echo json_encode(array($e0, $e1, $e2, $e3, $e4, $e5, $e6, $e7));
    }
?>