<?php
If( $_GET["a"]=='homepage' OR !$_GET["a"] ){
    /**
     * Homepage
     */
    Function vlozGalerii($obsah){
            $CONF = &$GLOBALS["config"];

            While( eregi('<img[^>]* alt="galery [0-9]+"[^>]*/>', $obsah) ){
                  $tmplGalerie = new GlassTemplate("templates/$CONF[vzhled]/galerie.html");
            
                  /* -- Získání ID galerie -- */
                  $idGalerie = preg_replace('|^.*<img[^>]* alt="galery ([0-9]+)"[^>]*/>.*$|Usi', "\\1", $obsah);
                  $idGalerie = preg_replace("|\s|Usi", "", $idGalerie);  //(nevim proč, ale php si tam přidávalo odřádkování)

                  
                  /* -- Vygenerování galerie -- */

                  //název
                  $radek = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]gal_kat WHERE id=$idGalerie AND skryta='ne'") );
                  $tmplGalerie->prirad("id", $radek["id"]);
                  $tmplGalerie->prirad("nazev", $radek["nazev"]);
                  
                  //fotky
                  $dotaz = Mysql_query("SELECT x.id as idKategorie, x.slozka, x.nazev, y.* FROM 
                                          $CONF[sqlPrefix]gal_kat x JOIN $CONF[sqlPrefix]galerie y ON x.id=y.kategorie
                                        WHERE x.id=$idGalerie AND x.skryta='ne' ORDER BY y.vaha ASC") or die(mysql_error());
                  While($radek=mysql_fetch_assoc($dotaz)){
                        $tmplGalerie->newBlok("obrazek");
                        
                        $tmplGalerie->prirad("obrazek.url", "$CONF[absDir]userfiles/galerie/$radek[slozka]/$radek[foto]");
                        $tmplGalerie->prirad("obrazek.urlNahled", "$CONF[absDir]userfiles/galerie/$radek[slozka]/nahledy/$radek[foto]");
                        $tmplGalerie->prirad("obrazek.kategorie", $radek["nazev"]);
                        $tmplGalerie->prirad("obrazek.popis", $radek["popis"]);                        
                  }
                  
                  /* -- Vložení do obsahu -- */
                  $obsahPred = $obsah;   
                  $obsah = preg_replace('|<img[^>]* alt="galery '.$idGalerie.'"[^>]*/>|Usi', $tmplGalerie->getHtml(), $obsah);
            }             
            
            Return $obsah;
    }
    
    $tmplHomepage = new GlassTemplate("templates/$CONF[vzhled]/homepage.html");     



    ////////////
    // Novinky
    ////////////
    // Zjištění obsahu novinky
    $tmplClanek = new GlassTemplate("templates/$CONF[vzhled]/clanek.html");
    
    $dotaz2 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='novinka' ORDER BY id DESC LIMIT 2");
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
    $tmplHomepage->prirad("novinky", $tmplClanek->getHtml());



    /////////////////////////////
    // Zobrazení přehledu zboží
    /////////////////////////////
    $tmplProdukty = new GlassTemplate("templates/$CONF[vzhled]/produkty.html");
    
    $dotaz2 = "SELECT *, MIN(cenaSDph), MIN(cenaBezDph) FROM $CONF[sqlPrefix]zbozi WHERE akce=1 GROUP BY produkt ORDER BY id DESC LIMIT 0,".$radek["pocet_zbozi"]."";
    $dotaz2 = Mysql_query($dotaz2);


    /* -- Zobrazení zboží -- */
    $i = 0;
    while($radek2=mysql_fetch_array($dotaz2)){
          $i++;
          $tmplProdukty->newBlok("produkt");

          $tmplProdukty->prirad("produkt.i", $i);
          // Název produktu
          $tmplProdukty->prirad("produkt.nazev", $radek2["produkt"]);
          // Číslo
          $tmplProdukty->prirad("produkt.cislo", $radek2["cislo"]);          
          // Krátký popis
          $tmplProdukty->prirad("produkt.popisUvod", substr(strip_tags($radek2["popis"]),0,180));
          // Ceny
          //dopočítání nezadaných cen
          $cenaSDph = $radek2["MIN(cenaSDph)"]?$radek2["MIN(cenaSDph)"]:($radek2["MIN(cenaBezDph)"]*(1+$radek2["dph"]/100));
          $cenaBezDph = $radek2["MIN(cenaBezDph)"]?$radek2["MIN(cenaBezDph)"]:($radek2["MIN(cenaSDph)"]/(1+$radek2["dph"]/100));
          $dph = $radek2["dph"]?$radek2["dph"]:($radek2["cenaSDph"]/$radek2["cenaBezDph"]*100-100);
          //zobrazení
          $tmplProdukty->prirad("produkt.cenaSDph", $cenaSDph);
          $tmplProdukty->prirad("produkt.cenaBezDph", $cenaBezDph);
          $tmplProdukty->prirad("produkt.dph", $dph);
          // Dostupnost
          $tmplProdukty->prirad("produkt.dostupnost", $radek2["dostupnost"]);
          // Akční nabídka
          If($radek2["akce"]) $tmpl->prirad("produkt.akce", "ano");
          // Url
          If( $CONF["mod_rewrite"] ){
              //(informace pro nové url se berou ze stávající url. Pokud zde nejsou, zkusí se získat z db.)
              $kategorie = $_GET["kategorie"]? $_GET["kategorie"]: ($radek2["kategorie"]? $radek2["id"].'-'.urlText($radek2["kategorie"]): '');
              $kategorie = $kategorie? ''.$kategorie.'/': '';
              $podkat1 = $_GET["podkat1"]? $_GET["podkat1"]: ($radek2["podkat1"]? $radek2["id"].'-'.urlText($radek2["podkat1"]): '');
              $podkat1 = $podkat1? ''.$podkat1.'/': '';
              $podkat2 = $_GET["podkat2"]? $_GET["podkat2"]: ($radek2["podkat2"]? $radek2["id"].'-'.urlText($radek2["podkat2"]): '');
              $podkat2 = $podkat2? ''.$podkat2.'/': '';
              $podkat3 = $_GET["podkat3"]? $_GET["podkat3"]: ($radek2["podkat3"]? $radek2["id"].'-'.urlText($radek2["podkat3"]): '');
              $podkat3 = $podkat3? ''.$podkat3.'/': '';
              $produkt = ''.$radek2["id"].'-'.urlText($radek2["produkt"]).".html";
              $tmplProdukty->prirad("produkt.url", $CONF["absDir"]."produkty/".$kategorie.$podkat1.$podkat2.$podkat3.$produkt);
          }
          Else{
              //(informace pro nové url se berou ze stávající url. Pokud zde nejsou, zkusí se získat z db.)
              $kategorie = $_GET["kategorie"]? $_GET["kategorie"]: ($radek2["kategorie"]? $radek2["id"].'-'.urlText($radek2["kategorie"]): '');
              $kategorie = $kategorie? '&kategorie='.$kategorie.'': '';
              $podkat1 = $_GET["podkat1"]? $_GET["podkat1"]: ($radek2["podkat1"]? $radek2["id"].'-'.urlText($radek2["podkat1"]): '');
              $podkat1 = $podkat1? '&podkat1='.$podkat1.'': '';
              $podkat2 = $_GET["podkat2"]? $_GET["podkat2"]: ($radek2["podkat2"]? $radek2["id"].'-'.urlText($radek2["podkat2"]): '');
              $podkat2 = $podkat2? '&podkat2='.$podkat2.'': '';
              $podkat3 = $_GET["podkat3"]? $_GET["podkat3"]: ($radek2["podkat3"]? $radek2["id"].'-'.urlText($radek2["podkat3"]): '');
              $podkat3 = $podkat3? '&podkat3='.$podkat3.'': '';
              $produkt = '&produkt='.$radek2["id"].'-'.urlText($radek["produkt"]);
              $tmplProdukty->prirad("produkt.url", "?a=produkty".$kategorie.$podkat1.$podkat2.$podkat3.$produkt);
          }
          // Url Do košíku
          $tmplProdukty->prirad("produkt.urlDoKosiku", "?a=kosik&b=pridat&produkt=".$radek2["id"]."-".urlText($radek2["produkt"]));
          // Obrázek
          $obrazek = ereg_replace("^;", "", $radek2["obrazky"]);
          $obrazek = preg_replace("|^([^;]*).*$|", "$1", $obrazek);
          If( $obrazek) $tmplProdukty->prirad("produkt.obrazek", "zbozi/obrazky/$obrazek");
          If(!$obrazek) $tmplProdukty->prirad("produkt.obrazek", file_exists("templates/$CONF[vzhled]/noPicture.jpg")?"templates/$CONF[vzhled]/noPicture.jpg":"templates/default/noPicture.jpg");
    }
    $tmplHomepage->prirad("produkty", $tmplProdukty->getHtml());
    
    
    ///////////
    // Zápatí
    ///////////
    $tmplClanek = new GlassTemplate("templates/$CONF[vzhled]/clanek.html");    
    $dotaz2=Mysql_query("SELECT *
                         FROM $CONF[sqlPrefix]clanky
                         WHERE id=".$radek["zapati"]);
    $radek2=Mysql_fetch_array($dotaz2);

    $tmplClanek->newBlok("clanek");
    $tmplClanek->prirad("clanek.nazev", "");  //$radek2["nazev"]
    $tmplClanek->prirad("clanek.datum", @date("j.n.Y G:i",$radek2["datum"]));
    $tmplClanek->prirad("clanek.uvod", $radek2["uvod"]);
    $tmplClanek->prirad("clanek.obsah", $radek2["obsah"]);


    $tmplHomepage->prirad("zapati", $tmplClanek->getHtml());


    ///////////
    // Anketa
    ///////////
    $tmplAnk = new GlassTemplate("templates/$CONF[vzhled]/ankety.html");
    
    $dotaz = Db_query("SELECT id,otazka FROM $CONF[sqlPrefix]ankety WHERE aktivni=1 ORDER BY id DESC");
    While($radek=mysql_fetch_assoc($dotaz)){
          $tmplAnk->newBlok("anketa");
                
          /* -- Přidání hlasu -- */
          Include_once 'ostatni.php/statistiky.funkce.php';   //umožňuje zjistit, jestli je návštěvník vyhledávací robot
          If( $_GET["anketa"]==$radek["id"] AND $_GET["odpoved"] ){
              // Promazání starých IP
              Db_query("DELETE FROM $CONF[sqlPrefix]anketyIp WHERE time<".(time()-60)."");
              // Hlasování
              $ip = $_SERVER["REMOTE_ADDR"]."/".$_SERVER["HTTP_X_FORWARDED_FOR"];
              If( !zjistiZ("$CONF[sqlPrefix]anketyIp", "id", "anketaId=$radek[id] AND ip='$ip'") AND !jeRobot() ){
                  Db_query("INSERT INTO $CONF[sqlPrefix]anketyIp(anketaId,time,ip) VALUES ($radek[id], ".time().", '$ip')");
                  Db_query("UPDATE $CONF[sqlPrefix]anketyOdpovedi SET pocet=(
                             (SELECT pocet FROM (SELECT pocet FROM $CONF[sqlPrefix]anketyOdpovedi WHERE id=$_GET[odpoved]) as pomTable LIMIT 1)+1) 
                            WHERE id=$_GET[odpoved]");
                           
                  If(mysql_affected_rows()==1) $tmplAnk->prirad("anketa.hlaska", "Váš hlas byl započítán.");
                  Else{$tmplAnk->prirad("anketa.hlaska", "Neznámá chyba."); bugReport("Chyba v hlasování v anketě, ovlivněných řádků: ".mysql_affected_rows());}
    
              }
              Else $tmplAnk->prirad("anketa.hlaska", "Nelze hlasovat vícekrát.");
          }
          
          /* -- Zobrazení -- */
          $tmplAnk->prirad("anketa.id", $radek["id"]);
          $tmplAnk->prirad("anketa.otazka", $radek["otazka"]);      
          /* -- Odpovědi -- */
          $dotaz2 = Db_query("SELECT * FROM $CONF[sqlPrefix]anketyOdpovedi WHERE anketaId=$radek[id] ORDER BY id ASC");
          $celkemHlasu = zjistiZ("$CONF[sqlPrefix]anketyOdpovedi","SUM(pocet)", "anketaId=$radek[id] GROUP BY anketaId");
          While($radek2=mysql_fetch_assoc($dotaz2)){
                $tmplAnk->newBlok("anketa.odpoved");
                $tmplAnk->prirad("anketa.odpoved.id", $radek2["id"]);
                $tmplAnk->prirad("anketa.odpoved.text", $radek2["odpoved"]);
                $tmplAnk->prirad("anketa.odpoved.url", $_SERVER["REQUEST_URL"]."?anketa=$radek[id]&odpoved=$radek2[id]");
                
                $tmplAnk->prirad("anketa.odpoved.pocetHlasu", $radek2["pocet"]);
                $tmplAnk->prirad("anketa.odpoved.procentHlasu", $celkemHlasu? round($radek2["pocet"]/$celkemHlasu*100,2): '0');
                $tmplAnk->prirad("anketa.odpoved.celkemHlasu", $celkemHlasu);
          }
          $tmplAnk->prirad("anketa.pocetOdpovedi", mysql_num_rows($dotaz2));
          $tmplAnk->prirad("anketa.celkemHlasu", $celkemHlasu);
    }
    
    $tmplHomepage->prirad("ankety", $tmplAnk->getHtml());


    //////////////////
    // Úvodní článek
    //////////////////
    $tmplClanek = new GlassTemplate("templates/$CONF[vzhled]/clanek.html");
        
    $dotaz=Mysql_query("SELECT x.*, y.*
                        FROM $CONF[sqlPrefix]homepage x, $CONF[sqlPrefix]clanky y
                        WHERE x.id=1 AND x.clanek=y.id");
    $radek=Mysql_fetch_array($dotaz);

    $tmplClanek->newBlok("clanek");
    $tmplClanek->prirad("clanek.nazev", $radek["nazev"]);
    $tmplClanek->prirad("clanek.datum", @date("j.n.Y G:i",$radek["datum"]));
    $tmplClanek->prirad("clanek.uvod", $radek["uvod"]);
    $tmplClanek->prirad("clanek.obsah", $radek["obsah"]);
    
    $tmplHomepage->prirad("clanek", vlozGalerii($tmplClanek->getHtml()));  


    $tmpl->prirad("obsah", $tmplHomepage->getHtml());

    ///////////////
    // partneri
    //////////////
    $partneri_dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky
                         WHERE id='8'");
    $p_radek=Mysql_fetch_array($partneri_dotaz);
    if($p_radek){
            $tmpl->newBlok("partneri_menu");
            $tmpl->prirad("partneri_menu.partneri", $p_radek["obsah"]);
            $tmpl->newBlok("paticka2");
    }
    else{
            $tmpl->newBlok("paticka1");
    }
    
    
    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", $radek["nazev"]);
}
?>