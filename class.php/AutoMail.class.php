<?php /* easyMail */
class AutoMail{
      var $error;
      var $data;
      var $noValues;


      Function AutoMail($data=''){
              /* -- Config -- */
              $this->noValues = array();
              $this->noValues[] = "mailKomu";
              $this->noValues[] = "mailPredmet";
              $this->noValues[] = "mailTextZac";
              $this->noValues[] = "mailTextKon";
              $this->noValues[] = "mailPovinne";
              $this->noValues[] = "submit";

              /* -- Ostatni -- */
              $this->data = $data?$data:$_POST;
              $this->errors = '';
      }



      Function posli(){

              /********************************/
              /* ZÍSKÁNÍ ODESLANÉHO NASTAVENÍ */
              /********************************/
              $CONF = &$GLOBALS["config"];

              // Od koho
              $odKoho = $CONF["mailer"];
              // Komu
              $komu = $this->data["mailKomu"]? $this->data["mailKomu"]: zjisti_z("$CONF[sqlPrefix]nastaveni", "emailAdmin", "id=1");
              // Predmet
              $predmet = $this->data["mailPredmet"];
              // Text začátku emailu
              $textZac = $this->data["mailTextZac"];
              // Text konce emailu
              $textKon = $this->data["mailTextKon"];
              // Povinné položky
              $povinne = explode(";",$this->data["mailPovinne"]);
              Foreach($povinne as $key=>$value){
                      /**
                       * (u klíčů post hodnot se automaticky nahrazuje mezera
                       *  podtržítkem. v nastavení tedy musíme udělat to samé.)
                       */
                      $povinne[$key] = str_replace(" ","_",$value);
              }



              /******************************/
              /* KONTROLA POVINNÝCH POLOŽEK */
              /******************************/
              Foreach($povinne as $key=>$value){
                      If( $value AND !$this->data[$value] ){
                          $this->error = "Nevyplnili jste všechny povinné položky. ";
                      }
              }




              /****************************/
              /* SESTAVENÍ OBSAHU E-MAILU */
              /****************************/
              If( !$this->error ){
                  // Začátek mailu
                  $mailBody = $textZac?($textZac."<p>"):'';
                  // Výpis všech hodnot
                  $mailBody.= '<table border="0">';
                  Foreach($this->data as $key=>$value){
                          If( !in_array($key,$this->noValues) ){
                              $mailBody.= '<tr>
                                             <td valign="top"><b>'.str_replace("_"," ",$key).'</b>: </td>
                                             <td>'.str_replace("\n","<br>",$value).'</td>
                                           </tr>
                                           ';
                          }
                  }
                  $mailBody.= '</table>';
                  // Konec mailu
                  $mailBody.= $textKon?("<p>".$textKon):'<p> ';
              }



              /************/
              /* ODESLÁNÍ */
              /************/
              If( !$this->error ){
                  $pom = easyMail($odKoho,$komu,$predmet,$mailBody);
                  
                  //log
                  $CONF = &$GLOBALS["config"];
                  @mysql_query("INSERT INTO $CONF[sqlPrefix]poptavky(datum, jmeno, adresa, telefon, email, dotaz, obsah)
                                    VALUES (NOW(), '$_POST[Jméno]', '$_POST[Adresa]', '$_POST[Telefon]', '$_POST[Email]', '$_POST[Chci_se_zeptat_na]', '$mailBody')");
                  
                  Return $pom;
              }
      }



      Function getError(){
              Return $this->error;
      }
}
?>
