<?php
If( $_GET["a"]=='mapa-webu' ){
    /**
     * vypíše stromovou strukturu všech odkazů na webu
     */
    $tmplMapa = new GlassTemplate("templates/$CONF[vzhled]/mapa.html", "templates/default/index.html");


    /****************/
    /* VÝPIS ČLÁNKŮ */
    /****************/
    $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='vodorovne'") or die(mysql_error());
    While($radek=mysql_fetch_assoc($dotaz)){
          $tmplMapa->newBlok("mapa.clanek");
          $tmplMapa->prirad("mapa.clanek.nazev", $radek["nazev"]);
          If($CONF["mod_rewrite"]) $tmplMapa->prirad("mapa.clanek.url", "$CONF[absDir]clanky/$radek[id]-".urlText($radek["nazev"]).".html");
          Else $tmplMapa->prirad("mapa.clanek.url", "index.php?a=clanky&clanek=$radek[id]-".urlText($radek["nazev"]));          
    }
    
    
    
    /******************/
    /* VÝPIS PRODUKTŮ */
    /******************/
    
    /* -- Kategorie -- */
    $dotaz = Mysql_query("SELECT id,kategorie FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL GROUP BY kategorie ORDER BY vaha DESC");
    While($kategorie=mysql_fetch_assoc($dotaz)){
          $tmplMapa->newBlok("mapa.kategorie");
          $tmplMapa->prirad("mapa.kategorie.nazev", $kategorie["kategorie"]);
          If($CONF["mod_rewrite"]) $tmplMapa->prirad("mapa.kategorie.url", "$CONF[absDir]produkty/$kategorie[id]-".urlText($kategorie["kategorie"])."/");
          Else $tmplMapa->prirad("mapa.kategorie.url", "index.php?a=produkty&kategorie=$kategorie[id]-".urlText($kategorie["kategorie"]));
          
          /* -- Podkat1 -- */
          $dotaz2 = Mysql_query("SELECT id,podkat1 FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND podkat1!='' AND kategorie='$kategorie[kategorie]' GROUP BY podkat1 ORDER BY podkat1 ASC");
          While($podkat1=mysql_fetch_assoc($dotaz2)){
                $tmplMapa->newBlok("mapa.kategorie.podkat1");
                $tmplMapa->prirad("mapa.kategorie.podkat1.nazev", $podkat1["podkat1"]);
                If($CONF["mod_rewrite"]) $tmplMapa->prirad("mapa.kategorie.podkat1.url", "$CONF[absDir]produkty/$kategorie[id]-".urlText($kategorie["kategorie"])."/$podkat1[id]-".urlText($podkat1["podkat1"])."/");
                Else $tmplMapa->prirad("mapa.kategorie.podkat1.url", "index.php?a=produkty&kategorie=$kategorie[id]-".urlText($kategorie["kategorie"])."&podkat1=$podkat1[id]-".urlText($podkat1["podkat1"]));
                
                /* -- Podkat2 -- */
                $dotaz3 = Mysql_query("SELECT id,podkat2 FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND podkat2!='' AND podkat1='$podkat1[podkat1]' GROUP BY podkat2 ORDER BY podkat2 ASC");
                While($podkat2=mysql_fetch_assoc($dotaz3)){
                      $tmplMapa->newBlok("mapa.kategorie.podkat1.podkat2");
                      $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.nazev", $podkat2["podkat2"]);
                      If($CONF["mod_rewrite"]) $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.url", "$CONF[absDir]produkty/$kategorie[id]-".urlText($kategorie["kategorie"])."/$podkat1[id]-".urlText($podkat1["podkat1"])."/$podkat2[id]-".urlText($podkat2["podkat2"])."/");
                      Else $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.url", "index.php?a=produkty&kategorie=$kategorie[id]-".urlText($kategorie["kategorie"])."&podkat1=$podkat1[id]-".urlText($podkat1["podkat1"])."&podkat2=$podkat2[id]-".urlText($podkat2["podkat2"]));
                      
                      /* -- Podkat3 -- */
                      $dotaz4 = Mysql_query("SELECT id,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND podkat3!='' AND podkat2='$podkat2[podkat2]' GROUP BY podkat2 ORDER BY podkat3 ASC");
                      While($podkat3=mysql_fetch_assoc($dotaz4)){
                            $tmplMapa->newBlok("mapa.kategorie.podkat1.podkat2.podkat3");
                            $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.podkat3.nazev", $podkat3["podkat3"]);
                            If($CONF["mod_rewrite"]) $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.podkat3.url", "$CONF[absDir]produkty/$kategorie[id]-".urlText($kategorie["kategorie"])."/$podkat1[id]-".urlText($podkat1["podkat1"])."/$podkat2[id]-".urlText($podkat2["podkat2"])."/$podkat3[id]-".urlText($podkat3["podkat3"])."/");
                            Else $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.podkat3.url", "index.php?a=produkty&kategorie=$kategorie[id]-".urlText($kategorie["kategorie"])."&podkat1=$podkat1[id]-".urlText($podkat1["podkat1"])."&podkat2=$podkat2[id]-".urlText($podkat2["podkat2"])."&podkat3=$podkat3[id]-".urlText($podkat3["podkat3"]));
                            
                            // Produkty
                            $dotazProdukty = Mysql_query("SELECT id,produkt,kategorie,podkat1,podkat2,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND kategorie='$kategorie[kategorie]' AND podkat1='$podkat1[podkat1]' AND podkat2='$podkat2[podkat2]' AND podkat3='' ORDER BY produkt ASC LIMIT 5");
                            While($produkt=mysql_fetch_assoc($dotazProdukty)){
                                  $tmplMapa->newBlok("mapa.kategorie.podkat1.podkat2.produkt");
                                  $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.produkt.nazev", $produkt["produkt"]);
                                  //url
                                  $url = "$CONF[absDir]produkty/";
                                  $url.= "$kategorie[id]-".urlText($kategorie["kategorie"])."/";
                                  $url.= "$podkat1[id]-".urlText($podkat1["podkat1"])."/";
                                  $url.= "$podkat2[id]-".urlText($podkat2["podkat2"])."/";
                                  $url.= "$podkat3[id]-".urlText($podkat3["podkat3"])."/";
                                  $url.= "$produkt[id]-".urlText($produkt["produkt"]).".html";
                                  $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.podkat3.produkt.url", $url);                                              
                            } 
                      }
                                              
                      // Produkty
                      $dotazProdukty = Mysql_query("SELECT id,produkt,kategorie,podkat1,podkat2,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND kategorie='$kategorie[kategorie]' AND podkat1='$podkat1[podkat1]' AND podkat2='$podkat2[podkat2]' AND podkat3='' ORDER BY produkt ASC LIMIT 5");
                      While($produkt=mysql_fetch_assoc($dotazProdukty)){
                            $tmplMapa->newBlok("mapa.kategorie.podkat1.podkat2.produkt");
                            $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.produkt.nazev", $produkt["produkt"]);
                            //url
                            $url = "$CONF[absDir]produkty/";
                            $url.= "$kategorie[id]-".urlText($kategorie["kategorie"])."/";
                            $url.= "$podkat1[id]-".urlText($podkat1["podkat1"])."/";
                            $url.= "$podkat2[id]-".urlText($podkat2["podkat2"])."/";
                            $url.= "$produkt[id]-".urlText($produkt["produkt"]).".html";
                            $tmplMapa->prirad("mapa.kategorie.podkat1.podkat2.produkt.url", $url);                                              
                      }                  
                }
                                
                // Produkty
                $dotazProdukty = Mysql_query("SELECT id,produkt,kategorie,podkat1,podkat2,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND kategorie='$kategorie[kategorie]' AND podkat1='$podkat1[podkat1]' AND podkat2='' AND podkat3='' ORDER BY produkt ASC LIMIT 5");
                While($produkt=mysql_fetch_assoc($dotazProdukty)){
                      $tmplMapa->newBlok("mapa.kategorie.podkat1.produkt");
                      $tmplMapa->prirad("mapa.kategorie.podkat1.produkt.nazev", $produkt["produkt"]);
                      //url
                      $url = "$CONF[absDir]produkty/";
                      $url.= "$kategorie[id]-".urlText($kategorie["kategorie"])."/";
                      $url.= "$podkat1[id]-".urlText($podkat1["podkat1"])."/";
                      $url.= "$produkt[id]-".urlText($produkt["produkt"]).".html";
                      $tmplMapa->prirad("mapa.kategorie.podkat1.produkt.url", $url);                                      
                }          
          }
          
          // Produkty
          $dotazProdukty = Mysql_query("SELECT id,produkt,kategorie,podkat1,podkat2,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND kategorie='$kategorie[kategorie]' AND podkat1='' AND podkat2='' AND podkat3='' ORDER BY produkt ASC LIMIT 5");
          While($produkt=mysql_fetch_assoc($dotazProdukty)){
                $tmplMapa->newBlok("mapa.kategorie.produkt");
                $tmplMapa->prirad("mapa.kategorie.produkt.nazev", $produkt["produkt"]); 
                //url
                $url = "$CONF[absDir]produkty/";
                $url.= "$kategorie[id]-".urlText($kategorie["kategorie"])."/";
                $url.= "$produkt[id]-".urlText($produkt["produkt"]).".html";
                $tmplMapa->prirad("mapa.kategorie.produkt.url", $url);
          }
    }
    
    
    
    /*****************************/
    /* PŘIŘAZENÍ DO HL. TEMPLATU */
    /*****************************/
    $tmpl->prirad("obsah", $tmplMapa->getHtml());



    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="'.$CONF["absDir"].'index.php">Úvodní strana</a> » Mapa webu');
    
    
    //////////////
    // TitleInfo
    //////////////    
    $tmpl->prirad("titleInfo", "Mapa webu");
}
?>