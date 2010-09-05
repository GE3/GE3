<?php
If( $kosik->getPocetPolozek()>0 ){
    // Template
    $tmplObjednavka = new GlassTemplate("templates/$CONF[vzhled]/objednavka.html", "templates/default/objednavka.html");
    $tmplObjednavka->newBlok("objednavkaKrok4");

    // Objednávka
    $objednavka = new Objednavka();
    $objednavka->nactiZboziZKosiku();
    $cenaDopravneho = zjisti_z("$CONF[sqlPrefix]doprava", "cena", "firma='".$_POST["dopravaFirma"]."' AND zpusob_platby='".$_POST["zpusobPlatby"]."' ");
    $objednavka->pridejZbozi($_POST["dopravaFirma"], $_POST["zpusobPlatby"], 1, $cenaDopravneho, 0, 19);

    // Odeslání objednávky
    If( $objednavka->ulozDoDatabaze() ){
        //email
        $tmplEmail = new GlassTemplate("templates/$CONF[vzhled]/objednavka.html", "templates/default/objednavka.html");
        $obsahMailu = $objednavka->priradDoTmpl($tmplEmail, "email", "templates/$CONF[vzhled]/objednavka.html");

        $odesilatel = $CONF["mailer"];
        $prijemce = zjisti_z("$CONF[sqlPrefix]nastaveni", "emailAdmin", "id=1 LIMIT 1").", ".$objednavka->uzivatel->getData("email");
        easyMail($odesilatel, $prijemce, "Potvrzení objednávky", $obsahMailu);

        //hláška
        $kosik->vysypejKosik();
        $tmplObjednavka->prirad("objednavkaKrok4.hlaska", "Vaše objednávka byla v pořádku odeslána a bude v nejbližší době vyřízena.");
    }
    Else{
        $tmplObjednavka->prirad("objednavkaKrok4.hlaska", "Chyba: ".$objednavka->getErrors());
    }

    // Zobrazení v hlavním templatu
    $objednavkaHtml = $tmpl->prirad("objednavkaKrok4", $tmplObjednavka->getHtml());
    $tmpl->prirad("obsah", $tmplObjednavka->getHtml());
    

    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="index.php">Úvodní strana</a> » Objednávka » 4) Odeslání objednávky');
}



Elseif( $kosik->getPocetPolozek()==0 ){
    $tmplObjednavka = new GlassTemplate("templates/$CONF[vzhled]/objednavka.html", "templates/default/objednavka.html");
    $tmplObjednavka->newBlok("objednavkaKrok4");
    $tmplObjednavka->prirad("objednavkaKrok4.hlaska", "Objednávka už byla odeslána, košík je prázdný.");
}
?>
