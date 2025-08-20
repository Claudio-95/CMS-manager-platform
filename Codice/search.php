<?php
    session_start();
    //se per l'utente è settato un cookie allora lo registro in sessione
    if (isset($_COOKIE["Login"])) {
        $_SESSION["nickname"] = $_COOKIE["Login"];
        $_SESSION["login"] = "OK";
    }
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <link rel="stylesheet" href="Default.css" />
        <link rel="icon" type="image/ico" href="img/favicon.ico" />
        <title>P-Link | Ricerca</title>
        <script src="jquery-3.4.1.js"></script>
        <script type="text/javascript">
            //reindirizza al blog corrispondente
            function apriBlog (obj) {
                var $id = $(obj).prev(); //(seleziona lo span che contiene l'id del blog)
                var $idText = $id.text();
                //AJAX - registra l'id nella sessione
                $.ajax({
                    url: "registraBlog.php",
                    method: "POST",
                    data: "id="+$idText,
                    dataType: "html",
                    success: function() {
                        //reindirizzo alla pagina del blog
                        $(window.location).attr("href", "blog.php");
                    }
                });
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
                            <?php
                                //se l'utente e' loggato gli mostro il menu' di benvenuto e il tasto di logout, altrimenti il menu' normale con pulsanti accedi e registrati
                                if (!isset($_SESSION["login"]) OR ($_SESSION["login"] != "OK")) : ?>
                                    <input type="button" class="button" value="Accedi" onclick="location.href='accedi.php'" id="linkLogin" />
                                    <input type="button" class="button" value="Registrati" onclick="location.href='registrazione.php'" id="linkRec" />
                                <?php else : ?>
                                    <span id='menuLogin'><?php echo "Ciao $_SESSION[nickname]!\t"?></span>
                                    <input type="button" class="button" value="Esci" onclick="location.href='logout.php'" id="linkLogout" />
                                <?php endif; ?>
                        </div>
                        <div class="search-container">
                            <form action="search.php" method="get">
                                <input type="text" placeholder="Cerca..." name="search">
                                <input type="submit" id="searchSubmit" name="searchSubmit" value="&#x02315;"/>
                            </form>
                        </div>
                    </div>
                </header>
                <div class="content">
                    <?php
                        //mi collego al DB 
                        include ("connect.php"); 
                        //se non e' stato inserito nulla stampo direttamente il risultato...
                        if (empty($_GET["search"])) {
                            echo "Nessun risultato trovato.";
                        }
                        //...altrimenti procedo con la ricerca
                        else {
                            //ottengo i dati della ricerca (protezione contro SQL Injection)
                            $query = mysqli_real_escape_string($link, $_GET["search"]);
                            //variabile che mi serve per capire se ho trovato qualcosa o no
                            $trovato = "";
                            //cerco se ci sono blog con quel nome
                            $sql = "SELECT `nomeBlog`, `blog_id` FROM `blog` WHERE `nomeBlog` REGEXP ('".$query."')";
                            $result0 = mysqli_query($link, $sql); 
                            //se si verifica un errore..
                            if (!$result0) {
                                die ("Errore query 0: " . mysqli_error($link));
                            }
                            $rows = mysqli_num_rows($result0);
                            //se risultano blog con quel nome stampo i risultati
                            if ($rows > 0) : ?>
                                <h3>Blog trovati</h3>
                                <?php while ($fetchBlog = mysqli_fetch_array($result0)) : ?>
                                    <span name="idBlog"><?php echo "$fetchBlog[blog_id]"; ?></span>
                                    <p name="risultatiRicerca" onclick="apriBlog(this)"><?php echo "$fetchBlog[nomeBlog]"; ?></p>
                                <?php endwhile;
                                //memorizzo che e' stato trovato qualcosa
                                $trovato = "Si";
                            endif;
                            //cerco se ci sono blog creati dall'utente ricercato, nel caso stampo i risultati
                            $sql = "SELECT b.`nomeBlog`, b.`blog_id` FROM `blog` AS b, `creazione` AS c WHERE c.`autoreC` = '".$query."' AND c.`blog_idC` = b.`blog_id`";  
                            $result1 = mysqli_query($link, $sql); 
                            //se si verifica un errore..
                            if (!$result1) {
                                die ("Errore query 1: " . mysqli_error($link));
                            }
                            $rows = mysqli_num_rows($result1);
                            //se ci sono stampo i risultati
                            if ($rows > 0) : ?>
                                <h3>Blog dell'utente ricercato</h3>
                                <?php while ($fetchNickBlog = mysqli_fetch_array($result1)) : ?>
                                    <span name="idBlog"><?php echo "$fetchNickBlog[blog_id]"; ?></span>
                                    <p name="risultatiRicerca" onclick="apriBlog(this)"><?php echo "$fetchNickBlog[nomeBlog]"; ?></p>
                                <?php endwhile;
                                //memorizzo che e' stato trovato qualcosa
                                $trovato = "Si";
                            endif;
                            //cerco se ci sono blog che hanno come tema la query inserita
                            $sql = "SELECT b.`nomeBlog`, b.`blog_id` FROM `blog` AS b, `argomento` AS a WHERE a.`nomeTema` = '".$query."' AND a.`blog_idArg` = b.`blog_id`";  
                            $result2 = mysqli_query($link, $sql); 
                            //se si verifica un errore..
                            if (!$result2) {
                                die ("Errore query 2: " . mysqli_error($link));
                            }
                            $rows = mysqli_num_rows($result2);
                            //se risultano blog con quel tema stampo i risultati
                            if ($rows > 0) : ?>
                                <h3>Blog a questo tema</h3>
                                <?php while ($fetchBlogTema = mysqli_fetch_array($result2)) : ?>
                                    <span name="idBlog"><?php echo "$fetchBlogTema[blog_id]"; ?></span>
                                    <p name="risultatiRicerca" onclick="apriBlog(this)"><?php echo "$fetchBlogTema[nomeBlog]"; ?></p>
                                <?php endwhile;
                                //memorizzo che e' stato trovato qualcosa
                                $trovato = "Si";
                            endif;
                            //cerco se ci sono post che contengono, nel titolo o nel testo, la query inserita
                            $sql = "SELECT DISTINCT b.`nomeBlog`, b.`blog_id` FROM `blog` AS b, `articolazione` AS a, `post` AS p WHERE b.`blog_id` = a.`blog_idAr` AND a.`post_idAr` = p.`post_id` AND (`titoloPost` REGEXP ('".$query."') OR `testoPost` REGEXP ('".$query."'))";  
                            $result3 = mysqli_query($link, $sql); 
                            //se si verifica un errore..
                            if (!$result3) {
                                die ("Errore query 3: " . mysqli_error($link));
                            }
                            $rows = mysqli_num_rows($result3);
                            //se risultano blog con quel post stampo i risultati
                            if ($rows > 0) : ?>
                                <h3>Blog trovati o che potrebbero interessarti</h3>
                                <?php while ($fetchPostBlog = mysqli_fetch_array($result3)) : ?>
                                    <span name="idBlog"><?php echo "$fetchPostBlog[blog_id]"; ?></span>
                                    <p name="risultatiRicerca" onclick="apriBlog(this)"><?php echo "$fetchPostBlog[nomeBlog]"; ?></p>
                                <?php endwhile;
                                //memorizzo che e' stato trovato qualcosa
                                $trovato = "Si";
                            endif;
                            //se non ho trovato nulla stampo il seguente messaggio
                            if (!$trovato) : ?>
                                <p name="risultatiRicerca">Nessun risultato trovato.</p>
                            <?php endif;
                        }
                    ?>
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