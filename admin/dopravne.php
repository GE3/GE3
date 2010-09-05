<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/dopravne.png"></td>
                  <td width="343"><font face="Arial" size="2">
                  <span style="font-weight: 700">Nastavení dopravy a plateb</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Zde můžete změnit ceny za dopravu, způsoby doručení zboží a plateb.
                  </font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php /* Změna dopravy */
If( $_POST["zmena_dopravy"] OR $_GET["smazatDopravu"] ){
    /* Nová doprava */
    If( $_POST["firma_nova"] ){
        If( Mysql_query("INSERT INTO $CONF[sqlPrefix]doprava(firma,zpusob_platby,cena) VALUES ('".$_POST["firma_nova"]."','".$_POST["zpusobPlatby_novy"]."','".$_POST["cena_nova"]."')") ){
            echo '
               <table><tr><td bgcolor="#FFDEDE">
               Doprava přidána.
               </td></tr></table>
               ';
        }Else{
              echo '
                 <table><tr><td bgcolor="#FFDEDE">
                 Při přidávání dopravy nastala neznámá chyba.
                 </td></tr></table>
                 ';
        }
    }

    /* Smazání dopravy */
    If( $_GET["smazatDopravu"] ){
        If( Mysql_query("DELETE FROM $CONF[sqlPrefix]doprava WHERE id=".$_GET["smazatDopravu"]." ") ){
            echo '
               <table><tr><td bgcolor="#FFDEDE">
               Doprava smazána... <span style="font-size: 80%;">[<a href="?m=dopravne">OK</a>]</span>
               </td></tr></table>
               ';
        }Else{
              echo '
                 <table><tr><td bgcolor="#FFDEDE">
                 Při mazání dopravy nastala neznámá chyba.
                 </td></tr></table>
                 ';
        }
    }

    /* Editace doprav */
    $editaceDoprav=TRUE;
    If(is_array($_POST["firma"]))
    Foreach( $_POST["firma"] as $key=>$value ){
             If( Mysql_query("UPDATE $CONF[sqlPrefix]doprava SET firma='".$value."',zpusob_platby='".$_POST["zpusobPlatby"][$key]."',cena='".$_POST["cena"][$key]."' WHERE id=$key  ") ){
             }
             Else{
                  $editaceDoprav=FALSE;
             }

             If( $_POST["prvni"]==$key ){
                 If( Mysql_query("UPDATE $CONF[sqlPrefix]doprava SET prvni='' ") AND Mysql_query("UPDATE $CONF[sqlPrefix]doprava SET prvni='1' WHERE id=$key  ") ){
                 }
                 Else{
                      $editaceDoprav=FALSE;
                 }
             }
    }
    If( $editaceDoprav AND count($_POST["firma"])>0 ){
        echo '
           <table><tr><td bgcolor="#FFDEDE">
           Úpravy proběhla úspěšně.
           </td></tr></table>
           ';
    }
    Elseif( count($_POST["firma"])>0 ){
            echo '
               <table><tr><td bgcolor="#FFDEDE">
               Při editaci dopravy nastala neznámá chyba: <br>'.mysql_error().'
               </td></tr></table>
               ';
    }
}
?>



<?php /* Doprava zboží */
echo '
      <form action="" method="post">
      ';
/* Výpis */
Echo '<p><table border="0">';
Echo '
             <tr>
              <td align="center"><b>První</b></td>
              <td align="center"><b>Firma</b></td>
              <td align="center"><b>Způsob platby</b></td>
              <td align="center"><b>Cena</b></td>
              <td align="center"><b>Možnosti</b></td>
             </tr>
      ';
$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]doprava ORDER BY prvni DESC,firma ASC");
While( $radek=Mysql_fetch_array($dotaz) ){
       $checked=$radek["prvni"]?"checked":"";
       Echo '
             <tr>
              <td title="Ve výpisu zobrazit tuto dopravu jako první"><input type="radio" name="prvni" value="'.$radek["id"].'" '.$checked.'></td>
              <td><input type="text" name="firma['.$radek["id"].']" value="'.$radek["firma"].'"></td>
              <td><input type="text" name="zpusobPlatby['.$radek["id"].']" value="'.$radek["zpusob_platby"].'"></td>
              <td><input type="text" name="cena['.$radek["id"].']" value="'.$radek["cena"].'" size="3"></td>
              <td style="padding-left: 12px;"><a href="?m=dopravne&smazatDopravu='.$radek["id"].'" style="text-decoration: none;">Smazat</a></td>
             </tr>
             ';
}
Echo '
             <tr>
              <td colspan="4" style="text-align: right;">
                  <input type="hidden" name="zmena_dopravy" value="ok">
                  <input type="submit" name="odeslat" value="Odeslat">
              </td>
             </tr>
      ';
Echo '
             <tr>
              <td colspan="4"><b>Přidat dopravu: </b></td>
             </tr>
             <tr>
              <td></td>
              <td><input type="text" name="firma_nova"></td>
              <td><input type="text" name="zpusobPlatby_novy"></td>
              <td><input type="text" name="cena_nova" size="3"></td>
              <td><input type="submit" name="odeslat" value="Přidat"></td>
             </tr>
      ';
Echo '</table>';

Echo '
      </form>
      ';
?>


<?php Include 'grafika_kon.inc'; ?>
