<?php
/*******************************************/
/* .-------------------------------------. */
/* | Kalendar.class.php (GT Portal)      | */
/* |-------------------------------------| */
/* |       Version: 0.1.0                | */
/* | Last updating: 21.7.2009            | */
/* |-------------------------------------| */
/* |        Author: Michal Mikoláš       | */
/* |          Firm: Grafart studio       | */
/* |                www.grafartstudio.cz | */
/* '-------------------------------------' */
/*******************************************/
/**
 * Kalendar
    ->Kalendar(rok,mesic)
    ->priradDoTmpl(&tmpl, blok, tmplKalendarCesta) =>kalendarHtml
 */
class Kalendar{
      var $rok;
      var $mesic;
      var $pocetDni;
      var $data = array();
      var $dny = array();



      /***************/
      /* KONSTRUKTOR */
      /***************/
      Function Kalendar($rok, $mesic){
              $this->rok = $rok;
              $this->mesic = $mesic;
              $this->pocetDni = cal_days_in_month(CAL_GREGORIAN, $this->mesic, $this->rok);
      }
      Function prvniDen(){  //vrací číslo 1-7 (po-ne)
              $prvniDen = date("w", strtotime("$this->rok-$this->mesic-1"));
              If($prvniDen==0) $prvniDen=7;
              Return $prvniDen;
      }
      
      
      /********************/
      /* FUNKCE KALENDÁŘE */
      /********************/
      Function addNote($den, $note){
              $i = count($this->data[$den]);
              $i++;
              $this->data[$den][$i]["note"]= $note;
              Return $i;
      }
      
      Function setDenTyp($den, $typ){
              $this->dny[$den]["typ"] = $typ;
              Return True;
      }
      


      /* -- Template -- */
      Function getHtml($tmplKalendarCesta, $blok=''){
              $tmplKalendar = new GlassTemplate($tmplKalendarCesta);
              $blok = $blok? "$blok.": '';
              
              
              $tmplKalendar->prirad($blok."rok", $this->rok);
              $tmplKalendar->prirad($blok."mesic", $this->mesic);
              
              // Doplnění prázdných dnů na začátku
              $prazdnychDnuZac = $this->prvniDen()-1;
              For($i=0; $i<$prazdnychDnuZac; $i++){ 
                  $tmplKalendar->newBlok($blok."denNoactiveZac");
                  $tmplKalendar->prirad($blok."denNoactiveZac.zobraz", true);
              }
              
              // Zobrazení poznámek v kalendáři
              For($i=1; $i<=$this->pocetDni; $i++){
                  $tmplKalendar->newBlok($blok."den");
                  $tmplKalendar->prirad($blok."den.den", $i);
                  $tmplKalendar->prirad($blok."den.typ", $this->dny[$i]["typ"]);
                  $tmplKalendar->prirad($blok."den.mesic", $this->mesic);
                  $tmplKalendar->prirad($blok."den.rok", $this->rok);  
                  If(is_array($this->data[$i]))
                    Foreach($this->data[$i] as $kay=>$value){
                            $tmplKalendar->newBlok($blok."den.note");
                            $tmplKalendar->prirad($blok."den.note.obsah", $value["note"]);        
                    }                 
              }
              
              // Doplnění prázdných dnů na konci
              $prazdnychDnuKon = ($prazdnychDnuZac+$this->pocetDni)<35? (35-$prazdnychDnuZac-$this->pocetDni): (42-$prazdnychDnuZac-$this->pocetDni);
              For($i=0; $i<$prazdnychDnuKon; $i++){ 
                  $tmplKalendar->newBlok($blok."denNoactiveKon");
                  $tmplKalendar->prirad($blok."denNoactiveKon.zobraz", true);
              }
              
              Return $tmplKalendar->getHtml();
      }
      
      Function priradDoTmpl(&$tmpl, $blok, $tmplKalendarCesta){
              $tmplKalendar = new GlassTemplate($tmplKalendarCesta);      

              // Přiřazení do tmplUzivatel
              $tmplKalendar->prirad("$blok.id", $this->id);
              $tmplKalendar->prirad("$blok.nazev", $this->nazev);
              $tmplKalendar->prirad("$blok.uvod", $this->uvod);
              $tmplKalendar->prirad("$blok.obsah", $this->obsah);
              $tmplKalendar->prirad("$blok.kategorie", $this->kategorie);

              // Přiřazení do tmpl
              $tmpl->prirad($blok, $tmplKalendar->getHtml());
              
              // Return
              Return $tmplKalendar->getHtml();
      }      
}
?>