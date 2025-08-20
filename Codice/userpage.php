<?php
    session_start();
    //se per l'utente è settato un cookie allora lo registro in sessione
    if (isset($_COOKIE["Login"])) {
        $_SESSION["nickname"] = $_COOKIE["Login"];
        $_SESSION["login"] = "OK";
    }
    //se l'utente tenta di accedere alla pagina ma non e' loggato lo reindirizzo alla pagina di accesso
    if((!isset($_SESSION["login"])) || ($_SESSION["login"] != "OK")) {
        header("Location: accedi.php");
    }
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <link rel="stylesheet" href="Default.css" />
        <link rel="icon" type="image/ico" href="img/favicon.ico" />
        <title>P-Link | Area privata</title>
        <script src="jquery-3.4.1.js"></script>
        <script type="text/javascript">
            //al caricamento della pagina
            $(document).ready(function() {
                //recupera l'id del blog cliccato
                $('.nomeBlog').click(function() {
                    var $Obj = $(this).next(); //(seleziona lo span che contiene l'id del blog)
                    var $id = $Obj.text();
                    //AJAX - registra l'id nella sessione
                    $.ajax({
                        url: "registraBlog.php",
                        method: "POST",
                        data: "id="+$id,
                        dataType: "html",
                        success: function() {
                            //reindirizzo alla pagina blog
                            $(window.location).attr("href", "blog.php");
                        }
                    });
                });
                //se clicco Cancella profilo
                $("#confermaEliminazione").click(function(){
                    //AJAX - cancello tutti i dati associati all'utente dal DB
                    $.ajax({
                        url: "eliminaProfilo.php",
                        method: "POST",
                        success: function() {
                            //reindirizzo alla pagina di login
                            $(window.location).attr("href", "accedi.php");
                        }
                    });
                });
                //se clicco Si (conferma di abbonamento)
                $("#confermaAbb").click(function(){
                    //AJAX - registro l'abbonamento nel DB
                    $.ajax({
                        url: "abbonamento.php",
                        method: "POST",
                        success: function() {
                            //eseguo il refresh della pagina
                            $(window.location).attr("href", "userpage.php");
                        }
                    });
                });
                //se clicco crea (blog)
                $("#creaBlog").click(function(e) {
                    //evita il comportamento di default del form
                    e.preventDefault();
                    //ottengo i dati inseriti
                    var $nome = $("#titleBlog").val();
                    var $tema = $("#topicBlog").val();
                    //AJAX - esegue il login
                    $.ajax({
                        url: "creaBlog.php",
                        method: "POST",
                        data: "titleBlog="+$nome+"&topicBlog="+$tema,
                        dataType: "html",
                        success: function(data) {
                            //se il login avviene correttamente...
                            if (data == "OK") {
                                //eseguo il refresh della pagina
                                $(window.location).attr("href", "userpage.php");
                            }
                            //...altrimenti stampo l'errore
                            else {
                                $("#erroreBlog").text(data);
                            }
                        }
                    });
                });
            });
            //JAVASCRIPT - apre e chiude il modale (abbonamento)
            function apriModaleAbb () {
                document.getElementById("modaleAbb").style.display = "block";
            }
            function chiudiModaleAbb () {
                document.getElementById("modaleAbb").style.display = "none";
            }
            //se clicco fuori dal modale si chiude (abbonamento)
            window.onclick = function(event) {
                if (event.target == document.getElementById("modaleAbb")) {
                    document.getElementById("modaleAbb").style.display = "none";
                }
            }//apre e chiude il modale (cancellazione profilo)
            function apriModaleProf () {
                document.getElementById("modaleProf").style.display = "block";
            }
            function chiudiModaleProf () {
                document.getElementById("modaleProf").style.display = "none";
            }
            //se clicco fuori dal modale si chiude (cancellazione profilo)
            window.onclick = function(event) {
                if (event.target == document.getElementById("modaleProf")) {
                    document.getElementById("modaleProf").style.display = "none";
                }
            }
            //apre e chiude il form creazione blog
            function apriFormBlog () {
                document.getElementById("formBlog").style.display = "block";
                document.getElementById("newBlog").style.display = "none";
            }
            function chiudiFormBlog () {
                document.getElementById("formBlog").style.display = "none";
                document.getElementById("newBlog").style.display = "inline-block";
            }
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
                            <span id='menuLogin'><?php echo "Ciao $_SESSION[nickname]!\t" ?></span>
                            <input type="button" class="button" value="Esci" onclick="location.href='logout.php'" id="linkLogout" />
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
                    <div class="container">
                        <div class="column" id="blogCoautore">
                            <!-- lista blog coautore -->
                            <h3>Blog di cui sei coautore</h3>
                            <?php
                                //mi collego al DB
                                include ("connect.php");
                                //ottengo il nickname dell'utente
                                $nick = $_SESSION["nickname"];
                                //query per verificare se l'utente e' coautore di qualche blog
                                $sql = "SELECT b.`nomeBlog`, c.`autoreC`, b.`blog_id` FROM `blog` AS b, `creazione` AS c WHERE b.`blog_id` = c.`blog_idC` AND c.`coautoreC`= '".$nick."'";
                                $result0 = mysqli_query($link, $sql);
                                //se si verifica un errore..
                                if (!$result0) {
                                    die ('Errore query 0: ' . mysqli_error($link));
                                }
                                $row = mysqli_num_rows($result0);
                                //se l'utente e' coautore di blog memorizzo i risultati in array
                                if ($row > 0) :
                                    while ($fetchBlog = mysqli_fetch_array($result0)) : ?>
                                        <!-- lista blog coautore -->
                                        <p name="coautore-blog">
                                            <span class="nomeBlog"><?php echo "$fetchBlog[0]"; ?></span>
                                            <span class="idBlog"><?php echo "$fetchBlog[2]"; ?></span>
                                            <br>
                                            <span class="autoreBlog"><?php echo "$fetchBlog[1]"; ?></span>
                                        </p>
                                    <?php endwhile;
                                else: ?>
                                    <!-- se l'utente non e' coautore di nessun blog -->
                                    <p>Non sei ancora coautore di nessun blog.</p>
                                <?php endif; ?>
                        </div>
                        <div class="column" id="tuoiBlog">
                            <h2>Area privata</h2>
                            <!-- status login e abbonamento -->
                            <p><?php echo "Status login = $_SESSION[login]"; ?></p>
                            <?php
                                //query che verifica se l'utente e' abbonato
                                $sql = "SELECT `inizioAbb`, `fineAbb` FROM `utenti` WHERE `nickname` = '".$nick."'";
                                $result1 = mysqli_query($link, $sql);
                                //se si verifica un errore..
                                if (!$result1) {
                                    die ('Errore query 1: ' . mysqli_error($link));
                                }
                                //memorizzo la data di inizio abbonamento
                                $abb = mysqli_fetch_assoc($result1);
                                //ottengo la data di fine abbonamento
                                $fineAbb_date = date("Y-m-d", strtotime($abb["fineAbb"]));
                                //ottengo la data corrente da comparare con quella di fine dell'abbonamento
                                $date = getdate(date("U"));
                                $current_timeStr = "$date[year]-$date[mon]-$date[mday]";
                                $current_time = strtotime($current_timeStr);
                                $current_date = date("Y-m-d", $current_time);
                                //controllo che la data di inzio abbonamento non sia NULL e che l'abbonamento non sia gia' scaduto
                                if ($abb["inizioAbb"] == NULL || $fineAbb_date < $current_date) : ?>
                                    <p>Status abbonamento = Non abbonato</p>
                                    <p>Con l'abbonamento puoi creare nuovi blog.</p>
                                    <input type="button" class="button" value="Abbonati ora" onclick="apriModaleAbb()" id="abb" />
                                    <!-- modale di conferma abbonamento -->
                                    <div id="modaleAbb" class="modale">
                                        <div class="modal-content">
                                            <p>L'abbonamento durer&agrave; un mese a partire da adesso. Confermi?</p><br><br>
                                            <input type="button" class="button" value="Si" id="confermaAbb" />
                                            <input type="button" class="button" value="No" onclick="chiudiModaleAbb()" id="chiudiModale" />
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <p>Status abbonamento = Abbonato</p>
                                    <input type="button" class="button" value="Crea nuovo blog" onclick="apriFormBlog()" id="newBlog" />
                                    <div class="formPopup" id="formBlog">
                                        <form name="formBlog">
                                            <h2>Nuovo blog</h2>
                                            Titolo<br>
                                            <input type="text" name="titleBlog" id="titleBlog"><br><br>
                                            Tema<br>
                                            <input type="text" name="topicBlog" id="topicBlog"><br><br>
                                            <input type="submit" value="Crea" id="creaBlog" class="button">
                                            <input type="button" class="button" value="Chiudi" onclick="chiudiFormBlog()" id="closeForm" />
                                        </form>
                                        <!-- span per eventuali errori di creazione blog -->
                                        <span id="erroreBlog"></span>
                                    </div>
                                <?php endif; ?>
                            <!-- lista blog creati -->
                            <h3>I tuoi blog</h3>
                            <?php
                                $sql = "SELECT b.`nomeBlog`, b.`blog_id` FROM `blog` AS b, `creazione` AS c WHERE b.`blog_id` = c.`blog_idC` AND c.`autoreC`= '".$nick."'";
                                $result2 = mysqli_query($link, $sql);
                                //se si verifica un errore..
                                if (!$result2) {
                                    die ('Errore query 2: ' . mysqli_error($link));
                                }
                                $row = mysqli_num_rows($result2);
                                //se l'utente e' autore di blog memorizzo i risultati in $blog
                                if ($row > 0) :
                                    while ($blog = mysqli_fetch_assoc($result2)) : ?>
                                        <!-- lista blog autore -->
                                        <span class="nomeBlog"><?php echo "$blog[nomeBlog]"; ?></span>
                                        <span class="idBlog"><?php echo "$blog[blog_id]"; ?></span>
                                    <?php endwhile;
                                //se l'utente non e' autore di nessun blog
                                else : ?>
                                    <p>Non hai ancora creato nessun blog.</p>
                            <?php endif;
                            ?>
                        </div>
                        <div class="column" id="profilo">
                            <h3>Profilo</h3>
                            <input type="button" class="button" id="eliminaProfilo" value="Cancella profilo" onclick="apriModaleProf()" />
                            <!-- Modale di conferma eliminazione profilo -->
                            <div id="modaleProf" class="modale">
                                <div class="modal-content">
                                    <p>Stai per cancellare il tuo profilo. Verranno cancellate tutte le tue attività sul sito. Confermi?</p><br><br>
                                    <input type="button" class="button" value="Si" id="confermaEliminazione" />
                                    <input type="button" class="button" value="No" onclick="chiudiModaleProf()" id="chiudiModale" />
                                </div>
                            </div>
                        </div>
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