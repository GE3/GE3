<?php
$tmpl->newBlok("novinkyEmailem");
If( $_POST["akce"]=='novinky-emailem-registrace' AND $_POST["email"] ){
    If(!zjisti_z("$CONF[sqlPrefix]novinky_emailem","id","LOWER(email)=LOWER('$_POST[email]')") ){
        Db_query("INSERT INTO $CONF[sqlPrefix]novinky_emailem(email) VALUES ('$_POST[email]')");
        If(mysql_affected_rows()==1) $tmpl->prirad("novinkyEmailem.hlaska", "Děkujeme za váš zájem.");
        Else{$tmpl->prirad("novinkyEmailem.hlaska", "Neznámá chyba."); bugReport("Neznámá chyba při registrace do novinek emailem. Ovlivněných řádků: ".mysql_affected_rows());}
    }
    Else $tmpl->prirad("novinkyEmailem.hlaska", "Již jste registrováni.");
}
?>
