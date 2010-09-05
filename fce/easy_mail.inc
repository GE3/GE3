<?php /* easyMail */
Function easyMail($odKoho,$komu,$predmet,$telo,$prilohy=''){
         /* Počáteční nastavení */
         If(file_exists("class.php/phpmailer/class.phpmailer.php")) require_once("class.php/phpmailer/class.phpmailer.php");
         If(file_exists("../class.php/phpmailer/class.phpmailer.php")) require_once("../class.php/phpmailer/class.phpmailer.php");
         $mail = new PHPMailer();
         $mail->SetLanguage("cz");
         $mail->IsHTML(true);
         $mail->CharSet = "utf-8";

         //$mail->IsSMTP();
         $mail->Host = "";



         /* Hlavička */
         //OdKoho
         If( eregi("<",$odKoho) AND eregi(">",$odKoho) ){
             $odKoho_explode=explode("<",$odKoho);
             $odesilatel_jmeno = $odKoho_explode[0];
             $odesilatel_adresa = str_replace(">","",$odKoho_explode[1]);
         }Else{
               $odesilatel_adresa = $odKoho;
         }
         //Předmět
         $predmet = imap_8bit($predmet);
         $predmet = "=?utf-8?Q?".$predmet."?=";
         //Příjemce
         If( is_array($komu) ){
             Foreach($komu as $key => $value){
                     $mail->AddAddress($value);
             }
         }
         Else $mail->AddAddress($komu);



         /* Tělo emailu */
         $html_text_mailu = $telo;

         $text_mailu = $telo;
         $text_mailu = str_replace("\r", "", $text_mailu);
         //scripty
         $text_mailu = preg_replace("|<script.*</script>|Usi", "", $text_mailu);
         //odřádkování
         $text_mailu = str_replace("<br>","\n",$text_mailu);
         $text_mailu = str_replace("<br />","\n",$text_mailu);
         $text_mailu = str_replace("<br/>","\n",$text_mailu);
         $text_mailu = str_replace("</p>","\n",$text_mailu);
         $text_mailu = str_replace("</tr>","\n",$text_mailu);
         $text_mailu = str_replace(array("\n ","\n&nbsp;"), "\n", $text_mailu);
         $text_mailu = str_replace(array("\n\n\n\n","\n\n\n"), "\n", $text_mailu);
         $text_mailu = str_replace(array("\n\n\n\n","\n\n\n"), "\n", $text_mailu);
         //ostatní
         $text_mailu = preg_replace("|^.*<body[^>]*>|Usi","",$text_mailu);         
         $text_mailu = str_replace("</td>","\t",$text_mailu);
         $text_mailu = str_replace("&nbsp;", " ", $text_mailu);
         $text_mailu = preg_replace("|\n +|", "\n", $text_mailu);
         $text_mailu = strip_tags($text_mailu);



         /* Přílohy */
         If( is_array($prilohy) ){
             Foreach($prilohy as $key=>$value){
                     $mail->AddAttachment($value);
             }
         }
         ElseIf( is_string($prilohy) AND $prilohy!="" ){
                 $mail->AddAttachment($prilohy);
         }



         /* Poslání emailu */
         $mail->From = $odesilatel_adresa;
         $mail->FromName = $odesilatel_jmeno;
         $mail->Subject = $predmet;

         $mail->Body = $html_text_mailu;
         $mail->AltBody = $text_mailu;

         $mail->WordWrap = 80;   //počet znaků na řádku

         if(!$mail->Send()){
            $odeslano=FALSE; //echo $mail->ErrorInfo;
         }else{
            $odeslano=TRUE;
         }



         Return $odeslano;
}


?>
