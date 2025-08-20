<?php
    session_start();
    //se per l'utente è settato un cookie allora lo registro in sessione
    if (isset($_COOKIE["Login"])) {
        $_SESSION["nickname"] = $_COOKIE["Login"];
        $_SESSION["login"] = "OK";
    }
    //mi collego al DB
    include ("connect.php");
    //se non risulta registrato nessun idBlog reindirizzo alla index.php
    if(!isset($_SESSION["idBlog"])) {
        header("Location: index.php");
    }
    //recupero l'id del blog
    $id = $_SESSION["idBlog"];
    //query per recuperare la grafica del blog
    $sql = "SELECT `graficaBlog` FROM `blog` WHERE `blog_id` = '".$id."'";
    $result = mysqli_query($link, $sql);
    //se si verifica un errore..
    if (!$result) {
        die ("Errore query grafica: " . mysqli_error($link));
    }
    //memorizzo la grafica del blog
    $grafica = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="initial-scale=1.0, width=device-width" />
        <link rel="stylesheet" href="Default.css" />
        <?php
            //se il blog ha una grafica diversa da quella di default aggiungo il relativo foglio di stile
            if ($grafica["graficaBlog"] != "Default.css") : ?>
                <link rel="stylesheet" href="<?php echo "$grafica[graficaBlog]"; ?>" />
            <?php endif;
        ?>
        <link rel="icon" type="image/ico" href="img/favicon.ico" />
        <title>P-Link | Blog</title>
        <script src="jquery-3.4.1.js"></script>
        <script type="text/javascript">
            //al caricamento della pagina
            $(document).ready(function(){
                //se clicco Conferma cambia la grafica del blog
                $("#changeGraphic").click(function(){
                    //ottiene il valore dell'opzione selezionata
                    $selected_option = $("#graficaBlog").find(":selected").val();
                    //AJAX - cambia grafica nel DB
                    $.ajax({
                        url: "cambiaGrafica.php",
                        method: "POST",
                        data: "grafica="+$selected_option,
                        dataType: "html",
                        success: function() {
                            //eseguo il refresh della pagina
                            $(window.location).attr("href", "blog.php");
                        }
                    });
                });
                //se clicco Mi piace aggiunge il mi piace al post
                $("input#mettiLike").click(function(){
                    //ottiene l'id del post
                    var $Obj = $(this).siblings("span").first();
                    var $id = $Obj.text();
                    //AJAX - registra il like nel DB
                    $.ajax({
                        url: "like.php",
                        method: "POST",
                        data: "idPost="+$id,
                        dataType: "html",
                        success: function() {
                            //eseguo il refresh della pagina
                            $(window.location).attr("href", "blog.php");
                        }
                    });
                });
                //se clicco Non mi piace aggiunge il non mi piace al post
                $("input#mettiDislike").click(function(){
                    //ottiene l'id del post
                    var $Obj = $(this).siblings("span").first();
                    var $id = $Obj.text();
                    //AJAX - registra il dislike nel DB
                    $.ajax({
                        url: "dislike.php",
                        method: "POST",
                        data: "idPost="+$id,
                        dataType: "html",
                        success: function() {
                            //eseguo il refresh della pagina
                            $(window.location).attr("href", "blog.php");
                        }
                    });
                });
                //se clicco Aggiungi coautore
                $("#aggiungiCoautore").click(function(){
                    //ottiene il nickname digitato
                    var $searchNick = $("#cercaCoautore").val();
                    //se e' stato digitato qualcosa...
                    if ($searchNick) {
                        //AJAX - registra il coautore nel DB
                        $.ajax({
                            url: "aggiungiCoautore.php",
                            method: "POST",
                            data: "searchNick="+$searchNick,
                            dataType: "html",
                            success: function(data) {
                                //se data e' 0 vuol dire che non ho trovato nessuno...
                                if (data == "0") {
                                    $("#esitoAggiungiCoautore").text("Nessun utente trovato.");
                                }
                                //... altrimenti eseguo il refresh della pagina
                                else {
                                    $(window.location).attr("href", "blog.php");
                                }
                            }
                        });
                    }
                    //...altrimenti stampo il relativo messaggio
                    else {
                        $("#esitoAggiungiCoautore").text("Inserisci nickname");
                    }
                });
                //se clicco Rimuovi coautore
                $("#rimuoviCoautore").click(function(){
                    //ottengo il nick dell'utente da rimuovere
                    var $nick = $(this).prev();
                    var $nickText = $nick.text();
                    //AJAX - elimina il coautore nel DB
                    $.ajax({
                        url: "rimuoviCoautore.php",
                        method: "POST",
                        data: "coautore="+$nickText,
                        datatype: "html",
                        success: function () {
                            //eseguo il refresh della pagina
                            $(window.location).attr("href", "blog.php");
                        }
                    });
                });
                //se clicco Smetti di collaborare
                $("#smettiCollaborare").click(function(){
                    //ottengo nick dell'autore del blog (serve per la query)
                    var $nick = $(this).prev();
                    var $nickText = $nick.text();
                    //AJAX - elimina il coautore nel DB
                    $.ajax({
                        url: "smettiCollaborare.php",
                        method: "POST",
                        data: "autore="+$nickText,
                        datatype: "html",
                        success: function () {
                            //reindirizzo all'userpage
                            $(window.location).attr("href", "userpage.php");
                        }
                    });
                });
                //se clicco Elimina blog
                $("#confermaEliminazione").click(function(){
                    //AJAX - cancella il blog dal DB, con tutti i post e le immagini annesse
                    $.ajax({
                        url: "eliminaBlog.php",
                        method: "POST",
                        success: function () {
                            //reindirizzo all'userpage
                            $(window.location).attr("href", "userpage.php");
                        }
                    });
                });
                //se clicco un blog con tema affine
                $('.nomeBlog').click(function() {
                    //recupero l'id del blog cliccato
                    var $Obj = $(this).next();
                    var $id = $Obj.text();
                    //AJAX - registra l'id nella sessione
                    $.ajax({
                        url: "registraBlog.php",
                        method: "POST",
                        data: "id="+$id,
                        dataType: "html",
                        success: function() {
                            //eseguo il refresh della pagina
                            $(window.location).attr("href", "blog.php");
                        }
                    });
                });
                //se clicco Pubblica (post)
                $("#pubblicaPost").click(function(e) {
                    //evita il comportamento di default del form
                    e.preventDefault();
                    //ottengo i dati inseriti nel form
                    var data = $("form")[1];
                    var formData = new FormData(data);
                    //AJAX - esegue la registrazione del post nel DB
                    $.ajax({
                        url: "pubblicaPost.php",
                        method: "POST",
                        type: "POST",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            //se tutto avviene correttamente...
                            if (data == "OK") {
                                //eseguo il refresh della pagina
                                $(window.location).attr("href", "blog.php");
                            }
                            //...altrimenti stampo l'errore
                            else {
                                $("#errorePost").text(data);
                            }
                        }
                    });
                });
            });
            //apre la textarea per inserire un commento
            function apriCommento (obj) {
                //recupero l'obj textarea relativa al post
                var $div = $(obj).next();
                $textarea = $div.children("input[type=text], textarea");
                //imposta gli id solo per il textarea, il pulsante e lo span del relativo post
                $textarea.attr("id", "scriviCommento"); //
                var $button = $textarea.next();
                $button.attr("id", "pubblicaCommento"); // ---> mi servono per il css
                var $span = $button.next();
                $span.attr("id", "erroreCommento");      //
                //mostro il div per commentare e nascondo il tasto di apertura
                $div.show();
                $(obj).hide();
                //se clicco Pubblica
                $button.click(function(){
                    //ottiene il commento digitato
                    var $commento = $textarea.val();
                    //ottengo l'id del post
                    var $id = $(obj).parent().siblings("span").first();
                    var $idText = $id.text();
                    //se e' stato digitato qualcosa...
                    if ($commento) {
                        //AJAX - registra il commento nel DB
                        $.ajax({
                            url: "pubblicaCommento.php",
                            method: "POST",
                            data: "commento="+$commento+"&id="+$idText,
                            dataType: "html",
                            success: function () {
                                //eseguo il refresh della pagina
                                $(window.location).attr("href", "blog.php");
                            }
                        });
                    }
                    //...altrimenti stampo il relativo messaggio
                    else {
                        $span.text("Inserisci commento");
                    }
                });
            }
            //se clicco il pulsante per cancellare il post
            function cancellaPost (obj) {
                //ottengo l'id del post
                var $id = $(obj).next().children("span").first();
                var $idText = $id.text();
                //AJAX - cancella il post dal DB
                $.ajax({
                    url: "cancellaPost.php",
                    method: "POST",
                    data: "id="+$idText,
                    dataType: "html",
                    success: function () {
                        //eseguo il refresh della pagina
                        $(window.location).attr("href", "blog.php");
                    }
                });
            }
            //se clicco il pulsante per cancellare il commento
            function cancellaCommento (obj) {
                //ottengo l'id del commento
                var $id = $(obj).prev();
                var $idText = $id.text();
                //AJAX - cancella il commento dal DB
                $.ajax({
                    url: "cancellaCommento.php",
                    method: "POST",
                    data: "id="+$idText,
                    dataType: "html",
                    success: function () {
                        //eseguo il refresh della pagina
                        $(window.location).attr("href", "blog.php");
                    }
                });
            }
            //se clicco il pulsante per mostrare i commenti
            function mostraCommenti (obj) {
                //nascondo la scritta "vedi tutti i commenti" e mostro i commenti
                $(obj).hide();
                $(obj).next().show();
            }
            //apre e chiude il modale
            function apriModale () {
                document.getElementById("modale").style.display = "block";
            }
            function chiudiModale () {
                document.getElementById("modale").style.display = "none";
            }
            //se clicco fuori dal modale si chiude
            window.onclick = function(event) {
                if (event.target == document.getElementById("modale")) {
                    document.getElementById("modale").style.display = "none";
                }
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
                <div class="content" id="content-blog">
                    <div class="container">
                        <div class="column" id="temiAffini">
                            <h3>Altri blog con lo stesso tema</h3>
                            <?php
                                //ottengo il tema del blog
                                $sql = "SELECT `nomeTema` FROM `argomento` AS a, `blog` AS b WHERE b.`blog_id` = '".$id."' AND b.`blog_id` = a.`blog_idArg`";
                                $result0 = mysqli_query($link, $sql);
                                //se si verifica un errore..
                                if (!$result0) {
                                    die ("Errore query 0: " . mysqli_error($link));
                                }
                                $tema = mysqli_fetch_assoc($result0);
                                //ottengo e stampo il nome dei blog con temi affini
                                $sql = "SELECT `nomeBlog`, `blog_id` FROM `blog` AS b, `argomento` AS a WHERE a.`nomeTema` = '".$tema["nomeTema"]."' AND a.`blog_idArg` = b.`blog_id` AND b.`blog_id` != '".$id."'";
                                $result1 = mysqli_query($link, $sql);
                                //se si verifica un errore..
                                if (!$result1) {
                                    die ("Errore query 1: " . mysqli_error($link));
                                }
                                $rows = mysqli_num_rows($result1);
                                //se non ci sono blog affini stampo che non c'e' nulla, altrimenti stampo la lista
                                if ($rows == 0) : ?>
                                    <p>Non ci sono blog affini.</p>
                                <?php else:
                                    while ($temiAffini = mysqli_fetch_assoc($result1)) : ?>
                                        <p class="nomeBlog"><?php echo "$temiAffini[nomeBlog]"; ?></p>
                                        <span id="idBlogAffine"><?php echo "$temiAffini[blog_id]"; ?></span>
                                    <?php endwhile;
                                endif;
                            ?>
                        </div>
                        <div class="column" id="blog">
                            <div id="infoBlog">
                                <?php
                                    //ottengo e stampo il nome e il tema del blog
                                    $sql = "SELECT `nomeBlog` FROM `blog` WHERE `blog_id` = '".$id."'";
                                    $result2 = mysqli_query($link, $sql);
                                    //se si verifica un errore..
                                    if (!$result2) {
                                        die ("Errore query 2: " . mysqli_error($link));
                                    }
                                    //memorizzo il nome del blog
                                    $nome = mysqli_fetch_assoc($result2);
                                ?>
                                <h1> <?php echo "$nome[nomeBlog]"; ?></h1>
                                <p>Genere: <?php echo "$tema[nomeTema]"; ?>.</p>
                                <?php
                                    //ottengo e stampo l'autore del blog
                                    $sql = "SELECT `autoreC` FROM `creazione` AS c, `blog` AS b WHERE b.`blog_id` = '".$id."' AND b.`blog_id` = c.`blog_idC`";
                                    $result3 = mysqli_query($link, $sql);
                                    //se si verifica un errore..
                                    if (!$result3) {
                                        die ("Errore query 3: " . mysqli_error($link));
                                    }
                                    //memorizzo l'autore del blog
                                    $autore = mysqli_fetch_assoc($result3);
                                ?>
                                <p>Autore: <?php echo "$autore[autoreC]"; ?>.</p>
                                <?php
                                    //ottengo eventuale coautore
                                    $sql = "SELECT `coautoreC` FROM `creazione` AS c, `blog` AS b WHERE b.`blog_id` = '".$id."' AND b.`blog_id` = c.`blog_idC`";
                                    $result4 = mysqli_query($link ,$sql);
                                    //se si verifica un errore..
                                    if (!$result4) {
                                        die ("Errore query 4: " . mysqli_error($link));
                                    }
                                    //memorizzo il coautore
                                    $coautore = mysqli_fetch_assoc($result4);
                                    //se non c'e' un coautore stampo che non c'e', altrimenti stampo il nickname
                                    if ($coautore["coautoreC"] == NULL) : ?>
                                        <p>Coautore: nessuno.</p>
                                    <?php else : ?>
                                        <p>Coautore: <?php echo "$coautore[coautoreC]"; ?>.</p>
                                    <?php endif;
                                ?>
                            </div>
                            <div id="post">
                                <?php
                                    //solo se l'utente e' loggato
                                    if (isset($_SESSION["login"]) && isset($_SESSION["nickname"])) :
                                        //solo se l'utente e' autore o coautore del blog gli do la possibilita' di pubblicare un nuovo post
                                        if ($_SESSION["nickname"] == $autore["autoreC"] || $_SESSION["nickname"] == $coautore["coautoreC"]) : ?>
                                            <form name="formPost" enctype="multipart/form-data">
                                                <h2>Pubblica un post</h2>
                                                <h3>Titolo:</h3>
                                                <textarea rows="1" cols="40" placeholder="Max 40 caratteri..." name="titoloPost" id="titoloPost" value="" maxlength="40"></textarea>
                                                <br>
                                                <h3>Testo:</h3>
                                                <textarea rows="10" cols="59" placeholder="Max 400 caratteri..." name="testoPost" id="testoPost" value="" maxlength="400"></textarea>
                                                <br>
                                                <h3>Inserisci un'immagine (facoltativo):</h3>
                                                <p>Peso massimo 1MB. Dimensioni consigliate: 640x480.<p>
                                                <input type="file" name="input_img" />
                                                <br><br>
                                                <!-- span per eventuali errori di pubblicazione post -->
                                                <span id="errorePost"></span>
                                                <br><br><input type="submit" class="button" value="Pubblica" id="pubblicaPost" />
                                            </form>
                                        <?php endif;
                                    endif;
                                ?>
                                <h2>Post</h2>
                                <?php
                                    //ottengo tutte le informazioni dei post
                                    $sql = "SELECT `post_id`, `dataPost`, `oraPost`, `titoloPost`, `nLike`, `nDislike`, `testoPost` FROM `post` AS p, `blog` AS b, `articolazione` AS a WHERE b.`blog_id` = '".$id."' AND b.`blog_id` = a.`blog_idAr` AND a.`post_idAr` = p.`post_id` ORDER BY p.`post_id` DESC";
                                    $result5 = mysqli_query($link, $sql);
                                    //se si verifica un errore..
                                    if (!$result5) {
                                        die ("Errore query 5: " . mysqli_error($link));
                                    }
                                    //se ci sono post li ottengo e li stampo...
                                    $rows = mysqli_num_rows($result5);
                                    if ($rows > 0) :
                                        while ($post = mysqli_fetch_array($result5)) : ?>
                                            <!-- tutti i post -->
                                            <div id="spazioPost">
                                                <?php
                                                    //solo se l'utente e' autore o coautore del blog gli do la possiblita' di rimuovere un post
                                                    if (isset($_SESSION["login"]) && ($_SESSION["nickname"] == $autore["autoreC"] || $_SESSION["nickname"] == $coautore["coautoreC"])) : ?>
                                                        <input type="button" class="button" value="&#10006;" name="cancellaPost" onclick="cancellaPost(this)" />
                                                    <?php endif;
                                                ?>
                                                <div id="post">
                                                    <!-- titolo e testo -->
                                                    <span id="idPost"><?php echo "$post[post_id]"; ?></span>
                                                <?php
                                                    //converto il formato della data in quello convenzionale italiano
                                                    $dataConverted = substr($post["dataPost"], 8, 2) . "/" . substr($post["dataPost"], 5, 2) . "/" . substr($post["dataPost"], 0, 4);
                                                ?>
                                                    <span id="titoloPost"><?php echo "$post[titoloPost]"; ?></span> &middot; <?php echo "$dataConverted - $post[oraPost]"; ?>
                                                    <br>
                                                    <p><?php echo "$post[testoPost]"; ?></p>
                                                <?php
                                                    //ottengo immagini post
                                                    $sql = "SELECT `pathImg` FROM `immagine` AS i, `allegato` AS a, `post` AS p WHERE p.`post_id` = '".$post["post_id"]."' AND p.`post_id` = a.`post_idAl` AND a.`img_idAl` = i.`img_id`";
                                                    $result6 = mysqli_query($link, $sql);
                                                    //se si verifica un errore..
                                                    if (!$result6) {
                                                        die ("Errore query 6: " . mysqli_error($link));
                                                    }
                                                    $rows1 = mysqli_num_rows($result6);
                                                    //solo se al post sono allegate una o piu' immagini le stampo
                                                    if ($rows1 > 0) :
                                                        while ($immagine = mysqli_fetch_assoc($result6)) : ?>
                                                            <div class="imgContainer">
                                                                <img src="<?php echo "$immagine[pathImg]" ?>" id="fotoPost" alt="Immagine post" /><br>
                                                            </div>
                                                        <?php endwhile;
                                                    endif;
                                                    //inizio codice per utenti loggati//
                                                    if (isset($_SESSION["login"]) && isset($_SESSION["nickname"])) :
                                                        //ottengo il numero di like al post
                                                        $sql = "SELECT `nLike` FROM `post` WHERE `post_id` = '".$post[0]."'";
                                                        $result7 = mysqli_query($link, $sql);
                                                        //se si verifica un errore..
                                                        if (!$result7) {
                                                            die ("Errore query 7: " . mysqli_error($link));
                                                        }
                                                        $nLike = mysqli_fetch_assoc($result7);
                                                        //ottengo il numero di dislike al post
                                                        $sql = "SELECT `nDislike` FROM `post` WHERE `post_id` = '".$post[0]."'";
                                                        $result8 = mysqli_query($link, $sql);
                                                        //se si verifica un errore..
                                                        if (!$result8) {
                                                            die ("Errore query 8: " . mysqli_error($link));
                                                        }
                                                        $nDislike = mysqli_fetch_assoc($result8);
                                                        //ottengo il nickname dell'utente e l'id del relativo post
                                                        $utente = $_SESSION["nickname"];
                                                        $idPost = $post["post_id"];
                                                        //query per controllare se l'utente ha gia' messo like al post
                                                        $sql = "SELECT `nicknameL` FROM `mipiace` WHERE `nicknameL` = '".$utente."' AND `post_idL` = '".$idPost."'";
                                                        $resultControllo = mysqli_query($link, $sql);
                                                        //se si verifica un errore..
                                                        if (!$resultControllo) {
                                                            die ("Errore query controllo like: " . mysqli_error($link));
                                                        }
                                                        $rows1 = mysqli_num_rows($resultControllo);
                                                        //se l'utente ha gia' messo like stampo il simbolo
                                                        if ($rows1 > 0) : ?>
                                                            <img src="img/like.png" id="like" alt="Ti piace" width=15px height=15px />
                                                        <?php endif;
                                                        //pulsante per mettere like con relativo contatore ?>
                                                        <input type="button" class="button" value="Mi piace" id="mettiLike" name="mettiLike" /> &middot; <?php echo "$nLike[nLike]"; ?>
                                                        <?php
                                                        //query per controllare se l'utente ha gia' messo dislike al post
                                                        $sql = "SELECT `nicknameDL` FROM `nonmipiace` WHERE `nicknameDL` = '".$utente."' AND `post_idDL` = '".$idPost."'";
                                                        $resultControllo = mysqli_query($link, $sql);
                                                        if (!$resultControllo) {
                                                            die ("Errore query controllo dislike: " . mysqli_error($link));
                                                        }
                                                        //se l'utente ha gia' messo dislike stampo il simbolo
                                                        $rows1 = mysqli_num_rows($resultControllo);
                                                        if ($rows1 > 0) : ?>
                                                            <img src="img/dislike.png" id="dislike" alt="Non ti piace" width=15px height=15px />
                                                        <?php endif;
                                                        //pulsante per mettere dislike con relativo contatore ?>
                                                        <input type="button" class="button" value="Non mi piace" id="mettiDislike" name="mettiDislike" /> &middot; <?php echo "$nDislike[nDislike]"; ?>
                                                    <?php endif;
                                                    //fine codice per utenti loggati//
                                                    ?>
                                                    <div id="sezioneCommenti">
                                                        <p id="titoloSezioneCommenti">Commenti</p>
                                                        <?php
                                                            //ottengo il numero di commenti al post
                                                            $sql = "SELECT * FROM `appunto` WHERE `post_idAp` = '".$post[0]."'";
                                                            $result9 = mysqli_query($link, $sql);
                                                            //se si verifica un errore..
                                                            if (!$result9) {
                                                                die ("Errore query 9: " . mysqli_error($link));
                                                            }
                                                            $nCommenti = mysqli_num_rows($result9);
                                                            //se non ci sono commenti stampo che non ci sono commenti...
                                                            if ($nCommenti == 0) :
                                                                //solo se l'utente e' loggato gli do la possibilita' di commentare
                                                                if (isset($_SESSION["login"]) && isset($_SESSION["nickname"])) : ?>
                                                                    <input type="button" class="button" value="Commenta" name="apriCommento" onclick="apriCommento(this)" />
                                                                    <div class="scriviCommento-container">
                                                                        <textarea rows="5" cols="25" placeholder="Scrivi un commento..."s maxlength="140"></textarea><input type="button" class="button" value="Pubblica" /><span></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <span>Non ci sono commenti.</span>
                                                            <?php
                                                            //...altrimenti, se i commenti sono piu' di due, li nascondo e creo la scorciatoia...
                                                            elseif ($nCommenti > 2) :
                                                                //solo se l'utente e' loggato gli do la possibilita' di commentare
                                                                if (isset($_SESSION["login"]) && isset($_SESSION["nickname"])) : ?>
                                                                    <input type="button" class="button" value="Commenta" name="apriCommento" onclick="apriCommento(this)" />
                                                                    <div class="scriviCommento-container">
                                                                        <textarea rows="5" cols="25" placeholder="Scrivi un commento..." maxlength="140"></textarea><input type="button" class="button" value="Pubblica" /><span></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <!-- scorciatoia commenti -->
                                                                <span name="vediCommenti" onclick="mostraCommenti(this)">Vedi tutti i <?php echo $nCommenti; ?> commenti</span>
                                                                <div name="commentiNascosti">
                                                                    <?php
                                                                        //query per recuperare i commenti al post
                                                                        $sql = "SELECT `testoCommento`, `autoreD`, `dataCommento`, `oraCommento`, `commento_id` FROM `commento` AS c, `appunto` AS a, `discussione` AS d WHERE a.`post_idAp` = '".$post[0]."' AND c.`commento_id` = a.`commento_idAp` AND d.`commento_idD` = a.`commento_idAp`";
                                                                        $result10 = mysqli_query($link, $sql);
                                                                        //se si verifica un errore..
                                                                        if (!$result10) {
                                                                            die ("Errore query 10: " . mysqli_error($link));
                                                                        }
                                                                        //stampo i commenti nel div nascosto
                                                                        while ($commento = mysqli_fetch_array($result10)) : ?>
                                                                            <span id="commento">
                                                                                <?php
                                                                                    //converto il formato della data in quello convenzionale italiano
                                                                                    $dataConverted = substr($commento["dataCommento"], 8, 2) . "/" . substr($commento["dataCommento"], 5, 2) . "/" . substr($commento["dataCommento"], 0, 4);
                                                                                    //stampo autore, data e ora commento
                                                                                    echo "$commento[autoreD]"." "; ?>&middot; <?php echo "$dataConverted - $commento[oraCommento]";
                                                                                    //solo se l'utente e' loggato..
                                                                                    if (isset($_SESSION["login"])) :
                                                                                        //..e solo se e' l'autore del commento gli do la possibilita' di cancellarlo
                                                                                        if ($commento["autoreD"] == $_SESSION["nickname"]) : ?>
                                                                                            <span id="idCommento"><?php echo "$commento[commento_id]"; ?></span>
                                                                                            <input type="button" class="button" value="&#10006;" name="cancellaCommento" onclick="cancellaCommento(this)" />
                                                                                        <?php endif;
                                                                                    endif; ?>
                                                                                <br>
                                                                                <?php echo "$commento[testoCommento]"; ?>
                                                                                <br>
                                                                            </span>
                                                                        <?php endwhile; ?>
                                                                </div>
                                                            <?php
                                                            //...altrimenti stampo i commenti
                                                            else :
                                                                //solo se l'utente e' loggato gli do la possibilita' di commentare
                                                                if (isset($_SESSION["login"]) && isset($_SESSION["nickname"])) : ?>
                                                                    <input type="button" class="button" value="Commenta" name="apriCommento" onclick="apriCommento(this)" />
                                                                    <div class="scriviCommento-container">
                                                                        <textarea rows="5" cols="25" placeholder="Scrivi un commento..." maxlength="140"></textarea><input type="button" class="button" value="Pubblica" /><span></span>
                                                                    </div>
                                                                <?php endif;
                                                                //query per recuperare i commenti al post
                                                                $sql = "SELECT `testoCommento`, `autoreD`, `dataCommento`, `oraCommento`, `commento_id` FROM `commento` AS c, `appunto` AS a, `discussione` AS d WHERE a.`post_idAp` = '".$post[0]."' AND c.`commento_id` = a.`commento_idAp` AND d.`commento_idD` = a.`commento_idAp`";
                                                                $result10 = mysqli_query($link, $sql);
                                                                //se si verifica un errore..
                                                                if (!$result10) {
                                                                    die ("Errore query 10: " . mysqli_error($link));
                                                                }
                                                                //stampo i commenti
                                                                while ($commento = mysqli_fetch_array($result10)) : ?>
                                                                    <span id="commento">
                                                                        <?php
                                                                            //converto il formato della data in quello convenzionale italiano
                                                                            $dataConverted = substr($commento["dataCommento"], 8, 2) . "/" . substr($commento["dataCommento"], 5, 2) . "/" . substr($commento["dataCommento"], 0, 4);
                                                                            echo "$commento[autoreD]"." "; ?>&middot; <?php echo "$dataConverted - $commento[oraCommento]";
                                                                            //solo se l'utente e' loggato..
                                                                            if (isset($_SESSION["login"]) && isset($_SESSION["nickname"])) :
                                                                                //..e solo se e' l'autore del commento gli do la possibilita' di cancellarlo
                                                                                if ($commento["autoreD"] == $_SESSION["nickname"]) : ?>
                                                                                    <span id="idCommento"><?php echo "$commento[commento_id]"; ?></span>
                                                                                    <input type="button" class="button" value="&#10006;" name="cancellaCommento" onclick="cancellaCommento(this)" />
                                                                                <?php endif;
                                                                            endif; ?>
                                                                        <br>
                                                                        <?php echo "$commento[testoCommento]"; ?>
                                                                        <br>
                                                                    </span>
                                                                <?php endwhile; ?>
                                                            <?php endif;
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile;
                                    //...altrimenti dico che non ci sono post
                                    else: ?>
                                        <p>Non ci sono post.</p>
                                    <?php endif;
                                ?>
                            </div>
                        </div>
                        <?php
                        //solo se l'utente e' loggato..
                        if (isset($_SESSION["login"])) :
                            //..e solo se e' autore o coautore gli do la possibilita' di gestire il blog
                            if ($_SESSION["login"] == "OK" && ($_SESSION["nickname"] == $autore["autoreC"] || $_SESSION["nickname"] == $coautore["coautoreC"])) : ?>
                                <div class="column" id="opzioniBlog">
                                    <h3>Gestione blog</h3>
                                    <?php
                                        //ottengo il nickname dell'utente loggato
                                        $utente = $_SESSION["nickname"];
                                        //se l'utente e' l'autore del blog allora gli do la possibilita' di aggiungere/revocare un coautore...
                                        if ($utente == $autore["autoreC"]) :
                                            //se non c'e' un coautore do la possibilita' di aggiungerne uno, altrimenti gli do la possibilita' di revocarlo
                                            if ($coautore["coautoreC"] == NULL) : ?>
                                                <input type="text" placeholder="Scrivi il nickname..." id="cercaCoautore" />
                                                <input type="button" class="button" value="Aggiungi coautore" id="aggiungiCoautore" /> <span id="esitoAggiungiCoautore"></span>
                                                <br>
                                            <?php else : ?>
                                                <span id="coautore"><?php echo "$coautore[coautoreC]"; ?></span>
                                                <input type="button" class="button" value="Revoca coautore" id="rimuoviCoautore" /> <span id="esitoRimuoviCoautore"></span>
                                                <br>
                                            <?php endif;
                                        //...altrimenti se e' il coautore gli do la possibilita' di smettere di collaborare
                                        elseif ($utente == $coautore["coautoreC"]) : ?>
                                            <p>Collabori a questo blog<p>
                                            <span id="autore"><?php echo "$autore[autoreC]"; ?></span>
                                            <input type="button" class="button" value="Smetti di collaborare" id="smettiCollaborare" />
                                            <br>
                                        <?php endif;
                                    ?>
                                    <h4>Personalizza blog</h4>
                                    <p>Seleziona una grafica: </p>
                                    <select name="graficaBlog" id="graficaBlog">
                                        <option value="" selected disabled hidden>Scegli grafica</option>
                                        <option value="Default.css">Default</option>
                                        <option value="Technology.css">Technology</option>
                                        <option value="Medieval.css">Medieval</option>
                                        <option value="Dark.css">Dark</option>
                                        <option value="Futuristic.css">Futuristic</option>
                                        <option value="Artistic.css">Artistic</option>
                                    </select>
                                    <input type="button" class="button" value="Conferma" id="changeGraphic" />
                                    <?php
                                        //solo se l'utente e' l'autore del blog allora gli do anche la possibilita' di eliminare il blog
                                        if ($utente == $autore["autoreC"]) : ?>
                                        <h4>Elimina blog</h4>
                                        <input type="button" class="button" value="Elimina blog" id="eliminaBlog" onclick="apriModale()" />
                                        <!-- Modale di conferma eliminazione -->
                                        <div id="modale" class="modale">
                                            <div class="modal-content">
                                                <p>Stai per cancellare il blog. Verranno cancellati anche i post e le immagini ad esso collegati. Confermi?</p><br><br>
                                                <input type="button" class="button" value="Si" id="confermaEliminazione" />
                                                <input type="button" class="button" value="No" onclick="chiudiModale()" id="chiudiModale" />
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php
                            //(creo comunque un div vuoto per impaginare correttamente quando non si e' autori/coautori del blog)
                            else: ?>
                                <div class="column" id="opzioniBlog"></div>
                            <?php endif;
                        //(creo comunque un div vuoto per impaginare correttamente quando non si e' loggati)
                        else : ?>
                            <div class="column" id="opzioniBlog"></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>