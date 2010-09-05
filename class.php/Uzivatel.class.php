<?php
/*******************************************/
/* .-------------------------------------. */
/* | Uzivatel.class.php                  | */
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
 * -- Struktura dat --
 * $_SESSION[uzivatelId]
 *          [uzivatelEmail]
 *          [uzivatelHeslo]
 *          [uzivatel...] atd
 * nebo
 * $this->data[id]
 *            [email]
 *            [heslo]
 *            [...] atd
 *
 * -- Funkce třídy Uzivatel --
 * Uzivatel->prihlasen()
 * Uzivatel->prihlasit($jmeno,$heslo)
 * Uzivatel->nactiZDb($id)
 * Uzivatel->odhlasit()
 * Uzivatel->getData($key)
 * Uzivatel->setData($key,$value)
 * Uzivatel->registrovan()
 */
/**
 * Třída Uzivatel slouží ke dvěma účelům:
 * 1) -Přihlašování uživatele, odhlašování a k dodávání informací
 *     o tomto uživatelí.
 *    -K tomuto uživateli se přistupuje přes email a heslo.
 *    -Informace o tomto uživateli jsou uložena v sessionu, dokud se uživatel
 *     neodhlásí, nezávisle na existenci objektu $uzivatel.
 * 2) -K manipulaci s daty registrovaného uživatele v databázi.
 *    -K uživateli se přistupuje přes id.
 *    -Informace o tomto uživateli nejsou ukládána do sessionu,
 *     ale jen do proměnných třídy.
 * * Pokud nejsou zadána žádná data, snaží se třída zjistit informace z sessionu
 *   a pracuje s přihlášeným uživatelem (pokud přihlášen je)
 */


// Funkce, které jsou potřeba k chodu třídy
If( function_exists('lcfirst') === false) {
    Function lcfirst($string) {
            $string[0] = strtolower($string[0]);
            Return $string;
    }
}



class Uzivatel{
      var $data;


      /***************/
      /* KONSTRUKTOR */
      /***************/
      Function Uzivatel($id=''){
              /**
               * při zadání id se načte uživatel z databáze
               * (nepřihlásí se, jen se o něm načtou informace)
               */

              If( $id ){
                  Return $this->nactiZDb($id);
              }
              Else{
                  Return True;
              }
      }



      /******************/
      /* VEŘEJNÉ FUNKCE */
      /******************/
      Function prihlasit($email, $heslo){
              /**
               * všechny informace o přihlášeném uživateli se ukládají
               * do sessionu ve formátu: $_SESSION["uzivatelNazevHodnoty"]
               */
              $CONF = &$GLOBALS["config"];

              // Dotaz
              $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zakaznici WHERE email='$email' AND heslo='$heslo'");
              $radek = @mysql_fetch_array($dotaz);

              // Uložení údajů do sessionu
              If( $radek["email"] AND $radek["heslo"] ){
                  Foreach($radek as $key=>$value){
                          $_SESSION["uzivatel".ucfirst($key)] = $value;
                  }
              }

              // Return
              Return ($radek["email"] AND $radek["heslo"])? True : False;
      }


      Function nactiZDb($id){
              /**
               * informace se uloží do $this->data["nazevHodoty"]
               * (uživatel se nepřihlásí, jen se o něm zjistí informace)
               */
              $CONF = &$GLOBALS["config"];

              // Načtení z DB
              $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zakaznici WHERE id=$id");
              $this->data = mysql_fetch_array($dotaz);

              // Return
              Return $this->data["email"]? True : False;  //(email je vždy povinná položka, slouží k přihlašování)
      }


      Function prihlasen(){
              /**
               * vrací true, pokud je na uživatelově PC přihlášen nějaký uživatel
               * (přihlášení může být i jednorázové, bez registrace do DB)
               */

              If($_SESSION["uzivatelEmail"] OR $this->data["email"]) Return True;
              Else Return False;
      }


      Function odhlasit(){
              /**
               * odhlásí uživatele
               */

              Foreach($_SESSION as $key=>$value){
                      If( ereg("^(uzivatel)", $key) ){
                          $_SESSION[$key] = '';
                      }
              }

              Return True;
      }


      Function getData($key){
              /**
               * vrací informaci o uživateli
               */

              Return $this->data[$key]? $this->data[$key]: $_SESSION["uzivatel".ucfirst($key)];
      }
      Function getPoznamkaObsah(){
              $CONF = &$GLOBALS["config"];
              $poznamka = @mysql_fetch_assoc( Mysql_query("SELECT poznamka FROM $CONF[sqlPrefix]zakazniciPoznamky WHERE email='".$this->data["email"]."'") );
              Return $poznamka["poznamka"];      
      }
      Function getPoznamkaTyp(){
              $CONF = &$GLOBALS["config"];
              $poznamka = @mysql_fetch_assoc( Mysql_query("SELECT typ FROM $CONF[sqlPrefix]zakazniciPoznamky WHERE email='".$this->data["email"]."'") );
              Return $poznamka["typ"];      
      }      


      Function setData($key, $value){
              /**
               * vložení informaci o uživateli
               */

              $this->data[$key] = $value;
      }



      /*******************/
      /* TEMPLATE FUNKCE */
      /*******************/
      Function priradDoTmpl(&$tmpl, $blok, $tmplUzivatelCesta){
              $tmplUzivatel = new GlassTemplate($tmplUzivatelCesta, file_exists("templates/default/uzivatel.html")? "templates/default/uzivatel.html": "../templates/default/uzivatel.html");

              /* -- Vytvoření bloku v templatu -- */
              $tmplUzivatel->newBlok($blok);
              $tmplUzivatel->prirad("$blok.zobraz", "true"); //blok chceme zobrazit i když uživatel není přihlášen

              /* -- Hodnoty pro uživatele načteného z DB -- */
              //(tyto hodnoty mají přednost před hodnotama v sessionu)
              If( $this->data["email"] ){
                  Foreach($this->data as $key=>$value){
                          // Přiřazení hodnoty
                          $tmplUzivatel->prirad("$blok.$key", $value);
                  }
              }
              /* -- Hodnoty pro přihlášeného uživatele -- */
              Else{
                  Foreach($_SESSION as $key=>$value){
                          // Získání správného klíče
                          //(klíč je v sessionu za slovem 'uzivatel' a první písmeno je změněno na velké)
                          $keyUpr = ereg_replace("^uzivatel", "", $key);  //smazání slova 'uzivatel'
                          $keyUpr = lcfirst($keyUpr);  //převod prvního písmene na malé
                          // Přiřazení hodnoty
                          $tmplUzivatel->prirad("$blok.$keyUpr", $value);
                  }
              }
              
              /* -- Poznámky -- */
              $tmplUzivatel->prirad("$blok.poznamkaTyp", $this->getPoznamkaTyp());
              $tmplUzivatel->prirad("$blok.poznamkaObsah", $this->getPoznamkaObsah());

              /* -- Return -- */
              Return $tmplUzivatel->getHtml();
      }

}
?>
