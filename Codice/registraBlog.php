<?php
    session_start();
    //mi collego al DB
	include ("connect.php");
    //se l'id risulta passato correttamente...
	if (isset($_POST["id"])) {
        //recupero id del blog e lo memorizzo in sessione
        $id = $_POST["id"];
        $_SESSION["idBlog"] = $id;
		//recupero il nome del blog
		$sql = "SELECT `nomeBlog` FROM `blog` WHERE `blog_id` = '".$id."'";
		$result = mysqli_query($link, $sql);
        //se si verifica un errore..
        if (!$result) {
            die ("Errore query registrazione blog: " . mysqli_error($link));
        }
        //memorizzo il nome del blog in sessione
		$blog = mysqli_fetch_assoc($result);
		$_SESSION["nomeBlog"] = $blog["nomeBlog"];
    }
    //...altrimenti stampo l'errore
    else {
        die ("Errore: " . mysqli_error($link));
    }
?>