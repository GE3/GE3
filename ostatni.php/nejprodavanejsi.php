<?php
/**
 * tento skript se stará o zobrazení bočního sloupce
 * s produkty v kategorii "Nejprodávanější"
 */



/********************/
/* ZOBRAZENÍ OBSAHU */
/********************/

/////////////////////////////
// Zobrazení přehledu zboží
/////////////////////////////

/* -- SQL Dotaz -- */
$dotaz = Mysql_query("SELECT *,MIN(cenaSDph),MIN(cenaBezDph)
                      FROM $CONF[sqlPrefix]zbozi
                      WHERE nejprodavanejsi>0
                      GROUP BY produkt ORDER BY nejprodavanejsi DESC, id DESC
                      LIMIT $CONF[nejprodMax]");


/* -- Zobrazení zboží -- */
while($radek=mysql_fetch_assoc($dotaz)){
      $tmpl->newBlok("nejprodavanejsi.produkt");

      // Název produktu
      $tmpl->prirad("nejprodavanejsi.produkt.nazev", $radek["produkt"]);
      // Číslo produktu
      $tmpl->prirad("nejprodavanejsi.produkt.cislo", $radek["cislo"]);      
      // Krátký popis
      $tmpl->prirad("nejprodavanejsi.produkt.popisUvod", substr(strip_tags($radek["popis"]),0,180));
      // Ceny
      //dopočítání nezadaných cen
      $cenaSDph = $radek["MIN(cenaSDph)"]?$radek["MIN(cenaSDph)"]:($radek["MIN(cenaBezDph)"]*(1+$radek["dph"]/100));
      $cenaBezDph = $radek["MIN(cenaBezDph)"]?$radek["MIN(cenaBezDph)"]:($radek["MIN(cenaSDph)"]/(1+$radek["dph"]/100));
      $dph = $radek["dph"]? $radek["dph"]: ($radek["cenaSDph"]/$radek["cenaBezDph"]*100-100);
      //zobrazení
      $tmpl->prirad("nejprodavanejsi.produkt.cenaSDph", $cenaSDph);
      $tmpl->prirad("nejprodavanejsi.produkt.cenaBezDph", $cenaBezDph);
      $tmpl->prirad("nejprodavanejsi.produkt.dph", $dph);
      // Dostupnost
      $tmpl->prirad("nejprodavanejsi.produkt.dostupnost", $radek["dostupnost"]);
      // Akční nabídka
      If($radek["akce"]) $tmpl->prirad("nejprodavanejsi.produkt.akce", "ano");
      // Url
      If( $CONF["mod_rewrite"] ){
          //(informace pro nové url se berou ze stávající url. Pokud zde nejsou, zkusí se získat z db.)
          $kategorie = $_GET["kategorie"]? $_GET["kategorie"]: ($radek["kategorie"]? $radek["id"].'-'.urlText($radek["kategorie"]): '');
          $kategorie = $kategorie? ''.$kategorie.'/': '';
          $podkat1 = $_GET["podkat1"]? $_GET["podkat1"]: ($radek["podkat1"]? $radek["id"].'-'.urlText($radek["podkat1"]): '');
          $podkat1 = $podkat1? ''.$podkat1.'/': '';
          $podkat2 = $_GET["podkat2"]? $_GET["podkat2"]: ($radek["podkat2"]? $radek["id"].'-'.urlText($radek["podkat2"]): '');
          $podkat2 = $podkat2? ''.$podkat2.'/': '';
          $podkat3 = $_GET["podkat3"]? $_GET["podkat3"]: ($radek["podkat3"]? $radek["id"].'-'.urlText($radek["podkat3"]): '');
          $podkat3 = $podkat3? ''.$podkat3.'/': '';
          $produkt = ''.$radek["id"].'-'.urlText($radek["produkt"]).".html";
          $tmpl->prirad("nejprodavanejsi.produkt.url", $CONF["absDir"]."produkty/".$kategorie.$podkat1.$podkat2.$podkat3.$produkt);
      }
      Else{
          //(informace pro nové url se berou ze stávající url. Pokud zde nejsou, zkusí se získat z db.)
          $kategorie = $_GET["kategorie"]? $_GET["kategorie"]: $radek["kategorie"];
          $kategorie = $kategorie? '&kategorie='.$kategorie.'': '';
          $podkat1 = $_GET["podkat1"]? $_GET["podkat1"]: $radek["podkat1"];
          $podkat1 = $podkat1? '&podkat1='.$podkat1.'': '';
          $podkat2 = $_GET["podkat2"]? $_GET["podkat2"]: $radek["podkat2"];
          $podkat2 = $podkat2? '&podkat2='.$podkat2.'': '';
          $podkat3 = $_GET["podkat3"]? $_GET["podkat3"]: $radek["podkat3"];
          $podkat3 = $podkat3? '&podkat3='.$podkat3.'': '';
          $produkt = '&produkt='.$radek["id"].'-'.urlText($radek["produkt"]);
          $tmpl->prirad("nejprodavanejsi.produkt.url", "?a=produkty".$kategorie.$podkat1.$podkat2.$podkat3.$produkt);
      }
      // Url Do košíku
      $tmpl->prirad("nejprodavanejsi.produkt.urlDoKosiku", $CONF["absDir"]."?a=kosik&b=pridat&produkt=".$radek["id"]."-".urlText($radek["produkt"]));
      // Obrázek
      $obrazek = ereg_replace("^;","",$radek["obrazky"]);
      $obrazek = preg_replace("|^([^;]*).*$|","$1",$radek["obrazky"]);
      $tmpl->prirad("nejprodavanejsi.produkt.obrazek", "zbozi/obrazky/".$obrazek);
}

?>

