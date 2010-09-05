<?php
/*******************************************/
/* .-------------------------------------. */
/* | Objednavka.class.php                | */
/* |-------------------------------------| */
/* |       Version: 0.2.0                | */
/* | Last updating: 17.7.2009            | */
/* |-------------------------------------| */
/* |        Author: Michal Mikoláš       | */
/* |          Firm: Grafart studio       | */
/* |                www.grafartstudio.cz | */
/* '-------------------------------------' */
/*******************************************/
/**
 * Objednávka se skládá ze třech částí:
 * 1) Objednané zboží (v košíku)
 * 2) Doprava (firma a způsob platby)
 * 3) Informace o uživateli (jméno, adresa, ...)
 *    - ukládají se do objektu třídy Uzivatel
 *    - do tohoto objektu se automataticky uloží všechny informace,
 *      které v DB začínají slovem 'uzivatel' (uzivatelJmeno, uzivatelMesto, ...)
 */


// Funkce, které jsou potřeba k chodu třídy
If( function_exists('lcfirst') === false) {
    Function lcfirst($string) {
            $string[0] = strtolower($string[0]);
            Return $string;
    }
}


class Objednavka{
      var $errors;
      var $mysqlId;   //pod jakým číslem je tato objednávka uložena v db
      //Info o objednávce
      var $date,$stav;
      //Info o zákazníkovi
      var $uzivatel;  //$uzivatel je objekt třídy Uzivatel
      //Doprava
      var $doprava,$zpusobPlatby;
      //Objednané zboží (formát kompatibilní s DB)
      var $zId = 0;  //key posledního přidaného zboží (v poli $zbozi, $mnozstvi atd.)
      var $cisla = array();
      var $zbozi = array();
      var $varianty = array();
      var $mnozstvi = array();
      var $cenySDph = array();
      var $cenyBezDph = array();
      var $dph = array();


      /***************/
      /* Konstruktor */
      /***************/
      Function Objednavka($id=''){
              /**
               * při zadání id se načte z databáze,
               * jinak se vytvoří prázdná objednávka
               */

              /* -- Vytvoření prázdné objednávky -- */
              If( !$id ){
                  $this->date = time();
                  $this->uzivatel = new Uzivatel();
                  Return True;
              }


              /* -- Načtení objednávky z DB -- */
              Else{
                  Return $this->nactiZDb($id);
              }
      }



      /***************/
      /* Vnitřní fce */
      /***************/
      Function kontrola(){
              /**
               * Vrací true, pokud objednávka obsahuje všechny potřebné informace.
               * Chyby zároveň ukládá do $this->errors.
               */

              $this->errors = '';

              //zákazník
              If( !$this->uzivatel->getData("jmeno") OR
                  !$this->uzivatel->getData("prijmeni") OR
                  !$this->uzivatel->getData("mesto") OR
                  !$this->uzivatel->getData("ulice") OR
                  !$this->uzivatel->getData("email") ) $this->errors.= 'Chybí informace o zákazníkovi; ';
              //doprava
              //If( !$this->doprava OR !$this->zpusobPlatby ) $this->errors.= 'Chybí informace o dopravě; ';
              //zboží
              If( ($this->zId)<1 ) $this->errors.= 'Žádné zboží v objednávce; ';
              If( count($this->zbozi) != count($this->mnozstvi) OR
                  count($this->zbozi) != count($this->varianty) OR
                  count($this->zbozi) != count($this->cenySDph) OR
                  count($this->zbozi) != count($this->cenyBezDph) OR
                  count($this->zbozi) != count($this->dph) ) $this->errors.= 'Zboží mišmaš; ';

              // Return
              If( !$this->errors ){
                  Return True;
                }Else{
                  Return False;
              }
      }


      /*********/
      /* MySQL */
      /*********/
      Function nactiZDb($id){
              $CONF = &$GLOBALS["config"];

              // SQL Dotaz
              $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]objednavky WHERE id=$id");
              $radek = mysql_fetch_array($dotaz);

              // Uložení informací
              $this->mysqlId = $radek["id"];
              //zákazník
              $this->uzivatel = new Uzivatel();
              Foreach($radek as $key=>$value){  //vložíme do objektu $uzivatel všechny hodnoty, začínající v DB slovem 'uzivatel'
                      If( ereg("^(uzivatel)", $key) ){
                          $keyUpr = ereg_replace("^uzivatel", "", $key);  //smazání slova 'uzivatel'
                          $keyUpr = lcfirst($keyUpr);  //převod prvního písmene na malé
                          $this->uzivatel->setData($keyUpr, $value);
                      }
              }
              //objednávka
              $this->date = $radek["date"];
              $this->stav = $radek["stav"];
              //doprava
              $this->doprava = $radek["doprava"];
              $this->zpusobPlatby = $radek["zpusobPlatby"];
              //zboží                                       //(chceme, aby se indexovalo od 1)
              $this->cisla = explode(";",$radek["cisla"]);  array_unshift($this->cisla, "");
              $this->zbozi = explode(";",$radek["zbozi"]);  array_unshift($this->zbozi, "");
              $this->varianty = explode(";",$radek["varianty"]);  array_unshift($this->varianty, "");
              $this->mnozstvi = explode(";",$radek["mnozstvi"]);  array_unshift($this->mnozstvi, "");
              $this->cenySDph = explode(";",$radek["cenySDph"]);  array_unshift($this->cenySDph, "");
              $this->cenyBezDph = explode(";",$radek["cenyBezDph"]);  array_unshift($this->cenyBezDph, "");
              $this->dph = explode(";",$radek["dph"]);  array_unshift($this->dph, "");
              $this->zId = count($this->zbozi)-1;

              If(!mysql_error()) Return True;
              Else Return False;
      }
      Function ulozDoDatabaze(){
              /**
               * funkce postupně sestaví SQL dotaz, který následně provede
               */
              $CONF = &$GLOBALS["config"];

              If( $this->kontrola() ){
                  $sqlKeys = '';
                  $sqlValues = '';

                  // Zákaznik
                  Foreach($_SESSION as $key=>$value){
                          If( ereg("^uzivatel[a-zA-Z_]+2?$", $key) AND $key!='uzivatelHeslo' ){
                              $sqlKeys.= "$key,";
                              $sqlValues.= "'$value',";
                          }
                  }
                  // Objednávka
                  $sqlKeys.= 'date,';  $sqlValues.= "'".$this->date."',";
                  $sqlKeys.= 'stav,';  $sqlValues.= "'".$this->stav."',";
                  // Doprava
                  $sqlKeys.= 'doprava,';  $sqlValues.= "'".$this->doprava."',";
                  $sqlKeys.= 'zpusobPlatby,';  $sqlValues.= "'".$this->zpusobPlatby."',";
                  // Zboží
                  $sqlKeys.= 'cisla,';  $sqlValues.= "'".implode(';', $this->cisla)."',";
                  $sqlKeys.= 'zbozi,';  $sqlValues.= "'".implode(';', $this->zbozi)."',";
                  $sqlKeys.= 'varianty,';  $sqlValues.= "'".implode(';', $this->varianty)."',";
                  $sqlKeys.= 'mnozstvi,';  $sqlValues.= "'".implode(';', $this->mnozstvi)."',";
                  $sqlKeys.= 'cenySDph,';  $sqlValues.= "'".implode(';', $this->cenySDph)."',";
                  $sqlKeys.= 'cenyBezDph,';  $sqlValues.= "'".implode(';', $this->cenyBezDph)."',";
                  $sqlKeys.= 'dph,';  $sqlValues.= "'".implode(';', $this->dph)."',";

                  // MySQL dotaz
                  $sqlKeys = ereg_replace(",$", "", $sqlKeys);
                  $sqlValues = ereg_replace(",$", "", $sqlValues);
                  $dotaz = "INSERT INTO $CONF[sqlPrefix]objednavky($sqlKeys) VALUES ($sqlValues)";
                  If( Mysql_query($dotaz) ){
                      Return True;
                    }Else{
                      $this->errors.= 'Chyba v přidávání objednávky do databáze: '.mysql_error().';';
                      Return False;
                  }
              }Else Return False;
      }

      Function smazZDatabaze($id=''){
              /**
               * Smaže objednávku z databáze.
               * Pokud je zadáno $id, smaže objednávku s tímto id,
               * jinak smaže z databáze sebe.
               */
              $CONF = &$GLOBALS["config"];

              $id = $id?$id:$this->mysqlId;

              /* -- Kontrola, jestli objednávka existuje -- */
              $dotaz = Mysql_query("SELECT id FROM $CONF[sqlPrefix]objednavky WHERE id=$id");
              $radek = Mysql_fetch_array($dotaz);
              If( !$radek["id"] ){
                  $this->errors = "Objednávka s id=$id, kterou chcete smazat, neexistuje; ";
              }

              /* -- Smazání -- */
              If( !$this->errors ){
                  If( Mysql_query("DELETE FROM $CONF[sqlPrefix]objednavky WHERE id=$id") ){
                      Return True;
                    }Else{
                      $this->errors = 'Chyba v mazání databáze: '.mysql_error().';';
                      Return False;
                  }
              }Else Return False;
      }

      Function fakturaExist(){
              /**
               * zkontroluje, jestli pro tuto objednávku je vystavená faktura
               * a vrátí jeho ID, nebo hodnotu false (nenalezeno)
               * (ve fakturách je uložená informace, ze které objednávky vzešly)
               */
              $CONF = &$GLOBALS["config"];

              $dotaz = Mysql_query("SELECT id FROM $CONF[sqlPrefix]faktury WHERE objednavkaId=".$this->mysqlId."");
              $radek = @mysql_fetch_array($dotaz);
              If( $radek["id"] ){
                  Return $radek["id"];
                }Else{
                  Return False;
              }
      }

      Function getFakturaCislo(){
              /**
               * vrací ID faktury, která byla vytvořena z této objednávky,
               * nebo false pro nenalezeno
               * (číslo faktury je ve formátu č./rok, např. 3/2009)
               */
              $CONF = &$GLOBALS["config"];

              If( $this->fakturaExist() ){
                  $dotaz = Mysql_query("SELECT faktCislo,date FROM $CONF[sqlPrefix]faktury WHERE objednavkaId=".$this->mysqlId."");
                  $radek = mysql_fetch_array($dotaz);

                  $fakturaCislo = $radek["faktCislo"]."/".date("Y",$radek["date"]);

                  Return $fakturaCislo;
              }
              Else{
                  $this->errors.= "Faktura pro tuto objednávku není vytvořena, nelze tedy zobrazit její číslo; ";
                  Return False;
              }
      }



      /************************************/
      /* Set, Insert, Update, Delete data */
      /************************************/
      Function setAdresa($jmeno,$prijmeni,$mesto,$ulice,$psc,$telefon,$email){
              $this->uzivatel->setData("jmeno", $jmeno);
              $this->uzivatel->setData("prijmeni", $prijmeni);
              $this->uzivatel->setData("mesto", $mesto);
              $this->uzivatel->setData("ulice", $ulice);
              $this->uzivatel->setData("psc", $psc);
              $this->uzivatel->setData("telefon", $telefon);
              $this->uzivatel->setData("email", $email);

              Return True;
      }

      Function setDoprava($doprava,$zpusobPlatby){
              $this->doprava = $doprava;
              $this->zpusobPlatby = $zpusobPlatby;

              Return True;
      }

      Function pridejZbozi($zbozi,$varianta,$mnozstvi,$cenaSDph=0,$cenaBezDph=0,$dph=0, $cislo=0){
              //dopočítání cen
              $cenaSDph = $cenaSDph? $cenaSDph: round($cenaBezDph*(1+$dph/100),2);
              $cenaBezDph = $cenaBezDph? $cenaBezDph: round($cenaSDph/(1+$dph/100),2);
              $dph = $dph? $dph:round($cenaSDph/$cenaBezDph*100-100);

              //přidání zboží
              $this->zId++;
              $this->cisla[($this->zId)] = $cislo;
              $this->zbozi[($this->zId)] = $zbozi;
              $this->varianty[($this->zId)] = $varianta;
              $this->mnozstvi[($this->zId)] = $mnozstvi;
              $this->cenySDph[($this->zId)] = $cenaSDph;
              $this->cenyBezDph[($this->zId)] = $cenaBezDph;
              $this->dph[($this->zId)] = $dph;

              Return $this->zId;
      }

      Function nactiZboziZKosiku(){
              Foreach($_SESSION["kosikProdukty"] as $key=>$value){
                      If( $value["nazev"] AND $value["mnozstvi"] ){
                          $this->pridejZbozi($value["nazev"], $value["varianta"], $value["mnozstvi"], $value["cenaSDph"], $value["cenaBezDph"], $value["dph"], $value["cislo"]);
                      }
              }

              Return True;
      }



      /**************/
      /* getCokoliv */
      /**************/
      Function getCenaCelkemSDph(){
               $celkemSDph = 0;
               Foreach($this->zbozi as $key=>$value){
                       If( $value ){
                           $celkemSDph+= $this->cenySDph[$key] * $this->mnozstvi[$key];
                       }
               }

               Return ceil($celkemSDph);
      }

      Function getCenaCelkemBezDph(){
               $celkemBezDph = 0;
               Foreach($this->zbozi as $key=>$value){
                       If( $value ){
                           $celkemBezDph+= $this->cenyBezDph[$key] * $this->mnozstvi[$key];
                       }
               }

               Return round($celkemBezDph, 2);
      }

      Function getPocetPolozek(){
               $pocetPolozek = 0;
               Foreach($this->mnozstvi as $key=>$value){
                       If( $value ){
                           $pocetPolozek+= $this->mnozstvi[$key];
                       }
               }

               Return $pocetPolozek;
      }

      Function getErrors(){
              Return $this->errors;
      }



      /*******************/
      /* TEMPLATE FUNKCE */
      /*******************/
      Function priradDoTmpl(&$tmpl, $blok, $tmplObjednavkaCesta){
              $CONF = &$GLOBALS["config"];

              $tmplObjednavka = new GlassTemplate($tmplObjednavkaCesta, (file_exists("templates/default/objednavka.html")?"templates/default/objednavka.html":"../templates/default/objednavka.html"));

              //objednávka
              $tmplObjednavka->prirad("$blok.id", $this->mysqlId);
              $tmplObjednavka->prirad("$blok.datum", date("j.n.Y G:i",$this->date));
              $tmplObjednavka->prirad("$blok.pocetPolozek", $this->getPocetPolozek());
              $tmplObjednavka->prirad("$blok.celkemSDph", $this->getCenaCelkemSDph());
              $tmplObjednavka->prirad("$blok.celkemBezDph", $this->getCenaCelkemBezDph());
              $tmplObjednavka->prirad("$blok.celkemDph", ($this->getCenaCelkemSDph()-$this->getCenaCelkemBezDph()) );
              $tmplObjednavka->prirad("$blok.stav", ($this->stav?$this->stav:'nevyřízeno'));
              //faktura pro tuto objednávku
              $faktura = @mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]faktury WHERE objednavkaId=$this->mysqlId"));
              $tmplObjednavka->prirad("$blok.fakturaId", $faktura["id"]);
              $tmplObjednavka->prirad("$blok.fakturaCislo", $faktura["faktCislo"]);
              $tmplObjednavka->prirad("$blok.fakturaRok", date("Y",$faktura["date"]));
              //uzivatel v přehledu objednávky
              $tmplObjednavka->prirad("$blok.jmeno", $this->uzivatel->getData("jmeno"));
              $tmplObjednavka->prirad("$blok.prijmeni", $this->uzivatel->getData("prijmeni"));
              $tmplObjednavka->prirad("$blok.mesto", $this->uzivatel->getData("mesto"));
              $tmplObjednavka->prirad("$blok.email", $this->uzivatel->getData("email"));
              $tmplObjednavka->prirad("$blok.poznamkaObsah", $this->uzivatel->getPoznamkaObsah());
              $tmplObjednavka->prirad("$blok.poznamkaTyp", $this->uzivatel->getPoznamkaTyp());
              //výpis produktů
              Foreach($this->zbozi as $key=>$value){
                      If( $value ){
                          $tmplObjednavka->newBlok("$blok.produkt");
                          $tmplObjednavka->prirad("$blok.produkt.cislo", $this->cisla[$key]);
                          $tmplObjednavka->prirad("$blok.produkt.nazev", $this->zbozi[$key]);
                          $tmplObjednavka->prirad("$blok.produkt.varianta", $this->varianty[$key]);
                          $tmplObjednavka->prirad("$blok.produkt.mnozstvi", $this->mnozstvi[$key]);
                          $tmplObjednavka->prirad("$blok.produkt.cenaSDph", $this->cenySDph[$key]);
                          $tmplObjednavka->prirad("$blok.produkt.cenaBezDph", $this->cenyBezDph[$key]);
                          $tmplObjednavka->prirad("$blok.produkt.celkemSDph", $this->cenySDph[$key]*$this->mnozstvi[$key]);
                          $tmplObjednavka->prirad("$blok.produkt.celkemBezDph", $this->cenyBezDph[$key]*$this->mnozstvi[$key]);
                      }
              }
              //doprava
              $dotaz = Mysql_query("SELECT DISTINCT firma FROM $CONF[sqlPrefix]doprava ORDER BY (prvni=1) DESC");
              While($radek = mysql_fetch_array($dotaz)){
                    $tmplObjednavka->newBlok("$blok.dopravaFirma");
                    $tmplObjednavka->prirad("$blok.dopravaFirma.nazev", $radek["firma"]);
              }
              //uzivatel - výpis všech údajů
              $uzivatelVypis = $this->uzivatel->priradDoTmpl($tmplObjednavka, "uzivatelVypis", (file_exists("templates/$CONF[vzhled]/uzivatel.html")?"templates/$CONF[vzhled]/uzivatel.html":"../templates/$CONF[vzhled]/uzivatel.html"));
              $tmplObjednavka->prirad("$blok.uzivatelVypis", $uzivatelVypis);

              @$tmpl->prirad($blok, $tmplObjednavka->getHtml());
              Return $tmplObjednavka->getHtml();
      }
}
?>
