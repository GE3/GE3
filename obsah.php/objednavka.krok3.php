<?php
If( !$_POST["objednat"] ){
    $objednavka = new Objednavka();
    $cenaDopravneho = zjisti_z("$CONF[sqlPrefix]doprava", "cena", "firma='".$_POST["dopravaFirma"]."' AND zpusob_platby='".$_POST["zpusobPlatby"]."' ");
    $objednavka->nactiZboziZKosiku();
    $objednavka->pridejZbozi(0, $_POST["dopravaFirma"], $_POST["zpusobPlatby"], 1, $cenaDopravneho, 0, 19);
    $objednavkaHtml = $objednavka->priradDoTmpl($tmpl, "objednavkaKrok3", "templates/$CONF[vzhled]/objednavka.html");
    $tmpl->prirad("obsah", $objednavkaHtml);

    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="index.php">Úvodní strana</a> » Objednávka » 3) Rekapitulace');
}



Elseif( $_POST["objednat"] ){
    Include "obsah.php/objednavka.krok4.php";
}
?>
