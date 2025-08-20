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
        <title>P-Link | Accedi</title>
        <script src="jquery-3.4.1.js"></script>
        <script type="text/javascript">
        //al caricamento della pagina
            $(document).ready(function() {
                //se clicco Accedi
                $("#login").click(function(e) {
                    //evita il comportamento di default del form
                    e.preventDefault();
                    //ottengo i dati inseriti
                    var $nickname = $("#0").val();
                    var $pass = $("#1").val();
                    //AJAX - esegue il login
                    $.ajax({
                        url: "login.php",
                        method: "POST",
                        data: "nickname="+$nickname+"&pass="+$pass,
                        dataType: "html",
                        success: function(data) {
                            //rimuovo eventuale testo dallo span
                            $("#erroreLogin").text("");
                            //se il login avviene correttamente...
                            if (data == "OK") {
                                //se l'utente vuole essere ricordato reindirizzo alla registraCookie.php (che poi reindirizzera' alla userpage)
                                if ($("#ricorda").is(":checked")) {
                                    $(window.location).attr("href", "registraCookie.php");
                                }
                                //altrimenti reindirizzo subito alla userpage
                                else {
                                    $(window.location).attr("href", "userpage.php");
                                }
                            }
                            //...altrimenti se non sono stati compilati tutti i campi stampo l'errore e segno in rosso quelli vuoti...
                            else if (data == "Tutti i campi sono obbligatori!") {
                                $("#erroreLogin").text(data);
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
                            else if (data.substr(1, 12) == "Errore query") {
                                $("#erroreLogin").text(data);
                            }
                            //...altrimenti stampo gli errori e segno in rosso i relativi campi
                            else {
                                //converto l'array di PHP come oggetto JSON
                                var dataArray = $.parseJSON(data);
                                //definisco un array di errori
                                var lista_errori = ["Nickname errato", "Password errata"];
                                //ciclo sull'array passato dal PHP
                                for (i = 0; i < dataArray.length; ++i) {
                                    //se il valore letto e' 1 stampo il relativo messaggio di errore e segno di rosso il relativo campo
                                    if (dataArray[i] == "1") {
                                        var errore = lista_errori[i];
                                        //se lo span non e' vuoto allora aggiungo l'errore andando a capo
                                        if ($("#erroreLogin:empty")) {
                                            $("#erroreLogin").append("<br>"+errore);
                                        }
                                        //altrimenti stampo semplicemente l'errore
                                        else {
                                            $("#erroreLogin").text(errore);
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
                            <input type="button" class="button" value="Registrati" onclick="location.href='registrazione.php'" id="linkRec" />
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
                    <!-- logo sito -->
                    <img src="img/logo.png" id="logo" alt="Logo"/>
                    <!-- form login -->
                    <h2>Login</h2>
                    <form name="formLogin">
                        Nickname<br>
                        <input type="text" name="nickname" id="0"><br><br>
                        Password<br>
                        <input type="password" name="pass" id="1"><br><br>
                        <input type="checkbox" name="ricorda" id="ricorda"><label for="ricorda">Mantieni l'accesso</label><br><br>
                        <!-- span per eventuali errori di login -->
                        <span id="erroreLogin"></span>
                        <input type="submit" value="Accedi" id="login" class="button">
                    </form>
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