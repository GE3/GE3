<?php
If( $_GET["a"]=='chci-prodat'){
    $tmplPoptavka = new GlassTemplate("templates/$CONF[vzhled]/chci-prodat.html");
    if( $_POST["odeslat"] ){
        if( $_POST["jmeno"] AND ($_POST["telefon"] OR $_POST["email"]) ){
            $mail = new NMail();
            $mail->setSubject("Vyplněn formulář Chci prodat");
            $mail->setFrom($CONF["mailer"]);
            $mail->addTo(zjisti_z("$CONF[sqlPrefix]nastaveni", "emailAdmin", "id=1"));
            /*if( preg_match(",", zjisti_z("$CONF[sqlPrefix]nastaveni", "emailAdmin", "id=1")) ){ 
                $to_address = explode(",", zjisti_z("$CONF[sqlPrefix]nastaveni", "emailAdmin", "id=1"));
                foreach($to_address as $to){
                        $mail->addTo($to);
                }
            }
            else $mail->addTo(zjisti_z("$CONF[sqlPrefix]nastaveni", "emailAdmin", "id=1"));*/
            $mail->setHtmlBody("<table>
                                <tr><td>* Jméno a příjmení:</td><td>$_POST[jmeno]</td></tr>
                                <tr><td>Adresa:</td><td>$_POST[adresa]</td></tr>
                                <tr><td>** Telefon:</td><td>$_POST[telefon]</td></tr>
                                <tr><td>** E-mail:</td><td>$_POST[email]</td></tr>
                                <tr><td colspan=\"2\">Vzkaz pro realitního makléře:</td></tr>
                                <tr><td colspan=\"2\">$_POST[vzkaz]</td></tr>
                                </table>");
            try{
                /*if( $_FILES['priloha']['name'] ){
                    move_uploaded_file($_FILES['priloha']['tmp_name'], 'userfiles/'.$_FILES['priloha']['name']);
                    $mail->addAttachment('userfiles/'.$_FILES['priloha']['name']);}*/
                $mail->send();
                /*if( $_FILES['priloha']['name'] ) unlink('userfiles/'.$_FILES['priloha']['name']);*/
                $tmplPoptavka->newBlok("zprava1");
            }catch(InvalidStateException $e){
                $tmplPoptavka->newBlok("zprava2");}
        }
        else{
            $tmplPoptavka->newBlok("zprava3");}
    }
    else{$tmplPoptavka->newBlok("formular");}    
    $tmpl->prirad("obsah", $tmplPoptavka->getHtml());

    $tmpl->prirad("navigace", 'Chci prodat');
}
?>