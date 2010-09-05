<?php
/*******************************************/
/* .-------------------------------------. */
/* | Kosik.class.php                     | */
/* |-------------------------------------| */
/* |       Version: 0.1.0                | */
/* | Last updating: 4.4.2009             | */
/* |-------------------------------------| */
/* |        Author: Michal Mikoláš       | */
/* |          Firm: Grafart studio       | */
/* |                www.grafartstudio.cz | */
/* '-------------------------------------' */
/*******************************************/
/**
 * Struktura košíku
 * $_SESSION[kosikProdukty][1][id]
 *                            [nazev]
 *                            [varianta]
 *                            [mnozstvi]
 *                            [cenaSDph]
 *                            [cenaBezDph]
 *                            [dph]
 */

class Kosik{
      var $info;  //hlášky typu "Zboží bylo přidáno do košíku" apod.


      /***************/
      /* KONSTRUKTOR */
      /***************/
      Function Kosik(){
              If( !is_array($_SESSION["kosikProdukty"]) ) $_SESSION["kosikProdukty"] = array();
              $this->info = '';
      }



      /******************/
      /* VNITŘNÍ FUNKCE */
      /******************/
      Function najdiProdukt($id){
              /**
               * vrací pozici produktu, nebo -1 při nenalezení
               */

              $i = -1;
              Foreach($_SESSION["kosikProdukty"] as $key=>$value){
                      If( $value["id"]==$id ){ $i=$key; }
              }

              Return $i;
      }

      Function getPocetPolozek(){
              /**
               * vrací počet položek v košíku
               */

              //(nemůžeme použít funkci count, protože ta počítá i prázdné řetězce)
              $pocetPolozek = 0;
              Foreach($_SESSION["kosikProdukty"] as $key=>$value){
                      If( $value["id"] AND $value["mnozstvi"]>0 ){
                          $pocetPolozek+= $value["mnozstvi"];
                      }
              }

              Return $pocetPolozek;
      }



      /**********************/
      /* UŽIVATELSKÉ FUNKCE */
      /**********************/
      Function pridejProdukt($id, $mnozstvi, $nazev='', $varianta='', $cenaSDph='', $cenaBezDph='', $dph=''){
              $CONF = &$GLOBALS["config"];

              // Oprava možných chyb ve vstupních proměnných
              $mnozstvi = $mnozstvi?$mnozstvi:1;
              $id = preg_replace("|^([0-9]*)-.*$|", "$1", $id);


              /* -- V košíku zatím není -- */
              If( $this->najdiProdukt($id)==(-1) ){
                  // Zjištění chybějících informací
                  If( !$nazev ){
                      $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE id=$id");
                      $radek = mysql_fetch_array($dotaz);

                      $cislo = $radek["cislo"];
                      $nazev = $radek["produkt"];
                      $varianta = $radek["varianta"];
                      $cenaSDph = $radek["cenaSDph"];
                      $cenaBezDph = $radek["cenaBezDph"];
                      $dph = $radek["dph"];
                  }

                  // Oprava chybějících informací o ceně
                  $cenaSDph = $cenaSDph?$cenaSDph:($cenaBezDph*(1+$dph/100));
                  $cenaBezDph = $cenaBezDph?$cenaBezDph:($cenaSDph/(1+$dph/100));
                  $dph = $dph?$dph:round($cenaSDph*100/$cenaBezDph-100);

                  // Přidání jako další hodnota v poli sessionu
                  $produkt = array("id" => $id,
                                   "cislo" => $cislo,
                                   "mnozstvi" => $mnozstvi,
                                   "nazev" => $nazev,
                                   "varianta" => $varianta,
                                   "cenaSDph" => $cenaSDph,
                                   "cenaBezDph" => $cenaBezDph,
                                   "dph" => $dph
                                   );
                  $_SESSION["kosikProdukty"][] = $produkt;
              }


              /* -- V košíku už je -- */
              Else{
                  //zjistím pozici produktu
                  $i = $this->najdiProdukt($id);
                  //přidáme určený počet produktů
                  $_SESSION["kosikProdukty"][$i]["mnozstvi"]+= $mnozstvi;
              }


              /* -- Return -- */
              $i = $this->najdiProdukt($id);
              If( $_SESSION["kosikProdukty"][$i]["nazev"] ){  //zkontrolujeme jestli byl produkt nalezen
                  $this->info = 'Zboží bylo přidáno do košíku.';
                  Return True;
              }
              Else{
                  $this->info = 'Chyba: Toto zboží neexistuje.';
                  Return False;
              }
      }


      Function smazProdukt($id){
              $i = $this->najdiProdukt($id);
              If( $i!=-1 ){
                  $_SESSION["kosikProdukty"][$i] = '';

                  $this->info = 'Položka byla smazána z košíku.';
                  Return True;
              }
              Else{
                  $this->info = 'Chyba: Položka v košíku nebyla nalezena a proto nemohla být smazána.';
                  Return False;
              }
      }


      Function editujProdukt($id, $mnozstvi, $variantaId=''){
              $CONF = &$GLOBALS["config"];

              $variantaId = $variantaId?$variantaId:$id;

              $i = $this->najdiProdukt($id);

              /* -- Změna množství a varianty -- */
              If( $mnozstvi>0 AND $i!=-1 ){
                  // Zjištění chybějících informací
                  $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE id=$variantaId");
                  $radek = mysql_fetch_array($dotaz);

                  $nazev = $radek["produkt"];
                  $varianta = $radek["varianta"];
                  $cenaSDph = $radek["cenaSDph"];
                  $cenaBezDph = $radek["cenaBezDph"];
                  $dph = $radek["dph"];

                  // Oprava chybějících informací o ceně
                  $cenaSDph = $cenaSDph?$cenaSDph:($cenaBezDph*(1+$dph/100));
                  $cenaBezDph = $cenaBezDph?$cenaBezDph:($cenaSDph/(1+$dph/100));
                  $dph = $dph?$dph:round($cenaSDph*100/$cenaBezDph-100);

                  // Vložení hodnot
                  $_SESSION["kosikProdukty"][$i]["id"] = $variantaId;
                  $_SESSION["kosikProdukty"][$i]["mnozstvi"] = $mnozstvi;
                  $_SESSION["kosikProdukty"][$i]["nazev"] = $nazev;
                  $_SESSION["kosikProdukty"][$i]["varianta"] = $varianta;
                  $_SESSION["kosikProdukty"][$i]["cenaSDph"] = $cenaSDph;
                  $_SESSION["kosikProdukty"][$i]["cenaBezDph"] = $cenaBezDph;
                  $_SESSION["kosikProdukty"][$i]["dph"] = $dph;
              }

              /* -- Pokud je množství 0, smažeme produkt -- */
              Elseif( $i!=-1 ){
                  smazProdukt($id);
              }

              /* -- Return -- */
              If( $i!=-1 ){
                  $this->info = 'Položka košíku byla upravena.';
                  Return True;
              }
              If( $i==-1 ){
                  $this->info = 'Chyba: Položka v košíku nebyla nalezena a proto nebyla upravena.';
                  Return False;
              }
      }


      Function vysypejKosik(){
              unset($_SESSION["kosikProdukty"]);
              Return True;
      }



      /*******************/
      /* TEMPLATE FUNKCE */
      /*******************/
      Function priradDoTmpl(&$tmpl, $blok, $tmplKosikCesta){
              $tmplKosik = new GlassTemplate($tmplKosikCesta,"templates/default/kosik.html");


              /////////////
              // Produkty
              /////////////
              $celkemSDph = 0;
              $celkemBezDph = 0;

              /* -- Výpis položek košíku -- */
              If( $this->getPocetPolozek()>0 ){  //pokud v košíku jsou produkty
                  // Produkty v košíku
                  Foreach($_SESSION["kosikProdukty"] as $key=>$value){
                          If( $value["id"] AND $value["mnozstvi"]>0 ){
                              $tmplKosik->newBlok("$blok.vypis.produkt");

                              $tmplKosik->prirad("$blok.vypis.produkt.id", $value["id"]);
                              $tmplKosik->prirad("$blok.vypis.produkt.nazev", $value["nazev"]);
                              $tmplKosik->prirad("$blok.vypis.produkt.varianta", $value["varianta"]);
                              $tmplKosik->prirad("$blok.vypis.produkt.mnozstvi", $value["mnozstvi"]);
                              $tmplKosik->prirad("$blok.vypis.produkt.cenaSDph", $value["cenaSDph"]);
                              $tmplKosik->prirad("$blok.vypis.produkt.cenaBezDph", $value["cenaBezDph"]);
                              $tmplKosik->prirad("$blok.vypis.produkt.dph", $value["dph"]);
                              $tmplKosik->prirad("$blok.vypis.produkt.celkemSDph", $value["cenaSDph"]*$value["mnozstvi"] );
                              $tmplKosik->prirad("$blok.vypis.produkt.celkemBezDph", $value["cenaBezDph"]*$value["mnozstvi"] );

                              $celkemSDph+= $value["cenaSDph"]*$value["mnozstvi"];
                              $celkemBezDph+= $value["cenaBezDph"]*$value["mnozstvi"];
                          }
                  }

                  // Hromadné info ve výpisu
                  $tmplKosik->prirad("$blok.vypis.pocetPolozek", $this->getPocetPolozek());
                  $tmplKosik->prirad("$blok.vypis.celkemSDph", ceil($celkemSDph));  //$celkemSDph se počítá ve výpisu položek
                  $tmplKosik->prirad("$blok.vypis.celkemBezDph", $celkemBezDph);  //$celkemBezDph taky ^
              }
              Else{      //pokud v košíku nejsou produkty, zobrazíme zprávu
                  $tmplKosik->newBlok("$blok.zprava");
                  $tmplKosik->prirad("$blok.zprava.text", "V košíku není žádné zboží.");
              }



              /////////////////////////////
              // Informace o celém košíku
              /////////////////////////////
              $tmplKosik->prirad("$blok.pocetPolozek", $this->getPocetPolozek());
              $tmplKosik->prirad("$blok.celkemSDph", $celkemSDph);
              $tmplKosik->prirad("$blok.celkemBezDph", $celkemBezDph);
              If($this->info) $tmplKosik->prirad("$blok.zprava.text", $this->info);



              ///////////////////////////////////
              // Přiřazení do hlavního templatu
              ///////////////////////////////////
              $tmpl->prirad($blok, $tmplKosik->getHtml());
              Return $tmplKosik->getHtml();
      }



      Function priradDoTmplPolozku(&$tmpl, $blok, $tmplKosikCesta, $id){
              $CONF = &$GLOBALS["config"];

              $tmplKosik = new GlassTemplate($tmplKosikCesta, "templates/default/kosik.html");

              //najdeme pozici produktu v košíku
              $i = $this->najdiProdukt($id);


              /* -- Přiřazení hodnot -- */
              //zjištění informací
              $id = $_SESSION["kosikProdukty"][$i]["id"];
              $nazev = $_SESSION["kosikProdukty"][$i]["nazev"];
              $varianta = $_SESSION["kosikProdukty"][$i]["varianta"];
              $mnozstvi = $_SESSION["kosikProdukty"][$i]["mnozstvi"];
              $cenaSDph = $_SESSION["kosikProdukty"][$i]["cenaSDph"];
              $cenaBezDph = $_SESSION["kosikProdukty"][$i]["cenaBezDph"];
              $dph = $_SESSION["kosikProdukty"][$i]["dph"];
              $celkemSDph = $_SESSION["kosikProdukty"][$i]["cenaSDph"] * $mnozstvi;
              $cenaBezDph = $_SESSION["kosikProdukty"][$i]["cenaBezDph"] * $mnozstvi;
              //přiřazení
              $tmplKosik->prirad("$blok.id", $id);
              $tmplKosik->prirad("$blok.nazev", $nazev);
              $tmplKosik->prirad("$blok.varianta", $varianta);
              $tmplKosik->prirad("$blok.mnozstvi", $mnozstvi);
              $tmplKosik->prirad("$blok.cenaSDph", $cenaSDph);
              $tmplKosik->prirad("$blok.cenaBezDph", $cenaBezDph);
              $tmplKosik->prirad("$blok.dph", $dph);
              $tmplKosik->prirad("$blok.celkemSDph", $celkemSDph);
              $tmplKosik->prirad("$blok.cenaBezDph", $cenaBezDph);

              /* -- Přiřazení všech variant -- */
              $dotaz = Mysql_query("SELECT id,varianta FROM $CONF[sqlPrefix]zbozi WHERE produkt='$nazev' ORDER BY varianta");
              $pocetVariant = 0;
              While($radek=mysql_fetch_array($dotaz)){
                    $tmplKosik->newBlok("$blok.variantaSelect");

                    $tmplKosik->prirad("$blok.variantaSelect.id", $radek["id"]);
                    $tmplKosik->prirad("$blok.variantaSelect.nazev", $radek["varianta"]);

                    //počet variant
                    If($radek["varianta"]) $pocetVariant++;
              }
              $tmplKosik->prirad("$blok.pocetVariant", $pocetVariant);

              /* -- Ostatní -- */
              If($this->info) $tmplKosik->prirad("$blok.zprava.text", $this->info);


              ///////////////////////////////////
              // Přiřazení do hlavního templatu
              ///////////////////////////////////
              $tmpl->prirad($blok, $tmplKosik->getHtml());
              Return $tmplKosik->getHtml();
      }
}
?>
