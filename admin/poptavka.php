<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/objednavky.png"></td>
                  <td width="616" colspan="2">
                  <p align="left"><span style="font-weight: 700">
                  <font face="Arial" size="2">Poptávka</font></span><font face="Arial" style="font-size: 8pt"><br>
                  Poptávka produktů pro textové stránky Vašeho webu.</font></td>
                </tr>
              </table>
              ';
Include '../class.php/Uzivatel.class.php';
Include '../class.php/Objednavka.class.php';
Include '../class.php/Faktura.class.php';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php
/**********************/
/* Smazání poptávky */
/**********************/
If( $_GET["smaz_poptavku"] AND zjisti_z("$CONF[sqlPrefix]poptavky", "id", "id=$_GET[smaz_poptavku]") ){
    Db_query("DELETE FROM $CONF[sqlPrefix]poptavky WHERE id=$_GET[smaz_poptavku]");
    Echo '<div style="color: #008000; border: 1px solid #008000; padding: 3px 3px 3px 6px;">Úspěšně smazáno.</div><p>';
}
?>



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



<p>


<?php
/*******************************/
/* ZMĚNA EMAILU SPRÁVCE SRÁNEK */
/*******************************/
Echo '<b>Změna e-mailu pro upozornění na nové poptávky:</b>
      <form action="" method="post">
      <input type="hidden" name="zmena_emailu" value="ok">
      E-mail: <input type="text" name="email" value="'.zjisti_z("$CONF[sqlPrefix]nastaveni","emailAdmin","id=1").'">
      <input type="submit" name="tlacitko5" value="Změnit">
      </form>'; 
      
      
      
      
/******************/
/* VÝPIS POPTÁVEK */
/******************/
$tmplPoptavky = new GlassTemplate("../templates/$CONF[vzhled]/poptavka.html");
$dotaz = Db_query("SELECT * FROM $CONF[sqlPrefix]poptavky ORDER BY datum DESC LIMIT 100");
While($radek=mysql_fetch_assoc($dotaz)){
      $tmplPoptavky->newBlok("poptavkaAdminPrehled");
      $tmplPoptavky->prirad("poptavkaAdminPrehled.id", $radek["id"]);
      $tmplPoptavky->prirad("poptavkaAdminPrehled.jmeno", $radek["jmeno"]);
      $tmplPoptavky->prirad("poptavkaAdminPrehled.datum", strtotime($radek["datum"]));
      $tmplPoptavky->prirad("poptavkaAdminPrehled.adresa", $radek["adresa"]);
      $tmplPoptavky->prirad("poptavkaAdminPrehled.telefon", $radek["telefon"]);
      $tmplPoptavky->prirad("poptavkaAdminPrehled.email", $radek["email"]);
      $tmplPoptavky->prirad("poptavkaAdminPrehled.dotaz", $radek["dotaz"]);
      $tmplPoptavky->prirad("poptavkaAdminPrehled.obsah", $radek["obsah"]);
}
Echo $tmplPoptavky->getHtml();
?>



<?php Include 'grafika_kon.inc'; ?>
