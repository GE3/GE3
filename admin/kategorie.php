<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/kategorie.png"></td>
                  <td width="616" colspan="2"><font face="Arial" size="2">
                  <span style="font-weight: 700">Kategorie</span></font><font face="Arial" style="font-size: 8pt"><br>
                  </font><font face="Arial"><span style="font-size: 8pt">
                  Úpravy, přejmenování a mazání kategorií a podkategorií.</span></font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>
<div style="position: relative;">


<?php /* Vytvoření */
If( $_POST["pridat_kat"] AND $_POST["novyNazev"] ){
    // rows
    $rows = 'kategorie';
    $rows.= ',popisKategorie';    
    $rows.= $_POST["kategorie"]?',podkat1':'';
    $rows.= $_POST["podkat1"]?',podkat2':'';
    $rows.= $_POST["podkat2"]?',podkat3':'';

    // values
    If( $_POST["kategorie"] ){
        $values = "'".str_replace("\"","´",$_POST["kategorie"])."'";
      }Else{
        $values = "'".str_replace("\"","´",$_POST["novyNazev"])."'";
    }
    $values.= ",'$_POST[popisKategorie]'";  
    If( $_POST["podkat1"] ){
        $values.= ",'".str_replace("\"","´",$_POST["podkat1"])."'";
      }ElseIf($_POST["kategorie"]){
        $values.= ",'".str_replace("\"","´",$_POST["novyNazev"])."'";
    }
    If( $_POST["podkat2"] ){
        $values.= ",'".str_replace("\"","´",$_POST["podkat2"])."','".str_replace("\"","´",$_POST["novyNazev"])."'";
      }ElseIf($_POST["podkat1"]){
        $values.= ",'".str_replace("\"","´",$_POST["novyNazev"])."'";
    }

    // obrázek
    if( $_FILES["obrazek"]["name"] ){
        move_uploaded_file($_FILES["obrazek"]["tmp_name"], "../zbozi/obrazky/".urlText($_FILES["obrazek"]["name"]));
        if( is_file("../zbozi/obrazky/".urlText($_FILES["obrazek"]["name"])) ){
            if( $_POST["podkat2"] ){
                $rows.= ",obrazekPodkat3";
                $values.= ",'".urlText($_FILES["obrazek"]["name"])."'";
            }
            elseif( $_POST["podkat1"] ){
                $rows.= ",obrazekPodkat2";
                $values.= ",'".urlText($_FILES["obrazek"]["name"])."'";
            }
            elseif( $_POST["kategorie"] ){
                $rows.= ",obrazekPodkat1";
                $values.= ",'".urlText($_FILES["obrazek"]["name"])."'";
            }
            else{
                $rows.= ",obrazekKategorie";
                $values.= ",'".urlText($_FILES["obrazek"]["name"])."'";
            }                        
        }
    }

    If( Mysql_query("INSERT INTO $CONF[sqlPrefix]zbozi($rows) VALUES ($values) ") ){
        Echo '<div style="border: 2px solid #008000; background-color: #EEFFEE; text-align: center; margin: 4px 6px 4px 6px; padding: 2px 4px 2px 4px; ">
              <b>Kategorie úspěšně vytvořena.</b>
              </div>
              ';
      }Else{
        Echo '<div style="border: 2px solid #800000; background-color: #FFEEEE; text-align: center; margin: 4px 6px 4px 6px; padding: 2px 4px 2px 4px; ">
              Chyba při editaci databáze: <p>'.mysql_error().'<p>'."INSERT INTO $CONF[sqlPrefix]zbozi($rows) VALUES ($values) ".'
              </div>
              ';
    }
}
?>
<?php /* Změna nazvu */
If( $_POST["zmenit_kat"] AND $_POST["staraKategorie"] ){
    $staraKategorie = $_POST["staraKategorie"];
    $staraPodkat1 = $_POST["staraPodkat1"];
    $staraPodkat2 = $_POST["staraPodkat2"];
    $staraPodkat3 = $_POST["staraPodkat3"];
    $kategorie = str_replace("\"","´",$_POST["kategorie"]);
    $podkat1 = str_replace("\"","´",$_POST["podkat1"]);
    $podkat2 = str_replace("\"","´",$_POST["podkat2"]);
    $podkat3 = str_replace("\"","´",$_POST["podkat3"]);

    $set = $kategorie?"kategorie='$kategorie'":'';
    $set = $podkat1?"podkat1='$podkat1'":$set;
    $set = $podkat2?"podkat2='$podkat2'":$set;
    $set = $podkat3?"podkat3='$podkat3'":$set;
    $set.= ", popisKategorie='$_POST[popisKategorie]'";  

    $where = "kategorie='$staraKategorie' ";
    $where .= $staraPodkat1?"AND podkat1='$staraPodkat1' ":'';
    $where .= $staraPodkat2?"AND podkat2='$staraPodkat2' ":'';
    $where .= $staraPodkat3?"AND podkat3='$staraPodkat3' ":'';

    
    if( $_FILES["obrazek"]["name"] ){
        move_uploaded_file($_FILES["obrazek"]["tmp_name"], "../zbozi/obrazky/".urlText($_FILES["obrazek"]["name"]));
        if( is_file("../zbozi/obrazky/".urlText($_FILES["obrazek"]["name"])) ){
            if($_POST["staraPodkat3"]) mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET obrazekPodkat3='".urlText($_FILES["obrazek"]["name"])."' WHERE $where ");
            elseif($_POST["staraPodkat2"]) mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET obrazekPodkat2='".urlText($_FILES["obrazek"]["name"])."' WHERE $where ");
            elseif($_POST["staraPodkat1"]) mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET obrazekPodkat1='".urlText($_FILES["obrazek"]["name"])."' WHERE $where ");
            elseif($_POST["staraKategorie"]) mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET obrazekKategorie='".urlText($_FILES["obrazek"]["name"])."' WHERE $where ");
        }
    }

    If( Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET $set WHERE $where ") ){
        Echo '<div style="border: 2px solid #008000; background-color: #EEFFEE; text-align: center; margin: 4px 6px 4px 6px; padding: 2px 4px 2px 4px; ">
              <b>Změna kategorie úspěšně provedena.</b>
              </div>
              ';
      }Else{
        Echo '<div style="border: 2px solid #800000; background-color: #FFEEEE; text-align: center; margin: 4px 6px 4px 6px; padding: 2px 4px 2px 4px; ">
              Chyba při editaci databáze: <p>'.mysql_error().'<p>'."UPDATE $CONF[sqlPrefix]zbozi SET $set WHERE $where ".'
              </div>
              ';
    }
}
?>
<?php /* Smazání */
If( $_GET["kategorieSmaz"] OR $_GET["podkat1Smaz"] OR $_GET["podkat2Smaz"] OR $_GET["podkat3Smaz"] ){
    If( $_GET["podkat3Smaz"] ){
        $kategorie = zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id=".$_GET["podkat3Smaz"]);
        $podkat1 = zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id=".$_GET["podkat3Smaz"]);
        $podkat2 = zjisti_z("$CONF[sqlPrefix]zbozi","podkat2","id=".$_GET["podkat3Smaz"]);
        $podkat3 = zjisti_z("$CONF[sqlPrefix]zbozi","podkat3","id=".$_GET["podkat3Smaz"]);
        $dotaz = "DELETE FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' AND podkat2='$podkat2' AND podkat3='$podkat3' ";
    }
    If( $_GET["podkat2Smaz"] ){
        $kategorie = zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id=".$_GET["podkat2Smaz"]);
        $podkat1 = zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id=".$_GET["podkat2Smaz"]);
        $podkat2 = zjisti_z("$CONF[sqlPrefix]zbozi","podkat2","id=".$_GET["podkat2Smaz"]);
        $dotaz = "DELETE FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' AND podkat2='$podkat2' ";
    }
    If( $_GET["podkat1Smaz"] ){
        $kategorie = zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id=".$_GET["podkat1Smaz"]);
        $podkat1 = zjisti_z("$CONF[sqlPrefix]zbozi","podkat1","id=".$_GET["podkat1Smaz"]);
        $dotaz = "DELETE FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' ";
    }
    If( $_GET["kategorieSmaz"] ){
        $kategorie = zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id=".$_GET["kategorieSmaz"]);
        $dotaz = "DELETE FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' ";
    }

    If( Mysql_query($dotaz) ){
        Echo '<div style="border: 2px solid #008000; background-color: #EEFFEE; text-align: center; margin: 4px 6px 4px 6px; padding: 2px 4px 2px 4px; ">
              <b>Kategorie úspěšně smazána. [<a href="index.php?m=kategorie">OK</a>]</b>
              </div>
              ';
      }Else{
        Echo '<div style="border: 2px solid #800000; background-color: #FFEEEE; text-align: center; margin: 4px 6px 4px 6px; padding: 2px 4px 2px 4px; ">
              Chyba při mazání dat z databáze: <p>'.mysql_error().'<p>'.$dotaz.'
              </div>
              ';
    }
}
?>
<?php /* Změna pořadí */
If( $_POST["zmenitPoradi"] ){
    $i = 0;
    Foreach($_POST["vaha"] as $key=>$value){
            If( $key ){
                $value = $value? $value: '0';
                If( Mysql_query("UPDATE $CONF[sqlPrefix]zbozi x, $CONF[sqlPrefix]zbozi y SET x.vaha=$value WHERE x.kategorie=y.kategorie AND y.id=$key") ){
                    $i++;
                }
            }
    }
    Echo '<div style="border: 2px solid #008000; background-color: #EEFFEE; text-align: center; margin: 4px 6px 4px 6px; padding: 2px 4px 2px 4px; ">
          <b>Kategorie úspěšně upraveny.</b>
          </div>
          ';
}

/* Změna viditelnosti */
if( $_GET["kategorieAktivni"] and !$_POST["zmenitPoradi"] ){
    $kategorie = zjisti_z("$CONF[sqlPrefix]zbozi", "kategorie", "id='$_GET[kategorieAktivni]'");
    $aktivni = zjisti_z("$CONF[sqlPrefix]zbozi", "kategorieAktivni", "id='$_GET[kategorieAktivni]'");
    $aktivni = $aktivni=='ano'? 'ne': 'ano';
    Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET kategorieAktivni='$aktivni' WHERE kategorie='$kategorie'");
}
if( $_GET["podkat1Aktivni"] and !$_POST["zmenitPoradi"] ){
    $kategorie = zjisti_z("$CONF[sqlPrefix]zbozi", "kategorie", "id='$_GET[podkat1Aktivni]'");
    $podkat1 = zjisti_z("$CONF[sqlPrefix]zbozi", "podkat1", "id='$_GET[podkat1Aktivni]'");
    $aktivni = zjisti_z("$CONF[sqlPrefix]zbozi", "podkat1Aktivni", "id='$_GET[podkat1Aktivni]'");
    $aktivni = $aktivni=='ano'? 'ne': 'ano';
    Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET podkat1Aktivni='$aktivni' WHERE kategorie='$kategorie' AND podkat1='$podkat1'");
}
?>



<h3 style="margin-bottom: 12px;">Strom kategorií <img src="images/addkat.png" height="16px" onClick="easyAjax('ajax.php/ajax.new_kat.php','tabulka=<?php Echo "$CONF[sqlPrefix]zbozi"; ?>','divObsah');" style="cursor: pointer; -moz-opacity: 0.8;"></h3>
<script type="text/javascript">
function plusMinus(imgId){
         if( document.getElementById(imgId).src=='http://www.gastrobumerang.cz/admin/images/plus.png' ){
             document.getElementById(imgId).src='images/minus.jpg';
         }else{
             document.getElementById(imgId).src='images/plus.png';
         }
}
</script>
<form method="post" action="">
<?php /* Zobrazení kategorií */
$dotazKategorie = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi GROUP BY kategorie ORDER BY vaha DESC, kategorie ASC");
// Zobrazení kategorií
Echo '<div style="font-size: 10pt; padding-left: 6px;">';
While($radekKategorie = mysql_fetch_array($dotazKategorie)){
      If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat1", "kategorie='".$radekKategorie["kategorie"]."' ORDER BY podkat1 DESC") ){  //Příprava vzhledu a JS funkcí
          $onClick = 'onClick="ukazSkryj(\'divPodkat1_'.$radekKategorie["id"].'\'); plusMinus(\'imgPlusKategorie'.$radekKategorie["id"].'\');"';
          $style = 'style="cursor: pointer;"';
          $obrazek = '<img id="imgPlusKategorie'.$radekKategorie["id"].'" src="images/plus.png">';
        }
      Else{ $onClick = ''; $style = ''; $obrazek = '<span style="font-size: 1px; padding-right: 8px;">&nbsp;</span>'; }
      $ikony = '<img src="images/edit16.png" onClick="easyAjax(\'ajax.php/ajax.edit_kat.php\',\'tabulka='."$CONF[sqlPrefix]zbozi".'&kategorie='.$radekKategorie["id"].'\',\'divObsah\');" height="16px" style="cursor: pointer; -moz-opacity: 0.7;">
                <img src="images/addkat.png" height="16px" onClick="easyAjax(\'ajax.php/ajax.new_kat.php\',\'tabulka='."$CONF[sqlPrefix]zbozi".'&kategorie='.$radekKategorie["id"].'\',\'divObsah\');" style="cursor: pointer; -moz-opacity: 0.7;">
                <img src="images/'.( $radekKategorie["kategorieAktivni"]=='ne'? 'oko-no.png': 'oko-yes.png' ).'" height="16px" onClick="location.href=\'?m=kategorie&kategorieAktivni='.$radekKategorie["id"].'\';" style="cursor: pointer; -moz-opacity: 0.7;">
                <img src="images/delete.png" onClick="if(confirm(\'Opravdu chcete tuto kategorii se vším, co obsahuje, smazat?\'))location.href=\'index.php?m=kategorie&kategorieSmaz='.$radekKategorie["id"].'\';" height="16px" style="cursor: pointer; -moz-opacity: 0.7;">
                ';
      Echo '<div style="margin-top: 4px;">
              <span '.$onClick.' '.$style.'>
                '.$obrazek.' 
                <input type="text" name="vaha['.$radekKategorie["id"].']" value="'.$radekKategorie["vaha"].'" style="font-size: 60%; color: #666666;" size="1">
                '.$radekKategorie["kategorie"].'
              </span> &nbsp; 
              '.$ikony.'
            </div>';  //Zobrazení názvu s odkazy pro změnu atd.

      // Zobrazení podkategorií
      If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat1", "kategorie='".$radekKategorie["kategorie"]."' ORDER BY podkat1 DESC") ){
          $dotazPodkat1 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".$radekKategorie["kategorie"]."' AND podkat1!='' GROUP BY podkat1 ORDER BY podkat1 ");
          Echo '<div id="divPodkat1_'.$radekKategorie["id"].'" style="font-size: 8pt; margin-left: 16px; display: none;">';
          While($radekPodkat1 = Mysql_fetch_array($dotazPodkat1)){
                If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat2", "kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' ORDER BY podkat2 DESC") ){  //Příprava vzhledu a JS funkcí
                    $onClick = 'onClick="ukazSkryj(\'divPodkat2_'.$radekPodkat1["id"].'\'); plusMinus(\'imgPlusPodkat1'.$radekPodkat1["id"].'\');"';
                    $style = 'style="cursor: pointer; "';
                    $obrazek = '<img id="imgPlusPodkat1'.$radekPodkat1["id"].'" src="images/plus.png">';
                  }
                Else{ $onClick = ''; $style = ''; $obrazek = '<span style="font-size: 1px; padding-right: 8px;">&nbsp;</span>'; }
                $ikony = '<img src="images/edit16.png" onClick="easyAjax(\'ajax.php/ajax.edit_kat.php\',\'tabulka='."$CONF[sqlPrefix]zbozi".'&kategorie='.$radekKategorie["id"].'&podkat1='.$radekPodkat1["id"].'\',\'divObsah\');" height="16px" style="cursor: pointer; -moz-opacity: 0.6;">
                          <img src="images/addkat.png" height="16px" onClick="easyAjax(\'ajax.php/ajax.new_kat.php\',\'tabulka='."$CONF[sqlPrefix]zbozi".'&podkat1='.$radekPodkat1["id"].'\',\'divObsah\');" style="cursor: pointer; -moz-opacity: 0.6;">
                          <img src="images/'.( $radekPodkat1["podkat1Aktivni"]=='ne'? 'oko-no.png': 'oko-yes.png' ).'" height="16px" onClick="location.href=\'?m=kategorie&podkat1Aktivni='.$radekPodkat1["id"].'\';" style="cursor: pointer; -moz-opacity: 0.7;">
                          <img src="images/delete.png" onClick="if(confirm(\'Opravdu chcete tuto kategorii se vším, co obsahuje, smazat?\'))location.href=\'index.php?m=kategorie&podkat1Smaz='.$radekPodkat1["id"].'\'" height="16px" style="cursor: pointer; -moz-opacity: 0.6;">
                          ';
                Echo '<span '.$onClick.' '.$style.'>
                        '.$obrazek.' 
                        '.$radekPodkat1["podkat1"].'
                      </span> &nbsp; '.$ikony.'<br>';  //Zobrazení názvu s odkazy pro změnu atd.

                // Zobrazení podkategorií 2
                If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat2", "kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' ORDER BY podkat2 DESC") ){
                    $dotazPodkat2 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' AND podkat2!='' GROUP BY podkat2 ORDER BY podkat2 ");
                    Echo '<div id="divPodkat2_'.$radekPodkat1["id"].'" style="font-size: 8pt; margin-left: 16px; display: none; ">';
                    While($radekPodkat2 = mysql_fetch_array($dotazPodkat2)){
                          If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat3", "kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' AND podkat2='".$radekPodkat2["podkat2"]."' AND podkat3!='' ORDER BY podkat3 DESC") ){  //Příprava vzhledu a JS funkcí
                              $onClick = 'onClick="ukazSkryj(\'divPodkat3_'.$radekPodkat2["id"].'\'); plusMinus(\'imgPlusPodkat2'.$radekPodkat2["id"].'\');"';
                              $style = 'style="cursor: pointer; "';
                              $obrazek = '<img id="imgPlusPodkat2'.$radekPodkat2["id"].'" src="images/plus.png">';
                            }
                          Else{ $onClick = ''; $style = ''; $obrazek = '<span style="font-size: 1px; padding-right: 8px;">&nbsp;</span>'; }
                          $ikony = '<img src="images/edit16.png" onClick="easyAjax(\'ajax.php/ajax.edit_kat.php\',\'tabulka='."$CONF[sqlPrefix]zbozi".'&kategorie='.$radekKategorie["id"].'&podkat1='.$radekPodkat1["id"].'&podkat2='.$radekPodkat2["id"].'\',\'divObsah\');" height="16px" style="cursor: pointer; -moz-opacity: 0.5;">
                                    <img src="images/delete.png" onClick="if(confirm(\'Opravdu chcete tuto kategorii se vším, co obsahuje, smazat?\'))location.href=\'index.php?m=kategorie&podkat2Smaz='.$radekPodkat2["id"].'\'" height="16px" style="cursor: pointer; -moz-opacity: 0.5;">
                                    <img src="images/addkat.png" height="16px" onClick="easyAjax(\'ajax.php/ajax.new_kat.php\',\'tabulka='."$CONF[sqlPrefix]zbozi".'&podkat2='.$radekPodkat2["id"].'\',\'divObsah\');" style="cursor: pointer; -moz-opacity: 0.5;">
                                    ';
                          Echo '<span '.$onClick.' '.$style.'>'.$obrazek.' '.$radekPodkat2["podkat2"].'</span> &nbsp; '.$ikony.'<br>';  //Zobrazení názvu s odkazy pro změnu atd.

                          // Zobrazení podkategorií 3
                          If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat3", "kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' AND podkat2='".$radekPodkat2["podkat2"]."' ORDER BY podkat3 DESC") ){
                              $dotazPodkat3 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' AND podkat2='".$radekPodkat2["podkat2"]."' AND podkat3!='' GROUP BY podkat3 ORDER BY podkat3 ");
                              Echo '<div id="divPodkat3_'.$radekPodkat2["id"].'" style="font-size: 8pt; margin-left: 16px; display: none;">';
                              While($radekPodkat3 = mysql_fetch_array($dotazPodkat3)){
                                    $ikony = '<img src="images/edit16.png" onClick="easyAjax(\'ajax.php/ajax.edit_kat.php\',\'tabulka='."$CONF[sqlPrefix]zbozi".'&kategorie='.$radekKategorie["id"].'&podkat1='.$radekPodkat1["id"].'&podkat2='.$radekPodkat2["id"].'&podkat3='.$radekPodkat3["id"].'\',\'divObsah\');" height="16px" style="cursor: pointer; -moz-opacity: 0.4;"> <img src="images/delete.png" onClick="if(confirm(\'Opravdu chcete tuto kategorii se vším, co obsahuje, smazat?\'))location.href=\'index.php?m=kategorie&podkat3Smaz='.$radekPodkat3["id"].'\'" height="16px" style="cursor: pointer; -moz-opacity: 0.4;"> ';
                                    Echo '<span style="font-size: 1px; padding-right: 8px;">&nbsp;</span> '.$radekPodkat3["podkat3"].' &nbsp; '.$ikony.'<br>';
                              }
                              Echo '</div>';
                          }

                    }
                    Echo '</div>';
                }

          }
          Echo '</div>';
      }

}
Echo '</div>';
?>
<p style="text-align: left;"><input type="submit" name="zmenitPoradi" value="Změnit váhu kategorií"></p>
</form>



<div id="divObsah" style="position: absolute; top: 52px; right: 0px;">
</div>


</div>
<p>&nbsp;</p>
<?php Include 'grafika_kon.inc'; ?>
