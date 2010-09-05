<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/fotogalerie.png" width="48" height="47"></td>
                  <td width="335"><font face="Arial" size="2">
                  <span style="font-weight: 700">Fotogalerie</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Editace či přidávání fotografií do galerií na Vašem webu.</font></td>
                </tr>
              </table>
              ';
              
include 'grafika_zac.inc';






function najdi_galerii($id,$co){
  $CONF = $GLOBALS["config"];
  $sql = mysql_fetch_array(mysql_query("SELECT $co FROM ".$CONF["sqlPrefix"]."gal_kat WHERE id = '$id'"));
  
  return $sql[$co];
}

function resizer($filename,$copypath,$MaxWidth,$MaxHeight){ //cesta k souboru, ktery chcete zmensit cesta, kam zmenseny soubor ulozit maximalni sirka zmenseneho obrazku   //maximalni vyska zmenseneho obrazku 
//zjistime puvodni velikost obrazku
  list($OrigWidth, $OrigHeight) = getimagesize($filename); //hodnota 0 v parametrech MaxWidth resp. MaxHeight znamena, ze sirka resp. vyska vysledku muze byt libovolna
  if ($MaxWidth == 0) $MaxWidth = $OrigWidth;
  if ($MaxHeight == 0) $MaxHeight = $OrigHeight; //nyni vypocitam pomer zmenseni
  $pw = $OrigWidth / $MaxWidth;
  $ph = $OrigHeight / $MaxHeight;
  if ($pw > $ph) $p = $pw;
  else  $p = $ph;
  if ($p < 1) $p = 1; //v p ted mame pomer pro zmenseni vypocitame vysku a sirku zmenseneho obrazku
  $NewWidth = (int)$OrigWidth / $p;
  $NewHeight = (int)$OrigHeight / $p; //vytvorime novy obrazek pozadovane vysky a sirky
  $image_p = imagecreatetruecolor($NewWidth, $NewHeight); //otevreme puvodni obrazek se souboru
  $image = imagecreatefromjpeg($filename); //a okopirujeme zmenseny puvodni obrazek do noveho
  
  imagecopyresampled($image_p, $image, 0, 0, 0, 0, $NewWidth, $NewHeight, $OrigWidth, $OrigHeight); //a ulozime
  
  imagejpeg($image_p, $copypath, 100);
}






/* vytvoření kategorie -> vložení do DB + vytvoření složky + podsložky pro náhledy */
if($_POST["kategorie"] == "Vytvořit" AND $_POST["nazev"]){
  $slozka = urlText($_POST["nazev"]);
  
  if(@mkdir('../userfiles/galerie/'.$slozka, 0700)) echo "Složka úspěšně vytvořena<br />";
  if(!@mkdir('../userfiles/galerie/'.$slozka.'/nahledy', 0700)) echo "";//"Chyba při vytváření složky náhledy!<br />";
  
  @chmod('../userfiles/galerie/'.$slozka, 0777);
  @chmod('../userfiles/galerie/'.$slozka.'/nahledy', 0777);
  
  $sql = "INSERT INTO ".$CONF["sqlPrefix"]."gal_kat VALUES (NULL, '$slozka', '$_POST[nazev]', 'ne')";
  if(mysql_query($sql)) echo "Kategorie byla úspěšně vytvořena!";
  //Else echo "Př vytváření kategorie došlo k chybě! ".mysql_error();
}

/* upload souboru + zmenšení a uložení náhledu + uložení do DB */
elseif($_POST["fotka"] == "Vložit"){
  if($soubor_type == "text/plain" or $soubor_type="text/html"){
    $soubor_name = urlText($_FILES["soubor"]["name"]);
    if(move_uploaded_file($_FILES["soubor"]["tmp_name"], "../userfiles/galerie/".najdi_galerii($_POST["galerie"],"slozka")."/$soubor_name")) {
      echo "Soubor <b>$soubor_name</b> byl úspěšně nahrán<hr />";
      
      $fotka = "../userfiles/galerie/".najdi_galerii($_POST["galerie"],"slozka")."/$soubor_name";
      $thumb = "../userfiles/galerie/".najdi_galerii($_POST["galerie"],"slozka")."/nahledy/$soubor_name";
      
      resizer($fotka,$thumb,"180","135");
      
      $vaha = mysql_fetch_array(mysql_query("SELECT vaha FROM ".$CONF["sqlPrefix"]."galerie WHERE kategorie = '$_POST[galerie]' ORDER BY vaha DESC LIMIT 1"));
      $sql = "INSERT INTO ".$CONF["sqlPrefix"]."galerie VALUES (NULL, '".($vaha["vaha"]+1)."', '$soubor_name', '$_POST[galerie]', '$_POST[popis]')";
      if(!mysql_query($sql))
        echo mysql_error();
     /* mail ("spravce@muj_server.cz", "Upload souboru $soubor_name",
       "Na server byl nahrán soubor /data/$soubor_name\n",
       "From: system@muj_server.cz\nX-web: http://www.muj_server.cz/system/upload.php"); */
    }
    else
      echo "Při nahrávání souboru došlo k chybě!<BR>";
  }
  else
    echo "Soubor není požadového MIME typu!<BR>";
}

/* úprava fotky */
elseif($_POST["uprava"] == "Upravit"){
  $j = 0;
  while($_POST["id_foto"][$j]){
    $zdroj = mysql_fetch_array(mysql_query("SELECT kategorie,foto FROM ".$CONF["sqlPrefix"]."galerie WHERE id = '".$_POST["id_foto"][$j]."'"));
    if($zdroj["kategorie"] != $_POST["galerie"][$j]){
      presun("../userfiles/galerie/".najdi_galerii($zdroj["kategorie"],"slozka")."/".$zdroj["foto"],"../userfiles/galerie/".najdi_galerii($_POST["galerie"][$j],"slozka")."/".$zdroj["foto"]);
      presun("../userfiles/galerie/".najdi_galerii($zdroj["kategorie"],"slozka")."/nahledy/".$zdroj["foto"],"../userfiles/galerie/".najdi_galerii($_POST["galerie"][$j],"slozka")."/nahledy/".$zdroj["foto"]);
    }
    $sql = "UPDATE ".$CONF["sqlPrefix"]."galerie SET kategorie = '".$_POST["galerie"][$j]."', vaha = '".$_POST["vaha"][$j]."', popis = '".$_POST["popis"][$j]."' WHERE id = '".$_POST["id_foto"][$j]."'";
    if(!mysql_query($sql))
      echo mysql_error();
    
    $j++;
  }
  //print_r($_POST);
}

elseif($_GET["smazat"] == "fotku"){
  $zdroj = mysql_fetch_array(mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."galerie WHERE id = '$_GET[id_foto]'"));

  $slozka = najdi_galerii($zdroj["kategorie"],"slozka");
  
  @unlink("../userfiles/galerie/".$slozka."/".$zdroj["foto"]);
  @unlink("../userfiles/galerie/".$slozka."/nahledy/".$zdroj["foto"]);
  
  $sql = mysql_query("DELETE FROM ".$CONF["sqlPrefix"]."galerie WHERE id = '$_GET[id_foto]'");
  
  if($sql){
    echo "Fotografie <b>$zdroj[foto]</b> byla úspěšně smazána.";
  }
  else
    echo "Při mazání fotografie došlo k neznámé chybě.";
}

elseif($_GET["smazat"] == "galerii"){
  $sql = mysql_query("DELETE FROM ".$CONF["sqlPrefix"]."gal_kat WHERE id = '$_GET[id_gal]'");
  
  if($sql)
    echo "Galerie byla úspěšně smazána.";
  else
    echo "Při mazání galerie došlo k chybě.";
}

/* zobrazování */
if(!isset($_GET["foto_kat"])){
  echo "<form method=\"post\"><b>Vytvořit novou kategorii:</b> <input type=\"text\" name=\"nazev\" /> <input type=\"submit\" name=\"kategorie\" value=\"Vytvořit\"></form><hr />";

  echo "<form method=\"post\" enctype=\"multipart/form-data\"><b>Vložit novou fotografii:</b><br /><table>";
  echo "<tr><td>Foto: </td><td><input type=\"file\" name=\"soubor\" /></tr>";
  echo "<tr><td>Kategorie: </td><td><select name=\"galerie\">";

  $gal = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."gal_kat ORDER BY skryta ,id ASC");
  while($g = mysql_fetch_array($gal))
    echo "<option value=\"$g[id]\"".($clanek["galerie"] == $g["id"] ? " selected=true" : "").">$g[nazev]</option>";

  echo "</select></tr>";
  echo "<tr><td>Krátký popis: </td><td><textarea name=\"popis\"></textarea></tr>";
  echo "<tr><td colspan=\"2\" align=\"right\"><input type=\"submit\" name=\"fotka\" value=\"Vložit\"></td></tr>";
  echo "</table></form><hr />";

  /* výpis fotek */
  $q = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."gal_kat ORDER BY skryta ,id ASC");
  while($gal = @mysql_fetch_array($q)){
    echo "<h3 style=\"font-size: 11pt; font-weight: bold;\">$gal[nazev] <a href=\"index.php?m=fotogalerie&smazat=galerii&id_gal=$gal[id]\" style=\"font-size: 80%; color: silver;\" onclick=\"return (window.confirm('Opravdu si přejete smazat tuto galerii?'));\">(smazat)</a></h3>";
    echo "<table>\n<tr>\n";
    $i = 1;
    $sql = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."galerie WHERE kategorie = '$gal[id]' ORDER BY vaha ASC");
    while($foto = mysql_fetch_array($sql)){
      echo "<td><a href=\"index.php?m=fotogalerie&foto_kat=$foto[kategorie]#$foto[id]\"><img src=\"../userfiles/galerie/".najdi_galerii($foto["kategorie"],"slozka")."/nahledy/$foto[foto]\" title=\"$foto[popis]\"></a></td>";
      if($i % 3 == 0) echo "</tr><tr>";
      ++$i;
    }
    echo "</tr>\n</table>&nbsp;<hr />";
  }
}
elseif($_GET["foto_kat"] != 0){
  echo "<div style=\"text-align: right;\"><a href=\"index.php?m=fotogalerie\">&lt;- zpátky k fotkám</a></div>";
  echo "<h3>Galerie: ".najdi_galerii($_GET["foto_kat"],"nazev")."</h3>";
  $sql = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."galerie WHERE kategorie = '$_GET[foto_kat]' ORDER BY vaha");
  
  echo "<form method=\"post\">";
  while($fotka = mysql_fetch_array($sql)){
    echo "<table><tr><td rowspan=\"3\" width=\"200\"><a name=\"$fotka[id]\"></a><img src=\"../userfiles/galerie/".najdi_galerii($fotka["kategorie"],"slozka")."/nahledy/$fotka[foto]\" title=\"$fotka[foto]\" style=\"float: left; margin: 5px;\"></td>";
    echo "<td>Kategorie: </b></td><td><select name=\"galerie[]\"><option value=\"1\">---</option>";

    $gal = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."gal_kat");
    while($g = mysql_fetch_array($gal))
      echo "<option value=\"$g[id]\"".($fotka["kategorie"] == $g["id"] ? " selected=true" : "").">$g[nazev]</option>";
    echo "</select></td></tr>";
    echo "<tr><td>Váha: </td><td><input type=\"text\" size=\"3\" maxlength=\"3\" name=\"vaha[]\" value=\"$fotka[vaha]\" /> <span style=\"font-size: 9px;\">(fotografie jsou řazeny od nejnižšího čísla)</span></td></tr>";
    echo "<tr><td valign=\"top\">Krátký popis: </td><td valign=\"top\"><textarea name=\"popis[]\">$fotka[popis]</textarea><input type=\"hidden\" name=\"id_foto[]\" value=\"$fotka[id]\" /></td></tr>";
    echo "<tr><td align=\"right\" colspan=\"3\"><a href=\"?m=fotogalerie&foto_kat=$fotka[kategorie]&smazat=fotku&id_foto=$fotka[id]\" style=\"font-weight: bold\">Smazat fotku</a></td></tr>";
    echo "<tr><td colspan=\"3\" align=\"center\"><hr /></td></tr>";
    echo "</table>";
  }
  echo "<div style=\"clear: both; text-align: right;\"><input type=\"submit\" name=\"uprava\" value=\"Upravit\" /></div></form>";
}

include 'grafika_kon.inc'; ?>