<?php
    session_start();
    //mi collego al DB
	include ("connect.php");
    //se la grafica risulta passata correttamente...
	if (isset($_POST["grafica"])) {
        //ottengo l'id del blog
        $idBlog = $_SESSION["idBlog"];
        //ottengo la grafica selezionata dall'utente
        $nuovaGrafica = $_POST["grafica"];
        //registro nel DB il cambio di grafica
        $sql = "UPDATE `blog` SET `graficaBlog` = '".$nuovaGrafica."' WHERE `blog_id` = '".$idBlog."'";
        $result = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result) {
            die ("Errore query cambio grafica: " . mysqli_error($link));
        }
    }
    //...altrimenti stampo l'errore
    else {
        die ("Errore: non hai selezionato nessuna grafica (oppure stai tentando di cambiarla senza averne i permessi, in tal caso vergognati).");
    }
?>