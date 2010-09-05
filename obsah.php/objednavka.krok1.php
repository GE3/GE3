<?php
/* -- PŘIHLÁŠENÍ A REGISTRACE -- */

// Přihlášení
If( $_POST["email"] AND $_POST["heslo"] AND !$_POST["prijmeni"] AND !$uzivatel->prihlasen() ){
    $uzivatel->prihlasit($_POST["email"], $_POST["heslo"]);
}
// Registrace
If( $_POST["email"] AND $_POST["prijmeni"] AND !$uzivatel->prihlasen() ){
    Include_once 'funkce.php/autoRegistrace.fce.php';
    autoRegistrace("templates/$CONF[vzhled]/uzivatel.html", "registrace");
    $uzivatel->prihlasit($_POST["email"], $_POST["heslo"]);
}



/* -- OBJEDNÁVKA KROK 1 -- */

/**
 * První krok objednávky slouží k získání informací o uživateli.
 * Zobrazí se, pokud máme v košíku zboží a pokud uživatel ještě není přihlášen.
 */
If( $kosik->getPocetPolozek()>0 AND !$uzivatel->getData("email") ){
    Include_once 'funkce.php/autoRegistrace.fce.php';


    ////////////////////////
    // Zobrazení formuláře
    ////////////////////////
    $tmplObjednavka = new GlassTemplate("templates/$CONF[vzhled]/objednavka.html", "templates/default/objednavka.html");
    $tmplObjednavka->newBlok("objednavkaKrok1");
    $tmplObjednavka->prirad("objednavkaKrok1.zobraz", "ano");
    $tmplObjednavka->prirad("objednavkaKrok1.registrace", autoRegistrace("templates/$CONF[vzhled]/uzivatel.html", "registrace2"));
    $tmpl->prirad("obsah", $tmplObjednavka->getHtml());



    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="index.php">Úvodní strana</a> » Objednávka » 1) Údaje o zákazníkovi');
}



/**
 * Na další krok se přejde pokud se už uživatel přihlásil a má zboží ke koupení
 */
Elseif( $kosik->getPocetPolozek()>0 ){
    Include "obsah.php/objednavka.krok2.php";
}
?>
