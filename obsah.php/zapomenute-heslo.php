<?php
If( $_GET["a"]=='zapomenute-heslo' ){
    /**
     * Vyhledávání
     */
    $tmplHeslo = new GlassTemplate("templates/$CONF[vzhled]/zapomenute-heslo.html");

    If( $_POST["email"] ){
        $radek_zakaznik = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]zakaznici WHERE email='$_POST[email]' LIMIT 1") );
        If( $radek_zakaznik["heslo"] ){
            easyMail($CONF["mailer"], $radek_zakaznik["email"], "E-shop: Zaslání zapomenutého hesla", 
                     "Dobrý den, <br>
                      požádali jste o zaslání přístupových údajů pro web ".eregi_replace("^.*@","",$CONF["mailer"]).": <p>
                      E-mail: $radek_zakaznik[email]<br>
                      Heslo: $radek_zakaznik[heslo] ".'
                      <p>&nbsp;<hr size="1" color="#666666">
                      <div style="font-size: 8pt; color: #666666; text-align: right;">Automaticky vygenerováno internetovým obchodem vytvořeným firmou <a href="http://www.grafartstudio.cz" target="_blank" style="color: #666666;">GRAFART STUDIO</a>.</div>');
            $tmplHeslo->prirad("zprava.text", "Heslo úspěšně odesláno na uvedený e-mail.");
        }
        Else $tmplHeslo->prirad("zprava.text", "Chyba! Tento e-mail zde není registrován."); 
    }

    $tmpl->prirad("obsah", $tmplHeslo->getHtml());
    

    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="'.$CONF["absDir"].'">Úvodní strana</a> » Zapomenuté heslo');

}
?>