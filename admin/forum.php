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
  if($_POST["odeslat"] == "odeslat" && $_POST["jmeno"] != "" && $_POST["zprava"] != ""){
    $_POST["zprava"] = nl2br(htmlspecialchars($_POST["zprava"]));
    $odp = ($_POST["odpoved"] == "" ? "NULL" : "'".$_POST["odpoved"]."'");
    $datum = time();
    
    $sql = mysql_query("INSERT INTO ".$CONF["sqlPrefix"]."forum VALUES (NULL, $odp, '$datum', '$_POST[jmeno]', '$_POST[email]', '$_POST[zprava]')");
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
?>
<table align="center" border="0" class="diskuse">
      <form method="post" action="">
         <input type="hidden" name="odpoved" value="<?php echo $_GET["id_odpovedi"]; ?>" />
         <tr><td><b>Jméno:</b></td><td><input type="text" name="jmeno" /></td></tr>
         <tr><td>E-mail: </td><td><input type="text" name="email" /></td></tr>
         <tr><td valign="top"><b>Zpráva:</b></td><td><textarea name="zprava"></textarea></td></tr>
         <?php
          if($_GET["id_odpovedi"] != "") echo "<tr><td colspan=\"2\" align=\"right\"><font class=\"mensi\">...odpovídáte na příspěvek č. $_GET[id_odpovedi].</font></td></tr>";
         ?>
         <tr><td></td><td align="right"><input type="submit" name="odeslat" value="odeslat"></td></tr>
      </form>
</table>
<table border="0" align="center" class="diskuse">
<?php
  $sql = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."forum WHERE id_odpovedi IS NULL ORDER BY id DESC");
  
  while($p = mysql_fetch_array($sql)){
    $email = ($p["email"] != "" ? " <a href=\"mailto:".$p["email"]."\" class=\"mensi\">[email]</a>" : "");
    $datum = date("j.n.Y H:i", $p["datum"]);
    $odpovedet = "<a href=\"?m=forum&amp;id_odpovedi=$p[id]\">odpovědět</a>";
    $smaz = "&nbsp;&nbsp;&nbsp;<a href=\"?m=forum&amp;id_smaz=$p[id]\" class=\"mensi\">[smaž]</a>"; //"";
    
    echo "<tr><td colspan=\"2\"><hr></td></tr>";
    echo "<tr><td>$p[id]. <b>$p[jmeno]</b>$email</td><td align=\"right\"><span class=\"mensi\">$datum</span>&nbsp;&nbsp;&nbsp;$odpovedet&nbsp;&nbsp;&nbsp;$smaz</td></tr>";
    echo "<tr><td colspan=\"2\"><p>$p[text]</p></td></tr>";
      $pod = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."forum WHERE id_odpovedi = '$p[id]'");
      while($od = mysql_fetch_array($pod)){
        $email_1 = ($od["email"] != "" ? " <a href=\"mailto:".$od["email"]."\" class=\"mensi\">[email]</a>" : "");
        $datum_1 = date("j.n.Y H:i", $od["datum"]);
        $smaz_1 = "&nbsp;&nbsp;&nbsp;<a href=\"?m=forum&amp;id_smaz=$od[id]\" class=\"mensi\">[smaž]</a>"; // "";
      
        echo "<tr><td colspan=\"2\"><table>";
        echo "<tr><td width=\"20\">&nbsp;</td><td><b>$od[jmeno]</b>$email_1</td><td align=\"right\"><span class=\"mensi\">$datum_1</span>&nbsp;&nbsp;&nbsp;$odpovedet_1$smaz_1</td></tr>";
        echo "<tr><td width=\"20\">&nbsp;</td><td colspan=\"2\"><p>$od[text]</p></td></tr>";
        echo "</table></td></tr>";
      }
  }
?>
</table>
<?php Include 'grafika_kon.inc'; ?>