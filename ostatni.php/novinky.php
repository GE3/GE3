<?php

// Zjištění obsahu novinky
$dotaz2 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='novinka' ORDER BY id DESC LIMIT 3");
$i = 0;
While($radek2 = mysql_fetch_array($dotaz2)){
      $i++;
      // Zobrazení
      $tmpl->newBlok("novinka");
      $tmpl->prirad("novinka.i", $i);
      $tmpl->prirad("novinka.nazev", $radek2["nazev"]);
      If( $CONF["mod_rewrite"])$tmpl->prirad("novinka.url", "clanky/".$radek2["id"]."-".urlText($radek2["nazev"]).".html");
      If(!$CONF["mod_rewrite"])$tmpl->prirad("novinka.url", "?a=clanky&clanek=".$radek2["id"]."-".urlText($radek2["nazev"]));
      $tmpl->prirad("novinka.datum", @date("j.n.Y G:i",$radek2["datum"]));
      $tmpl->prirad("novinka.uvod", $radek2["uvod"]);
      $tmpl->prirad("novinka.obsah", strip_tags($radek2["obsah"]));
}

?>

