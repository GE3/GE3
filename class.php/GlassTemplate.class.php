<?php
/**
 * Následují dodatečné funkce,
 * které jsou potřeba k chodu GlassTemplatu.
 */
If( !function_exists('array_intersect_key') ){
    Function array_intersect_key($isec, $arr2){
            $argc = func_num_args();
            For($i = 1; !empty($isec) && $i < $argc; $i++){
                $arr = func_get_arg($i);
                Foreach($isec as $k => $v)
                        If (!isset($arr[$k])) unset($isec[$k]);
            }
            Return $isec;
    }
}
If( !function_exists('arrayAppend') ){
    Function arrayAppend($arr1, $arr2){
            $res = $arr1 + $arr2;
            Foreach(array_intersect_key($arr1, $arr2) as $k => $v){
                    If( is_array($v) && is_array($arr2[$k]) ){
                        $res[$k] = arrayAppend($v, $arr2[$k]);
                    }
        }
        Return $res;
    }
}
If( !function_exists('getMicrotime') ){
    Function getMicrotime(){
            list($usec, $sec) = explode(" ",microtime());
            Return ((float)$usec + (float)$sec);
    }
}



/*******************************************/
/* .-------------------------------------. */
/* | GlassTemplate                       | */
/* |-------------------------------------| */
/* |       Version: 0.4.1                | */
/* | Last updating: 8.3.2010             | */
/* |-------------------------------------| */
/* |        Author: Michal Mikoláš       | */
/* |          Firm: Grafart studio       | */
/* |                www.grafartstudio.cz | */
/* '-------------------------------------' */
/*******************************************/
/**
 * GlassTemplate 0.4
 *   přidána podmínka kompilace pro novější soubory, než jsou zkompilované
 *   opravena chyba v compSudeLiche, která omylem pracovala víceřádkově [0.4.1] 
 * GlassTemplate 0.3
 *   vylepšen modul miniPhp, je teď možné přidávat příkaz Echo, vkládat podmínky atd.
 *   (pořád podporuje jen jeden příkaz v jednom bloku {php: ;} )
 *   přidána oprava html atributu background [0.3.1]
 *   opravena chyba v metodě opravCestuSrc a opravCestuCss, která nepočítala se znakem '-' v cestě k šabloně [0.3.2]  
 *   opravena chyba, kdy se blok nezobrazí pokud neobsahuje žádná data [0.3.3]
 *   opravena chyba s escapováním escapovaných uvozovek \' [0.3.3] (Michal) 
 * GlassTemplate 0.2
 *   (přidána podpora pro použití mod_rewrite)
 * GlassTemplate 0.1
 *   int newBlok(string $nazev);
 *   int prirad(string $nazev, string $hodnota);
 *   string getHtml();
 *   string getTmpl();
 *   bool setTmpl($input);
 *   float getDobaPrubehu(){
 *   string getErrors(){
 */
class GlassTemplate{
      //template a kompilace
      var $tmpl;
      var $tmplName;
      var $tmplComp;
      var $tmplFull;
      var $cestaTpl;
      //data a přiřazování hodnot
      var $data;
      var $dataPocitadlo = array();
      var $dataPocLast;
      //ostatní
      var $errors;
      var $microTime;
      var $conf = array();
      var $log;


      /***************/
      /* KONSTRUKTOR */
      /***************/
      Function GlassTemplate($input, $altInput=''){
              ////////////////////////////
              // Konfigurace a nastavení
              ////////////////////////////
              $CONF = &$GLOBALS["config"];
              $this->conf["saveCompile"] = $CONF["tmpl"]["saveCompile"]; // zapne/vypne ukládání zkompilované šablony do soboru [0 = vypnuto; 1 = zrychlí Template cca 10x]
              $this->conf["microtimePlaces"] = 4;   // počet desetinných míst v době generování stránky
              $this->conf["log"] = 0;               // počet záznamů v logu, než se vypíše [0 = vypnuto; číslo = počet (doporučuju 200)]



              /////////////////////
              // Kód konstruktoru
              /////////////////////
              /**/If($this->conf["log"])$this->log=new PowerLog($this->conf["log"]);
              $this->microTime = getMicrotime();
              $this->data = array();

              /* -- Načtení templatu -- */
              // Načtení templatu ze souboru
              If( !ereg("\n",$input) AND strlen($input)<=255 ){
                  If( file_exists($input) ){                      //název souboru
                      $this->tmpl = file_get_contents($input);
                      $this->tmplName = $input;
                  }
                  Elseif($altInput AND file_exists($altInput)){   //alternativní soubor (načte se pokud první soubor neexistuje)
                      $this->tmpl = file_get_contents($altInput);
                      $this->tmplName = $altInput;
                  }
                  Else{                                           //zapsání chyby, pokud nelze načíst žádný template
                      $this->errors.= 'Soubor se šablonou neexistuje nebo je zadaná špatná cesta; ';
                  }
              }
              // Načtení templatu ze vstupu $input
              Elseif($input){
                  $this->tmpl = $input;
              }

              //znak "\r" někdy dělá problémy, tak ho smažeme
              $this->tmpl = str_replace("\r","",$this->tmpl);
              //escapování jednoduchých uvozovek => ' na \'
              $this->tmpl = str_replace("'","\\'",$this->tmpl);
              $this->tmpl = str_replace("\\\\'","\\\\\\'",$this->tmpl);  //opravení chyby s javascriptovým kódem (\' změnil na \\')
              //smazání mezer v { nazev } v templatu
              $this->tmpl = preg_replace("|\{(\s)*|si",'{',$this->tmpl);
              $this->tmpl = preg_replace("|(\s)*\}|si",'}',$this->tmpl);
              //oprava cest k CSS a k obrázkům
              $this->tmpl = $this->opravCestuCss($this->tmpl);
              $this->tmpl = $this->opravCestuSrc($this->tmpl);
              //úprava bloků "<!-- BEGIN: blok -->..<!-- END: blok -->" na "{blok/}..{/blok}", syntaxe QuickTemplatu
              $this->tmpl = preg_replace("|<!-- BEGIN: ([a-zA-Z0-9_]+) -->|Usi", "{"."$1"."/}", $this->tmpl);
              $this->tmpl = preg_replace("|<!-- END: ([a-zA-Z0-9_]+) -->|Usi", "{/"."$1"."}", $this->tmpl);


              /* -- Vnitřní proměnné -- */
              $this->cestaTpl = preg_replace("|^(.*/)[a-zA-Z0-9_.]*$|si","$1",$this->tmplName);


              ///////////
              // Return
              ///////////
              If( !$this->errors ){
                  Return True;
                }Else{
                  Return False;
              }
      }



      /************************/
      /* -- VNITŘNÍ FUNKCE -- */
      /************************/

      /////////////////////////
      // Funkce pro kompilaci
      /////////////////////////
      Function getNazevBloku($blok){
              /**
               * funkce vrátí název prvního bloku,
               * který ve vstupní šabloně objeví
               */

              //Získání názvu
              $nazevBloku = preg_replace("|^.*\{(first:)?([a-zA-Z0-9_]*)/\}.*$|Usi","$2",$blok);
              $nazevBloku = preg_replace("|\s|","",$nazevBloku);

              Return $nazevBloku;
      }

      Function opravCestuCss($html){
              /**
               * pokud je template ve vnořeném adresáři, nebude souhlasit
               * cesta ke stylům a k jiným vloženým objektům
               */

              //Získání adresáře templatu
              If( eregi("^.*/[a-zA-Z0-9_.\-]*$",$this->tmplName) ){
                  $cestaTpl = preg_replace("|^(.*/)[a-zA-Z0-9_.\-]*$|si","$1",$this->tmplName);
                  $cestaTpl = "http://".$_SERVER["HTTP_HOST"].ereg_replace("/[^/]*$","/",$_SERVER["SCRIPT_NAME"]).$cestaTpl;
              }
              //Nalezení cesty k CSS
              While(preg_match("|<link [^>]*href=\"(?!$cestaTpl)[a-zA-Z0-9_/\-]*\.css\"|Usi",$html)){
                    //nalezení cesty k souboru
                    $cestaCss = preg_replace("|^(.*)<link [^>]*href=\"((?!$cestaTpl)[a-zA-Z0-9_/\-]*\.css)\"(.*)$|Usi","$2", str_replace("\n","",$html));  //z nějakého debilního důvodu mi '.' nebere znak \n
                    $cestaCss = preg_replace("|\s$|Usi","",$cestaCss);
                    //Vložení nové cesty k CSS do templatu
                    $html = str_replace("href=\"$cestaCss\"", "href=\"$cestaTpl"."$cestaCss\"", $html);
              }

              Return $html;
      }
      Function opravCestuSrc($html){
              /**
               * pokud je template ve vnořeném adresáři, nebude souhlasit
               * cesta ke některým souborům (např k obrázkům)
               */

              // Získání adresáře templatu
              If( eregi("^.*/[a-zA-Z0-9_.\-]*$",$this->tmplName) ){
                  $cestaTpl = preg_replace("|^(.*/)[a-zA-Z0-9_.\-]*$|si","$1",$this->tmplName);
                  $cestaTpl = "http://".$_SERVER["HTTP_HOST"].ereg_replace("/[^/]*$","/",$_SERVER["SCRIPT_NAME"]).$cestaTpl;
              }
              // Nahrazení všech src cest
              While(preg_match("|src=\"(?!$cestaTpl)[a-zA-Z0-9_/\-]*\.[a-zA-Z]{2,4}\"|Usi",$html)){
                    //nalezení cesty k souboru
                    $cestaSrc = preg_replace("|^(.*)src=\"((?!$cestaTpl)[a-zA-Z0-9_\/\-]*\.[a-zA-Z]{2,4})\"(.*)$|Usi","$2", str_replace("\n","",$html));  //[a-zA-Z0-9_\-]
                    $cestaSrc = preg_replace("|\s$|Usi","",$cestaSrc);
                    //vložení nové cesty k CSS do templatu
                    $html = str_replace("src=\"$cestaSrc\"", "src=\"$cestaTpl"."$cestaSrc\"", $html);
              }
              // Nahrazení všech background cest
              While(preg_match("|background=\"(?!$cestaTpl)[a-zA-Z0-9_/\-]*\.[a-zA-Z]{2,4}\"|Usi",$html)){
                    //nalezení cesty k souboru
                    $cestaSrc = preg_replace("|^(.*)background=\"((?!$cestaTpl)[a-zA-Z0-9_\/\-]*\.[a-zA-Z]{2,4})\"(.*)$|Usi","$2", str_replace("\n","",$html));  //[a-zA-Z0-9_\-]
                    $cestaSrc = preg_replace("|\s$|Usi","",$cestaSrc);
                    //vložení nové cesty k CSS do templatu
                    $html = str_replace("background=\"$cestaSrc\"", "background=\"$cestaTpl"."$cestaSrc\"", $html);
              }              
              Return $html;
      }
      Function opravCestuNotmpl($html){
              /**
               * pokud je template ve vnořeném adresáři, nebude souhlasit
               * cesta k některým souborům (např k obrázkům)
               */

              // Získání adresáře
              $cestaTpl = "http://".$_SERVER["HTTP_HOST"].ereg_replace("/[^/]*$","/",$_SERVER["SCRIPT_NAME"]);
              // Nahrazení všech cest
              $i=0;
              While($i<100 AND preg_match("|src=\"(?!$cestaTpl)(?!/)[a-zA-Z0-9_\/\-]*\.[a-zA-Z]{2,4}\"|Usi",str_replace("\n","",$html))){
                    //nalezení cesty k souboru
                    $cestaSrc = preg_replace("|^(.*)src=\"((?!$cestaTpl)(?!/)[a-zA-Z0-9_\/\-]*\.[a-zA-Z]{2,4})\"(.*)$|Usi","$2", str_replace("\n","",$html));  //[a-zA-Z0-9_\-]
                    $cestaSrc = preg_replace("|\s*$|Usi","",$cestaSrc);
                    //vložení nové cesty k CSS do templatu
                    $html = str_replace("src=\"$cestaSrc\"", "src=\"$cestaTpl"."$cestaSrc\"", $html);
                    $i++;
              }
              If($i==100) Echo "";//BUG - OPRAVIT (Floor-fitting,Nabídka) "Překročení maximálního počtu oprav ve funkci 'GlassTemplate($this->tmplName)-»opravCestuNotmpl()': $cestaSrc; ";
              Return $html;
      }

      Function getBlok($nazevBloku,$tmpl=''){
              /**
               * vrací část kódu ohraničeného
               * značkami {$nazevBloku/} a {/$nazevBloku}
               */

              //Získání kódu ze vstupu nebo z templatu třídy
              $tmpl = $tmpl?$tmpl:$this->tmplComp;

              // (před názvem bloku může být i slovo 'first:' nebo 'last:', musí se proto ošetřit všechny možnosti)
              //Možnost first-last
              If( eregi("(\{first:$nazevBloku/\}.*\{/last:$nazevBloku\})",$tmpl) ){
                  $tmpl = preg_replace("|^.*(\{first:$nazevBloku/\}.*\{/last:$nazevBloku\}).*$|Usi","$1",$tmpl);
              //Možnost first-nic
              }Elseif( eregi("(\{first:$nazevBloku/\}.*\{/$nazevBloku\})",$tmpl) ){
                  $tmpl = preg_replace("|^.*(\{first:$nazevBloku/\}.*\{/$nazevBloku\}).*$|Usi","$1",$tmpl);
              //Možnost nic-last
              }Elseif( eregi("(\{".$nazevBloku."/\}.*\{/last:$nazevBloku\})",$tmpl) ){
                  $tmpl = preg_replace("|^.*(\{".$nazevBloku."/\}.*\{/last:$nazevBloku\}).*$|Usi","$1",$tmpl);
              //Možnost nic-nic
              }Else{
                  $tmpl = preg_replace("|^.*(\{".$nazevBloku."/\}.*\{/$nazevBloku\}).*$|Usi","$1",$tmpl);
              }

              $tmpl = ereg_replace("\n$","",$tmpl);  //odstranění prázdného řádku nakonci (nevim proč to tam PHP přidávají)
              $tmpl = ereg_replace("\r$","",$tmpl);
              Return $tmpl;
      }

      Function compVnitrekCyklu($blok,$h=1){
              /**
               * funkce vloží do vnitřku bloku $blok podmínky IF tak,
               * aby v templatu bylo možno používat funkce first a last
               */

              //Zjistíme název prvního bloku, který v templatu je
              $nazevBloku = $this->getNazevBloku($blok);

              // (protože každý blok nemusí mít svůj first i last typ, budou se podmínky lišit podle toho, jaké alternativy blok má)
              //Všechny tři typy bloků
              If( eregi("(\{first:$nazevBloku/\}.*\{/last:$nazevBloku\})",$blok) ){
                  //First blok
                  $blok = str_replace('{first:'.$nazevBloku.'/}',"If(\$i$h==1){ \$this->tmplFull.='",$blok);
                  $blok = str_replace('{/first:'.$nazevBloku.'}',"';}",$blok);
                  //Normal blok
                  $blok = str_replace('{'.$nazevBloku.'/}',"If(\$i$h!=1 AND \$i$h!=\$pocet$h){ \$this->tmplFull.='",$blok);
                  $blok = str_replace('{/'.$nazevBloku.'}',"';}",$blok);
                  //Last blok
                  $blok = str_replace('{last:'.$nazevBloku.'/}',"If(\$i$h==\$pocet$h){ \$this->tmplFull.='",$blok);
                  $blok = str_replace('{/last:'.$nazevBloku.'}',"';}",$blok);

              //First a normal blok
              }Elseif( eregi("(\{first:$nazevBloku/\}.*\{/$nazevBloku\})",$blok) ){
                  //First blok
                  $blok = str_replace('{first:'.$nazevBloku.'/}',"If(\$i$h==1){ \$this->tmplFull.='",$blok);
                  $blok = str_replace('{/first:'.$nazevBloku.'}',"';}",$blok);
                  //Normal blok
                  $blok = str_replace('{'.$nazevBloku.'/}',"If(\$i$h!=1){ \$this->tmplFull.='",$blok);
                  $blok = str_replace('{/'.$nazevBloku.'}',"';}",$blok);

              //Normal a last blok
              }Elseif( eregi("(\{".$nazevBloku."/\}.*\{/last:$nazevBloku\})",$blok) ){
                  //Normal blok
                  $blok = str_replace('{'.$nazevBloku.'/}',"If(\$i$h!=\$pocet$h){ \$this->tmplFull.='",$blok);
                  $blok = str_replace('{/'.$nazevBloku.'}',"';}",$blok);
                  //Last blok
                  $blok = str_replace('{last:'.$nazevBloku.'/}',"If(\$i$h==\$pocet$h){ \$this->tmplFull.='",$blok);
                  $blok = str_replace('{/last:'.$nazevBloku.'}',"';}",$blok);

              //Normal blok
              }Else{
                  //Normal blok
                  $blok = str_replace('{'.$nazevBloku.'/}',"\$this->tmplFull.='",$blok);
                  $blok = str_replace('{/'.$nazevBloku.'}',"';",$blok);
              }

              Return $blok;
      }

      Function compMiniPhp($text){
              /**
               * převádí v templatu řetězce {php: php_kód;} na opravdový php kód
               */

              While( eregi("\{php:.*;\}",$text) ){
                  //Získání PHP kódu
                  $vnitrek = preg_replace("|^.*\{php:(.*);\}.*$|Us","$1",$text);
                  $vnitrek = str_replace("\n","",$vnitrek);  //nevím proč, ale php si tam přidává zalamování řádků
                  $vnitrek2 = str_replace("\'","'",$vnitrek);
                  //Nahrazení
                  /**///$text = str_replace("{php:$vnitrek;}","'; @\$this->tmplFull.=($vnitrek2); \$this->tmplFull.='",$text);
                  $vnitrek2 = preg_replace("|^\s*echo |Usi", "@\$this->tmplFull.=", $vnitrek2);
                  $text = str_replace("{php:$vnitrek;}","'; $vnitrek2; \$this->tmplFull.='",$text);
              }

              Return $text;
      }

      Function compSudeLiche($text,$h=1){
              /**
               * převádí řetězce 'hodnota1|hodnota2' na php kód který zajistí,
               * že tyto hodnoty se budou při vkládání střídat
               */

              If( preg_match("/\{[^}]*\|[^}]*\}/Ui", $text)  /*eregi("\{[^}]*\|[^}]*\}",$text)*/ ){
                  //Získání první a druhé hodnoty
                  $part1 = preg_replace("/^.*\{([^}]*)\|[^}]*\}.*$/Ui","\\1",$text);
                  $part2 = preg_replace("/^.*\{[^}]*\|([^}]*)\}.*$/Ui","\\1",$text);
                  /*$part1 = ereg_replace("^.*\{([^}]*)\|[^}]*\}.*$","$1",$text);
                  $part2 = ereg_replace("^.*\{[^}]*\|([^}]*)\}.*$","$1",$text);*/
                  //Nahrazení PHP kódem
                  $text = str_replace('{'.$part1.'|'.$part2.'}',"'; \$this->tmplFull.=( (\$i$h%2==1)?'$part1':'$part2' ); \$this->tmplFull.='",$text);
              }

              Return $text;
      }

      Function vytvorCykly($blok,$h=1 /*hloubka*/){
              /**
               * Funkce nahrazuje názvy bloků cyklem Foreach.
               * Pokud blok obsahuje další vnořený blok, rekurzivně zavolá
               * samu sebe a vnořený blok nahradí vnořeným cyklem.
               */


              /* -- Zjistíme název prvního bloku, který v templatu je -- */
              $nazevBloku = $this->getNazevBloku($blok);


              // (protože každý z vnořených cyklů pracuje s jinýma proměnnýma, se každá hloubka zpracovat jinak)
              /* -- Hloubka 1 -- */
              If( $h==1 ){
                  //Náhrada bloku za cyklus
                  $zacCykl = '
                              \';
                              $i1=1;
                              $pocet1=count($this->data["'.$nazevBloku.'"]);
                              If( is_array($this->data["'.$nazevBloku.'"]) )
                              Foreach($this->data["'.$nazevBloku.'"] as $key=>$value){
                                      ';  $zacCykl=eregi_replace("\n                              ","\n",$zacCykl);
                  $konCykl = '
                                      $i1++;
                              }
                              $this->tmplFull.= \'
                              ';  $konCykl=eregi_replace("\n                              ","\n",$konCykl);
                  $blokComp = $this->compVnitrekCyklu($blok,$h);   //tato funkce převede 'first', 'last' a 'normal' bloky na podmínky, díky kterým fungují
                  $blokComp = $zacCykl.$blokComp.$konCykl;   //vložíme blok do cyklu Foreach

                  //Náhrada vnořených bloků rekurzí
                  While(ereg("\{[a-zA-Z0-9_]*/\}",$blokComp)){
                        //zjistíme název prvního bloku, který v templatu je
                        $nazevBloku2 = $this->getNazevBloku($blokComp);
                        //...vybereme tento blok
                        $blok2 = $this->getBlok($nazevBloku2,$blokComp);
                        //...rekurzí v něm vytvoříme cykly
                        $blokComp2 = $this->vytvorCykly($blok2,$h+1);
                        //...a nahradíme jím starý blok
                        $blokComp = str_replace($blok2,$blokComp2,$blokComp);
                  }

                  //Náhrada proměnných v bloku
                  $blokComp = preg_replace("|\{((?!_)[a-zA-Z0-9_]*)\}|Usi","'.\$value[\"$1\"].'",$blokComp);

                  //Sudé/liché řádky
                  $blokComp = $this->compSudeLiche($blokComp,$h);
              }

              /* -- Hloubka 2 -- */
              If( $h==2 ){
                  //Náhrada bloku za cyklus
                  $zacCykl = '
                              \';
                              $i2=1;
                              $pocet2=count($value["'.$nazevBloku.'"]);
                              If( is_array($value["'.$nazevBloku.'"]) )
                              Foreach($value["'.$nazevBloku.'"] as $key2=>$value2){
                                      ';  $zacCykl=str_replace("\n                            ","\n",$zacCykl);
                  $konCykl = '
                                      $i2++;
                              }
                              $this->tmplFull.= \'
                              ';  $konCykl=str_replace("\n                            ","\n",$konCykl);
                  $blokComp = $this->compVnitrekCyklu($blok,$h);   //tato funkce převede 'first', 'last' a 'normal' bloky na podmínky, díky kterým fungují
                  $blokComp = $zacCykl.$blokComp.$konCykl;   //vložíme blok do cyklu Foreach

                  //Náhrada vnořených loků rekurzí
                  While(ereg("\{[a-zA-Z0-9_]*/\}",$blokComp)){
                        //zjistíme název prvního bloku, který v templatu je
                        $nazevBloku2 = $this->getNazevBloku($blokComp);
                        //...vybereme tento blok
                        $blok2 = $this->getBlok($nazevBloku2,$blokComp);
                        //...rekurzí v něm vytvoříme cykly
                        $blokComp2 = $this->vytvorCykly($blok2,$h+1);
                        //...a nahradíme jím starý blok
                        $blokComp = str_replace($blok2,$blokComp2,$blokComp);
                  }

                  //Náhrada proměnných v bloku
                  $blokComp = preg_replace("|\{((?!_)[a-zA-Z0-9_]*)\}|Usi","'.\$value2[\"$1\"].'",$blokComp);

                  //Sudé/liché řádky
                  $blokComp = $this->compSudeLiche($blokComp,$h);
              }

              /* -- Hloubka 3 a více -- */
              If( $h>2 ){
                  //Náhrada bloku za cyklus
                  $zacCykl = '
                              \';
                              $i'.$h.'=1;
                              $pocet'.$h.'=count($value'.($h-1).'["'.$nazevBloku.'"]);
                              If( is_array($value'.($h-1).'["'.$nazevBloku.'"]) )
                              Foreach($value'.($h-1).'["'.$nazevBloku.'"] as $key'.$h.'=>$value'.$h.'){
                                      ';  $zacCykl=str_replace("\n                        ","\n",$zacCykl);
                  $konCykl = '
                                      $i'.$h.'++;
                              }
                              $this->tmplFull.= \'
                              ';  $konCykl=str_replace("\n                        ","\n",$konCykl);
                  $blokComp = $this->compVnitrekCyklu($blok,$h);   //tato funkce převede 'first', 'last' a 'normal' bloky na podmínky, díky kterým fungují
                  $blokComp = $zacCykl.$blokComp.$konCykl;   //vložíme blok do cyklu Foreach

                  //Náhrada vnořených loků rekurzí
                  While(ereg("\{[a-zA-Z0-9_]*/\}",$blokComp)){
                        //zjistíme název prvního bloku, který v templatu je
                        $nazevBloku2 = $this->getNazevBloku($blokComp);
                        //...vybereme tento blok
                        $blok2 = $this->getBlok($nazevBloku2,$blokComp);
                        //...rekurzí v něm vytvoříme cykly
                        $blokComp2 = $this->vytvorCykly($blok2,$h+1);
                        //...a nahradíme jím starý blok
                        $blokComp = str_replace($blok2,$blokComp2,$blokComp);
                  }

                  //Náhrada proměnných v bloku
                  $blokComp = preg_replace("|\{((?!_)[a-zA-Z0-9_]*)\}|Usi","'.\$value".$h."[\"$1\"].'",$blokComp);

                  //Sudé/liché řádky
                  $blokComp = $this->compSudeLiche($blokComp,$h);
              }


              Return $blokComp;
      }

      Function compile(){
              /**
               * Funkce převede template do php kódu, který má místo proměnných
               * ve složených závorkách skutečné php proměnné
               * a místo bloků php cykly Foreach.
               * GlassTemplate podporuje ukládání už jednou zkompilovaného templatu,
               * takže při spuštění této funkce si nejdřív zkontroluje,
               * jestli už tato zkompilovaná verze existuje.
               */

              /* -- Existuje už zkompilovaný tmpl -- */
              If( $this->conf["saveCompile"] AND $this->tmplName AND file_exists($this->tmplName.".php") AND filectime($this->tmplName)<filectime($this->tmplName.".php") ){
                  //automatické proměnné
                  $this->data["_absDir"] = "http://".$_SERVER["HTTP_HOST"].ereg_replace("/[^/]*$","/",$_SERVER["SCRIPT_NAME"]);
                  $this->data["_errors"] = $this->getErrors();
                  $this->data["_dobaPrubehu"] = $this->getDobaPrubehu();
                  $this->data["_cestaTpl"] = $this->cestaTpl;

                  //načtení zkompilovaného templatu
                  $this->tmplComp = file_get_contents($this->tmplName.".php");
              }

              /* -- Zkomp. verze neexistuje -- */
              Else{
                  /**/If($this->conf["log"]) $this->log->add("začínám kompilovat", $this->tmpl, 1);
                  $this->tmplComp = $this->tmpl;

                  // Převod do PHP
                  While(ereg("\{[a-zA-Z0-9_]*/\}",$this->tmplComp)){
                        $nazevBloku = $this->getNazevBloku($this->tmplComp);
                        $blok = $this->getBlok($nazevBloku);
                        /**/If($this->conf["log"]) $this->log->add("nalezen blok <b>$nazevBloku</b>, vytvářím cykly", $blok, 2);
                        $blokComp = $this->vytvorCykly($blok,1);  //obsahuje i modul 'sudeLiche'
                        /**/If($this->conf["log"]) $this->log->add("kompilace bloku <b>$nazevBloku</b> dokončena", $blokComp, 2);
                        /**/If($this->conf["log"]) $tmplComp_zlh=$this->tmplComp;

                        $this->tmplComp = str_replace($blok,$blokComp,$this->tmplComp);
                        /**/If($this->conf["log"])If($tmplComp_zlh!=$this->tmplComp) $this->log->add("náhrada bloku <b>$nazevBloku</b> v hlavním templatu dokončena", $this->tmplComp, 2);
                        /**/If($this->conf["log"])If($tmplComp_zlh==$this->tmplComp) $this->log->add("náhrada bloku <b>$nazevBloku</b> v hlavním templatu CHYBA!!!", $this->tmplComp, 2);
                  }

                  // Náhrada proměnných
                  $this->data["_absDir"] = "http://".$_SERVER["HTTP_HOST"].ereg_replace("/[^/]*$","/",$_SERVER["SCRIPT_NAME"]);
                  $this->data["_errors"] = $this->getErrors();
                  $this->data["_dobaPrubehu"] = $this->getDobaPrubehu();
                  $this->data["_cestaTpl"] = $this->cestaTpl;
                  $this->tmplComp = preg_replace("|\{([a-zA-Z0-9_]*)\}|Usi","'.\$this->data[\"$1\"].'",$this->tmplComp);

                  //modul 'miniPhp'
                  $this->tmplComp = $this->compMiniPhp($this->tmplComp);

                  // Začátek a konec PHP
                  $zacPhp = '$this->tmplFull=\'';
                  $konPhp = '\';';
                  $this->tmplComp = $zacPhp.$this->tmplComp.$konPhp;

                  // Pokus o zapsání do .php souboru
                  If( $this->conf["saveCompile"] ){
                      @$os = fopen($this->tmplName.".php","w");
                      @chmod($this->tmplName.".php", 0777);
                      @fwrite($os,$this->tmplComp);
                      @fclose($os);
                  }
              }

              Return $this->tmplComp;
      }

      //////////////////////////////////
      // Funkce pro přiřazování hodnot
      //////////////////////////////////
      Function priradArray($nazev, $hodnota, $nazvyCesta=''){
              /**
               * funkce v názvu rozpozná cestu k proměnné a postupně rekurzí
               * vytvoří odpovídající pole
               */

              //Pokud $nazev je cesta + název proměnné
              If( ereg("\.",$nazev) ){
                  //získáme první název a odstraníme ho
                  $prvniNazev = preg_replace("|^([^.]*)\..*$|Usi","$1",$nazev);
                  $nazev = preg_replace("|^$prvniNazev\.?|si","",$nazev);
                  $nazvyCesta = $nazvyCesta?($nazvyCesta.".".$prvniNazev):$prvniNazev;

                  //provedeme rekurzivní vytvoření vnořeného pole
                  $vysl = array($prvniNazev => array($this->dataPocitadlo[$nazvyCesta] => $this->priradArray($nazev,$hodnota,$nazvyCesta, $id)));
              }
              //Pokud $nazev je jen název proměnné
              Else{
                  $vysl = array($nazev => $hodnota);
                  $this->dataPocLast = $this->dataPocitadlo[$nazvyCesta];
              }

              Return $vysl;
      }


      /******************/
      /* VEŘEJNÉ FUNKCE */
      /******************/
      Function newBlok($nazev){
              /**
               * vytvoří nový blok v templatu
               */

              // (nový blok vytvoříme tím, že zvětšíme jeho hodnotu počítadla o jedna)
              $this->dataPocitadlo[$nazev] = $this->dataPocitadlo[$nazev]?($this->dataPocitadlo[$nazev]+1):1;

              // a zařídíme, aby se zobrazil
              $this->prirad("$nazev._zobraz", True);

              Return $this->dataPocitadlo[$nazev];
      }

      Function prirad($nazev, $hodnota){
              /**
               * Přiřadí k názvu proměnné její hodnotu.
               * Do názvu lze zadat i absolutní cestu k proměnné tak,
               * jak je vnořená do bloků v templatu - tato cesta se převede
               * na vícerozměrné pole.
               */

              //vytvoření vícerozměrné pole (pokud je zadána cesta), nebo přiřazení do proměnné (pokud není zadána)
              $pomocnePole = $this->priradArray($nazev,$hodnota);   // (proměnnou bez cesty převede na jednorozměrné pole)
              //přidání tohoto pole do dat templatu
              $this->data = arrayAppend($this->data,$pomocnePole);

              Return $this->dataPocLast;   //vrátíme id bloku proměnné
      }

      Function getHtml(){
              /**
               * funkce vrací konečnou stránku, zaplněnou požadovanými daty
               */

              //načtení uživatelských funkcí v templatu
              $fceCesta = ereg_replace("/[^/]*$", "/", $this->tmplName)."_functions.php";
              If(file_exists($fceCesta)) Include_once $fceCesta;

              $this->compile();  //create PHP in template
              Eval($this->tmplComp);// or print("Testovací mód. Šablona $this->tmplName. Zkompilovaný zdroj: <p>\r\n\r\n<textarea style=width:100%;height:400px;><?php ".str_replace("textarea","text area",$this->tmplComp)."\r\n?"."></textarea>");  //run PHP scripts in template (saves output to $this->tmplFull)

              // Oprava cesty k obrázkům kvůli mod_rewrite
              $this->tmplFull = $this->opravCestuNotmpl($this->tmplFull);

              Return $this->tmplFull;
      }

      Function getTmpl(){
              /**
               * vrací načtený template včetně značek uvnitř { a }
               */
              Return $this->tmpl;
      }

      Function setTmpl($input){
              /**
               * umožňuje změnit template
               */
              $this->tmpl = $input;
              Return True;
      }

      Function getDobaPrubehu(){
              /**
               * vrací dobu, jakou trvalo vygenerovat celou stránku
               * od vytvoření instance objektu GlassTemplate
               * do zavolání funkce getHtml()
               */
              Return round((getMicrotime() - $this->microTime), $this->conf["microtimePlaces"]);
      }

      Function getErrors(){
              /**
               * Vrací výčet chyb, které byly v průběhu spracovávání dat zjištěny.
               * Chyby jsou odděleny středníkem.
               */
              Return $this->errors;
      }

}









/************/
/* PowerLog */
/************/
/**
 * Používá se při ladění skriptů pro zobrazení průběhu algoritmu
 */
class PowerLog{
      var $i;
      var $max;
      var $logs;

      /* -- Konstruktor -- */
      Function PowerLog($max){
              $this->i = 0;
              $this->max = $max;
              $this->logs = array();
      }

      Function add($nazev, $popis='', $h=1){
              $this->i++;
              $this->logs[$this->i] = array("nazev" => $nazev,
                                            "popis" => $popis,
                                            "h" => $h);

              If( $this->i > $this->max ){
                  Echo $this->getAll();
                  die();
              }
      }

      Function getAll(){
              $html = '<script type="text/javascript">
                        skryj = \'\';
                        function povolSkryvani(){
                           skryj = \'\';
                        }
                        function ukazSkryjLog(element) {
                           var srcElement = document.getElementById(element);

                           if( skryj != \'ne\' ){
                               if(srcElement != null) {
                                  if(srcElement.style.display == "block") {
                                     srcElement.style.display = "none";
                                  }
                                  else {
                                         srcElement.style.display = "block";
                                  }
                                  return false;
                               }
                           }
                        }
                       </script>
                       ';
              Foreach($this->logs as $key=>$value){
                      $html.= '<div style="cursor: pointer; margin-left: '.(($value["h"]-1)*18).'px;" onClick="ukazSkryjLog(\'textarea'.$key.'\');" onMouseOver="document.getElementById(\'divPopis'.$key.'\').style.backgroundColor=\'#FFDDDD\';" onMouseOut="document.getElementById(\'divPopis'.$key.'\').style.backgroundColor=\'#FFFFFF\';">
                                 -&gt; '.$value["nazev"].'
                                 <div id="divPopis'.$key.'" style="width: 900px; border: 1px solid #FFDDDD; margin-left: 20px; padding-bottom: 6px;">
                                   <textarea id="textarea'.$key.'" style="width: 800px; height: 240px; display: none;" onMouseDown="skryj=\'ne\';" onMouseUp="setTimeout(\'povolSkryvani()\',100);">'.$value["popis"].'</textarea>
                                 </div>
                               </div>
                               ';
              }

              Return $html;
      }

      Function getPocet(){
              Return $this->i;
      }
}

/*
$log = new PowerLog();
$log->add("chci se najíst", "mám chuť na sladký tvaroh", 1);
$log->add("vstávám ze židle", "bolí mé nohy ale nakonec to jde", 2);
$log->add("jdu do kuchyně", "pomalu a potichu", 2);
$log->add("beru z ledničky jídlo", "tvaroh, mlíko, granko", 2);
$log->add("tvořím jídlo", "složitý to proces", 2);
$log->add("dávám všechno do hrnce", "nejlépe vysoký hrnec", 3);
$log->add("beru šlehač", "je ve skříni nahoře", 3);
$log->add("všechno to zamíchám", "je to lehké, šlehač je na elektřinu", 3);
$log->add("jím", "vlastně piju, je to řídké", 2);
Echo $log->getAll();
*/
?>