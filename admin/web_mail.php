<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="7" align="center">&nbsp;</td>
                  <td width="49" valign="top">
                  <img border="0" src="images/webmail.png" width="47" height="47"></td>
                  <td width="591" align="center" colspan="2">
                  <p align="left"><font face="Arial" size="2">
                  <span style="font-weight: 700">Web Mail</span></font><font face="Arial" style="font-size: 8pt"><br>
                  On-line poštovní klient Vaší e-mailové adresy.</font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>



<script type="text/javascript">
<!--

function setFocus()
{
    if (document.imp_login.imapuser.value == "") {
        document.imp_login.imapuser.focus();
    } else {
        document.imp_login.pass.focus();
    }
}


function submit_login(e)
{

/* Active24 change - only for horde.active24.cz */
    return true;
/* end of Active24 change */
    if (typeof e != 'undefined' && !enter_key_trap(e)) {
        return;
    }

    if (document.imp_login.imapuser.value == "") {
        alert('Prosím zadejte Vaše uživatelské jméno.');
        document.imp_login.imapuser.focus();
        return false;
    } else if (document.imp_login.pass.value == "") {
        alert('Prosím zadejte Vaše heslo.');
        document.imp_login.pass.focus();
        return false;
    } else {
        document.imp_login.loginButton.disabled = true;
        document.imp_login.submit();
        return true;
    }
}
//-->
</script>



<form name="imp_login" id="formPrihlaseni" action="https://webmail.active24.cz/horde/imp/redirect.php" method="post" target="_parent">
<input type="hidden" name="actionID" value="" />
<input type="hidden" name="url" value="" />
<input type="hidden" name="load_frameset" value="1" />
<input type="hidden" name="autologin" value="0" />
<input type="hidden" name="server_key" value="imap" />

<table width="100%">
 <tr>
  <td align="center">
   <table align="center">
    <tr>
     <td align="right" class="light"><strong>Uživatelské jméno</strong></td>
     <td align="left" class="light" nowrap="nowrap">
      <input type="text" tabindex="1" name="imapuser" value="<?php Echo $CONF["mailLogin"];?>" style="direction:ltr" />
     </td>
    </tr>
    <tr>
     <td align="right" class="light"><strong>Heslo</strong></td>
     <td align="left">
      <input type="password" tabindex="2" name="pass" value="<?php Echo $CONF["mailPass"];?>" style="direction:ltr" />
     </td>
    </tr>
    <tr>
     <td>&nbsp;</td>
     <td align="left" class="light">
       <input type="hidden" name="new_lang" value="cs_CZ" />
       <input type="submit" class="button" name="loginButton" tabindex="4" value="Přihlásit se" onclick="return submit_login();" />
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>

<script type="text/javascript">
document.getElementById('formPrihlaseni').submit();
</script>



<?php Include 'grafika_kon.inc'; ?>
