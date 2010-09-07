<?php      
$html_nadpis='
          <table border="0" width="404" cellspacing="15">
            <tr>
              <td width="9">
                &nbsp;</td>
              <td width="12">
                <img border="0" src="images/editovat.png" width="46" height="46"></td>
              <td width="283">
                <font face="Arial" style="font-weight: 700" size="2">Editace
                produktů </font><font face="Arial" style="font-size: 8pt">
                <br>
                Slouží k úpravě údajů o položkách, přidávání nebo mazání
                jednotlivých údajů.</font></td>
            </tr>
          </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>
<?php
Include 'upravit.funkce.php';
?>



<?php
$kategorie=($_POST["kategorie"]=='nova_kategorie')?$_POST["nova_kategorie"]:$_POST["kategorie"];
$podkat1=($_POST["podkat1"]=='nova_podkat1')?$_POST["nova_podkat1"]:$_POST["podkat1"];
$podkat2=($_POST["podkat2"]=='nova_podkat2')?$_POST["nova_podkat2"]:$_POST["podkat2"];
$podkat3=($_POST["podkat3"]=='nova_podkat3')?$_POST["nova_podkat3"]:$_POST["podkat3"];
$nova_kategorie=$_POST["nova_kategorie"];
$vyrobce=$_POST["vyrobce"];
$dph=$_POST["dph"];
$cena=$_POST["cena_prehled"];
$pridat=$_POST["pridat"];
$dopravne=$_POST["dopravne"];
$popis1=$_POST["popis1"];
$popis2=$_POST["popis2"];
$popis3=$_POST["popis3"];
$cena_prehled=$_POST["cena_prehled"];
$hmotnost=$_POST["hmotnost"];
$akce=$_POST["akce"];
$dostupnost=$_POST["dostupnost"];
$varianta=$_POST["produkt"];
$varianta1=$_POST["varianta1"];
$varianta2=$_POST["varianta2"];
$varianta3=$_POST["varianta3"];
$varianta4=$_POST["varianta4"];
$varianta5=$_POST["varianta5"];
$varianta6=$_POST["varianta6"];
$varianta7=$_POST["varianta7"];
$varianta8=$_POST["varianta8"];
$varianta9=$_POST["varianta9"];
$varianta10=$_POST["varianta10"];
$varianta11=$_POST["varianta11"];
$varianta12=$_POST["varianta12"];
$varianta13=$_POST["varianta13"];
$varianta14=$_POST["varianta14"];
$varianta15=$_POST["varianta15"];
$varianta16=$_POST["varianta16"];
$varianta17=$_POST["varianta17"];
$varianta18=$_POST["varianta18"];
$varianta19=$_POST["varianta19"];
$varianta20=$_POST["varianta20"];
$cena1=$_POST["cena_prehled"];
$cena2=$_POST["cena2"];
$cena3=$_POST["cena3"];
$cena4=$_POST["cena4"];
$cena5=$_POST["cena5"];
$cena6=$_POST["cena6"];
$cena7=$_POST["cena7"];
$cena8=$_POST["cena8"];
$cena9=$_POST["cena9"];
$cena10=$_POST["cena10"];
$cena11=$_POST["cena11"];
$cena12=$_POST["cena12"];
$cena13=$_POST["cena13"];
$cena14=$_POST["cena14"];
$cena15=$_POST["cena15"];
$cena16=$_POST["cena16"];
$cena17=$_POST["cena17"];
$cena18=$_POST["cena18"];
$cena19=$_POST["cena19"];
$cena20=$_POST["cena20"];
$nove_varianty=$_POST["nove_varianty"];
$zmena_kategorie=$_POST["zmena_kategorie"];

$filtr_produkt=$_POST["filtr_produkt"]?$_POST["filtr_produkt"]:$_GET["filtr_produkt"];
$filtr_produkt=$filtr_produkt?$filtr_produkt:"*";
$filtr_kategorie=$_POST["filtr_kategorie"]?$_POST["filtr_kategorie"]:$_GET["filtr_kategorie"];
$filtr_kategorie=$filtr_kategorie?$filtr_kategorie:"*";
$filtr_vyrobce=$_POST["filtr_vyrobce"]?$_POST["filtr_vyrobce"]:$_GET["filtr_vyrobce"];
$filtr_vyrobce=$filtr_vyrobce?$filtr_vyrobce:"*";

$m=$_GET["m"];
$n=$_GET["n"];
$produkt=$_POST["produkt"]?$_POST["produkt"]:$_GET["produkt"];
$produkt = str_replace("\"","´",$produkt);
$id_varianta_smaz=$_GET["id_varianta_smaz"];

$produkt = $_POST["produkt"]? $_POST["produkt"]: $_GET["produkt"];


function zobraz_mezeru(){
   echo '
      <!-- mezera -->
      <table border="0" cellpading="0" cellspacing="0">
      <tr><td height="4" width="100%" bordercolor="green"></td></tr>
      </table>
      ';
}
?>



<?php /* Navigace */
echo '<div style="font-size: 10pt; font-weight: bold; margin: 0px 0px 20px 0px; position: relative; top: -16px;">
        <span style="font-weight: bold; color: #2B5202;">Nacházíte se v: </span>
        <a href="?m=editace_zbozi">Editace produktů</a> 
        '.( ($_GET["kategorie"] or $_GET["produkt"])? '»': '' ).' 
        '.cestaKategorie($_GET["kategorie"], $_GET["podkat1"], $_GET["podkat2"], $_GET["podkat3"]).'
      ';
if($_GET["produkt"]) echo '» <a href="?m=editace_zbozi&n=zmena_zakladnich_udaju&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'&podkat2='.$_GET["podkat2"].'&produkt='.$_GET["produkt"].'">'.zjisti_z("$CONF[sqlPrefix]zbozi","produkt","id=$_GET[produkt]").'</a> ';
echo '  </span>
      </div>';
?>



<?php /* Smazání variant */
if($id_varianta_smaz AND !$nove_varianty AND !$zmena_zakladnich_udaju_odeslana AND zjisti_z("$CONF[sqlPrefix]zbozi", "id", "id=$id_varianta_smaz")){
   $dotaz="DELETE FROM $CONF[sqlPrefix]zbozi WHERE id='$id_varianta_smaz'";
   $dotaz=mysql_query($dotaz);
   if($dotaz){
      echo '
         <table><tr><td bgcolor="#FFDEDE">
         Varianta úspěšně smazána.
         </td></tr></table>
         ';
      }else{
      echo '
         <table><tr><td bgcolor="#FFDEDE">
         Neznámá chyba.
         </td></tr></table>
         ';
      }
   }
?>



<?php /* Smazání zboží */
If( $_GET["zbozi_smaz"] ){
    $zboziId = $_GET["zbozi_smaz"];
    If( Mysql_query("DELETE FROM z1 USING $CONF[sqlPrefix]zbozi AS z1, $CONF[sqlPrefix]zbozi AS z2 WHERE z1.produkt=z2.produkt AND z2.id=$zboziId") ){
        Echo '
           <table><tr><td bgcolor="#FFDEDE">
           Zboží úspěšně smazáno.
           </td></tr></table>
           ';
    }
    Else{
        Echo '
           <table><tr><td bgcolor="#FFDEDE">
           Neznámá chyba: '.mysql_error().'
           </td></tr></table>
           ';
    }
}
?>



<?php /* Změna základních údajů */

//kontrola
if($_POST["zmena_zakladnich_udaju_odeslana"]){
   $chyby="";
   if(!is_numeric($_POST["ceny"][1]) AND $_POST["ceny"][1] AND $CONF["mod"]!='thalia'){$chyby=$chyby.'<br>Cena musí být číslo';}
   $i=2;
   $pocet=count($ceny);
   $chyby_var1="";
   $chyby_var2="";
   while($i<=$pocet){
      if(!$varianty[$i]){$chyby_var1="<br>Chybí informace u varianty$i";}
      if(!is_numeric($_POST["ceny"][$i]) AND $_POST["ceny"][$i] AND $CONF["mod"]!='thalia'){$chyby_var2="<br>Cena musí být číslo (varianta$i)";}
      $i=$i+1;
      }
   $chyby=$chyby.$chyby_var1.$chyby_var2;
   }

//úprava
if($_POST["zmena_zakladnich_udaju_odeslana"] AND !$chyby){
echo '<table><tr><td bgcolor="#FFDEDE">';

   //ceny
   If( $_POST["cenaTyp"]=='s DPH' ){
       $sqlCeny = "cenaSDph='$_POST[ceny][1]',cenaBezDph='0',puvodni_cena_s_dph='$_POST[puvodni_cena]',puvodni_cena_bez_dph=0";
   }
   Else{
       $sqlCeny = "cenaSDph='0',cenaBezDph='$_POST[ceny][1]',puvodni_cena_bez_dph='$_POST[puvodni_cena]',puvodni_cena_s_dph=0";   
   }
   //dotaz
   $dotaz="UPDATE $CONF[sqlPrefix]zbozi SET cislo='$_POST[cislo]',popis='$_POST[popis]',$sqlCeny,dph='$dph',akce='$akce',neprehlednete='$_POST[neprehlednete]',nejprodavanejsi='$_POST[nejprodavanejsi]',dostupnost='$dostupnost',vyrobce='$vyrobce' WHERE produkt='$produkt'";
   $dotaz=mysql_query($dotaz);
   if($dotaz){
      echo '
         Editace základních údajů proběhla úspěšně. 
         <script type="text/javascript">
           function presmeruj(){
             location.href=\'index.php?m=editace_zbozi&kategorie='.$_GET["kategorie"].'\';
           }
           setTimeout("presmeruj()", 2000);
         </script>
         ';
      }else{
      echo '
         Při editaci nastala neznámá chyba:
         <div style="border: 1px solid red; width: 60%;">'.mysql_error().'<br>
            <div style="background-color: #FFEEEE;">'.$tmp.'</div>
         </div>';
      }

   //úprava variant
   $i=1;
   $pocet=(count($_POST["varianty"])+1);
   while($i<=$pocet){
      //ceny
      If( $_POST["cenaTyp"]=='s DPH' ){
          $sqlCeny = "cenaSDph='".$_POST["ceny"][$i]."',cenaBezDph='0'";   
      }
      Else{
          $sqlCeny = "cenaSDph='0',cenaBezDph='".$_POST["ceny"][$i]."'";   
      }
      //dotaz
      $dotaz="UPDATE $CONF[sqlPrefix]zbozi SET cislo='$_POST[cislo]',popis='$_POST[popis]',varianta='".$_POST["varianty"][$i]."',$sqlCeny,dph='$dph',dostupnost='$dostupnost',vyrobce='$vyrobce' WHERE id='".$_POST["idy"][$i]."'";
      $dotaz=mysql_query($dotaz) or die(mysql_error());
      if($dotaz AND $i==$pocet){
         echo '<br>Editace variant a cen zboží proběhla úspěšně.';
         }elseif($i==$pocet){
         echo '<br>Při editaci variant a cen zboží nastala neznámá chyba.';
         }

      $i=$i+1;
      }

echo '</td></tr></table>';
   }elseif($chyby){
echo '
   <table><tr><td bgcolor="#FFDEDE">
   Úpravy nelze provést. Chyby: '.$chyby.'
   </td></tr></table>';
   }
?>



<?php
/*******************/
/* PŘIDÁNÍ VARIANT */
/*******************/

///////////
// Funkce
///////////
Function inputyProNoveVarianty($max=10){
        $inputy = '';
        $i = 1;
        While($i<=$max){
              $cssDisplay = ($i<=2)?'block':'none';
              $cssDisplay = ($_POST["noveVariantyNazev[$i]"] OR $_POST["noveVariantyNazev[$i]"] OR $_POST["noveVariantyNazev[".($i-1)."]"] OR $_POST["noveVariantyCena[".($i-1)."]"])? 'block': $cssDisplay;

              $inputy.= '
                        <div id="divNovaVarianta'.$i.'" style="display: '.$cssDisplay.';">
                          Varianta '.$i.': <input name="noveVariantyNazev['.$i.']" id="inputNoveVariantyNazev'.$i.'" type="text" onChange="if(document.getElementById(\'inputNoveVariantyNazev'.$i.'\').value && document.getElementById(\'inputNoveVariantyCena'.$i.'\').value){document.getElementById(\'divNovaVarianta'.($i+2).'\').style.display=\'block\';}else{document.getElementById(\'divNovaVarianta'.($i+2).'\').style.display=\'none\';}">
                                           <input name="noveVariantyCena['.$i.']" id="inputNoveVariantyCena'.$i.'" type="text" onChange="if(document.getElementById(\'inputNoveVariantyNazev'.$i.'\').value && document.getElementById(\'inputNoveVariantyCena'.$i.'\').value){document.getElementById(\'divNovaVarianta'.($i+2).'\').style.display=\'block\';}else{document.getElementById(\'divNovaVarianta'.($i+2).'\').style.display=\'none\';}">
                        </div>
                        ';
              $i++;
        }

        Return $inputy;
}


////////////////////////
// Zapsání do databáze
////////////////////////
If( $_POST["noveVariantyNazev"][1] ){
    //(pro vložení nových variant je potřeba zjistit všechny informace
    // o předchozích variantách, aby nové varianty měly stejné údaje)

    /* -- Zjištění informací -- */
    $dotaz=mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi z1, $CONF[sqlPrefix]zbozi z2 WHERE z1.produkt=z2.produkt AND z2.id=$_GET[produkt]");
    $radek=mysql_fetch_assoc($dotaz);

    /* -- Vytvoření variant -- */
    $i = 1;
    While($_POST["noveVariantyNazev"][$i]){  //cyklus zajistí uložení všech variant

          /* -- Vytvoření dotazu -- */
          $sqlKeys = '';
          $sqlValues = '';

          // Obecné hodnoty
          $sqlKeys.= "varianta, ";
          $sqlValues.= "'".$_POST["noveVariantyNazev"][$i]."', ";

          $noKeys = array("id", "varianta", "cenaSDph", "cenaBezDph");
          Foreach($radek as $key=>$value){
                  If( !in_array($key, $noKeys) AND !is_numeric($key) ){
                      $sqlKeys.= "$key, ";
                      $sqlValues.= "'$value', ";
                  }
          }

          // Ceny
          If($radek["cenaSDph"]){ $sqlKeys.= "cenaSDph, ";
                                  $sqlValues.= "'".$_POST["noveVariantyCena"][$i]."', "; }
          If($radek["cenaBezDph"]){ $sqlKeys.= "cenaBezDph, ";
                                    $sqlValues.= "'".$_POST["noveVariantyCena"][$i]."', "; }

          /* -- Provedení dotazu -- */
          $sqlKeys = ereg_replace(", $", "", $sqlKeys);
          $sqlValues = ereg_replace(", $", "", $sqlValues);
          $dotaz = Mysql_query("INSERT INTO $CONF[sqlPrefix]zbozi($sqlKeys) VALUES ($sqlValues)");

          $i++;
    }

    /* -- Zobrazení hlášky -- */
    If( !mysql_error() ){
        Echo '<table><tr><td bgcolor="#DEFEDE">
              Varianty úspěšně přidány.
              </td></tr></table>
              ';
    }
    Else{
        Echo '<table><tr><td bgcolor="#FFDEDE">
              Varianty nelze přidat. Chyby: <br>'.mysql_error().'
              </td></tr></table>
              ';
    }
}


?>



<?php /* Změna základních údajů */
If( $_GET["n"]=="zmena_zakladnich_udaju" AND $_GET["produkt"] ){

    // Přílohy
    Function ikonaSouboru($nazevSouboru){
            $CONF = &$GLOBALS["config"];
    
            $pripona = eregi_replace("^.*\.([a-z]*)$", "\\1", $nazevSouboru);
            $pripona = strtolower($pripona);
    
            If(!file_exists("ikonySouboru/$pripona.png")) 
               $pripona="default";  
            
            Return "$pripona.png";
    }
    
    
    $prilohyHtml = '<div style="margin-top: 24px; border: 1px solid #666666; padding: 2px 4px 4px 4px; position: relative">
                    <b>Přílohy </b>
                    <span style="position: absolute; top: 0px; right: 4px; color: #000080; cursor: pointer;" onClick="easyAjaxPopup(\'Administrace příloh\', \'upravit.ajax.prilohy.php\', \'id='.$_GET["produkt"].'\');">
                      Upravit
                    </span> 
                    <br>';
    
    $produkt = mysql_fetch_assoc( Mysql_query("SELECT prilohy FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[produkt]") );
    $prilohy = explode(";", $produkt["prilohy"]);
    Foreach($prilohy as $key=>$value){
            If( $value ){
                $prilohyHtml.= '<img src="ikonySouboru/'.ikonaSouboru($value).'" style="position: relative; top: 3px;"> 
                                <a href="../zbozi/prilohy/'.$value.'" target="_blank" style="text-decoration: none;">'.$value.'</a> <br>';
            }
    }

    $prilohyHtml.= '</div>';



   // Zobrazení formuláře
   $dotaz="SELECT z1.* FROM $CONF[sqlPrefix]zbozi z1,$CONF[sqlPrefix]zbozi z2 WHERE z1.produkt=z2.produkt AND z2.id='$_GET[produkt]' ORDER BY z1.id ASC";
   $dotaz=mysql_query($dotaz) or die("609. ".mysql_error());
   $radek=mysql_fetch_array($dotaz);
   $akce_checked=$radek["akce"]?"checked":"";
   $neprehlednete_checked=$radek["neprehlednete"]?"checked":"";
   $nejprodavanejsi_checked=$radek["nejprodavanejsi"]?"checked":"";

   echo '
      <b>Editace základních údajů</b>
      <form method="post" action="">
        <input type="hidden" name="produkt" value="'.$radek["produkt"].'">
        <input type="hidden" name="zmena_zakladnich_udaju_odeslana" value="TRUE">
        <input type="hidden" name="varianta" value="'.$radek["varianta"].'">
        
        <table border="0" width="100%">
          <tr>
            <td>Název:</td>
            <td>'.$radek["produkt"].' <img src="images/edit16.png" style="cursor: pointer;" title="Změnit" onClick="easyAjaxPopup(\'Změna názvu produktu\', \'ajax.php/ajax.zmena_nazvu_produktu.php\', \'id='.$radek["id"].'\');"></td>
            <td rowspan="6" align="right" valign="top">
              <div style="text-align: left;">
                <input type="checkbox" name="akce" value="1" '.$akce_checked.'>Přidat do Akční nabídky
                <br><input type="checkbox" name="neprehlednete" value="1" '.$neprehlednete_checked.'>Přidat do Nepřehlédněte
                <br><input type="checkbox" name="nejprodavanejsi" value="1" '.$nejprodavanejsi_checked.'>Přidat do Nejprodávanější
                <p>
                '.$prilohyHtml.'
              </div>
            </td>
          </tr>
          <tr>
            <td>Číslo:</td>
            <td><input type="text" name="cislo" value="'.$radek["cislo"].'"></td>
          </tr>
          <tr>
            <td>Kategorie:</td>
            <td>
              '.$radek["kategorie"].' 
              '.($radek["podkat1"]? '<br>&nbsp; » '.$radek["podkat1"]: '').' '.($radek["podkat2"]? ' » '.$radek["podkat2"]: '').' '.($radek["podkat3"]? ' » '.$radek["podkat3"]: '').'
              <img src="images/edit16.png" style="cursor: pointer;" title="Změnit" onClick="easyAjaxPopup(\'Změna kategorie\', \'upravit.ajax.zmena_kategorie.php\', \'id='.$radek["id"].'\');">
            </td>
          </tr>
          <tr>
            <td>Výrobce:</td>
            <td><input type="text" name="vyrobce" value="'.$radek["vyrobce"].'"></td>
          </tr>
          <tr>
            <td>Skladem:</td>
            <td>
              <select name="dostupnost">
                 <option value="ANO" '.($radek["dostupnost"]=="ANO"?"selected":"").'>Ano</option>
                 <option value="NE" style="color: #005826;" '.($radek["dostupnost"]=="NE"?"selected":"").'>Ne</option>
              </select>            
            </td>
          </tr>
          <tr>
            <td>Cena je uvedena:</td>
            <td>
              <select name="cenaTyp">
                <option value="s DPH" '.( $radek["cenaSDph"]?'selected':'' ).'>s DPH</option>
                <option value="bez DPH" '.( $radek["cenaBezDph"]?'selected':'' ).'>bez DPH</option>                  
              </select>
              <input type="text" name="dph" value="'.$radek["dph"].'" size="1" style="font-size: 8pt; color: #333333;"> %            
            </td>
          </tr>
          <tr><td '.( zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_puvodni_cena_active","id=1")? '': 'style="text-decoration: line-through;"' ).'>
            Původní cena:</td><td>
             <input type="text" name="puvodni_cena" value="'.( $radek["puvodni_cena_s_dph"]? $radek["puvodni_cena_s_dph"]: $radek["puvodni_cena_bez_dph"] ).'" '.( zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_puvodni_cena_active","id=1")? '': 'disabled' ).'>
             </td>
          </tr>
        </table>
      <br>&nbsp;<br><strong>Obrázky </strong> [<span style="cursor: pointer; color: #000080;" onClick="easyAjaxPopup(\'Správa obrázků\', \'upravit.ajax.obrazky.php\', \'id='.$_GET["produkt"].'\');">Upravit</span>]<br>&nbsp;';
   $obrazky = explode(";", $radek["obrazky"]);
   Foreach($obrazky as $key=>$value){
           If($value) Echo '<img src="../zbozi/obrazky/'.$value.'" height="64px" border="0"> ';
   }
   Echo '</p><p>';
   echo '<table border="0">
         <tr><td colspan="3">&nbsp;</td></tr>    ';
   echo '<tr><td><b>Varianty</b></td><td>Název</td><td>Cena</td><td></td></tr>';
   echo '<tr><td>Varianta1: </td><td><input type="text" name="varianty[1]" value="'.$radek["varianta"].'"></td><td><input type="text" name="ceny[1]" value="'.( $radek["cenaSDph"]? $radek["cenaSDph"]: $radek["cenaBezDph"] ).'"><input type="hidden" name="idy[1]" value="'.$radek["id"].'"></td><td>'.( (mysql_num_rows($dotaz)>1)? '<img src="images/delete.png" style="cursor: pointer;" onClick="if(confirm(\'Opravdu chcete tuto variantu smazat?\'))document.location.href=\'?m=editace_zbozi&produkt='.$_GET["produkt"].'&n=zmena_zakladnich_udaju&id_varianta_smaz='.$radek["id"].'\';">': '').'</td></tr>';
   $i=2;
   while($radek2=mysql_fetch_array($dotaz)){
      echo '<tr><td>Varianta '.$i.': </td>
         <td><input type="hidden" name="idy['.$i.']" value="'.$radek2["id"].'">
             <input type="text" name="varianty['.$i.']" value="'.$radek2["varianta"].'"></td>
         <td><input type="text" name="ceny['.$i.']" value="'.( $radek2["cenaSDph"]? $radek2["cenaSDph"]: $radek2["cenaBezDph"] ).'"></td>
         <td><img src="images/delete.png" style="cursor: pointer;" onClick="if(confirm(\'Opravdu chcete tuto variantu smazat?\'))document.location.href=\'?m=editace_zbozi&produkt='.$_GET["produkt"].'&n=zmena_zakladnich_udaju&id_varianta_smaz='.$radek2["id"].'\';"></td></tr>
         ';
      $i=$i+1;
   }
   echo '<tr><td colspan="3">&nbsp;<br>
            <b>Nové varianty </b>
            '.inputyProNoveVarianty().'
         </td></tr>   
         </table>
         ';

   if($radek["akce"]){$akce_checked='checked';}
echo '
      <br>&nbsp;<br>Popis: <br>
        <script type="text/javascript">
        popis=\''.str_replace("'","´",str_replace("\n","",str_replace("\r","",$radek["popis"]))).'\';
        var oFCKeditor = new FCKeditor(\'popis\');
        oFCKeditor.BasePath = "fckeditor/";
        oFCKeditor.ToolbarSet = "Grafart_produkty";
        oFCKeditor.Value = popis;
        oFCKeditor.Height = "300px";
        oFCKeditor.Width = "600px";
        oFCKeditor.Create();
        </script>
        <noscript><textarea cols="60" rows="6" name="popis">'.$radek["popis"].'</textarea></noscript>

      <p><input type="submit" name="tlacitko" value="Odeslat">

      </form>
      <p>&nbsp;
      ';
   }
?>



<?php /* Seznam produktů */

/***************/
/* Stránkování */
/***************/
If( !$n AND !$produkt ){

    // Podmínky dotazu
    $filtr_vyrobce_sql = str_replace("*", "%", $filtr_vyrobce);
    $podminky = "CHAR_LENGTH(produkt)>0 AND LOWER(vyrobce) LIKE LOWER('%$filtr_vyrobce_sql%') ";
    If( $_GET["kategorie"] ){
        $podminky.= "AND kategorie=(SELECT kategorie FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[kategorie]) ";
    }
    If( $_GET["podkat1"] ){
        $podminky.= "AND podkat1=(SELECT podkat1 FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[podkat1]) ";
    }
    If( $_GET["podkat2"] ){
        $podminky.= "AND podkat2=(SELECT podkat2 FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[podkat2]) ";
    }
    If( $_GET["podkat3"] ){
        $podminky.= "AND podkat3=(SELECT podkat3 FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[podkat3]) ";
    }            
    
    $dotaz = "SELECT COUNT(DISTINCT produkt) FROM $CONF[sqlPrefix]zbozi WHERE $podminky ";
    $dotaz = mysql_query($dotaz);
    $radek = mysql_fetch_array($dotaz);
    $pocetProduktu = $radek["COUNT(DISTINCT produkt)"];

    $i = 0;
    $odkazy = '';
    $produkt_tmp = "";
    While($i<=$pocetProduktu){
          $i++;
          If( $i%10 == 1 ){
              If( $_GET["id_min"]==$i ){
                  $style = 'color: red; text-decoration: none;';
                }Else{
                  $style = '';
              }
              $odkazy.= '<a href="?m=editace_zbozi&id_min='.$i.'&filtr_vyrobce='.$filtr_vyrobce.'&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'&podkat2='.$_GET["podkat2"].'&podkat3='.$_GET["podkat3"].'" style="'.$style.'">'.ceil($i/10).'</a>&nbsp;&nbsp;|&nbsp; ';
          }
    }
    $odkazy = '<div style="text-align: center; font-size: 80%; margin: 24px 0px 12px 0px;">'.str_replace("|&nbsp; &konec;","",$odkazy."&konec;").'</div>';
}




/*******************/
/* Seznam produktů */
/*******************/
If( !$n AND !$produkt ){
  
    If( !$_GET["produkt"] ){
        echo '<form action="" method="post">
              <center>
              Vyhledávání: <input type="text" name="vyhledavani" value="'.$_POST["vyhledavani"].'"> 
              <input type="submit" name="hledej" value="Hledat">
              </center>
              </form> 
              &nbsp;<br>';
    }
    
    If( !$_GET["kategorie"] and !$_POST["vyhledavani"] ){
        Echo '<script type="text/javascript">
              function plusMinus(imgId){
                       if( document.getElementById(imgId).src.search(/plus.png/)>1 ){
                           document.getElementById(imgId).src=\'images/minus.jpg\';
                       }else{
                           document.getElementById(imgId).src=\'images/plus.png\';
                       }
              }
              </script>
              Vyberte si prosím kategorii, ve které chcete editovat zboží. 
              <p>
              <table border="0" align="center"><tr><td>
                '.stromKategorii().'
              </td></tr></table>&nbsp;<br> ';
    }
    Else{
        // Změna váhy produktů
        If( $_POST["vahyProduktu"] ){
            Foreach($_POST["vahyProduktu"] as $key=>$value){
                    Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET vahaProduktu=$value WHERE id=$key");
            }
        }
        // Podmínky dotazu
        $filtr_vyrobce = $_POST["filtr_vyrobce"]? $_POST["filtr_vyrobce"]: $_GET["filtr_vyrobce"]?$_GET["filtr_vyrobce"]:'*';
        $filtr_vyrobce_sql = str_replace("*", "%", $filtr_vyrobce);
        $podminky = "CHAR_LENGTH(produkt)>0 AND LOWER(vyrobce) LIKE LOWER('%$filtr_vyrobce_sql%') ";
        If( $_GET["kategorie"] ){
            $podminky.= "AND kategorie=(SELECT kategorie FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[kategorie]) ";
        }
        If( $_GET["podkat1"] ){
            $podminky.= "AND podkat1=(SELECT podkat1 FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[podkat1]) ";
        }
        If( $_GET["podkat2"] ){
            $podminky.= "AND podkat2=(SELECT podkat2 FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[podkat2]) ";
        }
        If( $_GET["podkat3"] ){
            $podminky.= "AND podkat3=(SELECT podkat3 FROM $CONF[sqlPrefix]zbozi WHERE id=$_GET[podkat3]) ";
        }
        if( $_POST["vyhledavani"] ){
            $podminky.= "AND (LOWER(cislo) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(kategorie) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(popisKategorie) LIKE LOWER('%$_POST[vyhledavani]%') OR
                              LOWER(podkat1) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(podkat2) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(podkat3) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(produkt) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(varianta) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(vyrobce) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(prilohy) LIKE LOWER('%$_POST[vyhledavani]%') OR 
                              LOWER(popis) LIKE LOWER('%$_POST[vyhledavani]%') 
                              ) ";
        }         
          
        $id_min = $_GET["id_min"]?$_GET["id_min"]:1; $id_min--;
        $dotaz_txt="SELECT * FROM $CONF[sqlPrefix]zbozi WHERE $podminky GROUP BY produkt ORDER BY vahaProduktu DESC";  //LIMIT $id_min,10
        $dotaz=mysql_query($dotaz_txt) or die(mysql_error()."<br>$dotaz_txt");
           
        //Echo $odkazy;
             
      
        /* -- Zobrazení seznamu produktů po řádcích -- */
        //Echo '<span style="font-size: 10pt; font-weight: bold;"><a href="?m=editace_zbozi" style="font-weight: bold; color: #2B5202;">Přehled kategorií</a><span style="font-weight: bold; color: #008000;">:</span> '.cestaKategorie($_GET["kategorie"], $_GET["podkat1"], $_GET["podkat2"], $_GET["podkat3"]).'</span>';
        If( mysql_num_rows($dotaz)>0 ){
            $i = 1;
            $id_min = $_GET["id_min"]?$_GET["id_min"]:1;
            Echo '<p>
                  <form action="" method="post">
                  <table width="100%" border="0">
                    <tr>
                      <td bgcolor="" style="width: 5px;"></td>
                      <td bgcolor="#2B5202" width="16"></td>
                      <td bgcolor="#2B5202" width="*" style="color: #FFFFFF;">&nbsp;<b>Název produktu</b></td>
                      <td bgcolor="#2B5202" width="128" align="right" style="color: #FFFFFF;"><b>Cena</b>&nbsp;</td>
                      <td bgcolor="#2B5202" width="64" style="color: #FFFFFF;">&nbsp;</td>
                    </tr>';
            while($radek=mysql_fetch_array($dotaz)){
                  $bgcolor = ($i%2==1)? '#DFDFDF': '#F0F0FF';
                  //obrázek
                  $obrazek = ereg_replace("^;+", "", $radek["obrazky"]);
                  $obrazek = preg_replace("|^([^;]*).*$|", "$1", $radek["obrazky"]);
                  //title info
                  $infoProdukt = str_replace(array('"',"'"), "´", $radek["produkt"]);
                  $infoPopis = substr( strip_tags(str_replace(array('"',"'"), "´", $radek["popis"])), 0, 160);        
                  $titleInfo = "<img src=../zbozi/obrazky/$obrazek align=left width=64><b>$infoProdukt</b><br>$infoPopis<div class=clear> </div>";
                  echo '<script type="text/javascript">
                        function smazProdukt(id,produkt){
                                if(confirm(produkt+\'\\n\\nOpravdu chcete tento produkt smazat?\')){location.href=\'?m=editace_zbozi&zbozi_smaz=\'+id;}
                        }
                        </script>
                        <tr>
                          <td style="text-align: right;">
                            <input type="text" name="vahyProduktu['.$radek["id"].']" value="'.$radek["vahaProduktu"].'" size="1" style="border: 1px solid #DFDFDF; color: #666666; margin: 0px;">
                          </td>
                          <td bgcolor="'.$bgcolor.'"><img src="../zbozi/obrazky/'.$obrazek.'" width="16" height="16" title="'.$titleInfo.'" onMouseMove="cotojatko(this);"></td>
                          <td bgcolor="'.$bgcolor.'" onClick="popupMenu(\'<a href=?m=editace_zbozi&n=zmena_zakladnich_udaju&produkt='.$radek["id"].'>Upravit</a><a href=../index.php?a=produkty&produkt='.$radek["id"].' target=blank>Náhled v e-shopu</a><a href=´´javascript:smazProdukt('.$radek["id"].',\\\''.$radek["produkt"].'\\\');´´>Smazat</a>\');" style="cursor: pointer;">'.$radek["produkt"].'</td>
                          <td bgcolor="'.$bgcolor.'" align="right">'.($radek["cenaSDph"]? hezkaCena($radek["cenaSDph"]).' <span style="font-size: 7pt; color: #333333;">s DPH</span>': hezkaCena($radek["cenaBezDph"]).' <span style="font-size: 7pt; color: #333333;">s DPH</span>' ).'</td>
                          <td bgcolor="'.$bgcolor.'" align="right" style="padding-right: 2px">
                            <a href="?m=editace_zbozi&n=zmena_zakladnich_udaju&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'&podkat2='.$_GET["podkat2"].'&produkt='.$radek["id"].'"><img src="images/edit16.png" border="0"></a>
                            <a href="../index.php?a=produkty&produkt='.$radek["id"].'" target="blank"><img src="images/preview.png" width="16" height="16" border="0"></a>
                            <img src="images/delete.png" border="0" style="cursor: pointer;" onClick="if(confirm(\''.$radek["produkt"].'\\n\\nOpravdu chcete tento produkt smazat?\')){location.href=\'?m=editace_zbozi&zbozi_smaz='.$radek["id"].'\';}">
                          </td>
                        </tr>';
                  $i++;
                  $produkt_tmp=$produkt_tmp."<ˇ>".$radek["produkt"]."<ˇ>";
            }
            Echo '</table>
                  &nbsp;
                  <div style="text-align: right;"><input type="submit" name="odeslat" value="Změnit váhu produktů"></div>
                  </form>';
        }
        Else Echo '<p>Žádné zboží v této kategorii.';
    }
    
    
    /* Info */
echo '<div style="font-size: 10pt; font-weight: bold; margin: 20px 0px 0px 0px; position: relative; top: -16px;">
        <span style="font-weight: bold; color: #2B5202;">Produktů v e-shopu: </span> 
        '.zjisti_z("$CONF[sqlPrefix]zbozi", "COUNT(DISTINCT produkt)", "1=1").'
      </div>
     ';    
}
?>



<?php Include 'grafika_kon.inc'; ?>
