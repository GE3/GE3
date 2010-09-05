<?php
If( $_GET["a"]=='novinky' ){

    // Zjištění obsahu novinky
    $tmplClanek = new GlassTemplate("templates/$CONF[vzhled]/clanek.html");
    
    $dotaz2 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='novinka' ORDER BY id DESC");
    $i = 0;
    While($radek2 = mysql_fetch_array($dotaz2)){
          $i++;
          // Zobrazení
          $tmplClanek->newBlok("novinka");
          $tmplClanek->prirad("novinka.i", $i);
          $tmplClanek->prirad("novinka.nazev", $radek2["nazev"]);
          If( $CONF["mod_rewrite"])$tmplClanek->prirad("novinka.url", "clanky/".$radek2["id"]."-".urlText($radek2["nazev"]).".html");
          If(!$CONF["mod_rewrite"])$tmplClanek->prirad("novinka.url", "?a=clanky&clanek=".$radek2["id"]."-".urlText($radek2["nazev"]));
          $tmplClanek->prirad("novinka.datum", @date("j.n.Y G:i",$radek2["datum"]));
          $tmplClanek->prirad("novinka.uvod", $radek2["uvod"]);
    }
    $tmpl->prirad("obsah", $tmplClanek->getHtml());


    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", "Novinky");
}
?>