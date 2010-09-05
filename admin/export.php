<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/ulozit.png" width="47" height="47"></td>
                  <td width="616" colspan="2"><font face="Arial" size="2">
                  <span style="font-weight: 700">Zálohovat</span></font><font face="Arial" style="font-size: 8pt"><br>
                  </font><font face="Arial"><span style="font-size: 8pt">
                  Slouží pro zálohu dat z databáze. </span></font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php /* Vytvoření zálohy dat */
if($_POST["nazev_souboru"]){
   $datum=date("j.n.Y H:i");

   Include 'mysql_dump.inc';

}
?>



<?php /* Zobrazení formuláře */
echo '
   <form action="" method="post">
      Prosím zadejte název souboru, do kterého se má záloha provést. Tento soubor bude obsahovat data z databáze a datum vyexportování.
      <p>
      Název souboru: <input type="text" name="nazev_souboru">
      <input type="submit" name="ok" value="Vygenerovat zálohu">
   </form>
   ';
?>



<?php /* Zobrazení vyśledku */
if($_POST["nazev_souboru"] AND $export){
   echo '
      <hr width="100%">
      <p>Data byla vyexportována do souboru <a href="export/'.$_POST["nazev_souboru"].'.sql">'.$_POST["nazev_souboru"].'.dat</a>. Soubor si můzěte stáhnout, jeho záloha zůstane uložena na serveru.<br>
      Obsah souboru:
      <form action="" method="">
      <textarea rows="20" cols="100">'.$export.'</textarea>
      </form>
      ';
   }
?>



<?php Include 'grafika_kon.inc'; ?>
