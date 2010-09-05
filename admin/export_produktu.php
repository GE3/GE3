<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/excel.png" width="48" height="48"></td>
                  <td width="335"><font face="Arial" size="2">
                  <span style="font-weight: 700">Export produktů</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Umožňuje exportovat seznam produktů do formátu pro Microsoft Excel.</font></td>
                </tr>
              </table>
              
              <script type="text/javascript">
              posledniDiv=\'\';
              function ukazSkryjProdukty(divId){
                       if( posledniDiv!=\'\' ){
                           ukazSkryj(posledniDiv);
                       }
                       ukazSkryj(divId);
                       posledniDiv=divId;
              }
              posledniKategorie=\'\';
              function ukazSkryjNazevKategorie(divId){
                       /*if( posledniKategorie!=\'\' ){
                           document.getElementById(\'divNazevKategorie\'+posledniKategorie).style.backgroundColor = \'#DFDFDF\';
                       }
                       document.getElementById(\'divNazevKategorie\'+divId).style.backgroundColor = \'#F0F0FF\';*/
                       posledniKategorie=divId;              
              }              
              </script>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<form action="" method="post" style="margin: 0px 0px 0px 0px;">
<b>Exportovat pouze kategorie/zboží:</b>
<div style="clear: both;"></div>
<?php /* Zobrazení */
/* Kategorie */
$autoScript = '';
Echo '<div style="padding: 2px 2px 2px 2px; float: left;">';
$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL GROUP BY kategorie ORDER BY kategorie ASC ");
While($radek=mysql_fetch_array($dotaz)){
      Echo '<div id="divNazevKategorie'.$radek["id"].'" style="position: relative; left: 2px;">
              <input type="checkbox" onChange="fCheckZbozi'.$radek["id"].'();" id="checkKategorie'.$radek["id"].'" name="kategorieCheck'.$radek["id"].'" value="'.$radek["id"].'" style="position: relative; top: 2px; _top: 1px;">
              <span onClick="ukazSkryjProdukty(\'divProdukty'.$radek["id"].'\'); ukazSkryjNazevKategorie('.$radek["id"].');" style="cursor: pointer; text-decoration: underline;">
                '.$radek["kategorie"].'
              </span>
            </div>
            ';
      $autoScript.= 'document.getElementById(\'checkKategorie'.$radek["id"].'\').checked=\'checked\'; fCheckZbozi'.$radek["id"].'(); ';
}
Echo '</div>';


/* Zboží v kategorii */
$dotaz=Mysql_query("SELECT kategorie,id FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL GROUP BY kategorie ORDER BY kategorie ASC ");
While($radek=mysql_fetch_array($dotaz)){
      //Právě načítaná kategorie kategorie
      Echo '<div id="divProdukty'.$radek["id"].'" style="display: none; padding-left: 2px;">';
      $dotaz2=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".$radek["kategorie"]."' GROUP BY produkt ORDER BY kategorie ASC ");
      While($radek2=mysql_fetch_array($dotaz2)){
            If( $radek2["produkt"] ){
                Echo '<div style="width: 196px; height: 20px; float: left; overflow: hidden;">
                        <input type="checkbox" id="checkZbozi'.$radek2["id"].'" name="zboziCheck['.$radek2["id"].']" value="'.$radek2["id"].'" style="position: relative; top: 2px;">
                        '.$radek2["produkt"].'
                      </div>
                      ';
            }
      }
      Echo '  <div style="width: 100%; clear: both;"></div>
            </div>';
}

Echo '<div style="clear: both;"></div>';


/* Javascripty pro check zboží */
Echo '<script type="text/javascript">';
$dotaz=Mysql_query("SELECT kategorie,id FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL GROUP BY kategorie ORDER BY kategorie ASC ");
While($radek=mysql_fetch_array($dotaz)){
      Echo 'function fCheckZbozi'.$radek["id"].'(){';
      $dotaz2=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE produkt IS NOT NULL AND kategorie='".$radek["kategorie"]."' GROUP BY produkt ORDER BY id ASC ");
      While($radek2=mysql_fetch_array($dotaz2)){
            If( $radek2["produkt"] ){           
                Echo 'document.getElementById(\'checkZbozi'.$radek2["id"].'\').checked=document.getElementById(\'checkKategorie'.$radek["id"].'\').checked;
                      ';
            }
      }
      Echo '}';
}
Echo '</script>
      <script type="text/javascript">
      '.$autoScript.'
      </script>';
?>



<?php /* Export do csv */
If( $_POST["submit"] ){

    $csv = "Číslo;Název zboží;Skladem;Cena\r\n";
    Foreach($_POST["zboziCheck"] as $key=>$value){
            $radek = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE id=$value LIMIT 1") );
            $csv.= "$radek[cislo];$radek[produkt];".( $radek["dostupnost"]?$radek["dostupnost"]:"NE" ).";".str_replace(".",",", $radek["cenaSDph"]?$radek["cenaSDph"]:($radek["cenaBezDph"]*(1+$radek["dph"]/100)) )."\r\n";            
    }
    $os = fopen("export/produkty.csv", "w");
    fwrite($os, iconv("utf-8","windows-1250",$csv) );
    fclose($os);

    // Zobrazení výsledků
    If( !mysql_error() ){
        Echo '
              <div style="margin-top: 12px;"><b>Zboží bylo úspěšně vyexportováno do souboru <a href="export/produkty.csv">produkty.csv</a>.</b></div>
              &nbsp;<br>
              ';
    }
}
?>



<div style="width: 100%; border: 0px solid #008000; padding: 4px 6px 2px 2px; clear: both; text-align: right;">
  <input type="submit" name="submit" value="Exportovat">
</div>
</form>



<?php Include 'grafika_kon.inc'; ?>
