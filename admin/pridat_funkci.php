<?php
Include '../fce/easy_mail.inc';
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="7" align="center">&nbsp;</td>
                  <td width="49" valign="top">
                  <img border="0" src="images/pridat_funkci.png" width="47" height="47"></td>
                  <td width="591" align="center" colspan="2">
                  <p align="left"><font face="Arial" size="2">
                  <span style="font-weight: 700">Přidat funkci</span></font><font face="Arial" style="font-size: 8pt"><br>
                  Práce naší společnosti je vždy klientům na míru a tak si zde
                  můžete zadat požadavek na funkci, kterou zde postrádáte.</font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>


<?php
If( $_POST["popis_funkce"] ){
    $serverName=$_SERVER["HTTP_HOST"]?$_SERVER["HTTP_HOST"]:$_SERVER["SERVER_NAME"];
    $odKoho=$serverName.' <technici@grafartstudio.cz>';
    $komu="alesnovak@grafartstudio.cz";
    $predmet="Přidat funkci - ".$serverName;
    $mailBody='Na serveru <b>'.$serverName.'</b> byl vyplněn <u>požadavek na novou funkci</u>: <br>
               &nbsp;<br>
               <table border="0">
                   <tr>
                     <td width="128">Vaše jméno:</td>
                     <td>'.$_POST["jmeno"].'</td>
                   </tr>
                   <tr>
                     <td width="80">Kontaktní e-mail: </td>
                     <td>'.$_POST["email"].'</td>
                   </tr>
                   <tr>
                      <td valign="top">Funkci chci:&nbsp;</td>
                      <td>
                        <b>'.$_POST["funkci_chci"].'</b>
                      </td>
                   </tr>
                   <tr>
                     <td>Pracovní název funkce: </td>
                     <td>'.$_POST["funkce"].'</td>
                   </tr>
                   <tr>
                      <td valign="top">Popis funkce: </td>
                      <td>
                         '.str_replace(" ","&nbsp;",str_replace("\n","<br>",$_POST["popis_funkce"])).'
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



<?php /* Článek */
$obsah=file_get_contents('http://www.grafartstudio.cz/gtp/globals.php?clanek=5');
Echo $obsah;
?>
<form action="" method="post">
   <table border="0">
       <tr>
         <td width="128">Vaše jméno:</td>
         <td><input type="text" name="jmeno"></td>
       </tr>
       <tr>
         <td width="80"><nobr>Kontaktní e-mail: </nobr></td>
         <td><input type="text" name="email"></td>
       </tr>
       <tr>
          <td>Funkci chci: </td>
          <td>
            <select name="funkci_chci">
              <option value="Nezávazně poptat">Nezávazně poptat</option>
              <option value="Objednat">Objednat</option>
            </select>
          </td>
       </tr>
       <tr>
         <td>Pracovní název funkce: </td>
         <td><input type="text" name="funkce" value="<?php Echo urldecode($_GET["funkce"]); ?>"></td>
       </tr>
       <tr>
          <td>Popis funkce: </td>
          <td>
             <textarea name="popis_funkce" rows="6" cols="55"></textarea>
          </td>
       </tr>
       <tr>
          <td> </td>
          <td><input type="submit" name="odeslat" value="Odeslat"></td>
       </tr>
   </table>
</form>



<?php Include 'grafika_kon.inc'; ?>
