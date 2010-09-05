<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="7" align="center">
                  &nbsp;</td>
                  <td width="49" valign="top">
                  <img border="0" src="images/software_ke_stazeni.png" width="49" height="45"></td>
                  <td width="591" align="center" colspan="2">
                  <p align="left"><span style="font-weight: 700">
                  <font face="Arial" size="2">Software ke stažení</font></span><font face="Arial" style="font-size: 8pt"><br>
                  Zde naleznete software, který vám usnadní a urychlí práci
                  při administraci vašich stránek.</font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php
$obsah = file_get_contents('http://www.grafartstudio.cz/gtp/globals.php?clanek=4');
//$obsah = iconv("WINDOWS-1250", "UTF-8", $obsah);
Echo $obsah;
?>



<?php Include 'grafika_kon.inc'; ?>
