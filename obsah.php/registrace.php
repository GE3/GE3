<?php
If( $_GET["a"]=='registrace' ){
    Include 'funkce.php/autoRegistrace.fce.php';

    $tmpl->prirad("obsah", autoRegistrace("templates/$CONF[vzhled]/uzivatel.html", "registrace"));


    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="index.php">Úvodní strana</a> » Registrace');
}
?>