<?php
/**
 * Druhý krok se zobrazí v případě, že ještě nebyl vybrán
 * způsob dopravy a způsob platby.
 */
If( !$_POST["dopravaFirma"] OR !$_POST["zpusobPlatby"] ){
    $objednavka = new Objednavka();
    $objednavka->nactiZboziZKosiku();
    $objednavkaHtml = $objednavka->priradDoTmpl($tmpl, "objednavkaKrok2", "templates/$CONF[vzhled]/objednavka.html");
    $tmpl->prirad("obsah", $objednavkaHtml);

    If(file_exists("obsah.php/objednavka.krok2.slevy.php")) Include "obsah.php/objednavka.krok2.slevy.php";
    Elseif(file_exists("objednavka.krok2.slevy.php")) Include "objednavka.krok2.slevy.php";

    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="index.php">Úvodní strana</a> » Objednávka » 2) Doprava');
}



/**
 * Ke třetímu kroku přejdeme až při zadání dopravy a způsobu platby.
 */
Elseif( $_POST["dopravaFirma"] AND $_POST["zpusobPlatby"] ){
    Include "obsah.php/objednavka.krok3.php";
}
?>
