<?php
/**********/
/* FUNKCE */
/**********/
Function priradOdkazyPodkat(&$tmpl,$kategorieScript,$podkat1Script='',$podkat2Script=''){
        $CONF = &$GLOBALS["config"];

        $vysledek='';

        If( $podkat2Script ){
                //Zjištění id aktuální kategorie
                $getPodkat2Id = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat2"]);

                //Pokud je tato podkategorie aktuálnní
                If( $getPodkat2Id==$podkat2Script ){
                    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id='$podkat1Script'")."' AND podkat1='".zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id='$podkat1Script'")."' AND podkat2='".zjisti_z("$CONF[sqlPrefix]zbozi","podkat2","id='$podkat2Script'")."' GROUP BY podkat3 ORDER BY podkat3 ASC");
                    While($radek=mysql_fetch_array($dotaz)){
                          If( $radek["podkat3"] ){
                              // Seo url?
                              $url = $CONF["mod_rewrite"]? $CONF["absDir"]."produkty/$_GET[kategorie]/$_GET[podkat1]/$_GET[podkat2]/$radek[id]-".urlText($radek["podkat3"])."/": "?a=produkty&kategorie=".$_GET["kategorie"]."&podkat1=".$_GET["podkat1"]."&podkat2=".$_GET["podkat2"]."&podkat3=".$radek["id"].'-'.urlText($radek["podkat3"]) ;

                              // Přiřadíme odkazy na její podkategorie do templatu
                              $tmpl->newBlok("menuProdukty.odkaz.podOdkaz1.podOdkaz2.podOdkaz3");
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.podOdkaz2.podOdkaz3.url", $url);
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.podOdkaz2.podOdkaz3.seoPodkat3", $radek["id"].'-'.urlText($radek["podkat3"]));
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.podOdkaz2.podOdkaz3.text", $radek["podkat3"]);
                          }
                    }
                }
        }
        Elseif( $podkat1Script ){
                //Zjištění id aktuální kategorie
                $getPodkat1Id = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["podkat1"]);

                //Pokud je tato podkategorie aktuálnní
                If( $getPodkat1Id==$podkat1Script ){
                    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id='$podkat1Script'")."' AND podkat1='".zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id='$podkat1Script'")."' GROUP BY podkat2 ORDER BY podkat2 ASC");
                    While($radek=mysql_fetch_array($dotaz)){
                          If( $radek["podkat2"] ){
                              // Seo url?
                              $url = $CONF["mod_rewrite"]? $CONF["absDir"]."produkty/$_GET[kategorie]/$_GET[podkat1]/$radek[id]-".urlText($radek["podkat2"])."/": "?a=produkty&kategorie=".$_GET["kategorie"]."&podkat1=".$_GET["podkat1"]."&podkat2=".$radek["id"].'-'.urlText($radek["podkat2"]) ;

                              // Přiřadíme odkazy na její podkategorie do templatu
                              $tmpl->newBlok("menuProdukty.odkaz.podOdkaz1.podOdkaz2");
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.podOdkaz2.url", $url);
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.podOdkaz2.seoPodkat2", $radek["id"].'-'.urlText($radek["podkat2"]));
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.podOdkaz2.text", $radek["podkat2"]);

                              // Rekurze vnořených podkategorií
                              priradOdkazyPodkat($tmpl,$kategorieScript,$podkat1Script,$radek["id"]);
                          }
                    }
                }
        }
        Elseif( $kategorieScript ){
                //Zjištění id aktuální kategorie
                $getKategorieId = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["kategorie"]);

                //Pokud je tato kategorie aktuálnní
                If( $getKategorieId==$kategorieScript ){
                    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id='$kategorieScript'")."' GROUP BY podkat1 ORDER BY podkat1 ASC");
                    While($radek=mysql_fetch_array($dotaz)){
                          If( $radek["podkat1"] and $radek["podkat1Aktivni"]!='ne' ){
                              // Seo url?
                              $url = $CONF["mod_rewrite"]? $CONF["absDir"]."produkty/$_GET[kategorie]/$radek[id]-".urlText($radek["podkat1"])."/": "?a=produkty&kategorie=".$_GET["kategorie"]."&podkat1=".$radek["id"].'-'.urlText($radek["podkat1"]) ;

                              // Přiřadíme odkazy na její podkategorie do templatu
                              $tmpl->newBlok("menuProdukty.odkaz.podOdkaz1");
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.url", $url);
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.seoPodkat1", $radek["id"].'-'.urlText($radek["podkat1"]));
                              $tmpl->prirad("menuProdukty.odkaz.podOdkaz1.text", $radek["podkat1"]);

                              // Rekurze vnořených podkategorií
                              priradOdkazyPodkat($tmpl,$kategorieScript,$radek["id"]);
                          }
                    }
                }
        }

}



/*****************************/
/* ZOBRAZENÍ KATEGORIÍ ZBOŽÍ */
/*****************************/

/* -- Zjistíme kategorie -- */
$dotaz="SELECT * FROM $CONF[sqlPrefix]zbozi GROUP BY kategorie ORDER BY vaha DESC, kategorie ASC";
$dotaz=mysql_query($dotaz) or die(mysql_error());

/* -- Vložení do šablony -- */
$tmpl->newBlok("menuProdukty");
While($radek=mysql_fetch_array($dotaz)){
      If( strlen($radek["kategorie"])>0 AND $radek["kategorieAktivni"]!='ne' ){  //kategorie bez názvu se nezobrazují
          // Seo url?
          $url = $CONF["mod_rewrite"]? $CONF["absDir"]."produkty/$radek[id]-".urlText($radek["kategorie"])."/": '?a=produkty&kategorie='.$radek["id"].'-'.urlText($radek["kategorie"]) ;

          // Přiřadíme do templatu
          $tmpl->newBlok("menuProdukty.odkaz");
          $tmpl->prirad("menuProdukty.odkaz.url", $url);
          $tmpl->prirad("menuProdukty.odkaz.seoKategorie", $radek["id"].'-'.urlText($radek["kategorie"]));
          $tmpl->prirad("menuProdukty.odkaz.text", $radek["kategorie"]);

          priradOdkazyPodkat($tmpl,$radek["id"]);
      }
}



/***************************/
/* ZOBRAZENÍ VÝROBCŮ ZBOŽÍ */
/***************************/

/* -- Zjistíme kategorie -- */
$dotaz="SELECT * FROM $CONF[sqlPrefix]zbozi WHERE vyrobce!='' GROUP BY vyrobce ORDER BY vyrobce ASC";
$dotaz=mysql_query($dotaz) or die(mysql_error());

/* -- Vložení do šablony -- */
$tmpl->newBlok("menuVyrobci");
While($radek=mysql_fetch_array($dotaz)){
      // Seo url?
      $url = $CONF["mod_rewrite"]? $CONF["absDir"]."produkty/?vyrobceId=$radek[id]-".urlText($radek["vyrobce"]): '?a=produkty&vyrobceId='.$radek["id"].'-'.urlText($radek["vyrobce"]) ;

      // Přiřadíme do templatu
      $tmpl->newBlok("menuVyrobci.odkaz");
      $tmpl->prirad("menuVyrobci.odkaz.url", $url);
      $tmpl->prirad("menuVyrobci.odkaz.seoVyrobce", $radek["id"].'-'.urlText($radek["vyrobce"]));
      $tmpl->prirad("menuVyrobci.odkaz.text", $radek["vyrobce"]);
}
?>