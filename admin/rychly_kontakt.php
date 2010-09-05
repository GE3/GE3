<?php
Include '../fce/easy_mail.inc';
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/komunikujte_s_nami.png" width="48" height="48"></td>
                  <td width="616" colspan="2"><font face="Arial" size="2">
                  <span style="font-weight: 700">Rychlý kontakt na techniky</span></font><font face="Arial" style="font-size: 8pt"><br>
                  V případě jakýchkoliv technických otázek či problémů s
                  čímkoliv nás kontaktujte zde. </font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>


<?php
If( $_POST["otazka"] ){
    $serverName=$_SERVER["HTTP_HOST"]?$_SERVER["HTTP_HOST"]:$_SERVER["SERVER_NAME"];
    $odKoho=$serverName.' <technici@grafartstudio.cz>';
    $komu="technici@grafartstudio.cz";
    $predmet="Vyplněn Rychlý kontakt z ".$serverName;
    $mailBody='Na serveru <b>'.$serverName.'</b> byl vyplněn <u>rychlý kontakt</u>: <br>
               &nbsp;<br>
               <table border="0">
                   <tr>
                     <td width="80">Vaše jméno: </td>
                     <td>'.$_POST["jmeno"].'</td>
                   </tr>
                   <tr>
                     <td width="80">Kontaktní e-mail: </td>
                     <td>'.$_POST["email"].'</td>
                   </tr>
                   <tr>
                     <td>Předmět: </td>
                     <td>'.$_POST["predmet"].'</td>
                   </tr>
                   <tr>
                      <td colspan="2">
                         <b>Popis problému, reklamace, nebo technický dotaz:</b> <br>
                         '.str_replace(" ","&nbsp;",str_replace("\n","<br>",$_POST["otazka"])).'
                      </td>
                   </tr>
               </table>
               ';
     If( easyMail($odKoho,$komu,$predmet,$mailBody) ){
         Echo '<div style="border: 1px solid #008000; color: #008000; font-weight: bold; background-color: #EEFFEE; margin: 4px 2px 18px 2px; padding: 2px 4px 2px 4px;">Informace byly úspěšně odeslány technikům GRAFART STUDIA. </div>';
     }Else{
         Echo '<div style="border: 1px solid #800000; color: #800000; font-weight: bold; background-color: #FFEEEE; margin: 4px 2px 18px 2px; padding: 2px 4px 2px 4px;">Při odesílání nastala neznámá chyba. </div>';
     }
}
?>
<form action="" method="post">
   <table border="0">
       <tr>
         <td width="80">Vaše jméno: </td>
         <td><input type="text" name="jmeno"></td>
       </tr>
       <tr>
         <td width="80"><nobr>Kontaktní e-mail: </nobr></td>
         <td><input type="text" name="email"></td>
       </tr>       
       <tr>
         <td>Předmět: </td>
         <td><input type="text" name="predmet"></td>
       </tr>
       <tr>
          <td colspan="2">
             Popis problému, reklamace nebo technický dotaz:
             <textarea name="otazka" rows="6" cols="60"></textarea>
          </td>
       </tr>
       <tr>
          <td colspan="2"><input type="submit" name="odeslat" value="Odeslat"></td>
       </tr>
   </table>
</form>



<?php Include 'grafika_kon.inc'; ?>
