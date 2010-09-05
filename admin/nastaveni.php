<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/objednavky.png"></td>
                  <td width="616" colspan="2">
                  <p align="left"><span style="font-weight: 700">
                  <font face="Arial" size="2">Nastavení</font></span><font face="Arial" style="font-size: 8pt"><br>
                  Zde si můžete nastavit e-mail, na který budou chodit poptávky a dotazy vašich zákazníků.</font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php /* Změna admin emailu */
If( $_POST["zmena_emailu"] AND $_POST["email"] ){
    $email=$_POST["email"];
    If( Mysql_query("UPDATE $CONF[sqlPrefix]nastaveni SET  emailAdmin='$email' WHERE id=1") ){
      echo '
         <table><tr><td bgcolor="#FFDEDE">
         Email byl úspěšně změněn.
         </td></tr></table>
         ';
      $GLOBALS["emailAdmin"]=$_POST["email"];
      }else{
      echo '
         <table><tr><td bgcolor="#FFDEDE">
         Při změně emailu nastala neznámá chyba.
         </td></tr></table>
         ';
      }
}
?>





<?php
/*******************************/
/* ZMĚNA EMAILU SPRÁVCE SRÁNEK */
/*******************************/
Echo '<b>Změna e-mailu pro upozornění na nové objednávky:</b>
      <form action="" method="post">
      <input type="hidden" name="zmena_emailu" value="ok">
      E-mail: <input type="text" name="email" value="'.zjisti_z("$CONF[sqlPrefix]nastaveni","emailAdmin","id=1").'">
      <input type="submit" name="tlacitko5" value="Změnit">
      </form>';  
?>



<?php Include 'grafika_kon.inc'; ?>
