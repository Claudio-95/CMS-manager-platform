<?php
    session_start();
    //se per l'utente è settato un cookie allora lo registro in sessione
    if (isset($_COOKIE["Login"])) {
        $_SESSION["nickname"] = $_COOKIE["Login"];
        $_SESSION["login"] = "OK";
    }
    //se l'utente tenta di accedere ma e' gia' loggato lo reindirizzo alla userpage
    if((isset($_SESSION["login"])) AND ($_SESSION["login"] == "OK")) {
        header("Location: userpage.php");
    }
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <link rel="stylesheet" href="Default.css" />
        <link rel="icon" type="image/ico" href="img/favicon.ico" />
        <title>P-Link | Registrazione</title>
        <script src="jquery-3.4.1.js"></script>
        <script type="text/javascript">
            //al caricamento della pagina
            $(document).ready(function(){
                //se clicco Registrati
                $("#rec").click(function(e) {
                    //evita il comportamento di default del form
                    e.preventDefault();
                    //ottengo i dati inseriti nel form
                    var data = $("form")[1];
                    var formData = new FormData(data);
                    //AJAX - esegue la registrazione dell'utente nel DB
                    $.ajax({
                        url: "registration.php",
                        method: "POST",
                        type: "POST",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            //rimuovo eventuale testo dallo span
                            $("#erroreRec").text("");
                            //se tutto avviene correttamente...
                            if (data == "OK") {
                                //reindirizzo alla userpage
                                $(window.location).attr("href", "userpage.php");
                            }
                            //...altrimenti se non sono stati compilati tutti i campi stampo l'errore e segno in rosso quelli vuoti...
                            else if (data == "Tutti i campi sono obbligatori!") {
                                $("#erroreRec").text(data);
                                $("form").find("input").each(function() {
                                    //se il campo e' vuoto segno il bordo di rosso
                                    if ($(this).val() == "") {
                                        $(this).css("border-color", "red");
                                    }
                                    //altrimenti lo lascio col bordo di default
                                    else {
                                        $(this).css("border-color", "initial");
                                    }
                                });
                            }
                            //...altrimenti se si e' verificato un errore nella query stampo l'errore...
                            else if (data.substr(1,26) == "Errore query registrazione") {
                                $("#erroreRec").text("Nickname e/o email già esistenti");
                            }
                            //...altrimenti stampo gli errori e segno in rosso i relativi campi
                            else {
                                //converto l'array di PHP come oggetto JSON
                                var dataArray = $.parseJSON(data);
                                //definisco un array di errori
                                var lista_errori = ["Il nickname contiene caratteri non ammessi!", "La password contiene caratteri non ammessi oppure non rispetta la lunghezza richiesta (minimo 4 e massimo 16)", "Inserire un'email valida", "Inserire numero di telefono/cellulare valido", "Inserire numero di documento valido", "Provieni dal futuro D:", "Inserire luogo di rilascio valido", "Inserire ente di rilascio valido"];
                                //ciclo sull'array passato dal PHP
                                for (i = 0; i < dataArray.length; ++i) {
                                    //se il valore letto e' 1 stampo il relativo messaggio di errore e segno di rosso il relativo campo
                                    if (dataArray[i] == "1") {
                                        var errore = lista_errori[i];
                                        //se lo span non e' vuoto allora aggiungo l'errore andando a capo
                                        if ($("#erroreRec:empty")) {
                                            $("#erroreRec").append("<br>"+errore);
                                        }
                                        //altrimenti stampo semplicemente l'errore
                                        else {
                                            $("#erroreRec").text(errore);
                                        }
                                        $("#"+ i).css("border-color", "red");
                                    }
                                    //altrimenti lo lascio col bordo di default
                                    else {
                                        $("#"+ i).css("border-color", "initial");
                                    }
                                }
                            }
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
        <div id="page-container">
            <div id="content-wrap">
                <header>
                    <div class="header">
                        <!-- logo sito -->
                        <img src="img/logo.png" id="logo" alt="Logo"/>
                        <!-- menu' -->
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="userpage.php">Area privata</a></li>
                        </ul>
                        <div class="menu">
                            <input type="button" class="button" value="Accedi" onclick="location.href='accedi.php'" id="linkLogin" />
                        </div>
                        <div class="search-container">
                            <form action="search.php" method="get">
                                <input type="text" placeholder="Cerca..." name="search">
                                <input type="submit" id="searchSubmit" value="&#x02315;"/>
                            </form>
                        </div>
                    </div>
                </header>
                <div class="content">
                    <!-- form registrazione -->
                    <h2>Registrazione</h2>
                    <div class="formRec">
                        <p>Attraverso questo modulo di registrazione sarà possibile iscriversi alla community del sito. Potrete avere un vostro profilo, commentare i post e mettere Mi piace o Non mi piace. Inoltre, se in seguito si sceglie di sottoscrivere l'abbonamento premium, sar&agrave; possibile anche creare, modificare e gestire blog e pubblicare post.</p>
                        <p>Il <strong>nickname</strong> non pu&ograve; contenere spazi, caratteri accentati e caratteri speciali eccetto <strong>'</strong> <strong>-</strong> <strong>_</strong>. La <strong>password</strong> deve avere almeno 4 caratteri ed un massimo di 16; inoltre non pu&ograve; contenere spazi, caratteri accentati e caratteri speciali eccetto <strong>'</strong> <strong>-</strong> <strong>_</strong>.</p>
                        <p><strong>N.B.:</strong> i campi sono tutti obbligatori.</p>
                        <form id="formRegistrazione">
                            <fieldset id="generali">
                                <legend><b>Generali</b></legend>
                                Nickname<br>
                                <input type="text" name="nickname" id="0"><br><br>
                                Password<br>
                                <input type="password" name="pass" id="1"><br><br>
                                Email<br>
                                <input type="text" name="email" id="2"><br><br>
                                Telefono/Cellulare<br>
                                <input type="text" name="telefono" id="3"><br><br>
                            </fieldset>
                            <fieldset id="documento">
                                <legend><b>Documento d'identit&agrave;</b></legend>
                                Numero<br>
                                <input type="text" name="numeroDoc" id="4"><br><br>
                                Data rilascio<br>
                                <input type="date" name="dataDoc" id="5"><br><br>
                                Luogo rilascio<br>
                                <input type="text" name="luogoDoc" id="6"><br><br>
                                Ente rilascio<br>
                                <input type="text" name="enteDoc" id="7"><br><br>
                            </fieldset>
                            <!-- span per eventuali errori di registrazione -->
                            <span id="erroreRec"></span>
                            <input type="submit" value="Registrati" id="rec" class="button">
                        </form>
                    </div>
                </div>
            </div>
            <footer>
                <div class="footer">
                    <p>Ideato e progettato da: </p>
                    <address>
                        <b>Claudio De Martino</b><br />
                        <a href="mailto:claudio.demartino11@gmail.com">claudio.demartino11@gmail.com</a>
                    </address>
                </div>
            </footer>
        </div>
    </body>
</html>