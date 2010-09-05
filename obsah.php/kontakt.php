<?php
If( $_GET["a"]=='kontakt' OR $_GET["a"]=='poptat' ){
    $tmplKontakt = new GlassTemplate("templates/$CONF[vzhled]/$_GET[a].html", "templates/default/$_GET[a].html");

    ///////////////////////////////
    // Odeslání dotazu na produkt
    ///////////////////////////////
    If( $_POST["mailKomu"] OR $_POST["mailPredmet"] ){
        $autoMail = new AutoMail();

        If( $autoMail->posli() ){
            $tmplKontakt->newBlok("zpravaOk");
            $tmplKontakt->prirad("zpravaOk.obsah", "Zpráva byla úspěšně odeslána.");
        }
        Else{
            $tmplKontakt->newBlok("zpravaError");
            $tmplKontakt->prirad("zpravaError.obsah", "Při odesílání došlo k chybě. <br>".$autoMail->getError());
        }
    }

    $tmpl->prirad("obsah", $tmplKontakt->getHtml());


    /////////////
    // Navigace
    /////////////
    $navigace = $_GET["a"]=='kontakt'? 'Kontakt': 'Poptat';
    $tmpl->prirad("navigace", '<a href="index.php">Úvodní strana</a> » '.$navigace);
}
?>

