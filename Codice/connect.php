<?php
    //configurazione connessione
    include ("config.php");
    //connessione
    $link = mysqli_connect($DB_host, $DB_user, $DB_password);
    //se la connessione non va a buon fine
    if (!$link) {
        die ("Non riesco a connettermi: " . mysqli_error($link));
    }
    //selezione del DB
    $db_selected = mysqli_select_db($link, $DB_name);
    //se la selezione non va a buon fine
    if (!$db_selected) {
        die ("Errore nella selezione del database: " . mysqli_error($link));
    }
?>