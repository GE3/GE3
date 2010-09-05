<?php
If( $_GET["a"]=='produkty' AND !$_GET["produkt"] ){
    /**
     * tento skript se aktivuje jen při výpisu určité kategorie produktů
     */


    /**********/
    /* CONFIG */
    /**********/
    $pocet = $_POST["pocet"]?$_POST["pocet"]:$_GET["pocet"];
    $pocet = $pocet?$pocet:9;



    /**********/
    /* FUNKCE */
    /**********/
    Function strom(){
            /**
             * vrací aktuální cestu ve tvaru 'kategorie » podkategorie » ...'
             */
            $CONF = &$GLOBALS["config"];

            //zjištění id kategorií a podkategorií z url
            $kategorie = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["kategorie"]);
            $podkat1 = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat1"]);
            $podkat2 = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat2"]);
            $podkat3 = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat3"]);

            //zformování konečné cesty
            If( $CONF["mod_rewrite"] ){
                $cesta='<a href="'.$CONF["absDir"].'produkty/'.$_GET["kategorie"].'/">'.zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id='$kategorie'").'</a> ';
                $cesta.=$podkat1?'» <a href="'.$CONF["absDir"].'produkty/'.$_GET["kategorie"].'/'.$_GET["podkat1"].'/">'.zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id='$podkat1'").'</a> ':'';
                $cesta.=$podkat2?'» <a href="'.$CONF["absDir"].'produkty/'.$_GET["kategorie"].'/'.$_GET["podkat1"].'/'.$_GET["podkat2"].'/">'.zjisti_z("$CONF[sqlPrefix]zbozi","podkat2","id='$podkat2'").'</a> ':'';
                $cesta.=$podkat3?'» <a href="'.$CONF["absDir"].'produkty/'.$_GET["kategorie"].'/'.$_GET["podkat1"].'/'.$_GET["podkat2"].'/'.$_GET["podkat3"].'/">'.zjisti_z("$CONF[sqlPrefix]zbozi","podkat3","id='$podkat3'").'</a> ':'';
            }
            Else{
                $cesta='<a href="?a=produkty&kategorie='.$_GET["kategorie"].'">'.zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id='$kategorie'").'</a> ';
                $cesta.=$podkat1?'» <a href="?a=produkty&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'">'.zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id='$podkat1'").'</a> ':'';
                $cesta.=$podkat2?'» <a href="?a=produkty&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'&podkat2='.$_GET["podkat2"].'">'.zjisti_z("$CONF[sqlPrefix]zbozi","podkat2","id='$podkat2'").'</a> ':'';
                $cesta.=$podkat3?'» <a href="?a=produkty&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'&podkat2='.$_GET["podkat2"].'&podkat3='.$_GET["podkat3"].'">'.zjisti_z("$CONF[sqlPrefix]zbozi","podkat3","id='$podkat3'").'</a> ':'';
            }

            Return $cesta;
    }
    Function podkat(){
            /**
             * vrací seznam odkazů na aktuální podkategorie
             */
            $CONF = &$GLOBALS["config"];

            //zjištění id kategorií a podkategorií z url
            $kategorie = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["kategorie"]);
            $podkat1 = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat1"]);
            $podkat2 = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat2"]);
            $podkat3 = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat3"]);

            //zjištění v kolikáté podkategorii se nacházíme
            $i=1;
            $i=$_GET["podkat1"]?2:$i;
            $i=$_GET["podkat2"]?3:$i;

            If( $i<=3 AND !$podkat3 ){
                //sql dotaz
                $podminka="kategorie='".zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id=$kategorie")."' ";
                $podminka.=$podkat1?"AND podkat1='".zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id=$podkat1")."' ":"";
                $podminka.=$podkat2?"AND podkat2='".zjisti_z("$CONF[sqlPrefix]zbozi","podkat2","id=$podkat2")."' ":"";
                $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE $podminka GROUP BY podkat$i ORDER BY podkat$i ASC");
                //vytvoření odkazů
                $nalezeno=FALSE;
                While($radek=Mysql_fetch_array($dotaz)){
                      If( $radek["podkat$i"] ){
                          $nalezeno=TRUE;

                          //vygenerování seo-url pro aktuální odkaz
                          If( $CONF["mod_rewrite"] ){
                              unset($seoKategorie,$seoPodkat1,$seoPodkat2,$seoPodkat3);
                              $seoKategorie = ( $_GET["kategorie"]?$_GET["kategorie"]:($radek["id"]."-".urlText($radek["kategorie"])) )."/";
                              $seoPodkat1 = ( $_GET["podkat1"]?$_GET["podkat1"]:($radek["id"]."-".urlText($radek["podkat1"])) )."/";
                              If($i>1) $seoPodkat2 = ( $_GET["podkat2"]?$_GET["podkat2"]:($radek["id"]."-".urlText($radek["podkat2"])) )."/";
                              If($i>2) $seoPodkat3 = ( $_GET["podkat3"]?$_GET["podkat3"]:($radek["id"]."-".urlText($radek["podkat3"])) )."/";

                              $vysledek.='
                                  <a href="'.$CONF["absDir"].$seoKategorie.$seoPodkat1.$seoPodkat2.$seoPodkat3.'">
                                     '.$radek["podkat$i"].'</a>&nbsp; |&nbsp;&nbsp;';
                          }
                          Else{
                              unset($seoKategorie,$seoPodkat1,$seoPodkat2,$seoPodkat3);
                              $seoKategorie = "&kategorie=".( $_GET["kategorie"]?$_GET["kategorie"]:($radek["id"]."-".urlText($radek["kategorie"])) );
                              $seoPodkat1 = "&podkat1=".( $_GET["podkat1"]?$_GET["podkat1"]:($radek["id"]."-".urlText($radek["podkat1"])) );
                              If($i>1) $seoPodkat2 = "&podkat2=".( $_GET["podkat2"]?$_GET["podkat2"]:($radek["id"]."-".urlText($radek["podkat2"])) );
                              If($i>2) $seoPodkat3 = "&podkat3=".( $_GET["podkat3"]?$_GET["podkat3"]:($radek["id"]."-".urlText($radek["podkat3"])) );

                              $vysledek.='
                                  <a href="?a=produkty'.$seoKategorie.$seoPodkat1.$seoPodkat2.$seoPodkat3.'">
                                     '.$radek["podkat$i"].'</a>&nbsp; |&nbsp;&nbsp;';
                          }
                      }
                }

                //pokud existují podkategorie, vypíšeme je
                If( $nalezeno ){
                    $vysledek=str_replace('|&nbsp;&nbsp;&end;',"",$vysledek."&end;");
                    Return "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-»&nbsp;".$vysledek."<br>";
                }
            }
    }



    /********************/
    /* ZOBRAZENÍ OBSAHU */
    /********************/
    $tmplProdukty = new GlassTemplate("templates/$CONF[vzhled]/produkty.html");    

    /////////////
    // Filtrace
    /////////////
    $tmplProdukty->newBlok("filtrace");

    //nadpis, akt.cesta a výběr podkategorie
    $kategorieId = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["kategorie"]);
    $tmplProdukty->prirad("filtrace.nadpis", zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id='".$kategorieId."' "));
    $tmplProdukty->prirad("filtrace.strom", strom());
    $tmplProdukty->prirad("filtrace.podkategorie", podkat());
    //seznam výrobců
    $dotaz=Mysql_query("SELECT x.vyrobce FROM $CONF[sqlPrefix]zbozi x, $CONF[sqlPrefix]zbozi y WHERE x.vyrobce!='' AND x.kategorie=y.kategorie AND y.id='$kategorieId' GROUP BY vyrobce ORDER BY vyrobce ASC") or die(mysql_error());
    While( $radek=Mysql_fetch_array($dotaz) ){
           $tmplProdukty->newBlok("filtrace.vyrobce");
           $tmplProdukty->prirad("filtrace.vyrobce.nazev", $radek["vyrobce"]);
    }


    /////////////////////////////////
    // Zobrazení přehledu kategorií
    /////////////////////////////////
    if( $_GET["kategorie"] and $_GET["podkat1"] and $_GET["podkat2"] ){
        $tmplProdukty->newBlok("vypis_kategorii");
        $dotaz = mysql_query("SELECT x.* FROM (SELECT * FROM $CONF[sqlPrefix]zbozi ORDER BY obrazekPodkat3 DESC) x, $CONF[sqlPrefix]zbozi y WHERE x.kategorie=y.kategorie AND x.podkat1=y.podkat1 AND x.podkat2=y.podkat2 AND y.id='".preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat2"])."' AND x.podkat3!='' GROUP BY podkat3");
        $i=0;
        while($podkat=mysql_fetch_assoc($dotaz)){
              $i++;
              $tmplProdukty->newBlok("vypis_kategorii.kategorie");
              $tmplProdukty->prirad("vypis_kategorii.kategorie.i", $i);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.id", $podkat["id"]);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.nazev", $podkat["podkat3"]);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.seoNazev", urlText($podkat["podkat3"]));
              $tmplProdukty->prirad("vypis_kategorii.kategorie.obrazek", "$CONF[absDir]zbozi/obrazky/$podkat[obrazekPodkat3]");
              if($CONF["mod_rewrite"]) $tmplProdukty->prirad("vypis_kategorii.kategorie.url", "$CONF[absDir]produkty/$_GET[kategorie]/$_GET[podkat1]/$_GET[podkat2]/$podkat[id]-".urlText($podkat["podkat3"])."/");
              else $tmplProdukty->prirad("vypis_kategorii.kategorie.url", "$CONF[absDir]index.php?a=kategorie&kategorie=$_GET[kategorie]&podkat1=$_GET[podkat1]&podkat2=$_GET[podkat2]&podkat3=$podkat[id]-".urlText($podkat["podkat3"])."");
        }
    }
    elseif( $_GET["kategorie"] and $_GET["podkat1"] ){
        $tmplProdukty->newBlok("vypis_kategorii");
        $dotaz = mysql_query("SELECT x.* FROM (SELECT * FROM $CONF[sqlPrefix]zbozi ORDER BY obrazekPodkat2 DESC) x, $CONF[sqlPrefix]zbozi y WHERE x.kategorie=y.kategorie AND x.podkat1=y.podkat1 AND y.id='".preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat1"])."' AND x.podkat2!='' GROUP BY podkat2");
        $i=0;
        while($podkat=mysql_fetch_assoc($dotaz)){
              $i++;
              $tmplProdukty->newBlok("vypis_kategorii.kategorie");
              $tmplProdukty->prirad("vypis_kategorii.kategorie.i", $i);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.id", $podkat["id"]);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.nazev", $podkat["podkat2"]);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.seoNazev", urlText($podkat["podkat2"]));
              $tmplProdukty->prirad("vypis_kategorii.kategorie.obrazek", "$CONF[absDir]zbozi/obrazky/$podkat[obrazekPodkat2]");
              if($CONF["mod_rewrite"]) $tmplProdukty->prirad("vypis_kategorii.kategorie.url", "$CONF[absDir]produkty/$_GET[kategorie]/$_GET[podkat1]/$podkat[id]-".urlText($podkat["podkat2"])."/");
              else $tmplProdukty->prirad("vypis_kategorii.kategorie.url", "$CONF[absDir]index.php?a=kategorie&kategorie=$_GET[kategorie]&podkat1=$_GET[podkat1]&podkat2=$podkat[id]-".urlText($podkat["podkat2"])."");
        }
    }
    elseif( $_GET["kategorie"] ){
        $tmplProdukty->newBlok("vypis_kategorii");
        $dotaz = mysql_query("SELECT x.* FROM (SELECT * FROM $CONF[sqlPrefix]zbozi ORDER BY obrazekPodkat1 DESC) x, $CONF[sqlPrefix]zbozi y WHERE x.kategorie=y.kategorie AND y.id='".preg_replace("|^([0-9]*)-.*$|","$1",$_GET["kategorie"])."' AND x.podkat1!='' GROUP BY podkat1");
        $i=0;
        while($podkat=mysql_fetch_assoc($dotaz)){
              $i++;
              
              $prvni_podkat = mysql_fetch_assoc( mysql_query("SELECT x.id FROM $CONF[sqlPrefix]zbozi x, $CONF[sqlPrefix]zbozi y WHERE x.kategorie=y.kategorie AND x.podkat1=y.podkat1 AND y.id=$podkat[id] ORDER BY x.podkat1 ASC") );
              $id = $prvni_podkat["id"];
              
              $tmplProdukty->newBlok("vypis_kategorii.kategorie");
              $tmplProdukty->prirad("vypis_kategorii.kategorie.i", $i);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.id", $id);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.nazev", $podkat["podkat1"]);
              $tmplProdukty->prirad("vypis_kategorii.kategorie.seoNazev", urlText($podkat["podkat1"]));
              $tmplProdukty->prirad("vypis_kategorii.kategorie.obrazek", "$CONF[absDir]zbozi/obrazky/$podkat[obrazekPodkat1]");
              if($CONF["mod_rewrite"]) $tmplProdukty->prirad("vypis_kategorii.kategorie.url", "$CONF[absDir]produkty/$_GET[kategorie]/$id-".urlText($podkat["podkat1"])."/");
              else $tmplProdukty->prirad("vypis_kategorii.kategorie.url", "$CONF[absDir]index.php?a=kategorie&kategorie=$_GET[kategorie]&podkat1=$id-".urlText($podkat["podkat1"])."");
        }
    } 


    /////////////////////////////
    // Zobrazení přehledu zboží
    /////////////////////////////

    /* -- Sestavení dotazu -- */

    // Podmínky
    //kategorie a podkategorie
    $kategorieId = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["kategorie"]);  //zjištění id kategorií a podkat.
    $podkat1Id = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat1"]);
    $podkat2Id = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat2"]);
    $podkat3Id = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat3"]);

    $podminka = $kategorieId? "kategorie='".zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id='$kategorieId' ")."' ": '';               //kategorie
    $podminka.= $podkat1Id? "AND podkat1='".zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id='$podkat1Id' ")."' ": '';  //podkategorie 1
    $podminka.= $podkat2Id? "AND podkat2='".zjisti_z("$CONF[sqlPrefix]zbozi","podkat2","id='$podkat2Id' ")."' ": '';  //podkategorie 2
    $podminka.= $podkat3Id? "AND podkat3='".zjisti_z("$CONF[sqlPrefix]zbozi","podkat3","id='$podkat3Id' ")."' ": '';  //podkategorie 3

    //výrobce
    $vyrobce = $_POST["vyrobce"]?$_POST["vyrobce"]:$_GET["vyrobce"];
    $vyrobce = $_GET["vyrobceId"]? zjisti_z("$CONF[sqlPrefix]zbozi","vyrobce","id=".eregi_replace("^([0-9]+)-.*$","\\1",$_GET["vyrobceId"])." LIMIT 1"): $vyrobce;
    $podminka.= $vyrobce?"AND vyrobce='$vyrobce' ":'';

    // Šeřadit podle
    $seradit_podle = $_POST["seradit_podle"]?$_POST["seradit_podle"]:$_GET["seradit_podle"];
    $seradit_podle = $seradit_podle?$seradit_podle:'vahaProduktu DESC';
    $seradit_podle.= ", cenaSDph ASC";

    // Limit
    $strana = $_GET["strana"]?$_GET["strana"]:1;
    $limit = ($strana*$pocet-$pocet).",".$pocet;

    // Spuštění dotazu
    $podminka.= "AND produkt IS NOT NULL ";
    $podminka = eregi_replace("^AND ", "", $podminka);
    $dotaz = "SELECT *, MIN(cenaSDph), MIN(cenaBezDph) FROM $CONF[sqlPrefix]zbozi WHERE $podminka GROUP BY produkt ORDER BY $seradit_podle LIMIT $limit";
    $dotaz = Mysql_query($dotaz);


    /* -- Zobrazení zboží -- */
    $i=0;
    while($radek=mysql_fetch_array($dotaz)){
          $i++;
          $tmplProdukty->newBlok("produkt");

          $tmplProdukty->prirad("produkt.i", $i);
          // Název produktu
          $tmplProdukty->prirad("produkt.nazev", $radek["produkt"]);
          // Číslo produktu
          $tmplProdukty->prirad("produkt.cislo", $radek["cislo"]);          
          // Výrobce
          $tmplProdukty->prirad("produkt.vyrobce", $radek["vyrobce"]);
          // Krátký popis
          $tmplProdukty->prirad("produkt.popisUvod", ereg_replace( " [^ ]*$", "", substr(strip_tags($radek["popis"]),0,180)) );
          // Ceny
          //dopočítání nezadaných cen
          $cenaSDph = $radek["MIN(cenaSDph)"]?$radek["MIN(cenaSDph)"]:($radek["MIN(cenaBezDph)"]*(1+$radek["dph"]/100));
          $cenaBezDph = $radek["MIN(cenaBezDph)"]?$radek["MIN(cenaBezDph)"]:($radek["MIN(cenaSDph)"]/(1+$radek["dph"]/100));
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
          $tmplProdukty->prirad("produkt.urlDoKosiku", $CONF["absDir"]."?a=kosik&b=pridat&produkt=".$radek["id"]."-".urlText($radek["produkt"]));
          // Obrázek
          $obrazek = ereg_replace("^;", "", $radek["obrazky"]);
          $obrazek = preg_replace("|^([^;]*).*$|", "$1", $obrazek);
          If( $obrazek) $tmplProdukty->prirad("produkt.obrazek", "zbozi/obrazky/$obrazek");
          If(!$obrazek) $tmplProdukty->prirad("produkt.obrazek", file_exists("templates/$CONF[vzhled]/noPicture.jpg")?"templates/$CONF[vzhled]/noPicture.jpg":"templates/default/noPicture.jpg");
    }
    $tmplProdukty->prirad("pocetProduktu", $i);
              

    /* -- Odkazy na stránky -- */

    // Zjištění počtu produktů
    // (proměnné $podminka a $seradit_podle jsou zachované ze skriptu výše)
    $dotaz = "SELECT COUNT(DISTINCT produkt) FROM $CONF[sqlPrefix]zbozi WHERE $podminka ORDER BY $seradit_podle";
    $dotaz = Mysql_query($dotaz);
    $radek = mysql_fetch_array($dotaz);
    $pocetProduktu = $radek["COUNT(DISTINCT produkt)"];

    // Vytvoření odkazů
    $tmplProdukty->newBlok("strankovani");
    //načtení existujících hodnot a převedení na url
    $urlPodkat1 = $_GET["podkat1"]?"&podkat1=".$_GET["podkat1"]:'';
    $urlPodkat2 = $_GET["podkat2"]?"&podkat2=".$_GET["podkat2"]:'';
    $urlPodkat3 = $_GET["podkat3"]?"&podkat3=".$_GET["podkat3"]:'';
    $urlPocet = $_POST["pocet"]?$_POST["pocet"]:$_GET["pocet"]; $urlPocet = $urlPocet?"&pocet=".$urlPocet:'';
    $urlSeraditPodle = $_POST["seradit_podle"]?$_POST["seradit_podle"]:$_GET["seradit_podle"]; $urlSeraditPodle = $urlSeraditPodle?"&seradit_podle=".$urlSeraditPodle:'';
    $urlVyrobce = $_POST["vyrobce"]?$_POST["vyrobce"]:$_GET["vyrobce"]; $urlVyrobce = $urlVyrobce?"&vyrobce=".$urlVyrobce:'';
    $urlVyrobceId = $_GET["vyrobceId"]? "&vyrobceId=$_GET[vyrobceId]": '';
    //urlFirst
    If( $CONF["mod_rewrite"] ){
        $tmplProdukty->prirad("strankovani.urlFirst", "?".str_replace('&','',$urlPocet).$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
    }
    Else{
        $tmplProdukty->prirad("strankovani.urlFirst", "?a=produkty&kategorie=".$_GET["kategorie"].$urlPodkat1.$urlPodkat2.$urlPodkat3.$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
    }
    //urlPrew
    If( $CONF["mod_rewrite"] ){
        $strana = $_GET["strana"]?($_GET["strana"]-1):'';
        $urlStrana = ($strana AND $strana>1)?"strana=".$strana:'';
        $tmplProdukty->prirad("strankovani.urlPrev", "?".$urlStrana.$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
    }
    Else{
        $strana = $_GET["strana"]?($_GET["strana"]-1):'';
        $urlStrana = ($strana AND $strana>1)?"&strana=".$strana:'';
        $tmplProdukty->prirad("strankovani.urlPrev", "?a=produkty&kategorie=".$_GET["kategorie"].$urlPodkat1.$urlPodkat2.$urlPodkat3.$urlStrana.$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
    }
    //urlNext
    If( $CONF["mod_rewrite"] ){
        $pocetStran = ceil($pocetProduktu/$pocet);
        $strana = $_GET["strana"]?($_GET["strana"]+1):2;
        $urlStrana = ($strana<=$pocetStran)?"strana=".$strana:'';
        $urlStrana = ($strana>$pocetStran)?"strana=".$pocetStran:$urlStrana;
        $urlStrana = ($strana>$pocetStran AND $pocetStran==1)?'':$urlStrana;
        $tmplProdukty->prirad("strankovani.urlNext", "?".$urlStrana.$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
    }
    Else{
        $pocetStran = ceil($pocetProduktu/$pocet);
        $strana = $_GET["strana"]?($_GET["strana"]+1):2;
        $urlStrana = ($strana<=$pocetStran)?"&strana=".$strana:'';
        $urlStrana = ($strana>$pocetStran)?"&strana=".$pocetStran:$urlStrana;
        $urlStrana = ($strana>$pocetStran AND $pocetStran==1)?'':$urlStrana;
        $tmplProdukty->prirad("strankovani.urlNext", "?a=produkty&kategorie=".$_GET["kategorie"].$urlPodkat1.$urlPodkat2.$urlPodkat3.$urlStrana.$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
    }
    //urlLast
    If( $CONF["mod_rewrite"] ){
        $urlStrana = ($pocetStran>1)?"strana=".$pocetStran:'';
        $tmplProdukty->prirad("strankovani.urlLast", "?".$urlStrana.$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
    }
    Else{
        $urlStrana = ($pocetStran>1)?"&strana=".$pocetStran:'';
        $tmplProdukty->prirad("strankovani.urlLast", "?a=produkty&kategorie=".$_GET["kategorie"].$urlPodkat1.$urlPodkat2.$urlPodkat3.$urlStrana.$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
    }
    //čísla stránek
    $i = 1;
    While($i<=$pocetStran){
          $tmplProdukty->newBlok("strankovani.odkaz");
          $urlStrana = ($i>1)?"&strana=$i":'';
          If( $CONF["mod_rewrite"]) $tmplProdukty->prirad("strankovani.odkaz.url", "?".str_replace('&','',$urlStrana).$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
          If(!$CONF["mod_rewrite"]) $tmplProdukty->prirad("strankovani.odkaz.url", "?a=produkty&kategorie=".$_GET["kategorie"].$urlPodkat1.$urlPodkat2.$urlPodkat3.$urlStrana.$urlPocet.$urlSeraditPodle.$urlVyrobce.$urlVyrobceId);
          $tmplProdukty->prirad("strankovani.odkaz.cisloStrany", $i);
          $i++;
    }
    $tmplProdukty->prirad("strankovani.pocetStran", ($i-1));
    
    
    /* -- Popisek kategorie -- */
    $tmplProdukty->prirad("popisKategorie", zjisti_z("$CONF[sqlPrefix]zbozi","popisKategorie","id=$kategorieId"));    
    
    
    $tmpl->prirad("obsah", $tmplProdukty->getHtml());    



    /************/
    /* NAVIGACE */
    /************/
    $vyrobceId = ereg_replace("^([0-9]*)-.*$","\\1",$_GET["vyrobceId"]);
    $navigace = preg_replace("|^(.*)<a href=\"[^\"]*\">([^<]*)</a> $|Usi", "$1"."$2", strom());  //poslední položka v navigaci nebude odkaz
    $navigace = $navigace? $navigace: zjisti_z("$CONF[sqlPrefix]zbozi","vyrobce","id=$vyrobceId LIMIT 1");
    $tmpl->prirad("navigace",'<a href="'.$CONF["absDir"].'">Úvodní strana</a> » '.$navigace);



    /**************/
    /* TITLE INFO */
    /**************/
    // Podkat1
    If( $_GET["podkat1"] ){
        $podkat1Id = ereg_replace("^([0-9]*)-.*$","\\1",$_GET["podkat1"]);
        $radek = mysql_fetch_assoc( Mysql_query("SELECT kategorie, podkat1 FROM $CONF[sqlPrefix]zbozi WHERE id=$podkat1Id") );
        $titleInfo = $radek["kategorie"]." » ".$radek["podkat1"];
    }
    // Kategorie
    Elseif( $_GET["kategorie"] ){
        $kategorieId = ereg_replace("^([0-9]*)-.*$","\\1",$_GET["kategorie"]);
        $radek = mysql_fetch_assoc( Mysql_query("SELECT kategorie FROM $CONF[sqlPrefix]zbozi WHERE id=$kategorieId") );
        $titleInfo = $radek["kategorie"];
    }
    Elseif( $_GET["vyrobceId"] ){
        $vyrobceId = ereg_replace("^([0-9]*)-.*$","\\1",$_GET["vyrobceId"]);
        $radek = mysql_fetch_assoc( Mysql_query("SELECT vyrobce FROM $CONF[sqlPrefix]zbozi WHERE id=$vyrobceId") );
        $titleInfo = $radek["vyrobce"];    
    }

    $tmpl->prirad("titleInfo", $titleInfo);
}
?>

