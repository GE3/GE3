<?php

//urlText
Function urlText($text){
        /**
         * Tato funkce se používá jak pro převod textů do url,
         * tak pro převod názvů souborů na serveru.
         */
        setlocale(LC_CTYPE, "cs_CZ.utf-8");
        $return = iconv("utf-8", "us-ascii//TRANSLIT", $text);
        $return = Str_Replace(Array(" ", "_", ",", "/", "+", "&"), "-", $return); //nahradí mezery, podtržítka apod. pomlčkami
        $return = Str_Replace(Array("----", "---", "--"), "-", $return); //odstraní více pomlček za sebou
        $return = Str_Replace(Array("(",")","!","\"","'","%"), "", $return); //odstraní ().!,"'
        $return = Str_replace('-&end;','',$return.'&end;'); //pomlčky na začátku a konci
        $return = Str_replace('&begin;-','','&begin;'.$return);
        $return = Str_replace('&begin;','',$return);
        $return = Str_replace('&end;','',$return);
        $return = StrToLower($return); //velká písmena nahradí malými.
        $return = preg_replace("|[^a-zA-Z0-9._\-]|Usi", "", $return);
        return $return;
}


//zjisti_z()
Function zjisti_z($tabulka,$udaj,$podminka){
         $dotaz=Mysql_query("SELECT $udaj FROM $tabulka WHERE $podminka");
         $radek=@Mysql_fetch_assoc($dotaz);

         If(!mysql_error()) Return $radek[$udaj];
         If( mysql_error()) Return False;
}

//prohledej_kosik_id()
function prohledej_kosik_id($hledej){
   $pocet=count($_SESSION["kosik_id"]);
   $i=0;
   $nalezeno=FALSE;
   while($i<$pocet){
      if($_SESSION["kosik_id"][$i]==$hledej){
         $nalezeno=($i+1);
         $i=$pocet;   //ukončení hledání
         }
      $i=$i+1;
      }
   return $nalezeno;
   }

//zjisti_informace()
function zjisti_informace($id){
   global $tabulka;
   $dotaz="SELECT * FROM $tabulka WHERE id='$id'";
   $dotaz=mysql_query($dotaz);
   $zjisti_informace=mysql_fetch_array($dotaz);

   return $zjisti_informace;
   }

//count_full() pole, vynechává prázdné hodnoty
function count_full($pole){
   $i=0;
   $pocet=0;
   while($i<count($pole)){
      if($pole[$i]!=""){
         //$pocet=$pocet+1;
         $pocet+=$pole[$i];
         }
      $i=$i+1;
      }

   return $pocet;
   }

function hezkaCena($cena){
        $cena = number_format($cena, 2, ",", " ");
        $cena = ereg_replace(",00", ",-", $cena);
        Return $cena;
}

function celkova_cena(){
   $cena=0;
   $pocet=count($_SESSION["kosik_id"]);
   $i=0;
   while($i<$pocet){
      $zjisti_informace=zjisti_informace($_SESSION["kosik_id"][$i]);
      $cena=$cena+($zjisti_informace["cena"]*$_SESSION["kosik_mnozstvi"][$i]);
      $i=$i+1;
      }

   return $cena;
   }

Function index(){
         $index=true;

         If( $_GET["m"] OR $_GET["produkt"] OR $_GET["novinka"] OR $_GET["kategorie"] OR $_GET["kosik"] OR ($_GET["clanek"] AND $_GET["clanek"]!='home') ){
             $index=false;
         }

         Return $index;
}






function obix_mail($od_koho="",$komu,$predmet="",$zprava="",$cesta="",$kopie=""){



   function create_mime($od_koho,$komu,$predmet,$zprava,$cesta='',$kopie=''){
      $envelope["from"]=$od_koho;
      $envelope["to"]=$komu;
      $envelope["cc"]=$kopie;

      $part1["type"]=TYPEMULTIPART;      //odesíláme víc typů dat
      $part1["subtype"]="mixed";

      /* text emailu */
      $part2["type"]=TYPETEXT;
      $part2["subtype"]="plain";
      $part2["description"]=$predmet;
      $part2["contents.data"]=$zprava;

      /* vložíme přílohu */
      if($cesta){
         $os=fopen($cesta,'r');
         $soubor=fread($os,filesize($cesta));
         fclose($os);

         $part3["type"]=TYPEAPPLICATION;
         $part3["encoding"]=ENCBINARY;
         $part3["subtype"]="octet-stream";
         $part3["description"]=basename($cesta);
         $part3["contents.data"]=$soubor;
         }

      /* slepení jednotlivých částí */
      $body[1]=$part1;
      $body[2]=$part2;
      if($cesta){
         $body[3]=$part3;
         }

      $content=imap_mail_compose($envelope,$body);
      return $content;
      }



   function cestina($text,$jen_zst=''){   //tohle prostě nechápu, má to něco společného s kravinou jménem ISO-8859-2
      $text=stripslashes($text);
      $text=str_replace('ž','z',$text);
      $text=str_replace('š','s',$text);
      $text=str_replace('ť','t',$text);
      if(!$jen_zst){
         $text="=?iso-8859-2?Q?".imap_8bit($text)."?=";   //Problém s češtinou u Odesilatele a Předmětu
         }
      return $text;
      }



   /* Skript na odeslání */

   //kontrola chyb
   $chyby="";
   if(!$komu){$chyby=$chyby."Chybí adresa příjemce";}

   if(!$chyby){
      //zpracování textů
      $predmet=cestina($predmet);
      //$zprava=cestina($zprava,'jen_zst');
      $content=create_mime($od_koho,$komu,$predmet,$zprava,$cesta,$kopie);

      //rozdělení $content na hlavičku a tělo e-mailu
      $header_end=StrPos($content,"\r\n\r\n");
      $header=SubStr($content,0,$header_end+2);
      $body=SubStr($content,$header_end,StrLen($content)-$header_end);

      //oprava hlavičky kvůli češtiny ve zprávě e-mailu a kvůli odeslání přílohy
      $body=str_replace("Content-Type: TEXT/plain; CHARSET=US-ASCII","Content-Type: text/html; charset=windows-1250",$body);
      $body=str_replace("Content-Transfer-Encoding: BASE64","Content-Transfer-Encoding: BASE64\nContent-Disposition: attachment;  filename=\"".basename($cesta)."\"",$body);

      /* odešleme MIME e-mail včetně přílohy */
      if(!$chyby){
         if(mail("",$predmet,$body,$header)){
            if($cesta){unlink($cesta);}
            return TRUE;
            }else{
               return FALSE;
               }
         }
   }else{
      return FALSE;
      }
}
?>
