<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/fora.png" width="48" height="48"></td>
                  <td width="616" colspan="2"><font face="Arial" size="2">
                  <span style="font-weight: 700">Fórum</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Zde můžete založit diskusní fórum, aby Vaši klienti mohli přímo na stránkách diskutovat a vy s nimi. </font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>
<?php
if($_POST["ulozit"]){
    $dotazCl=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky") or die ('Chyba ve ukladani do databaze: '.mysql_error());
        While($clanekCl=mysql_fetch_array($dotazCl)){
          $clanekId=$clanekCl["id"];
          $radek=$_POST["$clanekId"];
          if($radek["forum"]=="ano"){
             $forumU=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]forums WHERE clanek='$clanekId'"));
             $globals=$radek["globals"];
             if($forumU["id"]){
                 $ulozeni=Mysql_query("UPDATE $CONF[sqlPrefix]forums SET clanek='$clanekCl[id]', globals='$globals' WHERE id='$forumU[id]'");
             }
             else{
                $tema="";
                if($globals==1){
                  $forumU=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]forums WHERE globals='1' LIMIT 1"));
                  $tema=$forumU["tema"];
                }
                $ulozeni=Mysql_query("INSERT INTO $CONF[sqlPrefix]forums(clanek,tema,globals) VALUES ('$clanekCl[id]','$tema','$globals')");
            }
          }
          else{
             $forumS=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]forums WHERE clanek='$clanekId'"));
             $globals=$radek["globals"];
             if($forumS["id"]){
                 $smaz=Mysql_query("DELETE FROM $CONF[sqlPrefix]forums WHERE id='".$forumS["id"]."' LIMIT 1");
                 if($forumS["globals"]==0){$smaz=Mysql_query("DELETE FROM $CONF[sqlPrefix]forum WHERE forum='".$forumS["id"]."'");}
             }
          }
        }
        echo "Změna byla úspěšně uložena.";

}
if(!$_GET["forum"]){
  echo '<form method="post" action="">
  <table align="center">';
  echo'<tr style="text-align: center;">
  <th>Fórum</th>
  <th>Název</th>
  <th>Společné</th>
  <th>Vlastní</th>
  </tr>';
  $dotazC1=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='vodorovne' AND id!='2' AND id!='3' AND id!='11' ORDER BY id ASC") or die ('Chyba ve ukladani do databaze: '.mysql_error());
  While($clanek=mysql_fetch_array($dotazC1)){
      $forum=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]forums WHERE clanek='$clanek[id]'"));
      $checked='';
      $checkedG1='';
      $checkedG0='';
      if($forum['id']){
          $checked="checked";
          if($forum["globals"]==1){
             $checkedG1="checked";
          }
          if($forum["globals"]==0){
             $checkedG0="checked";
          }
      }
      echo "<tr>";
      echo '<td style="text-align: center;"><input type="checkbox" '.$checked.' name="'.$clanek["id"].'[forum]" value="ano"></td>';
      echo '<td>';
      if($forum["globals"]==1){
         $url="?m=forums&forum=global";
      }
      if($forum["globals"]==0){
         $url="?m=forums&forum=".$forum['id']."";
      }
      if($checked=="checked"){
        echo '<a href="'.$url.'">';
      }
      echo ''.$clanek["nazev"].'';
      if($checked=="checked"){echo '</a>';}
      echo '</td>';
      echo '<td style="text-align: center;"><input type="radio" '.$checkedG1.'  name="'.$clanek["id"].'[globals]" value="1"></td>';
      echo '<td style="text-align: center;"><input type="radio" '.$checkedG0.'  name="'.$clanek["id"].'[globals]" value="0"></td>';
      echo "</tr>";
      $dotazC2=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='$clanek[id]' ORDER BY id ASC") or die ('Chyba ve ukladani do databaze: '.mysql_error());
      While($podclanek=mysql_fetch_array($dotazC2)){
          $checkedP='';
          $checkedPG1='';
          $checkedPG0='';
          $forum=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]forums WHERE clanek='$podclanek[id]'"));
          if($forum['id']){
              $checkedP="checked";
              if($forum['globals']==1){
                 $checkedPG1="checked";
              }
              if($forum['globals']==0){
                 $checkedPG0="checked";
              }
          }
          echo "<tr>";
          echo '<td style="text-align: center;"><input type="checkbox" '.$checkedP.' name="'.$podclanek["id"].'[forum]" value="ano"></td>';
           echo '<td>';
      if($forum["globals"]==1){
         $url="?m=forums&forum=global";
      }
      if($forum["globals"]==0){
         $url="?m=forums&forum=".$forum['id']."";
      }
      if($checkedP=="checked"){
        echo '<a href="'.$url.'">';
      }
      echo '-&nbsp;'.$podclanek["nazev"].'';
      if($checkedP=="checked"){echo '</a>';}
      echo '</td>';
          echo '<td style="text-align: center;"><input type="radio" '.$checkedPG1.'  name="'.$podclanek["id"].'[globals]" value="1"></td>';
          echo '<td style="text-align: center;"><input type="radio" '.$checkedPG0.'  name="'.$podclanek["id"].'[globals]" value="0"></td>';
          echo "</tr>";
      }
  }
  $dotazC3=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='' ORDER BY id ASC") or die ('Chyba ve ukladani do databaze: '.mysql_error());
  While($clanekV=mysql_fetch_array($dotazC3)){
      $forum=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]forums WHERE clanek='$clanekV[id]'"));
      $checked='';
      $checkedG1='';
      $checkedG0='';
      if($forum['id']){
          $checked="checked";
          if($forum['globals']==1){
             $checkedG1="checked";
          }
          if($forum['globals']==0){
             $checkedG0="checked";
          }
      }
      echo "<tr>";
      echo '<td style="text-align: center;"><input type="checkbox" '.$checked.' name="'.$clanekV["id"].'[forum]" value="ano"></td>';
      echo '<td>';
      if($forum["globals"]==1){
         $url="?m=forums&forum=global";
      }
      if($forum["globals"]==0){
         $url="?m=forums&forum=".$forum['id']."";
      }
      if($checked=="checked"){
        echo '<a href="'.$url.'">';
      }
      echo ''.$clanekV["nazev"].'';
      if($checked=="checked"){echo '</a>';}
      echo '</td>';
      echo '<td style="text-align: center;"><input type="radio" '.$checkedG1.'  name="'.$clanekV["id"].'[globals]" value="1"></td>';
      echo '<td style="text-align: center;"><input type="radio" '.$checkedG0.'  name="'.$clanekV["id"].'[globals]" value="0"></td>';
      echo "</tr>";
  }
  echo '<tr><td colspan="4" style="text-align: center;">&nbsp;</td></tr>';
  echo '<tr><td colspan="4" style="text-align: center;"><input type="submit" value="uložit změny" name="ulozit"></td></tr>';
  echo "</table></form>";
}
if($_GET["forum"]){
  echo'<p><center><a href="?m=forums"><<< Zpět</a></center></p><p>&nbsp;</p>';
    if($_POST["ulozitTema"]){
      if($_GET["forum"]=="global"){
        $ulozeni=Mysql_query("UPDATE $CONF[sqlPrefix]forums SET tema='$_POST[tema]' WHERE globals='1'");
      }
      else{
        $ulozeni=Mysql_query("UPDATE $CONF[sqlPrefix]forums SET tema='$_POST[tema]' WHERE id='$_GET[forum]'");
      }
    }
    if($_GET["forum"]=="global"){$forums2=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]forums WHERE globals='1' LIMIT 1"));}
    else{$forums2=mysql_fetch_array(Mysql_query("SELECT * FROM $CONF[sqlPrefix]forums WHERE id='$_GET[forum]'"));}
    echo'<form method="post" action=""><p style="text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Téma diskuze:&nbsp;<input type="text" name="tema" value="'.$forums2["tema"].'" style="width: 176px;" />&nbsp;<input type="submit" value="Uložit" name="ulozitTema"></p></form>';
    if($_POST["odeslat"] == "odeslat" && $_POST["jmeno"] != "" && $_POST["zprava"] != ""){
      $_POST["zprava"] = nl2br(htmlspecialchars($_POST["zprava"]));
      $odp = ($_POST["odpoved"] == "" ? "NULL" : "'".$_POST["odpoved"]."'");
      $datum = time();

      $sql = mysql_query("INSERT INTO ".$CONF["sqlPrefix"]."forum VALUES (NULL, $odp, '$_GET[forum]', '$datum', '$_POST[jmeno]', '$_POST[email]', '$_POST[zprava]')") or die ('Chyba ve ukladani do databaze: '.mysql_error());
      if($sql){
        echo "<p>Váš příspěvek byl úspěšně vložen.</p>";
      }
      else{
        echo "<p>Při ukládání Vašeho příspěvku došlo k chybě.</p>";
      }
  }
  elseif(isset($_POST["odeslat"]) && ($_POST["jmeno"] == "" OR $_POST["zprava"] == "")){
    echo "Nevyplnili jste všechna potřebná pole!";
  }
  elseif(isset($_GET["id_smaz"])){
    $sql = mysql_query("DELETE FROM ".$CONF["sqlPrefix"]."forum WHERE id = '$_GET[id_smaz]'");
    if($sql)
      echo "<span style=\"background-color: #FFDEDE;\">Příspěvek byl úspěšně smazán.</span>";
    else
      echo "<span style=\"background-color: #FFDEDE;\">Neznámá chyba. <br>".mysql_error()."</span>";
  }
    echo'<table align="center" border="0" class="diskuse">
      <form method="post" action="">
         <input type="hidden" name="odpoved" value="'.$_GET["id_odpovedi"].'" />
         <tr><td style="text-align: right;"><b>Jméno:</b></td><td><input type="text" name="jmeno" style="width: 176px;"/></td></tr>
         <tr><td style="text-align: right;">E-mail: </td><td><input type="text" name="email" style="width: 176px;"/></td></tr>
         <tr><td valign="top"><b>Zpráva:</b></td><td><textarea name="zprava"></textarea></td></tr>';
         if($_GET["id_odpovedi"] != "") echo "<tr><td colspan=\"2\" align=\"right\"><font class=\"mensi\">...odpovídáte na příspěvek č. $_GET[id_odpovedi]."; echo'</font></td></tr>
         <tr><td style="text-align: center"; colspan="2"><input type="submit" name="odeslat" value="odeslat"></td></tr>
      </form>
</table>
<table border="0" align="center" class="diskuse">';

  $sql = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."forum WHERE forum='$_GET[forum]' AND id_odpovedi IS NULL ORDER BY id DESC");

  while($p = mysql_fetch_array($sql)){
    $email = ($p["email"] != "" ? " <a href=\"mailto:".$p["email"]."\" class=\"mensi\">[email]</a>" : "");
    $datum = date("j.n.Y H:i", $p["datum"]);
    $odpovedet = "<a href=\"?m=forums&forum=$_GET[forum]&amp;id_odpovedi=$p[id]\">odpovědět</a>";
    $smaz = "&nbsp;&nbsp;&nbsp;<a href=\"?m=forums&forum=$_GET[forum]&amp;id_smaz=$p[id]\" class=\"mensi\">[smaž]</a>"; //"";

    echo "<tr><td colspan=\"2\"><hr></td></tr>";
    echo "<tr><td>$p[id]. <b>$p[jmeno]</b>$email</td><td align=\"right\"><span class=\"mensi\">$datum</span>&nbsp;&nbsp;&nbsp;$odpovedet&nbsp;&nbsp;&nbsp;$smaz</td></tr>";
    echo "<tr><td colspan=\"2\"><p>$p[text]</p></td></tr>";
      $pod = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."forum WHERE id_odpovedi = '$p[id]'");
      while($od = mysql_fetch_array($pod)){
        $email_1 = ($od["email"] != "" ? " <a href=\"mailto:".$od["email"]."\" class=\"mensi\">[email]</a>" : "");
        $datum_1 = date("j.n.Y H:i", $od["datum"]);
        $smaz_1 = "&nbsp;&nbsp;&nbsp;<a href=\"?m=forums&forum=$_GET[forum]&amp;id_smaz=$od[id]\" class=\"mensi\">[smaž]</a>"; // "";

        echo "<tr><td colspan=\"2\"><table>";
        echo "<tr><td width=\"20\">&nbsp;</td><td><b>$od[jmeno]</b>$email_1</td><td align=\"right\"><span class=\"mensi\">$datum_1</span>&nbsp;&nbsp;&nbsp;$odpovedet_1$smaz_1</td></tr>";
        echo "<tr><td width=\"20\">&nbsp;</td><td colspan=\"2\"><p>$od[text]</p></td></tr>";
        echo "</table></td></tr>";
      }
  }
echo'</table>';
}
?>
<?php Include 'grafika_kon.inc'; ?>