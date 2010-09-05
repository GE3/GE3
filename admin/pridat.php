<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="9">
                    &nbsp;</td>
                  <td width="12">
                    <img border="0" src="images/pridat.png" width="47" height="45"></td>
                  <td width="283"><b>
                    <font face="Arial" style="font-size: 11pt">Přidat
                    produkt</font></b><font face="Arial" style="font-size: 8pt"><br>
                    Slouží k přidávání nového produktu.</font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php
/***********************/
/* PŘIDÁNÍ DO DATABÁZE */
/***********************/
If( $_POST["produkt"] ){
    
    ///////////
    // Funkce
    /////////// 
    Function getChyby(){
            //zkontroluje povinné položky
            $CONF = &$GLOBALS["config"];

            $chyby = '';

            If( !($_POST["kategorie"] OR $_POST["kategorieNova"]) OR
                !$_POST["produkt"] OR
                !($_POST["cena"] OR $_POST["variantyCena"][1] OR $CONF["mod"]=='thalia') OR
                !$_POST["cenaTyp"] OR
                !($_POST["dph"] OR $CONF["mod"]=='thalia')
                 )$chyby.= 'Produkt \''.$_POST["produkt"].'\' - nevyplnili jste všechny povinné údaje; ';

            If( zjisti_z("$CONF[sqlPrefix]zbozi", "id", "produkt='$_POST[produkt]'"))
                $chyby.= 'Produkt \''.$_POST["produkt"].'\' již v databázi existuje; ';

            Return $chyby;
    }

    Function getFriendlyFilename($soubor, $nazevProduktu){
            //vrací název souboru pro obrázek podle názvu produktu
            $pripona = preg_replace("|^.*\.([a-zA-Z]+)$|", "$1", $soubor);

            $friendlyName = urlText($nazevProduktu);
            $i=0;
            $slozka = eregi("jpg|jpeg|bmp|png|gif",$pripona)? "obrazky": "prilohy";
            Do{
              $i++;
              $jedinecnyNazev = $friendlyName.($i>1?"-$i":"").".$pripona";
            }While( file_exists("../zbozi/$slozka/$jedinecnyNazev") );

            Return $jedinecnyNazev;
    }


    //////////////////
    // Přidání do DB
    //////////////////
    If( !getChyby() ){
        $kategorie = $_POST["kategorieNova"]? str_replace("\"","´",$_POST["kategorieNova"]): str_replace("\"","´",$_POST["kategorie"]);
        $podkat1 = $_POST["nova_podkat1"]? str_replace("\"","´",$_POST["nova_podkat1"]): str_replace("\"","´",$_POST["podkat1"]);
        $podkat2 = $_POST["nova_podkat2"]? str_replace("\"","´",$_POST["nova_podkat2"]): str_replace("\"","´",$_POST["podkat2"]);
        $podkat3 = $_POST["nova_podkat3"]? str_replace("\"","´",$_POST["nova_podkat3"]): str_replace("\"","´",$_POST["podkat3"]);
        $vyrobce = $_POST["vyrobceNovy"]? str_replace("\"","´",$_POST["vyrobceNovy"]): str_replace("\"","´",$_POST["vyrobce"]);
        $produkt = str_replace("\"","´",$_POST["produkt"]);

        /* -- Vytvoření dotazu -- */
        $i = 1;
        Do{ 
            //(cyklus zajistí uložení všech variant produktu)
            $sqlKeys = '';
            $sqlValues = '';

            // Všeobecné údaje
            $sqlKeys.= "cislo, kategorie, podkat1, podkat2, podkat3, produkt, vyrobce, popis, akce, neprehlednete, nejprodavanejsi, dostupnost, ";
            $sqlValues.= "'$_POST[cislo]', '$kategorie', '$podkat1', '$podkat2', '$podkat3', '$produkt', '$vyrobce', '$_POST[popis]', '$_POST[akce]', '$_POST[neprehlednete]', '$_POST[nejprodavanejsi]', '$_POST[dostupnost]', ";
            // Obrázky
            If( $i==1 ){   //obrázky stačí uploadnout jen jednou..
                $pripony = array("jpg", "jpeg", "bmp", "png", "gif");
                $obrazkyNazvy = '';
                Foreach($_FILES["obrazky"]["name"] as $key=>$value){
                        $pripona = preg_replace("|^.*\.([a-zA-Z]+)$|", "$1", strtolower($value));
                        If( $value AND in_array($pripona, $pripony) ){
                            $nazevSouboru = getFriendlyFilename($_FILES["obrazky"]["name"][$key], $_POST["produkt"]);
                            move_uploaded_file($_FILES["obrazky"]["tmp_name"][$key], "../zbozi/obrazky/$nazevSouboru");
                            $obrazkyNazvy.= "$nazevSouboru;";
                        }
                }
            }
            If($obrazkyNazvy){ $sqlKeys.= "obrazky, ";   //..ale zapsat do DB se musí
                               $sqlValues.= "'$obrazkyNazvy', ";
                             }            
            // Přílohy
            If( $i==1 ){   //přílohy stačí uploadnout jen jednou..
                $priponyZakazane = array("php", "php2", "php3", "php4");
                $prilohyNazvy = '';
                Foreach($_FILES["prilohy"]["name"] as $key=>$value){
                        $pripona = preg_replace("|^.*\.([a-zA-Z]+)$|", "$1", $value);
                        If( $value AND !in_array($pripona, $priponyZakazane) ){
                            $nazevSouboruBezPripony = eregi_replace("^(.*)\.[a-z]+$", "\\1", $_FILES["prilohy"]["name"][$key]);
                            $nazevSouboru = getFriendlyFilename($_FILES["prilohy"]["name"][$key], $nazevSouboruBezPripony);
                            
                            move_uploaded_file($_FILES["prilohy"]["tmp_name"][$key], "../zbozi/prilohy/$nazevSouboru");
                            $prilohyNazvy.= "$nazevSouboru;";
                        }
                }
            }            
            If($prilohyNazvy){ $sqlKeys.= "prilohy, ";   //..ale zapsat do DB se musí
                               $sqlValues.= "'$prilohyNazvy', ";
                             }
            // Ceny
            If($_POST["cenaTyp"]=='s DPH'){ $sqlKeys.= "cenaSDph, ";
                                            $sqlValues.= "'".($_POST["variantyCena"][$i]?$_POST["variantyCena"][$i]:$_POST["cena"])."', ";
                                            $sqlKeys.= "puvodni_cena_s_dph, ";
                                            $sqlValues.= "'$_POST[puvodni_cena]', ";
                                          }
            If($_POST["cenaTyp"]=='bez DPH'){ $sqlKeys.= "cenaBezDph, ";
                                              $sqlValues.= "'".($_POST["variantyCena"][$i]?$_POST["variantyCena"][$i]:$_POST["cena"])."', ";
                                              $sqlKeys.= "puvodni_cena_bez_dph, ";
                                              $sqlValues.= "'$_POST[puvodni_cena]', ";
                                            }
            $sqlKeys.= "dph, ";
            $sqlValues.= "$_POST[dph], ";
            // Varianta
            $sqlKeys.= "varianta, ";
            $sqlValues.= "'".$_POST["variantyNazev"][$i]."', ";


            /* -- Provedení dotazu -- */
            $sqlKeys = ereg_replace(", $", "", $sqlKeys);
            $sqlValues = ereg_replace(", $", "", $sqlValues);
            $dotaz = Mysql_query("INSERT INTO $CONF[sqlPrefix]zbozi($sqlKeys) VALUES ($sqlValues)") or die("Chyba v SQL dotazu: ".mysql_error());

            $i++;
        }while( $_POST["variantyNazev"][$i] );


        /* -- Zobrazení hlášky -- */
        Echo '<div style="border: 2px solid #008000; background-color: #EEFFEE; text-align: center;">
                <b>Produkt \''.$_POST["produkt"].'\' byl úspěšně zařazen databáze.</b> <br>
              </div><br>&nbsp;';
    }

    Else{
        /* -- Zobrazení chyby -- */
        Echo '<div style="border: 2px solid #800000; background-color: #FFEEEE; text-align: center;">
                <b>Při přidávání produktu nastaly tyto chyby:</b> <br>'.getChyby().'
              </div><br>&nbsp;';
    }

}



/*************/
/* ZOBRAZENÍ */
/*************/

///////////
// Funkce
///////////
Function inputyProVarianty($max=10){
        $inputy = '';
        $i = 1;
        While($i<=$max){
              $cssDisplay = ($i<=2)?'block':'none';
              $cssDisplay = ($_POST["variantyNazev[$i]"] OR $_POST["variantyNazev[$i]"] OR $_POST["variantyNazev[".($i-1)."]"] OR $_POST["variantyCena[".($i-1)."]"])? 'block': $cssDisplay;

              $inputy.= '
                        <div id="divVarianta'.$i.'" style="display: '.$cssDisplay.';">
                          Varianta '.$i.': <input name="variantyNazev['.$i.']" id="inputVariantyNazev'.$i.'" type="text" onChange="if(document.getElementById(\'inputVariantyNazev'.$i.'\').value && document.getElementById(\'inputVariantyCena'.$i.'\').value){document.getElementById(\'divVarianta'.($i+2).'\').style.display=\'block\';}else{document.getElementById(\'divVarianta'.($i+2).'\').style.display=\'none\';}">
                                      <input name="variantyCena['.$i.']" id="inputVariantyCena'.$i.'" type="text" onChange="if(document.getElementById(\'inputVariantyNazev'.$i.'\').value && document.getElementById(\'inputVariantyCena'.$i.'\').value){document.getElementById(\'divVarianta'.($i+2).'\').style.display=\'block\';}else{document.getElementById(\'divVarianta'.($i+2).'\').style.display=\'none\';}">
                        </div>
                        ';
              $i++;
        }

        Return $inputy;
}

Function inputyProObrazky($max=10){
        $inputy = '';
        $i = 1;
        While($i<=$max){
              $cssDisplay = ($i<=2)?'inline':'none';

              $inputy.= '
                        <span id="spanObrazek'.$i.'" style="display: '.$cssDisplay.';"><input type="file" name="obrazky['.$i.']" onChange="if(this.value){document.getElementById(\'spanObrazek'.($i+2).'\').style.display=\'inline\';}else{document.getElementById(\'spanObrazek'.($i+2).'\').style.display=\'none\';}"><br></span>
                        ';
              $i++;
        }

        Return $inputy;
}

Function inputyProPrilohy($max=10){
        $inputy = '';
        $i = 1;
        While($i<=$max){
              $cssDisplay = ($i<=2)?'inline':'none';

              $inputy.= '
                        <span id="spanPriloha'.$i.'" style="display: '.$cssDisplay.';"><input type="file" name="prilohy['.$i.']" onChange="if(this.value){document.getElementById(\'spanPriloha'.($i+2).'\').style.display=\'inline\';}else{document.getElementById(\'spanPriloha'.($i+2).'\').style.display=\'none\';}"><br></span>
                        ';
              $i++;
        }

        Return $inputy;
}

Function getOptions($nazev, $tabulka, $podminky='1=1', $selected=''){
        $options = '';

        $dotaz=Mysql_query("SELECT * FROM $tabulka WHERE $podminky");
        While($radek=mysql_fetch_array($dotaz)){
              $options.= '<option value="'.$radek[$nazev].'" '.($radek[$nazev]==$selected?'selected':'').'>'.$radek[$nazev].'</option>';
        }

        Return $options;
}


//////////////
// Zobrazení
//////////////

$posledni = mysql_fetch_array( Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi ORDER BY id DESC LIMIT 1") );

$dotaz="SELECT * FROM $CONF[sqlPrefix]zbozi ORDER BY kategorie,produkt ASC";
$dotaz=mysql_query($dotaz);

echo '
<script type="text/javascript">
function novaKategorie(hodnota){
         if(hodnota==""){
            document.getElementById("inputNovaKategorie").value="";
            document.getElementById("inputNovaKategorie").style.display="inline";
         }
         else{
            document.getElementById("inputNovaKategorie").value="";
            document.getElementById("inputNovaKategorie").style.display="none";
         }
}
function skryjPodkat(cislo){
         if( cislo<=4 ){
             document.getElementById("spanPodkat4").innerHTML="";
             document.getElementById("divPodkat4").innerHTML="";
         }
         if( cislo<=3 ){
             document.getElementById("spanPodkat3").innerHTML="";
             document.getElementById("divPodkat3").innerHTML="";
         }
         if( cislo<=2 ){
             document.getElementById("spanPodkat2").innerHTML="";
             document.getElementById("divPodkat2").innerHTML="";
         }
         if( cislo==1 ){
             document.getElementById("spanPodkat1").innerHTML="";
             document.getElementById("divPodkat1").innerHTML="";
         }
}
function ukazPodkat(cislo){
         document.getElementById("spanPodkat"+cislo).innerHTML="&nbsp;Podkategorie"+cislo+": ";
}
function ukazSkryj(element) {
   var srcElement = document.getElementById(element);
   if(srcElement != null) {
      if(srcElement.style.display == "block") {
         srcElement.style.display= \'none\';
      }
      else {
             srcElement.style.display=\'block\';
      }
      return false;
   }
}
</script>


<FORM method="post" action="" enctype="multipart/form-data" style="position: relative;">
  <input type="hidden" name="pridat" value="pridat">

   <div style="position: absolute; top: 0px; right: 12px; display: none;">
     <input type="checkbox" name="akce" value="1">Přidat do Akční nabídky <br>
     <input type="checkbox" name="neprehlednete" value="1">Přidat do Nepřehlédněte <br>
     <input type="checkbox" name="nejprodavanejsi" value="1">Přidat do Nejprodávanější <br>
   </div>

  <table>
  <tr><td>
  <b>Kategorie:</b></td><td>
     <select name="kategorie" onChange="novaKategorie(this.value);skryjPodkat(2);if(this.value==\'\')skryjPodkat(1);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat1.php\',\'kategorie=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat1\');ukazPodkat(1);}">
        <option value="">-nová kategorie- =&gt;</option>
        '.getOptions("kategorie", "$CONF[sqlPrefix]zbozi", "1=1 GROUP BY kategorie ORDER BY kategorie ASC, produkt ASC", $posledni["kategorie"]).'
     </select>
     <input type="text" id="inputNovaKategorie" name="kategorieNova" value="" onChange="skryjPodkat(2);if(this.value==\'\')skryjPodkat(1);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat1.php\',\'kategorie=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat1\');ukazPodkat(1);}">
     </td></tr>
  <tr><td>
     <script type="text/javascript">
     function novaPodkat(hodnota,cislo){
              if(hodnota==""){
                 document.getElementById("inputNovaPodkat"+cislo).value="";
                 document.getElementById("inputNovaPodkat"+cislo).style.display="inline";
              }
              else{
                 document.getElementById("inputNovaPodkat"+cislo).value="";
                 document.getElementById("inputNovaPodkat"+cislo).style.display="none";
              }
     }
     </script>
  <span style="font-size: 80%; font-weight: bolder;" id="spanPodkat1"></span></td><td>
     <div id="divPodkat1"></div></td></tr>
  <tr><td>
  <span style="font-size: 80%; font-weight: bolder;" id="spanPodkat2"></span></td><td>
     <div id="divPodkat2"></div></td></tr>
  <tr><td>
  <span style="font-size: 80%; font-weight: bolder;" id="spanPodkat3"></span></td><td>
     <div id="divPodkat3"></div></td></tr>
  <tr><td>
  <span style="font-size: 80%; font-weight: bolder;" id="spanPodkat4"></span></td><td>
     <div id="divPodkat4"></div></td></tr>
  <tr><td>
  <b>Název:</b></td><td>
     <input type="text" name="produkt"></td></tr>
  <tr><td>
  Číslo:</td><td>
     <input type="text" name="cislo"></td></tr>     
  <tr><td>
  <b>Cena:</b></td><td>
     <input type="text" name="cena">
     <select name="cenaTyp" style="color: #333333;">
       <option value="s DPH" '.($posledni["cenaSDph"]? 'selected' : '' ).'>s DPH: </option>
       <option value="bez DPH" '.($posledni["cenaBezDph"]? 'selected' : '' ).'>bez DPH: </option>
     </select>
     <input type="text" name="dph" value="'.$posledni["dph"].'" size="1" style="font-size: 8pt; color: #333333;">%
  </td></tr>
  <tr><td '.( zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_puvodni_cena_active","id=1")? '': 'style="text-decoration: line-through;"' ).'>
  Původní cena:</td><td>
     <input type="text" name="puvodni_cena" '.( zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_puvodni_cena_active","id=1")? '': 'disabled' ).'>
     </td></tr>
  <tr><td>
  Skladem:</td><td>
     <select name="dostupnost">
        <option value="ANO">ANO</option>
        <option value="NE" style="color: red;">NE</option>
     </select></td></tr>
  <tr><td>
  Výrobce:</td><td>
     <select name="vyrobce" onChange="if(this.value==\'\'){document.getElementById(\'inputVyrobceNovy\').style.display=\'inline\';}else{document.getElementById(\'inputVyrobceNovy\').value=\'\'; document.getElementById(\'inputVyrobceNovy\').style.display=\'none\';}">
       <option value="">-nový výrobce- =&gt; </option>
       '.getOptions("vyrobce", "$CONF[sqlPrefix]zbozi", "vyrobce!='' GROUP BY vyrobce ORDER BY vyrobce ASC", $posledni["vyrobce"]).'
     </select>
     <input type="text" id="inputVyrobceNovy" name="vyrobceNovy" value="'.$posledni["vyrobce"].'" style="display: '.($posledni["vyrobce"]?'none':'inline').';">
     </td></tr>
  <tr><td valign="top" style="padding-top: 5px;">
  Obrázky:</td><td>
     '.inputyProObrazky(10).'
     </td></tr>
  <tr><td valign="top">
  &nbsp;<br>Varianty:</td><td>
    <table border="0" cellspacing="0" cellpading="0">
       <tr>
          <td width="64px"> </td><td width="146px">Název</td><td>Cena</td>
       </tr>
    </table>
    '.inputyProVarianty(10).'
  </td></tr>
  <tr><td valign="top">
    Popis:</td><td width="600">
  </td></tr>
  <tr><td colspan="2">
      <script type="text/javascript">
      var oFCKeditor = new FCKeditor(\'popis\');
      oFCKeditor.BasePath = "fckeditor/";
      oFCKeditor.ToolbarSet = "Grafart_produkty";
      oFCKeditor.Value = "";
      oFCKeditor.Height = "400px";
      oFCKeditor.Create();
      </script>
      <noscript><textarea name="popis" rows="12" cols="65"></textarea></noscript>
  </td></tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr><td valign="top" style="padding-top: 5px;">
  <nobr>Připojit přílohu:</nobr></td><td>
     '.inputyProPrilohy(10).'
     </td></tr>  
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr><td></td><td align="right"><input type="submit" name="tl_pridat" value="Přidat do databáze"></td></tr>
  </table>
</FORM>
';

?>



<?php Include 'grafika_kon.inc'; ?>
