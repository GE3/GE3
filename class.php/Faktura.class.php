<?php
/*******************************************/
/* .-------------------------------------. */
/* | Faktura.class.php                   | */
/* |-------------------------------------| */
/* |       Version: 0.3.0                | */
/* | Last updating: 17.7.2009            | */
/* |-------------------------------------| */
/* |        Author: Michal Mikoláš       | */
/* |          Firm: Grafart studio       | */
/* |                www.grafartstudio.cz | */
/* '-------------------------------------' */
/*******************************************/


// Funkce, které jsou potřeba k chodu třídy
If( function_exists('lcfirst') === false) {
    Function lcfirst($string) {
            $string[0] = strtolower($string[0]);
            Return $string;
    }
}


class Faktura{
      var $errors;
      var $mysqlId;
      //Info o zákazníkovi
      var $uzivatel;  //$uzivatel je objekt třídy Uzivatel
      //Info o objednávce
      var $date,$stav;
      //Doprava
      var $doprava,$zpusob_platby;
      //Objednané zboží
      var $zId = 0;
      var $zbozi = array();
      var $varianty = array();
      var $mnozstvi = array();
      var $cenySDph = array();
      var $cenyBezDph = array();
      var $dph = array();
      //Navíc pro faktury
      var $objednavkaId;
      var $faktCislo;
      var $dodavatel;
      var $date2;
      var $varSymb;
      var $konstSymb;


      /***************/
      /* Konstruktor */
      /***************/
      Function Faktura($id=''){
              $CONF = &$GLOBALS["config"];

              /* -- Vytvoření prázdné faktury -- */
              If( !$id ){
                  $this->date = time();
                  $this->date2 = date("j. n. Y");
                  $this->konstSymb = $CONF["faktura"]["konstSymb"];
                  $this->dodavatel = $CONF["faktura"]["dodavatel"];
                  $this->stav = 'Nevyřízeno';
                  $this->uzivatel = new Uzivatel();

                  //Variabilní symbol podle čísla faktury
                    $dotaz = Mysql_query("SELECT faktCislo FROM $CONF[sqlPrefix]faktury WHERE date>".strtotime(date("Y")."-01-01 00:00:00")." AND date<".(strtotime((date("Y")+1)."-01-01 00:00:00")-1)." ORDER BY faktCislo DESC");
                    $radek = @mysql_fetch_array($dotaz);
                    $faktCislo = $radek["faktCislo"]?($radek["faktCislo"]+1):1;
                  $this->varSymb = $faktCislo."".date("Y");

                  Return True;
              }

              /* -- Načtení z databáze -- */
              Else{
                  Return $this->nactiZDb($id);
              }
      }



      /***************/
      /* Vnitřní fce */
      /***************/
      Function kontrola(){
              $this->errors = '';

              //zákazník
              If( !$this->uzivatel->getData("jmeno") OR
                  !$this->uzivatel->getData("prijmeni") OR
                  !$this->uzivatel->getData("mesto") OR
                  !$this->uzivatel->getData("ulice") OR
                  !$this->uzivatel->getData("email") ) $this->errors.= 'Chybí informace o zákazníkovi; ';
              //doprava
              //If( !$this->doprava OR !$this->zpusob_platby ) $this->errors.= 'Chybí informace o dopravě; ';
              //zboží
              If( ($this->zId)<1 ) $this->errors.= 'Žádné zboží v objednávce; ';
              If( count($this->zbozi) != count($this->mnozstvi) OR
                  count($this->zbozi) != count($this->cenySDph) OR
                  count($this->zbozi) != count($this->dph) ) $this->errors.= 'Zboží mišmaš; ';

              If( !$this->errors ){
                  Return True;
              }
              Else{
                  Return False;
              }
      }



      /*********/
      /* MySQL */
      /*********/
      Function nactiZDb($id){
              $CONF = &$GLOBALS["config"];

              // SQL Dotaz
              $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]faktury WHERE id=$id");
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
              $this->zpusob_platby = $radek["zpusob_platby"];
              //zboží
              $this->zbozi = explode(";",$radek["zbozi"]);  array_unshift($this->zbozi, "");
              $this->varianty = explode(";",$radek["varianty"]);  array_unshift($this->varianty, "");
              $this->mnozstvi = explode(";",$radek["mnozstvi"]);  array_unshift($this->mnozstvi, "");
              $this->cenySDph = explode(";",$radek["cenySDph"]);  array_unshift($this->cenySDph, "");
              $this->cenyBezDph = explode(";",$radek["cenyBezDph"]);  array_unshift($this->cenyBezDph, "");
              $this->dph = explode(";",$radek["dph"]);  array_unshift($this->dph, "");
              $this->zId = count($this->zbozi)-1;
              //navíc pro faktury
              $this->objednavkaId = $radek["objednavkaId"];
              $this->faktCislo = $radek["faktCislo"];
              $this->dodavatel = $radek["dodavatel"];
              $this->date2 = $radek["date2"];
              $this->varSymb = $radek["varSymb"];
              $this->konstSymb = $radek["konstSymb"];

              If(!mysql_error()) Return True;
              Else Return False;
      }

      Function vytvorZObjednavky($idObjednavky){
              $CONF = &$GLOBALS["config"];

              /* --Načtení objednávky-- */
              $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]objednavky WHERE id=$idObjednavky");
              $radek = @mysql_fetch_array($dotaz);
              If( mysql_error() OR !$radek["uzivatelEmail"] ){$this->errors.= 'Chyba v načítání objednávky '.$idObjednavky.'z databáze: '.mysql_error().'; ';}

              //zákaznik
              $this->uzivatel = new Uzivatel();
              Foreach($radek as $key=>$value){  //vložíme do objektu $uzivatel všechny hodnoty, začínající v DB slovem 'uzivatel'
                      If( ereg("^(uzivatel)", $key) ){
                          $keyUpr = ereg_replace("^uzivatel", "", $key);  //smazání slova 'uzivatel'
                          $keyUpr = lcfirst($keyUpr);  //převod prvního písmene na malé
                          $this->uzivatel->setData($keyUpr, $value);
                      }
              }
              //objednávka
              $this->date = time();
              $this->stav = $radek["stav"];
              //doprava
              $this->doprava = $radek["doprava"];
              $this->zpusob_platby = $radek["zpusob_platby"];
              //zboží
              $this->zbozi = explode(";",$radek["zbozi"]);  array_unshift($this->zbozi, "");
              $this->varianty = explode(";",$radek["varianty"]);  array_unshift($this->varianty, "");
              $this->mnozstvi = explode(";",$radek["mnozstvi"]);  array_unshift($this->mnozstvi, "");
              $this->cenySDph = explode(";",$radek["cenySDph"]);  array_unshift($this->cenySDph, "");
              $this->cenyBezDph = explode(";",$radek["cenyBezDph"]);  array_unshift($this->cenyBezDph, "");
              $this->dph = explode(";",$radek["dph"]);  array_unshift($this->dph, "");
              $this->zId = count($this->zbozi)-1;
              //navíc pro faktury
              $dotaz = Mysql_query("SELECT faktCislo FROM $CONF[sqlPrefix]faktury WHERE date>".strtotime(date("Y")."-01-01 00:00:00")." AND date<".(strtotime((date("Y")+1)."-01-01 00:00:00")-1)." ORDER BY faktCislo DESC");
              $radek = @mysql_fetch_array($dotaz);
              $this->faktCislo = $radek["faktCislo"]?($radek["faktCislo"]+1):1;
              $this->objednavkaId = $idObjednavky;
              $this->dodavatel = $this->dodavatel;
              $this->date2 = $this->date2;
              $this->varSymb = $this->varSymb;
              $this->konstSymb = $this->konstSymb;


              /* -- Uložení do faktur -- */
              //zjištění, jestli už tato faktura neexistuje
              $dotaz = Mysql_query("SELECT objednavkaId FROM $CONF[sqlPrefix]faktury WHERE objednavkaId='".$this->objednavkaId."' ");
              $radek = @mysql_fetch_array($dotaz);
              If( $this->kontrola() AND !$radek["objednavkaId"] ){
                  $sqlKeys = '';
                  $sqlValues = '';

                  // Zákaznik
                  Foreach($this->uzivatel->data as $key=>$value){
                          If( $value ){
                              $sqlKeys.= "uzivatel".ucfirst($key).", ";
                              $sqlValues.= "'$value', ";
                          }
                  }
                  // Objednávka
                  $sqlKeys.= 'date, ';  $sqlValues.= "'".$this->date."', ";
                  $sqlKeys.= 'stav, ';  $sqlValues.= "'".$this->stav."', ";
                  // Doprava
                  //$sqlKeys.= 'doprava, ';  $sqlValues.= "'".$this->doprava."', ";
                  //$sqlKeys.= 'zpusobPlatby, ';  $sqlValues.= "'".$this->zpusobPlatby."', ";
                  // Zboží
                  $sqlKeys.= 'zbozi, ';  $sqlValues.= "'".implode(';', $this->zbozi)."', ";
                  $sqlKeys.= 'varianty, ';  $sqlValues.= "'".implode(';', $this->varianty)."', ";
                  $sqlKeys.= 'mnozstvi, ';  $sqlValues.= "'".implode(';', $this->mnozstvi)."', ";
                  $sqlKeys.= 'cenySDph, ';  $sqlValues.= "'".implode(';', $this->cenySDph)."', ";
                  $sqlKeys.= 'cenyBezDph, ';  $sqlValues.= "'".implode(';', $this->cenyBezDph)."', ";
                  $sqlKeys.= 'dph, ';  $sqlValues.= "'".implode(';', $this->dph)."', ";
                  // Navíc pro faktury
                  $sqlKeys.= 'objednavkaId, ';  $sqlValues.= "'".$this->objednavkaId."', ";
                  $sqlKeys.= 'faktCislo, ';  $sqlValues.= "'".$this->faktCislo."', ";
                  $sqlKeys.= 'dodavatel, ';  $sqlValues.= "'".$this->dodavatel."', ";
                  $sqlKeys.= 'date2, ';  $sqlValues.= "'".$this->date2."', ";
                  $sqlKeys.= 'varSymb, ';  $sqlValues.= "'".$this->varSymb."', ";
                  $sqlKeys.= 'konstSymb, ';  $sqlValues.= "'".$this->konstSymb."', ";

                  // MySQL dotaz
                  $sqlKeys = ereg_replace(", $", "", $sqlKeys);
                  $sqlValues = ereg_replace(", $", "", $sqlValues);
                  $dotaz = "INSERT INTO $CONF[sqlPrefix]faktury($sqlKeys) VALUES ($sqlValues)";
                  If( Mysql_query($dotaz) ){
                      Return True;
                  }
                  Else{
                      $this->errors.= 'Chyba v přidávání faktury do databáze: '.mysql_error()."; ";
                      Return False;
                  }

              }
              Elseif($radek["objednavkaId"]){
                  $this->errors.= 'Faktura, kterou se snažíte vytvořit, již existuje; ';
                  Return False;
              }
              Else Return False;
      }

      Function smazZDatabaze($id=''){
              $CONF = &$GLOBALS["config"];
      
              $id = $id?$id:$this->mysqlId;
              /* --Kontrola-- */
              //Existence
              $dotaz = Mysql_query("SELECT uzivatelJmeno FROM $CONF[sqlPrefix]faktury WHERE id=$id");
              $radek = Mysql_fetch_array($dotaz);
              If( !$radek["uzivatelJmeno"] ){
                  $this->errors.= "Objednávka s id=$id, kterou chcete smazat, neexistuje; ";
              }

              If( !$this->errors ){
                  If( Mysql_query("UPDATE $CONF[sqlPrefix]faktury
                                   SET uzivatelJmeno='',uzivatelPrijmeni='',uzivatelMesto='',uzivatelUlice='',uzivatelPsc='',uzivatelTelefon='',uzivatelEmail='',stav='',doprava='',zpusob_platby='',zbozi='',varianty='',mnozstvi='',cenySDph='',cenyBezDph='',dph='',objednavkaId=0,dodavatel='',date2='',varSymb='',konstSymb=''
                                   WHERE id=$id") ){
                      Return True;
                    }Else{
                      $this->errors.= 'Chyba v mazání databáze: '.mysql_error().';';
                      Return False;
                  }
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

      Function setDoprava($doprava,$zpusob_platby){
              $this->doprava = $doprava;
              $this->zpusob_platby = $zpusob_platby;

              Return True;
      }

      Function upravZbozi($id,$zbozi,$varianta,$mnozstvi,$cenaSDph=0,$cenaBezDph=0,$dph=0){
              //dopočítání cen
              $cenaSDph = $cenaSDph?$cenaSDph:($cenaBezDph*(1+$dph/100));
              $cenaBezDph = $cenaBezDph?$cenaBezDph:($cenaSDph/(1+$dph/100));
              $dph = $dph?$dph:round($cenaSDph/$cenaBezDph*100-100);

              //úprava
              $this->zbozi[$id] = $zbozi;
              $this->varianty[$id] = $varianta;
              $this->mnozstvi[$id] = $mnozstvi;
              $this->cenySDph[$id] = $cenaSDph;
              $this->cenyBezDph[$id] = $cenaBezDph;
              $this->dph[$id] = $dph;

              Return $id;
      }

      Function pridejZbozi($zbozi,$varianta,$mnozstvi,$cenaSDph=0,$cenaBezDph=0,$dph=0){
              //dopočítání cen
              $cenaSDph = $cenaSDph?$cenaSDph:($cenaBezDph*(1+$dph/100));
              $cenaBezDph = $cenaBezDph?$cenaBezDph:($cenaSDph/(1+$dph/100));
              $dph = $dph?$dph:round($cenaSDph/$cenaBezDph*100-100);

              //přidání zboží
              $this->zId++;
              $this->zbozi[($this->zId)] = $zbozi;
              $this->varianty[($this->zId)] = $varianta;
              $this->mnozstvi[($this->zId)] = $mnozstvi;
              $this->cenySDph[($this->zId)] = $cenaSDph;
              $this->cenyBezDph[($this->zId)] = $cenaBezDph;
              $this->dph[($this->zId)] = $dph;

              Return $this->zId;
      }



      /**************/
      /* getCokoliv */
      /**************/

      Function getCenaCelkemSDph(){
               $celkemSDph = 0;
               Foreach($this->zbozi as $key=>$value){
                       If( $value ){
                           $celkemSDph+= ($this->cenySDph[$key]? $this->cenySDph[$key]: $this->cenyBezDph[$key]*(1+$this->dph[$key]/100)) * $this->mnozstvi[$key];
                       }
               }

               Return round($celkemSDph);
      }

      Function getCenaCelkemBezDph(){
               $celkemBezDph = 0;
               Foreach($this->zbozi as $key=>$value){
                       If( $value ){
                           $celkemBezDph+= ($this->cenyBezDph[$key]? $this->cenyBezDph[$key]: $this->cenySDph[$key]/(1+$this->dph[$key]/100)) * $this->mnozstvi[$key];
                       }
               }

               Return round($celkemBezDph);
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
      Function priradDoTmpl(&$tmpl, $blok, $tmplFakturaCesta){
              $CONF = &$GLOBALS["config"];

              $tmplFaktura = new GlassTemplate($tmplFakturaCesta, (file_exists("templates/$CONF[vzhled]/faktura.html")?"templates/default/faktura.html":"../templates/default/faktura.html"));

              //objednávka
              $tmplFaktura->prirad("$blok.id", $this->mysqlId);
              $tmplFaktura->prirad("$blok.datum", date("j.n.Y G:i",$this->date));
              $tmplFaktura->prirad("$blok.rok", date("Y",$this->date));
              $tmplFaktura->prirad("$blok.pocetPolozek", $this->getPocetPolozek());
              $tmplFaktura->prirad("$blok.celkemSDph", $this->getCenaCelkemSDph());
              $tmplFaktura->prirad("$blok.celkemBezDph", $this->getCenaCelkemBezDph());
              $tmplFaktura->prirad("$blok.celkemDph", ($this->getCenaCelkemSDph()-$this->getCenaCelkemBezDph()) );
              $tmplFaktura->prirad("$blok.stav", ($this->stav?$this->stav:'Nevyřízeno'));
              //faktura
              $tmplFaktura->prirad("$blok.objednavkaId", $this->objednavkaId);
              $tmplFaktura->prirad("$blok.faktCislo", $this->faktCislo);
              $tmplFaktura->prirad("$blok.dodavatel", $this->dodavatel);
              $tmplFaktura->prirad("$blok.datum2", $this->date2);
              $tmplFaktura->prirad("$blok.varSymb", $this->varSymb);
              $tmplFaktura->prirad("$blok.konstSymb", $this->konstSymb);
              //uzivatel v přehledu objednávky
              $tmplFaktura->prirad("$blok.jmeno", $this->uzivatel->getData("jmeno"));
              $tmplFaktura->prirad("$blok.prijmeni", $this->uzivatel->getData("prijmeni"));
              $tmplFaktura->prirad("$blok.mesto", $this->uzivatel->getData("mesto"));
              $tmplFaktura->prirad("$blok.email", $this->uzivatel->getData("email"));
              $tmplFaktura->prirad("$blok.poznamkaObsah", $this->uzivatel->getPoznamkaObsah());
              $tmplFaktura->prirad("$blok.poznamkaTyp", $this->uzivatel->getPoznamkaTyp());              
              //výpis produktů
              $i = 1;
              Foreach($this->zbozi as $key=>$value){
                      If( $value ){
                          $tmplFaktura->newBlok("$blok.produkt");
                          $tmplFaktura->prirad("$blok.produkt.i", $i);
                          $tmplFaktura->prirad("$blok.produkt.nazev", $this->zbozi[$key]);
                          $tmplFaktura->prirad("$blok.produkt.varianta", $this->varianty[$key]);
                          $tmplFaktura->prirad("$blok.produkt.mnozstvi", $this->mnozstvi[$key]);
                          $tmplFaktura->prirad("$blok.produkt.cenaSDph", $this->cenySDph[$key]);
                          $tmplFaktura->prirad("$blok.produkt.cenaBezDph", $this->cenyBezDph[$key]);
                          $tmplFaktura->prirad("$blok.produkt.celkemSDph", $this->cenySDph[$key]*$this->mnozstvi[$key]);
                          $tmplFaktura->prirad("$blok.produkt.celkemBezDph", $this->cenyBezDph[$key]*$this->mnozstvi[$key]);
                          $i++;
                      }
              }
              //doprava
              $dotaz = Mysql_query("SELECT DISTINCT firma FROM $CONF[sqlPrefix]doprava ORDER BY (prvni=1) DESC");
              While($radek = mysql_fetch_array($dotaz)){
                    $tmplFaktura->newBlok("$blok.dopravaFirma");
                    $tmplFaktura->prirad("$blok.dopravaFirma.nazev", $radek["firma"]);
              }
              //uzivatel - výpis všech údajů
              $uzivatelVypis = $this->uzivatel->priradDoTmpl($tmplFaktura, "uzivatelVypis", (file_exists("templates/$CONF[vzhled]/uzivatel.html")?"templates/$CONF[vzhled]/uzivatel.html":"../templates/$CONF[vzhled]/uzivatel.html"));
              $tmplFaktura->prirad("$blok.uzivatelVypis", $uzivatelVypis);

              $uzivatelVypisMini = $this->uzivatel->priradDoTmpl($tmplFaktura, "uzivatelVypisMini", (file_exists("templates/$CONF[vzhled]/uzivatel.html")?"templates/$CONF[vzhled]/uzivatel.html":"../templates/$CONF[vzhled]/uzivatel.html"));
              $tmplFaktura->prirad("$blok.uzivatelVypisMini", $uzivatelVypisMini);

              @$tmpl->prirad($blok, $tmplFaktura->getHtml());
              Return $tmplFaktura->getHtml();
      }
}
?>
