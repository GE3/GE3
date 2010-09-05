<?php
If( $_GET["a"]=='vyhledavani' ){
    /**
     * Vyhledávání
     */
    $tmplVyhledavani = new GlassTemplate("templates/$CONF[vzhled]/vyhledavani.html");
    $hledej = $_POST["hledej"]?$_POST["hledej"]:$_GET["hledej"];


    /************************/
    /* VYHLEDÁVÁNÍ VE ZBOŽÍ */
    /************************/

    //////////////
    // SQL Dotaz
    //////////////
    $dotaz = Mysql_query("SELECT *, MIN(cenaSDph), MIN(cenaBezDph) FROM $CONF[sqlPrefix]zbozi
                            WHERE (
                                   LOWER(kategorie) LIKE LOWER('%$hledej%') OR
                                   LOWER(podkat1) LIKE LOWER('%$hledej%') OR
                                   LOWER(podkat2) LIKE LOWER('%$hledej%') OR
                                   LOWER(podkat3) LIKE LOWER('%$hledej%') OR
                                   LOWER(produkt) LIKE LOWER('%$hledej%') OR
                                   LOWER(popis) LIKE LOWER('%$hledej%')
                                  )
                                  AND produkt!=''
                            GROUP BY produkt
                            ORDER BY id DESC
                            LIMIT 50
                          ") or die(mysql_error());



    ////////////////////
    // Zobrazení zboží
    ////////////////////
    $tmplProdukty = new GlassTemplate("templates/$CONF[vzhled]/produkty.html");
    $pocetNalezenych = 0;
    while($radek=mysql_fetch_array($dotaz) AND $hledej){
          $tmplProdukty->newBlok("produkt");

          $tmplProdukty->prirad("produkt.i", $pocetNalezenych+1);
          // Název produktu
          $tmplProdukty->prirad("produkt.nazev", $radek["produkt"]);
          // Číslo produktu
          $tmplProdukty->prirad("produkt.cislo", $radek["cislo"]);          
          // Krátký popis
          $tmplProdukty->prirad("produkt.popisUvod", ereg_replace( " [^ ]*$", "", substr(strip_tags($radek["popis"]),0,180)) );
          // Ceny
          //dopočítání nezadaných cen
          $cenaSDph = $radek["MIN(cenaSDph)"]?$radek["MIN(cenaSDph)"]:($radek["MIN(cenaBezDph)"]*(1+$radek["dph"]/100));
          @$cenaBezDph = $radek["MIN(cenaBezDph)"]?$radek["MIN(cenaBezDph)"]:($radek["MIN(cenaSDph)"]/(1+$radek["dph"]/100));
          @$dph = $radek["dph"]?$radek["dph"]:($radek["cenaSDph"]/$radek["cenaBezDph"]*100-100);
          //zobrazení
          $tmplProdukty->prirad("produkt.cenaSDph", $cenaSDph);
          $tmplProdukty->prirad("produkt.cenaBezDph", $cenaBezDph);
          $tmplProdukty->prirad("produkt.dph", $dph);
          // Dostupnost
          $tmplProdukty->prirad("produkt.dostupnost", $radek["dostupnost"]);
          // Akční nabídka
          If($radek["akce"]) $tmplProdukty->prirad("produkt.akce", "ano");
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
              $tmplProdukty->prirad("produkt.url", $CONF["absDir"]."produkty/".$kategorie.$podkat1.$podkat2.$podkat3.$produkt);
          }
          Else{
              //(informace pro nové url se berou ze stávající url. Pokud zde nejsou, zkusí se získat z db.)
              $kategorie = $_GET["kategorie"]? $_GET["kategorie"]: ($radek["kategorie"]? $radek["id"].'-'.urlText($radek["kategorie"]): '');
              $kategorie = $kategorie? '&kategorie='.$kategorie.'': '';
              $podkat1 = $_GET["podkat1"]? $_GET["podkat1"]: ($radek["podkat1"]? $radek["id"].'-'.urlText($radek["podkat1"]): '');
              $podkat1 = $podkat1? '&podkat1='.$podkat1.'': '';
              $podkat2 = $_GET["podkat2"]? $_GET["podkat2"]: ($radek["podkat2"]? $radek["id"].'-'.urlText($radek["podkat2"]): '');
              $podkat2 = $podkat2? '&podkat2='.$podkat2.'': '';
              $podkat3 = $_GET["podkat3"]? $_GET["podkat3"]: ($radek["podkat3"]? $radek["id"].'-'.urlText($radek["podkat3"]): '');
              $podkat3 = $podkat3? '&podkat3='.$podkat3.'': '';
              $produkt = '&produkt='.$radek["id"].'-'.urlText($radek["produkt"]);
              $tmplProdukty->prirad("produkt.url", "?a=produkty".$kategorie.$podkat1.$podkat2.$podkat3.$produkt);
          }
          // Url Do košíku
          $tmplProdukty->prirad("produkt.urlDoKosiku", "?a=kosik&b=pridat&produkt=".$radek["id"]."-".urlText($radek["produkt"]));
          // Obrázek
          $obrazek = ereg_replace("^;", "", $radek["obrazky"]);
          $obrazek = preg_replace("|^([^;]*).*$|","$1", $obrazek);
          If( $obrazek) $tmplProdukty->prirad("produkt.obrazek", "zbozi/obrazky/$obrazek");
          If(!$obrazek) $tmplProdukty->prirad("produkt.obrazek", file_exists("templates/$CONF[vzhled]/noPicture.jpg")?"templates/$CONF[vzhled]/noPicture.jpg":"templates/default/noPicture.jpg");

          $pocetNalezenych++;
    }
    $tmplVyhledavani->prirad("produkty", $tmplProdukty->getHtml());
    If( $pocetNalezenych==0 ) $tmplVyhledavani->prirad("zprava.text", "Žádné zboží nebylo nalezeno.");



    /***************************/
    /* VYHLEDÁVÁNÍ VE ČLÁNCÍCH */
    /***************************/

    //////////////
    // SQL Dotaz
    //////////////
    $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky
                            WHERE (
                                   LOWER(nazev) LIKE LOWER('%$hledej%') OR
                                   LOWER(uvod) LIKE LOWER('%$hledej%') OR
                                   LOWER(obsah) LIKE LOWER('%$hledej%')
                                  )
                                  AND nazev!=''
                            ORDER BY datum DESC
                            LIMIT 50
                          ") or die(mysql_error());



    ////////////////////
    // Zobrazení článků
    ////////////////////
    $tmplClanek = new GlassTemplate("templates/$CONF[vzhled]/clanek.html");
    $i = 0;
    While($radek=mysql_fetch_array($dotaz)){
          $i++;
          // Zobrazení
          $tmplClanek->newBlok("clanekPrehled");
          $tmplClanek->prirad("clanekPrehled.i", $i);
          $tmplClanek->prirad("clanekPrehled.nazev", $radek["nazev"]);
          If( $CONF["mod_rewrite"])$tmplClanek->prirad("clanekPrehled.url", "clanky/".$radek["id"]."-".urlText($radek["nazev"]).".html");
          If(!$CONF["mod_rewrite"])$tmplClanek->prirad("clanekPrehled.url", "?a=clanky&clanek=".$radek["id"]."-".urlText($radek["nazev"]));
          $tmplClanek->prirad("clanekPrehled.datum", @date("j.n.Y G:i",$radek["datum"]));
          $tmplClanek->prirad("clanekPrehled.uvod", $radek["uvod"]);
          
          $pocetNalezenych++;
    }
    $tmplVyhledavani->prirad("clanky", $tmplClanek->getHtml());
    $tmplVyhledavani->prirad("pocetNalezenych", $pocetNalezenych);



    ///////////////////////////
    // Statistiky vyhledávání
    ///////////////////////////
    $id = zjisti_z("$CONF[sqlPrefix]vyhledavani", "id", "text=LOWER('$hledej')");
    If( $id ){
        If( $pocetNalezenych>0 ) $dotaz = Mysql_query("UPDATE $CONF[sqlPrefix]vyhledavani SET rating=(rating+1) WHERE id=$id") or die(mysql_error());
        Else $dotaz = Mysql_query("UPDATE $CONF[sqlPrefix]vyhledavani SET rating=(rating-1) WHERE id=$id") or die(mysql_error());
    }
    Else{
        If( $pocetNalezenych>0  ) $dotaz = Mysql_query("INSERT INTO $CONF[sqlPrefix]vyhledavani(text,rating) VALUES (LOWER('$hledej'),1)") or die(mysql_error());
        If( $pocetNalezenych==0 ) $dotaz = Mysql_query("INSERT INTO $CONF[sqlPrefix]vyhledavani(text,rating) VALUES (LOWER('$hledej'),-1)") or die(mysql_error());
    }



    ///////////////////////
    // Tipy k vyhledávání
    ///////////////////////
    $tolerance = 5;

    $rating = zjisti_z("$CONF[sqlPrefix]vyhledavani", "rating", "text='$hledej'");
    $rating = ($rating>0)?$rating:'1';

    /* -- SQL Dotaz -- */
    If( $tolerance==0 ){
        /**
         * při toleranci 0 se jednoduše zeptáme
         */
        $soundexHledej = "SUBSTRING( SOUNDEX(LOWER('$hledej')), 1, 4)";
        $dotaz = Mysql_query("SELECT text FROM $CONF[sqlPrefix]vyhledavani WHERE rating>$rating AND text!=LOWER('$hledej') AND SUBSTRING(SOUNDEX(text),1,4)=$soundexHledej ORDER BY rating DESC,id DESC") or die(mysql_error());
    }
    If( $tolerance>=1 ){
        /**
         * u tolerance větší než 1 musíme ignorovat první písmeno
         * a ze zbývajících hodnot vytvořit interval
         */
        $soundexMin = "(SUBSTRING( SOUNDEX(LOWER('$hledej')), 2, 3 ) - $tolerance)";
        $soundexMax = "(SUBSTRING( SOUNDEX(LOWER('$hledej')), 2, 3 ) + $tolerance)";

        $dotaz = Mysql_query("SELECT text FROM $CONF[sqlPrefix]vyhledavani WHERE rating>$rating AND text!=LOWER('$hledej') AND (SUBSTRING(SOUNDEX(text),2,3)>=$soundexMin AND SUBSTRING(SOUNDEX(text),2,3)<=$soundexMax) ORDER BY rating DESC,id DESC") or die(mysql_error());
    }

    /* -- Zobrazení tipů -- */
    $tipTmp = '';
    While($radek=mysql_fetch_array($dotaz)){
          //zabránění zobrazení více podobných slov za sebou
          If( soundex($radek["text"])!=soundex($tipTmp) ){
              $tmplVyhledavani->newBlok("tipy.slovo");
              $tmplVyhledavani->prirad("tipy.slovo.text", $radek["text"]);
              $tipTmp = $radek["text"];
          }
    }
    
    
    
    $tmpl->prirad("obsah", $tmplVyhledavani->getHtml());



    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="'.$CONF["absDir"].'">Úvodní strana</a> » Vyhledávání');




/*    $dotaz=mysql_query("SELECT *,SUBSTRING(SOUNDEX(text),2,3) FROM g3_vyhledavani ORDER BY SUBSTRING(SOUNDEX(text),2,3)") or die(mysql_error());
    While($radek=Mysql_fetch_array($dotaz)){
          Echo "text: ".$radek["text"]."; soundex(): ".substr(soundex($radek["text"]),1,3)."; SOUNDEX(): ".$radek["SUBSTRING(SOUNDEX(text),2,3)"]."<br>";
    }*/
}
?>