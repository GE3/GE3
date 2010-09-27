<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/formular_icon.png" width="48" height="48"></td>
                  <td width="616" colspan="2"><font face="Arial" size="2">
                  <span style="font-weight: 700">Formuláře</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Tento modul slouží k vytváření formulářů, které jsou po vyplnění Vašimi klienty odesílány na Vámi zvolený e-mail. </font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>
 <?php
 if($_GET["akce"]=="editovat"){
   if($_GET["smaz"]){
        $polozkaM=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_items WHERE id='$_GET[smaz]'"));
        $smazPolozku = mysql_query("DELETE FROM ".$CONF["sqlPrefix"]."mailforms_items WHERE id = '$_GET[smaz]' LIMIT 1");
        $dotazPolozky=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_items WHERE mailform='$polozkaM[mailform]'");
            While($polozkaPosun=mysql_fetch_array($dotazPolozky)){
                 if($polozkaPosun["poradi"]>$polozkaM["poradi"]){
                   $polozkaPosun["poradi"]=$polozkaPosun["poradi"]-1;
                   $updatePoradi=Mysql_query("UPDATE $CONF[sqlPrefix]mailforms_items SET poradi='$polozkaPosun[poradi]' WHERE id='$polozkaPosun[id]'");
                 }
            }
   }
   if($_POST["vytvorit"]){
       $m_polozkaId=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_items ORDER BY id DESC LIMIT 1"));
       $typ=$_POST["typ"];
       $pocet = mysql_result(mysql_query("SELECT COUNT(*) FROM $CONF[sqlPrefix]mailforms_items WHERE mailform='$_GET[id]' AND typ!='polozka'  AND typ!='zaskrtavatko'"), 0);
       $poradi = $pocet+1;

       if($typ=="vyber"){
            $typ=$_POST["vybernik"];
            if($typ=="moznosti"){
                $vytvor_item=Mysql_query("INSERT INTO $CONF[sqlPrefix]mailforms_items(mailform,typ,obsah,nazev,polozka,poradi) VALUES ('$_GET[id]','$typ','','$_POST[popis]','$polozka','$poradi')");
                $nas_id=mysql_insert_id();
                $obsah='<select name="form'.$nas_id.'">';
                $updateO=Mysql_query("UPDATE $CONF[sqlPrefix]mailforms_items SET obsah='$obsah' WHERE id='$nas_id'");

                foreach ($_POST['polozky'] as $key => $val) {
                    if($val!=''){
                     $obsah='<option value="'.$val.'">'.$val.'</option>';
                     $polozka=$nas_id;
                     $vytvor_item=Mysql_query("INSERT INTO $CONF[sqlPrefix]mailforms_items(mailform,typ,obsah,nazev,polozka,poradi) VALUES ('$_GET[id]','polozka','$obsah','','$polozka','')");
                    }
                }
            }
            if($typ=="zaskrtavatka"){
                $obsah="";
                $vytvor_item=Mysql_query("INSERT INTO $CONF[sqlPrefix]mailforms_items(mailform,typ,obsah,nazev,polozka,poradi) VALUES ('$_GET[id]','$typ','$obsah','$_POST[popis]','$polozka','$poradi')");
                $nas_id=mysql_insert_id();
                foreach ($_POST['polozky'] as $key => $val) {
                    if($val!=''){
                     $obsah='<input type="checkbox" name="zaskrtavatko'.$nas_id.'['.$key.']" value="- zaškrtnuto">&nbsp;'.$val.'&nbsp;';
                     $polozka=$nas_id;
                     $vytvor_item=Mysql_query("INSERT INTO $CONF[sqlPrefix]mailforms_items(mailform,typ,obsah,nazev,polozka,poradi) VALUES ('$_GET[id]','zaskrtavatko','$obsah','$val','$polozka','')");
                    }
                }
            }
       }
       else{
           $vytvor_item=Mysql_query("INSERT INTO $CONF[sqlPrefix]mailforms_items(mailform,typ,obsah,nazev,polozka,poradi) VALUES ('$_GET[id]','$typ','','$_POST[popis]','$polozka','$poradi')");
           $nas_id=mysql_insert_id();
           if($typ=="text"){$obsah='<input type="text" size="20" style="width: 175px;" name="form'.$nas_id.'">';}
           if($typ=="textarea"){$obsah='<textarea rows="5" cols="20" name="form'.$nas_id.'"></textarea>';}
           $updateO=Mysql_query("UPDATE $CONF[sqlPrefix]mailforms_items SET obsah='$obsah' WHERE id='$nas_id'");

       }
   }
   if($_POST["upravit"]){
     $dotazMFU=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_items WHERE mailform='$_GET[id]' AND typ!='polozka' ORDER BY id");
            While($m_polozkaU=mysql_fetch_array($dotazMFU)){
                $id=$m_polozkaU['id'];
                $poradi=$_POST[$id]['poradi'];
                $update=Mysql_query("UPDATE $CONF[sqlPrefix]mailforms_items SET poradi='$poradi' WHERE id='$m_polozkaU[id]'");
             }
   }
   if($_POST["ulozitPopis"]){
     $updatePopis=Mysql_query("UPDATE $CONF[sqlPrefix]mailforms SET popis='$_POST[nazevFormulare]' WHERE id='$_GET[id]'");
   }
   $popisM=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms WHERE id='$_GET[id]'"));
   echo'<form method="post" action=""><table align="center">
    <tr><td style="text-align: center;"><b>Popis formuláře</b></td><td></td></tr>
    <tr>
        <td style="text-align: center;"><textarea rows="5" cols="20" name="nazevFormulare">'.$popisM["popis"].'</textarea></td>
    </tr>
    <tr>
        <td style="text-align: center;"><input type="submit" value="Uložit" name="ulozitPopis"></td>
      </tr>
    <tr><td>&nbsp;</td></tr></table></form>';
   echo'<form method="post" action=""><table align="center">
      <tr>
        <th style="text-align: center;">Popis</th>
        <th style="text-align: center;">Typ</th>
        <th style="text-align: center;">Pořadí</th>
        <th style="text-align: center;">Akce</th>
      </tr>';
      $dotazMP=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_items WHERE mailform='$_GET[id]' AND typ!='polozka' AND typ!='zaskrtavatko' ORDER BY poradi");
            While($m_polozka=mysql_fetch_array($dotazMP)){
              echo'<tr><td style="text-align: right;">'.$m_polozka["nazev"].'</td>';
              if($m_polozka["typ"]=="moznosti"){
                $dotazMS2=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_items WHERE polozka='$m_polozka[id]' ORDER BY id");
                echo '<td>'.$m_polozka['obsah'];
                While($sel_polozka=mysql_fetch_array($dotazMS2)){
                  echo $sel_polozka['obsah'];
                }
                echo "</select></td>";
              }
              if($m_polozka["typ"]=="zaskrtavatka"){
                $dotazMS2=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_items WHERE polozka='$m_polozka[id]' ORDER BY id");
                echo '<td>';
                While($sel_polozka=mysql_fetch_array($dotazMS2)){
                  echo $sel_polozka['obsah'];
                }
                echo "</td>";
              }
              if($m_polozka["typ"]!="zaskrtavatka" AND $m_polozka["typ"]!="moznosti"){
                echo'<td>'.$m_polozka["obsah"].'</td>';
              }
              echo'<td><input type="text" size="2" name="'.$m_polozka["id"].'[poradi]" value="'.$m_polozka["poradi"].'"></td>';
              echo'<td style="text-align: center;"><a href="?m=mailforms&akce=editovat&id='.$_GET['id'].'&smaz='.$m_polozka["id"].'"><img src="images/delete.png" alt="smazat" title="smazat" style="border: 0px;"/></a></td></tr>';

            }
      echo '<tr><td colspan="3" style="text-align: center;"><input type="submit" value="upravit pořadí" name="upravit"></td></tr>';
      echo '<tr><td>&nbsp;</td></tr>
      </table></form>';
      echo'<form method="post" action=""><table align="center">
      <tr>
        <th style="text-align: center;">Popis</th>
        <th style="text-align: center;">Typ</th>
        <th>&nbsp;</th>
      </tr>
      <tr><td><input type="text" size="20" name="popis"></td><td><select name="typ" onChange="';echo"if(this.value=='vyber') $('divPolozka1').style.display='block';if(this.value!='vyber') $('divPolozka1').style.display='none';";echo'">
      <option value="text" onClick="';echo"if(this.value!='') $('divPolozka1').style.display='none';";echo'">jednoduché textové pole</option>
      <option value="textarea">velké textové pole</option>
      <option value="vyber">výběrník</option>
      </select></td><td><input type="submit" value="vytvořit" name="vytvorit"></td></tr>
   </table>';
   ?>
   <div style="text-align: center;">
        <div id="divPolozka1" style="display: none;"><input type="radio" name="vybernik" value="moznosti" checked>&nbsp;možnosti&nbsp;&nbsp;<input type="radio" name="vybernik" value="zaskrtavatka">&nbsp;zaškrtávátka<br />Položka 1:&nbsp;<input type="text" name="polozky[1]" onChange="if(this.value!='') $('divPolozka3').style.display='block';"><br />Položka 2:&nbsp;<input type="text" name="polozky[2]" onChange="if(this.value!='') $('divPolozka4').style.display='block';"></div>
        <div id="divPolozka3" style="display: none;" onChange="if(this.value!='') $('divPolozka5').style.display='block';">Položka 3:&nbsp;<input type="text" name="polozky[3]"></div>
        <div id="divPolozka4" style="display: none;" onChange="if(this.value!='') $('divPolozka6').style.display='block';">Položka 4:&nbsp;<input type="text" name="polozky[4]"></div>
        <div id="divPolozka5" style="display: none;" onChange="if(this.value!='') $('divPolozka7').style.display='block';">Položka 5:&nbsp;<input type="text" name="polozky[5]"></div>
        <div id="divPolozka6" style="display: none;" onChange="if(this.value!='') $('divPolozka8').style.display='block';">Položka 6:&nbsp;<input type="text" name="polozky[6]"></div>
        <div id="divPolozka7" style="display: none;" onChange="if(this.value!='') $('divPolozka9').style.display='block';">Položka 7:&nbsp;<input type="text" name="polozky[7]"></div>
        <div id="divPolozka8" style="display: none;" onChange="if(this.value!='') $('divPolozka10').style.display='block';">Položka 8:&nbsp;<input type="text" name="polozky[8]"></div>
        <div id="divPolozka9" style="display: none;" onChange="if(this.value!='') $('divPolozka11').style.display='block';">Položka 9:&nbsp;<input type="text" name="polozky[9]"></div>
        <div id="divPolozka10" style="display: none;" onChange="if(this.value!='') $('divPolozka12').style.display='block';">Položka 10:&nbsp;<input type="text" name="polozky[10]"></div>
        <div id="divPolozka11" style="display: none;" onChange="if(this.value!='') $('divPolozka13').style.display='block';">Položka 11:&nbsp;<input type="text" name="polozky[11]"></div>
        <div id="divPolozka12" style="display: none;" onChange="if(this.value!='') $('divPolozka14').style.display='block';">Položka 12:&nbsp;<input type="text" name="polozky[12]"></div>
        <div id="divPolozka13" style="display: none;">Položka 13:&nbsp;<input type="text" name="polozky[13]"></div>
        <div id="divPolozka14" style="display: none;">Položka 14:&nbsp;<input type="text" name="polozky[14]"></div>
   </div>
 <?
 echo'</form>';
 echo '<p>&nbsp;</p>';
 if($_POST["ulozitM"]){
     $updateM=Mysql_query("UPDATE $CONF[sqlPrefix]mailforms SET od='$_POST[odesilatel]',komu='$_POST[prijemce]',predmet='$_POST[predmet]' WHERE id='$_GET[id]'");
 }
 $mailformM=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms WHERE id=$_GET[id]"));
 echo '<form action="" method="post"><table align="center">';
 echo '<tr><th style="text-align: center;">Odesilatel</th><th style="text-align: center;">Příjemce</th><th style="text-align: center;">Předmět</th></tr>';
 echo '<tr><td><input type="text" size="20" name="odesilatel" value="'.$mailformM["od"].'"></td>
<td><input type="text" size="20" name="prijemce" value="'.$mailformM["komu"].'"></td>
<td><input type="text" size="20" name="predmet" value="'.$mailformM["predmet"].'"></td><td><input type="submit" value="Uložit" name="ulozitM"></td></tr>';
 echo '</table></form>';

 echo'<p><center><a href="?m=mailforms"><<< Zpět</a></center></p>';
 }
 if($_GET["akce"]=="smazat"){
   $smazi = mysql_query("DELETE FROM ".$CONF["sqlPrefix"]."mailforms WHERE id = '$_GET[id]' LIMIT 1");
   $smazi = mysql_query("DELETE FROM ".$CONF["sqlPrefix"]."mailforms_items WHERE mailform = '$_GET[id]'");
   $smazi = mysql_query("DELETE FROM ".$CONF["sqlPrefix"]."mailforms_rozdeleni WHERE mailform = '$_GET[id]'");

echo'<meta http-equiv="refresh" content="0;url=?m=mailforms">';
 }
 if($_GET["akce"]=="zobrazeni"){
   if($_POST["ulozit"]){
     foreach ($_POST['mailform'] as $key => $val) {
                    if($val!=0){
                      $zkouska=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_rozdeleni WHERE clanek='$key'"));
                      if($zkouska["mailform"]){
                        $vytvor_rozdeleni=Mysql_query("UPDATE $CONF[sqlPrefix]mailforms_rozdeleni SET mailform='$val' WHERE clanek='$key'");
                      }
                      else{
                        $vytvor_rozdeleni=Mysql_query("INSERT INTO $CONF[sqlPrefix]mailforms_rozdeleni(clanek,mailform) VALUES ('$key','$val')");}
                    }
                    if($val==0){
                      $smazz = @mysql_query("DELETE FROM ".$CONF["sqlPrefix"]."mailforms_rozdeleni WHERE clanek='$key' LIMIT 1");                             }

     }

   }
   echo '<form method="post" action="">
  <table align="center">';
  echo'<tr style="text-align: center;">
  <th>Název článku</th>
  <th>Formulář</th>
  </tr>';
  $dotazC1=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='vodorovne' AND id!='2' AND id!='3' AND id!='11' ORDER BY id ASC") or die ('Chyba ve ukladani do databaze: '.mysql_error());
  While($clanek=mysql_fetch_array($dotazC1)){
      $mailformR=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_rozdeleni WHERE clanek='$clanek[id]'"));
      $id=$clanek["id"];
      echo "<tr>";
      echo '<td>';
      echo ''.$clanek["nazev"].'';
      echo '</td>';
      echo '<td><select name="mailform['.$id.']"><option value="0">Bez formuláře</option>';
      $dotazMT=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms");
        While($mailformT=mysql_fetch_array($dotazMT)){
                echo '<option value="'.$mailformT["id"].'" ';
                if($mailformR['mailform']==$mailformT['id']){echo "selected";}
                echo '>'.$mailformT["nazev"].'</option>';
      }
      echo '</select></td>';
      echo "</tr>";
      $dotazC2=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='$clanek[id]' ORDER BY id ASC") or die ('Chyba ve ukladani do databaze: '.mysql_error());
      While($podclanek=mysql_fetch_array($dotazC2)){
        $mailformRP=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_rozdeleni WHERE clanek='$podclanek[id]'"));
          $selected='';
      if($mailformRP['mailform']){
          $selected="selected";
      }
      $idpod=$podclanek["id"];
      echo "<tr>";
      echo '<td>';
      echo '-&nbsp;'.$podclanek["nazev"].'';
      echo '</td>';
      echo '<td><select name="mailform['.$idpod.']"><option value="0">Bez formuláře</option>';
      $dotazMT=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms");
        While($mailformT=mysql_fetch_array($dotazMT)){
                echo '<option value="'.$mailformT["id"].'"';
                if($mailformRP['mailform']==$mailformT['id']){echo $selected;}
                echo '>'.$mailformT["nazev"].'</option>';
      }
      echo '</select></td>';
      echo "</tr>";
      }
  }
  $dotazC3=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='' ORDER BY id ASC") or die ('Chyba ve ukladani do databaze: '.mysql_error());
  While($clanekV=mysql_fetch_array($dotazC3)){
    $mailformRV=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms_rozdeleni WHERE clanek='$clanekV[id]'"));
      $selected='';
      if($mailformRV['mailform']){
          $selected="selected";
      }
      $idV=$clanekV["id"];
      echo "<tr>";
      echo '<td>';
      echo ''.$clanekV["nazev"].'';
      echo '</td>';
      echo '<td><select name="mailform['.$idV.']"><option value="0">Bez formuláře</option>';
      $dotazMT=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms");
        While($mailformT=mysql_fetch_array($dotazMT)){
                echo '<option value="'.$mailformT["id"].'"';
                if($mailformRV['mailform']==$mailformT['id']){echo $selected;}
                echo '>'.$mailformT["nazev"].'</option>';
      }
      echo '</select></td>';
      echo "</tr>";
  }
  echo '<tr><td colspan="4" style="text-align: center;">&nbsp;</td></tr>';
  echo '<tr><td colspan="4" style="text-align: center;"><input type="submit" value="uložit změny" name="ulozit"></td></tr>';
  echo "</table></form>";
  echo'<p><center><a href="?m=mailforms"><<< Zpět</a></center></p>';
}


 if(!$_GET["akce"]){
     if($_POST["vytvorit"]){
        $vytvor=Mysql_query("INSERT INTO $CONF[sqlPrefix]mailforms(nazev) VALUES ('$_POST[nazev]')");
        $p_mailform=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms ORDER BY id DESC LIMIT 1"));
        echo'<meta http-equiv="refresh" content="0;url=?m=mailforms&akce=editovat&id='.$p_mailform["id"].'">';
     }
     echo '<form method="post" action="?m=mailforms">';
     echo '<table align="center">';
     echo '<tr><td colspan="2" style="text-align: center;"><b>Vytvořit nový formulář</b></td></tr>';
     echo '<tr><td>Název:&nbsp;<input type="text" size="20" name="nazev"></td><td><input type="submit" value="vytvořit" name="vytvorit"></td></tr>';
     echo "</table>";
     echo "</form>";
     echo '<p style="text-align: center;"><a href="?m=mailforms&akce=zobrazeni" style="text-decoration: underline;">Nastavení zobrazení</a></p>';
     echo '<table align="center" style="min-width: 40%">';
     echo '<tr>
     <th style="text-align: center;">Název formuláře</th>
     <th style="text-align: center;" colspan="2">Akce</th>
     </tr>';
     $dotazMF=Mysql_query("SELECT * FROM $CONF[sqlPrefix]mailforms") or die ('Chyba ve ukladani do databaze: '.mysql_error());
            While($mailform=mysql_fetch_array($dotazMF)){
              echo'<tr><td>'.$mailform["nazev"].'</td>';
              echo'<td style="text-align: center;"><a href="?m=mailforms&akce=editovat&id='.$mailform["id"].'" style="text-decoration: underline;"><img src="images/edit16.png" alt="upravit" title="upravit" style="border: 0px;"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="?m=mailforms&akce=smazat&id='.$mailform["id"].'"><img src="images/delete.png" alt="smazat" title="smazat" style="border: 0px;"/></a></td></tr>';
            }
     echo "</table>";
 }
 ?>

<?php Include 'grafika_kon.inc'; ?>