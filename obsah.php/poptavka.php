<?php
If( $_GET["a"]=='poptavky'){
 $tmplPoptavka = new GlassTemplate("templates/$CONF[vzhled]/poptavka.html");
if($_POST["odeslat"]){
  if($_POST["predmet"]!='' AND $_POST["specifikace"]!='' AND $_POST["psc"]!='' AND $_POST["email"]!='' AND $_POST["telefon"]!='' AND $_POST["kraj"]!=''){
        $od_koho="poptavka@drevostavby-rob.cz";
        $komu="technici@grafartstudio.cz";
        $predmet="Poptávka";
        $zprava="<table>
        <tr><td>Předmět poptávky</td><td>$_POST[predmet]</td></tr>
        <tr><td>Bližší specifikace poptávky</td><td>$_POST[specifikace]</td></tr>
        <tr><td>Hodnota poptávky</td><td>$_POST[hodnota]</td></tr>
        <tr><td>Finanční služby</td><td>$_POST[sluzby]</td></tr>
        <tr><td></td><td>$_POST[typ]</td></tr>
        <tr><td>&nbsp;</td><td></td></tr>
        <tr><td>Kontaktní údaje:</td><td></td></tr>
        <tr><td>Jméno, příjmení</td><td>$_POST[jmeno]</td></tr>
        <tr><td>Ulice , číslo popisné</td><td>$_POST[ulice]</td></tr>
        <tr><td>PSČ, město</td><td>$_POST[psc]</td></tr>
        <tr><td>Telefon</td><td>$_POST[telefon]</td></tr>
        <tr><td>E-mail</td><td>$_POST[email]</td></tr>
        <tr><td>Kraj</td><td>$_POST[kraj]</td></tr>
        <tr><td>Bližší specifikace, město, obec &nbsp;</td><td>$_POST[blizsi]</td></tr>
        </table>";


            $mail = new NMail();
            $mail->setSubject($predmet);
            $mail->setFrom($od_koho);
            $mail->addTo($komu);
            $mail->setHtmlBody($zprava);
            try{
                if( $_FILES['priloha']['name'] ){
                    move_uploaded_file($_FILES['priloha']['tmp_name'], 'userfiles/'.$_FILES['priloha']['name']);
                    $mail->addAttachment('userfiles/'.$_FILES['priloha']['name']);}
                $mail->send();
                if( $_FILES['priloha']['name'] ) unlink('userfiles/'.$_FILES['priloha']['name']);
                $tmplPoptavka->newBlok("zprava1");
            }catch(InvalidStateException $e){
                $tmplPoptavka->newBlok("zprava2");}
            /*if(easyMail("$od_koho","$komu","$predmet","$zprava")){   //pokud server e-mail odešle...
              $tmplPoptavka->newBlok("zprava1");
              }else{                               //...a pokud ne
                $tmplPoptavka->newBlok("zprava2");
              }*/
   }
   else{
     $tmplPoptavka->newBlok("zprava3");}
  }



    if(!$_POST["odeslat"]){$tmplPoptavka->newBlok("formular");}
    $tmpl->prirad("obsah", $tmplPoptavka->getHtml());

    $tmpl->prirad("navigace", 'Poptávky');
}
?>