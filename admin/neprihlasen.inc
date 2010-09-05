<HTML>
<HEAD>
   <title>Admin sekce - přihlášení</title>
   <LINK rel="stylesheet" type="text/css" href="images/index000.css">
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <style>
   td{
      font-family: Arial;
      font-size: 80%;
   }
   </style>
</HEAD>
<BODY bgcolor="#EAEAEA" style="font-family: Arial; font-size: 80%;">


    <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
      <tr>
        <td>

    <div align="center">
      <table border="0" width="6%" cellpadding="0" style="border-collapse: collapse" height="324">
        <tr>
          <td>
          <div align="center" style="position: relative;">
            <img src="../templates/<?php Echo $CONF["vzhled"];?>/logo_firmy.jpg" style="position: absolute; top: 26px; right: 26px;">
            <table border="0" width="800" bgcolor="#FFFFFF" height="444" cellspacing="3" cellpadding="3">
              <tr>
                <td valign="top" height="134" width="437">
                <a target="_blank" href="http://www.grafartstudio.cz/">

                <img border="0" src="images/hlavicka.jpg" width="400" height="132"></a></td>
                <td valign="top" height="134" width="342">
                <p align="right">
                <font face="Arial" style="font-size: 8pt" color="#C0C0C0">&nbsp;</font></td>
              </tr>
              <tr>
                <td width="788" colspan="2"><center>
                <h2><font face="Arial" size="3" color="#808080">Vítejte
                v admin sekci Vaší internetové prezentace.</font></h2>

                <h2><font face="Arial" size="2" color="#808080">Prosím
                zadejte heslo:</font></h2>
                </center>
                <div align="center">
                  <form method="post" action="index.php">
                  <table cellpadding="4" width="160">
                    <tr>
                      <td>

                      <font face="Arial" style="font-size: 9pt" color="#808080">
                      Heslo: </font></td>
                      <td width="108"><font face="Arial">
                      <input name="heslo" type="password"></font></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td align="right" width="108">

                      <font face="Arial">
                      <input type="submit" name="odeslat" value="Odeslat" type="submit"></font></td>
                    </tr>
                  </table>
                  </form>
                </div>
                <p><font face="Arial">&nbsp; </font></p>
                <p align="center">&nbsp;</td>
              </tr>

            </table>
          </div>
          </td>
        </tr>
      </table>
    </div>
    <p align="center"><font face="Arial" style="font-size: 9pt">
    <a style="color: #C0C0C0; text-decoration: none" href="http://www.grafartstudio.cz/">
    Copyright <?php Echo (date("Y")-4)." - ".date("Y"); ?> GRAFART STUDIO</a></font></p>
        <p align="center"><span style="color: #C0C0C0">&nbsp; </span></p>

        <p>&nbsp;</td>
      </tr>
    </table>


   <!--
   <a href="http://www.grafartstudio.cz"><img src="hlavicka.jpg" border="0"></a>
   <center><h2>Vítejte v admin sekci e-shopu, prosím zadejte heslo.</h2></center>
   <table align="center">
      <form method="post" action="index.php">
      <tr>
         <td>Heslo: </td><td><input type="password" name="heslo"></td>
      </tr><tr>
         <td></td><td align="right"><input type="submit" name="odeslat" value="Odeslat"></td>
      </tr>
      </form>
   </table>
   <p>&nbsp;
   -->


</BODY>
</HTML>

<!-- This document saved from http://www.grafart.cz/multi-shop/admin/index.php?m=odhlasit_se -->
