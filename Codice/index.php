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
        <title>P-Link | Home</title>
        <script src="jquery-3.4.1.js"></script>
        <script type="text/javascript">
            //apre il blog cliccato
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
                        //rimando alla pagina blog
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
                                <?php endif;
                            ?>
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
                        <div class="column" class="tendenza">
                            <h3>Temi di tendenza</h3>
                            <?php
                                //mi collego al DB
                                include ("connect.php");
                                //ottengo i temi con popolarita' piu' alta, per mostrare una tendenza degli argomenti attuali sul sito (i primi 5)
                                $sql = "SELECT `nome` FROM `tema` ORDER BY `popolarita` DESC LIMIT 5";
                                $result0 = mysqli_query($link, $sql);
                                //se si verifica un errore..
                                if (!$result0) {
                                    die ('Errore query 0: ' . mysqli_error($link));
                                }
                                $rowsTemi = mysqli_num_rows($result0);
                                //$conta serve per indicare la posizione in tendenza del tema
                                $conta = 1;
                                if ($rowsTemi > 0) :
                                    while ($fetchTemi = mysqli_fetch_assoc($result0)) : ?>
                                        <p class="temiTendenza"><?php echo "$conta"." - ";
                                            echo "$fetchTemi[nome]"; ?>
                                        </p>
                                        <?php $conta = $conta + 1;
                                    endwhile;
                                else : ?>
                                    <p>Non ci sono temi di tendenza in questo momento.</p>
                                <?php endif;
                            ?>
                        </div>
                        <div class="column" id="infoSito">
                            <h2>Benvenuto su P-Link</h2>
                            <h3>Obiettivo del sito</h3>
                            <p>Il sito offre la possibilit&agrave; di creare e gestire blog personali e interagire con gli altri utenti. Per gli utenti visitatori non registrati &egrave; comunque possibile navigare nel sito per leggere e ricercare blog, post e commenti.</p>
                            <h3>Abbonamento</h3>
                            <p>La funzionalit&agrave; di creazione blog &egrave; esclusiva degli utenti abbonati. L'abbonamento ha durata un mese dalla data di sottoscrizione ed &egrave; facile, veloce e gratuito.</p>
                            <h3>Progetto</h3>
                            <p>Il sito &egrave; realizzato a scopo didattico come progetto per il corso di Basi di dati e Laboratorio Web (12 CFU) 2018/2019 di Informatica Umanistica, Universit&agrave; di Pisa. </p>
                        </div>
                        <div class="column" id="randomBlog">
                            <h3>Scopri alcuni blog di P-Link</h3>
                            <?php //seleziono alcuni blog casualmente dal DB (5 blog)
                                $sql = "SELECT `nomeBlog`, `nomeTema`, `blog_id` FROM `blog` AS b, `argomento` AS a WHERE b.`blog_id` = a.`blog_idArg` ORDER BY RAND() LIMIT 5";
                                $result1 = mysqli_query($link, $sql);
                                //se si verifica un errore..
                                if (!$result1) {
                                    die ("Errore query 1: " . mysqli_error($link));
                                }
                                //se risultano dei blog stampo la lista...
                                $rowsBlog = mysqli_num_rows($result1);
                                if ($rowsBlog > 0) :
                                    while ($fetchBlog = mysqli_fetch_assoc($result1)) : ?>
                                        <span name="idBlog"><?php echo "$fetchBlog[blog_id]"; ?></span>
                                        <p class="randomBlog" onclick="apriBlog(this)"><span id="nome"><?php echo "$fetchBlog[nomeBlog]"; ?></span><br>
                                            <span id="tema"><?php echo "$fetchBlog[nomeTema]"; ?></span>
                                        </p>
                                    <?php endwhile;
                                //...altrimenti stampo che non ci sono blog
                                else : ?>
                                    <p>Non ci sono blog in questo momento.</p>
                                <?php endif;
                            ?>
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