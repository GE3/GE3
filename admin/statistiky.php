<?php
Include '../fce/easy_mail.inc';
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/zakaznici.png"></td>
                  <td width="616" colspan="2">
                  <p align="left"><span style="font-weight: 700">
                  <font face="Arial" size="2">Statistiky</font></span><font face="Arial" style="font-size: 8pt"><br>
                  Přehled o počtu a typu návštěv, odkud lidé chodí, statistiky vyhledávačů.
                  </font></td>
                </tr>
              </table>
              ';
?>
<?php Include 'grafika_zac.inc'; ?>
<?php
/**********/
/* FUNKCE */
/**********/
Function GGraph($data, $nazev="", $sirka, $vyska, $typ, $stitkyX="", $stitkyY=""){
        //(funkce převzata z http://programy.wz.cz/clanky/27-php-grafy-pomoci-google-chart-api/)
        If( !function_exists("Dia") ){
            Function Dia($text){
                    $trans = array("á"=>"a", "ä"=> "a", "č"=>"c", "ď"=>"d", "é"=>"e", "ě"=>"e", "ë"=>"e", "í"=>"i", "&#239;"=>"i", "ň"=>"n", "ó"=>"o", "ö"=>"o", "ř"=>"r", "š"=>"s", "ť"=>"t", "ú"=>"u", "ů"=>"u", "ü"=>"u", "ý"=>"y", "&#255;"=>"y", "ž"=>"z", "Á"=>"A", "Ä"=>"A", "Č"=>"C", "Ď"=>"D", "É"=>"E", "Ě"=>"E", "Ë"=>"E", "Í"=>"I", "&#207;"=>"I", "Ň"=>"N", "Ó"=>"O", "Ö"=>"O", "Ř"=>"R", "Š"=>"S", "Ť"=>"T", "Ú"=>"U", "Ů"=>"U", "Ü"=>"U", "Ý"=>"Y", "&#376;"=>"Y", "Ž"=>"Z");
                    $preklad = strtr($text, $trans); //odstranění diakritiky
                    Return $preklad;
            }
        }
        //připravení názvu - pokud nebude zadaný, v adrese se parametr neobjeví
        If(trim($nazev)!="") $nazev2="&chtt=".Dia($nazev);
       
        //změna typu grafu na jejich google chart ekvivalenty (možno vynechat, ale české názvy jsou praktičtější)
        $typ = str_replace(array("3dkolacovy", "kolacovy", "sloupcovyh", "sloupcovyv", "carovy", "carovy2", "vennuv", "bodovy", "radar"), 
                           array("p3", "p", "bhg", "bvg", "lc", "ls", "v", "s", "r"), strtolower($typ));
       
        If( $typ!="lc" || $stitkyY=="" && $stitkyX!="" ){ // štítky pro koláčové grafy
            //odstranění diakritiky a převedení pole na řetězec
        } 
        Elseif( $stitkyY!="" && $stitkyX!="" ){ // štítky pro ostatní grafy
        }
       
        If( $typ=="p3" || $typ=="p" ){ // úprava pro koláčové grafy
           
            //odstranění diakritiky a převedení pole na řetězec
            If($stitkyX!="") $stitky2="&chl=".Dia(implode("|", $stitkyX));
           
            //sečtení prvků pole
            For($i=0; $i<count($data); $i++)
                $soucet+= $data[$i];
           
            //převedení dat na procenta - základ je součet
            For($i=0; $i<count($data); $i++){
                $data[$i] = $data[$i]/$soucet*100;
                $data[$i] = round($data[$i], 1);
            }
        } 
        Else{ // úprava pro ostatní grafy
           
            //odstranění diakritiky a převedení pole se štítky pro osu X a Y na řetězec
            If( $stitkyX!="" && $stitkyY!="" ){
                $stitky2 = "&chxt=x,y&chxl=0:|".Dia(implode("|", $stitkyX));
                $stitky2.= "|1:|".Dia(implode("|", $stitkyY));
            }
           
            //převedení dat na procenta - základ je maximum
            $maxd = max($data);
            For ($i=0; $i<count($data); $i++){
                $data[$i] = $data[$i]/$maxd*100;
                $data[$i] = round($data[$i], 1);
            }
        }
        //výpis dat v tzv. Text encoding
        $data2 = "t:".implode(",", $data);
       
        //vypsání jednotlivých částí grafu
        $g = "<img src=\"http://chart.apis.google.com/chart?";
        $g.= "cht=$typ";
        $g.= "&chd=$data2";
        $g.= $stitky2;
        $g.= "&chs=".$sirka."x".$vyska;
        $g.= "&chf=bg,s,EFEFEF";
        $g.= "$nazev2\"";
        $g.= " alt=\"$nazev\"";
        $g.= " width=\"$sirka\"";
        $g.= " height=\"$vyska\" />";
        return $g;
}


Function getAlias($text){
        // Vytvoření číselného hashe
        $hash = hash('crc32', $text);
        $hash = ereg_replace("[a-z]", "", $hash);
        
        // Načtení souboru a informací o něm
        $aliasy = file_get_contents("statistiky.aliasy.txt");
        $aliasy = str_replace("\r", "", $aliasy);
        $aliasy = explode("\n", $aliasy);
        $pocet = count($aliasy);
        
        // Přiťazení aliasu
        $zbytek = ($hash%($pocet+1));
        $alias = $aliasy[$zbytek];
        
        Return $alias;
}


Function timeToStr($time){
        // Konstantní časové hodnoty (počty sekund)
        $tyden = 60*60*24*7;  
        $den = 60*60*24; 
        $hodina = 60*60;
        $minuta = 60;
        
        If( $time>0 ){
            // Výpočty
            $tydnu = ($time>$tyden)? floor($time/$tyden): 0;
            $zbytek = $time - $tydnu*$tyden; 
            
            $dnu = ($zbytek>$den)? floor($zbytek/$den): 0;
            $zbytek = $zbytek - $dnu*$den;
            
            $hodin = ($zbytek>$hodina)? floor($zbytek/$hodina): 0;
            $zbytek = $zbytek - $hodin*$hodina;
            
            $minut = ($zbytek>$minuta)? floor($zbytek/$minuta): 0;
            $zbytek = $zbytek - $minut*$minuta;
            
            $sekund = $zbytek;
            
            // Vytvoření textového formátu
            $text = '';
            $informaci = 0;
            If( $tydnu ){
                $text.= "$tydnu týdnů, ";
                $informaci++;
            }
            If( $dnu ){
                $text.= "$dnu dnů, ";
                $informaci++;
            }        
            If( $hodin AND $informaci<2 ){
                $text.= "$hodin hodin, ";
                $informaci++;
            }     
            If( $minut AND $informaci<2 ){
                $text.= "$minut minut, ";
                $informaci++;
            }               
            If( $sekund AND $informaci<2 ){
                $text.= "$sekund sekund, ";
                $informaci++;
            }
        }            
        
        Return ereg_replace(", $", "", $text);
}


Function identifier($user_agent){
        $nazev = 'neznámý [<span title="'.str_replace('"','´',$user_agent).'" style="cursor: pointer;" onMouseOver="this.style.textDecoration=\'underline\';" onMouseOut="this.style.textDecoration=\'none\';">?</span>]';
        
        If( eregi("googlebot-image", $user_agent) ) $nazev = '<a href="http://images.google.cz/imghp?hl=cs&tab=wi" target="_blank" style="text-decoration: none;">Google-Images</a>';
        Elseif( eregi("google", $user_agent) ) $nazev = '<a href="http://www.google.cz" target="_blank" style="text-decoration: none;">Google</a>';
        Elseif( eregi("seznam", $user_agent) ) $nazev = '<a href="http://www.seznam.cz" target="_blank" style="text-decoration: none;">Seznam</a>';        
        Elseif( eregi("jyxo", $user_agent) ) $nazev = '<a href="http://www.jyxo.cz" target="_blank" style="text-decoration: none;">Jyxo</a>';
        Elseif( eregi("centrum", $user_agent) ) $nazev = '<a href="http://www.centrum.cz" target="_blank" style="text-decoration: none;">Centrum</a>';
        Elseif( eregi("yahoo", $user_agent) ) $nazev = '<a href="http://www.yahoo.com" target="_blank" style="text-decoration: none;">Yahoo</a>';
        Elseif( eregi("msnbot-media", $user_agent) ) $nazev = '<a href="http://www.msn.com" target="_blank" style="text-decoration: none;">MSN-Media</a>';
        Elseif( eregi("msn", $user_agent) ) $nazev = '<a href="http://www.msn.com" target="_blank" style="text-decoration: none;">MSN</a>';
        
        Return $nazev;                                              
}
?>



<div style="padding-left: 12px; padding-bottom: 2px; border-bottom: 1px solid black;">
<a href="?m=statistiky">Shrnutí</a> |
<a href="?m=statistiky&n=referer">Odkud lidé přišli</a> |
<a href="?m=statistiky&n=vyhledavace">Aktualizace vyhledávačů</a>
<?php Echo $CONF["mod"]=='ge3'? '| <a href="?m=statistiky&n=zbozi">Nejnavštěvovanější zboží</a>': ''; ?>
</div>




<?php
/***********/
/* SHRNUTÍ */
/***********/
If( !$_GET["n"] ){
    /////////////
    // Přehledy
    /////////////
    
    /* -- Celkový přehled -- */
    $radek = mysql_fetch_assoc( Mysql_query("SELECT COUNT(*),SUM(pocet) FROM $CONF[sqlPrefix]statNavstevy ") );
    $celkemNavstev = $radek["COUNT(*)"];
    $zobrazenoStranek = $radek["SUM(pocet)"];
    $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]statNavstevy GROUP BY ip");
    $navstevniku = mysql_num_rows($dotaz);    
    Echo '<h3>Přehledy</h3>
          <div style="width: 50%; float: left;">
          <table width="100%">
            <tr>
              <td bgcolor=#CFCFCF colspan="2"><b>Celkový přehled</b></td>
            </tr>
            <tr>
              <td bgcolor=#DFDFDF width="136">Celkem návštěv: </td>
              <td bgcolor=#EFEFEF>'.$celkemNavstev.' </td>              
            </tr>
            <tr>
              <td bgcolor=#DFDFDF>Unikátních návštěvníků: </td>
              <td bgcolor=#EFEFEF>'.$navstevniku.' </td>              
            </tr>            
            <tr>
              <td bgcolor=#DFDFDF>Zobrazeno stránek: </td>
              <td bgcolor=#EFEFEF>'.$zobrazenoStranek.' </td>              
            </tr>
          </table>
          </div>';
          
    /* -- Tento měsíc -- */
    $radek = mysql_fetch_assoc( Mysql_query("SELECT COUNT(*),SUM(pocet) FROM $CONF[sqlPrefix]statNavstevy WHERE rok='".date("Y")."' AND mesic='".date("n")."' ") );
    $celkemNavstev = $radek["COUNT(*)"];
    $zobrazenoStranek = $radek["SUM(pocet)"];
    $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]statNavstevy WHERE rok='".date("Y")."' AND mesic='".date("n")."' GROUP BY ip");
    $navstevniku = mysql_num_rows($dotaz);    
    Echo '<div>
          <table width="49%">
            <tr>
              <td bgcolor=#CFCFCF colspan="2"><b>Tento měsíc</b></td>
            </tr>
            <tr>
              <td bgcolor=#DFDFDF width="136">Celkem návštěv: </td>
              <td bgcolor=#EFEFEF>'.$celkemNavstev.' </td>              
            </tr>
            <tr>
              <td bgcolor=#DFDFDF>Unikátních návštěvníků: </td>
              <td bgcolor=#EFEFEF>'.$navstevniku.' </td>              
            </tr>            
            <tr>
              <td bgcolor=#DFDFDF>Zobrazeno stránek: </td>
              <td bgcolor=#EFEFEF>'.$zobrazenoStranek.' </td>              
            </tr>
          </table>
          </div>
          
          <div style="clear: both;">&nbsp;</div>          
          ';
          
          
          
    //////////////////////////////////////
    // Graf - Návštěvy za poslední měsíc
    //////////////////////////////////////
    Echo '<h3>Návštěvy za měsíc</h3>';
    $nazvyMesicu = array();
    $nazvyMesicu[1] = "Leden";
    $nazvyMesicu[2] = "Únor";
    $nazvyMesicu[3] = "Březen";
    $nazvyMesicu[4] = "Duben";
    $nazvyMesicu[5] = "Květen";
    $nazvyMesicu[6] = "Červen";
    $nazvyMesicu[7] = "Červenec";
    $nazvyMesicu[8] = "Srpen";
    $nazvyMesicu[9] = "Září";
    $nazvyMesicu[10] = "Říjen";
    $nazvyMesicu[11] = "Listopad";
    $nazvyMesicu[12] = "Prosinec";

    $dotaz2 = Mysql_query("SELECT DISTINCT mesic FROM $CONF[sqlPrefix]statNavstevy WHERE rok=".date("Y")." ORDER BY mesic ASC ");
    $grafy = '';
    $meny = '';
    $script = '';
    While($radek2=mysql_fetch_assoc($dotaz2)){
          $rok = date("Y");
          $mesic = $radek2["mesic"];
          // Kolik má tento měsíc dnů
          $pocetDnu = cal_days_in_month(CAL_GREGORIAN, $mesic, $rok);
          // Zjištění dnů a počtu návštěv
          $dotaz = Mysql_query("SELECT *,COUNT(pocet) FROM $CONF[sqlPrefix]statNavstevy WHERE rok=$rok AND mesic=$mesic GROUP BY den ORDER BY den ASC ");
          $data = array();
          $max = 0;
          While($radek=mysql_fetch_assoc($dotaz)){
                $data[$radek["den"]] = $radek["COUNT(pocet)"];
                If($radek["COUNT(pocet)"]>$max) $max=$radek["COUNT(pocet)"]; 
          }
          For($i=1; $i<=$pocetDnu; $i++){
              If(!isset($data[$i])) $data[$i] = 0;  //doplnění dnů, ve které nikdo nepřišel, nulami
          }
          ksort($data);   //odstranění problému s promíchanými dny
          // Popisky X
          $popiskyX = array();
          For($i=1; $i<=$pocetDnu; $i++){
              If($i%2==1 OR $i==$pocetDnu) $popiskyX[]=$i;
              Else $popiskyX[]=" ";
          }
          // Vytvoření grafu
          $meny.= '<span id="spanMesic'.$mesic.'" style="cursor: pointer;" onClick="ukazMesicGraf('.$mesic.')">'.$nazvyMesicu[$mesic].'</span> | ';
          $script.= 'document.getElementById(\'divGraf'.$mesic.'\').style.display=\'none\'; 
                     document.getElementById(\'spanMesic'.$mesic.'\').style.textDecoration=\'none\';';
          $grafy.= '<div id="divGraf'.$mesic.'" style="display: none; background-color: #EFEFEF;">
                      '.GGraph($data, $nazvyMesicu[$mesic], 608, 200, "carovy", $popiskyX, array("",round($max/4),round($max/2),round($max*3/4),$max) ).'
                      <table width="596" border="1" align="center" style="margin: 6px 6px 6px 6px;">
                        <tr><td>Den</td><td>Celkem návštěv</td><td>Unikátních návštěvníků</td><td>Zobrazeno stránek</td></tr>';
          $dotaz3 = mysql_query("SELECT *,SUM(pocet),COUNT(pocet) FROM $CONF[sqlPrefix]statNavstevy WHERE rok=$rok AND mesic=$mesic GROUP BY den ORDER BY den ASC ");
          $celkemSoucet=0; $unikatnichSoucet=0; $zobrazenoStranekSoucet=0;
          While($radek3=mysql_fetch_assoc($dotaz3)){
                $dotaz4 = mysql_query("SELECT * FROM $CONF[sqlPrefix]statNavstevy WHERE rok=$rok AND mesic=$mesic AND den=$radek3[den] GROUP BY ip"); 
                $grafy.= '<tr><td>'.$radek3["den"].'</td><td>'.$radek3["COUNT(pocet)"].'</td><td>'.mysql_num_rows($dotaz4).'</td><td>'.$radek3["SUM(pocet)"].'</td></tr>';
                $celkemSoucet+= $radek3["COUNT(pocet)"]; 
                $zobrazenoStranekSoucet+= $radek3["SUM(pocet)"];
          }
          $dotaz4 = mysql_query("SELECT * FROM $CONF[sqlPrefix]statNavstevy WHERE rok=$rok AND mesic=$mesic GROUP BY ip"); 
          $grafy.= '    <tr><td>součet: </td><td><strong>'.$celkemSoucet.'</strong></td><td><strong>'.mysql_num_rows($dotaz4).'</strong></td><td><strong>'.$zobrazenoStranekSoucet.'</strong></td></tr>
                      </table>
                    </div>'; 
    }
    
    /* -- Zobrazení grafů -- */     
    Echo '<div style="text-align: center; font-size: 8pt; color: #666666; background-color: #CFCFCF;">
            '.ereg_replace(" \| $","",$meny).' 
          </div>';
    Echo $grafy;
    Echo '<script type="text/javascript">
          function ukazMesicGraf(mesic){
                  '.$script.'
                  document.getElementById(\'divGraf\'+mesic).style.display=\'block\';
                  document.getElementById(\'spanMesic\'+mesic).style.textDecoration=\'underline\';
          }
          ukazMesicGraf('.date("n").');
          </script>'; 
          
          
          
          
    //////////////////////////////////////
    // Graf - Návštěvy za poslední roky
    //////////////////////////////////////
    Echo '<h3>Návštěvy za rok</h3>';

    $dotaz2 = Mysql_query("SELECT DISTINCT rok FROM $CONF[sqlPrefix]statNavstevy ORDER BY rok ASC, mesic ASC, den ASC ");
    $grafy = '';
    $meny = '';
    $script = '';
    While($radek2=mysql_fetch_assoc($dotaz2)){
          $rok = $radek2["rok"];
          // Zjištění dnů a počtu návštěv
          $dotaz = Mysql_query("SELECT *,COUNT(pocet) FROM $CONF[sqlPrefix]statNavstevy WHERE rok=$rok GROUP BY mesic ORDER BY mesic ASC, den ASC ");
          $data = array();
          $max = 0;
          While($radek=mysql_fetch_assoc($dotaz)){
                $data[] = $radek["COUNT(pocet)"];
                If($radek["COUNT(pocet)"]>$max) $max=$radek["COUNT(pocet)"]; 
          }
          // Popisky X
          $popiskyX = array();
          /*For($i=1; $i<=$pocetDnu; $i++){
              If($i%2==1 OR $i==$pocetDnu) $popiskyX[]=$i;
              Else $popiskyX[]=" ";
          }*/
          // Vytvoření grafu
          $meny.= '<span id="spanRok'.$rok.'" style="cursor: pointer;" onClick="ukazRokGraf('.$rok.')">'.$rok.'</span> | ';
          $script.= 'document.getElementById(\'divGraf'.$rok.'\').style.display=\'none\'; 
                     document.getElementById(\'spanRok'.$rok.'\').style.textDecoration=\'none\';';
          $grafy.= '<div id="divGraf'.$rok.'" style="display: none; background-color: #EFEFEF;">
                      '.GGraph($data, $rok, 608, 200, "carovy", $popiskyX, array("",round($max/4),round($max/2),round($max*3/4),$max) ).'
                      <table width="596" border="1" align="center" style="margin: 6px 6px 6px 6px;">
                        <tr><td>Měsíc</td><td>Celkem návštěv</td><td>Unikátních návštěvníků</td><td>Zobrazeno stránek</td></tr>';
          $dotaz3 = mysql_query("SELECT *,SUM(pocet),COUNT(pocet) FROM $CONF[sqlPrefix]statNavstevy WHERE rok=$rok GROUP BY mesic ORDER BY mesic ASC ");
          $celkemSoucet=0; $unikatnichSoucet=0; $zobrazenoStranekSoucet=0;
          While($radek3=mysql_fetch_assoc($dotaz3)){
                $dotaz4 = mysql_query("SELECT * FROM $CONF[sqlPrefix]statNavstevy WHERE rok=$rok AND mesic=$radek3[mesic] GROUP BY ip"); 
                $grafy.= '<tr><td>'.$nazvyMesicu[$radek3["mesic"]].'</td><td>'.$radek3["COUNT(pocet)"].'</td><td>'.mysql_num_rows($dotaz4).'</td><td>'.$radek3["SUM(pocet)"].'</td></tr>';
                $celkemSoucet+= $radek3["COUNT(pocet)"]; 
                $zobrazenoStranekSoucet+= $radek3["SUM(pocet)"];
          }
          $dotaz4 = mysql_query("SELECT * FROM $CONF[sqlPrefix]statNavstevy WHERE rok=$rok GROUP BY ip");
          $grafy.= '    <tr><td>součet: </td><td><strong>'.$celkemSoucet.'</strong></td><td><strong>'.mysql_num_rows($dotaz4).'</strong></td><td><strong>'.$zobrazenoStranekSoucet.'</strong></td></tr>
                      </table>
                    </div>'; 
    }
    
    /* -- Zobrazení grafů -- */     
    Echo '<div style="text-align: center; font-size: 8pt; color: #666666; background-color: #CFCFCF;">
            '.ereg_replace(" \| $","",$meny).' 
          </div>';
    Echo $grafy;
    Echo '<script type="text/javascript">
          function ukazRokGraf(rok){
                  '.$script.'
                  document.getElementById(\'divGraf\'+rok).style.display=\'block\';
                  document.getElementById(\'spanRok\'+rok).style.textDecoration=\'underline\';
          }
          ukazRokGraf('.date("Y").');
          </script>';           
          
          
                         
          
    //////////////////////
    // Poslední návštěvy
    //////////////////////
    /*Echo "<br>&nbsp;<h3>Posledních 100 návštěvníků</h3>
          <table border=0 width=100%>
            <tr>
              <td bgcolor=#CFCFCF width=40><strong>Alias</strong></td> 
              <td bgcolor=#CFCFCF width=120><strong>Datum</strong></td>
              <td bgcolor=#CFCFCF width=140><strong>Operační systém</strong></td>
              <td bgcolor=#CFCFCF><strong>Prohlížeč</strong></td>
              <td bgcolor=#CFCFCF width=130><strong>Prohlídnutých stránek</strong></td>     
            </tr>
          </table>
          <div style=\"\"> <!--height: 200px; overflow: auto;-->
          <table border=0 width=100%>
          ";
            
    $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]statNavstevy ORDER BY time DESC LIMIT 100");
    $i = 1;
    While($radek = mysql_fetch_assoc($dotaz)){
          $bgcolor = ($i%2)? "#EFEFEF": "#DFDFDF";
          Echo "<tr>
                  <td bgcolor=$bgcolor width=40><i>".( !eregi("vyhledávač",$radek["os"])? getAlias($radek["ip"]): '' )."</i></td>
                  <td bgcolor=$bgcolor width=120>".date("j.n.Y G:i",$radek["time"])."</td>
                  <td bgcolor=$bgcolor width=140>$radek[os]</td>
                  <td bgcolor=$bgcolor>$radek[prohlizec]</td>
                  <td bgcolor=$bgcolor width=110>$radek[pocet]</td>
                 </tr>";
          $i++;                            
    }
    Echo "</table>
          </div>";*/
     
}



/*******************************/
/* REFERER - ODKUD LIDÉ PŘIŠLI */
/*******************************/
If( $_GET["n"]=='referer' ){
    
    /////////////////////
    // Z jakých serverů
    /////////////////////
    Echo '<h3>Z jakých serverů zde lidé přicházejí</h3>';
    $dotaz = Mysql_query("SELECT *,SUM(pocet) FROM $CONF[sqlPrefix]statReferer GROUP BY server ORDER BY SUM(pocet) DESC ") or die(mysql_error());
    $i = 1;
    While($radek=mysql_fetch_assoc($dotaz)){
          Echo '<span style="cursor: pointer; font-weight: bold;" onClick="ukazSkryj(\'divOdkazy'.$i.'\');">
                  <img src="images/plus.png" border="0"> '.$radek["server"].'
                </span> ['.$radek["SUM(pocet)"].'] <br>';

          Echo '<div id="divOdkazy'.$i.'" style="padding-left: 24px; display: none;">';
          $dotaz2 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]statReferer WHERE server='$radek[server]' ORDER BY pocet DESC LIMIT 10 ");
          While($radek2=mysql_fetch_assoc($dotaz2)){
                $urlText = (strlen($radek2["url"])>60)? (substr($radek2["url"],0,60).'...'): $radek2["url"];
                Echo '<a href="'.$radek2["url"].'" style="text-decoration: none;">'.$urlText.'</a> ['.$radek2["pocet"].'] <br>';
          }
          Echo '</div>';
          
          $i++;
    }
    
    
    //////////////////
    // Klíčová slova
    //////////////////
    Echo '&nbsp;<br><h3>Úspěšná klíčová slova ve vyhledávačích</h3>';
    $dotaz = Mysql_query("SELECT *,SUM(pocet) FROM $CONF[sqlPrefix]statReferer WHERE fraze!='' GROUP BY server ORDER BY SUM(pocet) DESC ") or die(mysql_error());
    $i = 1;
    While($radek=mysql_fetch_assoc($dotaz)){
          Echo '<span style="cursor: pointer; font-weight: bold;" onClick="ukazSkryj(\'divKeywords'.$i.'\');">
                  <img src="images/plus.png" border="0"> '.$radek["server"].'
                </span> ['.$radek["SUM(pocet)"].'] <br>';

          Echo '<div id="divKeywords'.$i.'" style="padding-left: 24px; display: none;">';
          $dotaz2 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]statReferer WHERE server='$radek[server]' AND fraze!='' ORDER BY pocet DESC LIMIT 10 ");
          While($radek2=mysql_fetch_assoc($dotaz2)){
                Echo '<a href="'.$radek2["url"].'" style="text-decoration: none;">'.$radek2["fraze"].'</a> ['.$radek2["pocet"].'] <br>';
          }
          Echo '</div>';
          
          $i++;
    }    
}



/***************/
/* VYHLEDÁVAČE */
/***************/
If( $_GET["n"]=='vyhledavace' ){

    /////////////////////////
    // Přehledy vyhledávačů
    /////////////////////////
    Echo "<h3>Přehledy vyhledávačů</h3>
          <table border=0 width=100%>
            <tr>
              <td bgcolor=#CFCFCF><strong>Vyhledávač</strong></td> 
              <td bgcolor=#CFCFCF><strong>Poslední návštěva</strong></td>
              <td bgcolor=#CFCFCF><strong>Celkem návštěv</strong></td>     
              <td bgcolor=#CFCFCF><strong>Jak často zde chodí</strong></td>     
              <td bgcolor=#CFCFCF><strong>Doba mezi dvěma návštěvami</strong></td>
            </tr>";
            
    $i = 1;
    $vyhledavace = array();
    $vyhledavace["Google"] = "google";
    $vyhledavace["Seznam"] = "seznam";
    $vyhledavace["Jyxo"] = "jyxo";
    $vyhledavace["Centrum"] = "centrum";
    $vyhledavace["Yahoo"] = "yahoo";
    $vyhledavace["MSN"] = "msn";
    Foreach($vyhledavace as $key=>$value){
            // Poslední dva výskyty
            $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]statRobots WHERE LOWER(user_agent) LIKE '%$value%' ORDER BY time DESC");
            $radek = mysql_fetch_assoc($dotaz);
            $radek2 = mysql_fetch_assoc($dotaz);
            // Počet celkem
            $count = mysql_fetch_assoc( Mysql_query("SELECT COUNT(*) FROM $CONF[sqlPrefix]statRobots WHERE LOWER(user_agent) LIKE '%$value%' ") );
            // Počet / měsíc
            $count2 = mysql_fetch_assoc( Mysql_query("SELECT COUNT(*) FROM $CONF[sqlPrefix]statRobots WHERE LOWER(user_agent) LIKE '%$value%' AND time<".time()." AND time>".(time()-60*60*24*30)." ") );
            // Zobrazení
            $bgcolor = ($i%2)? "#EFEFEF": "#DFDFDF";
            Echo "<tr>
                    <td bgcolor=$bgcolor>$key</td>
                    <td bgcolor=$bgcolor>".( $radek["time"]? date("j.n.Y G:i",$radek["time"]): "&nbsp;- bez návštěvy - " )."</td>
                    <td bgcolor=$bgcolor>".$count["COUNT(*)"]."</td>                
                    <td bgcolor=$bgcolor>".$count2["COUNT(*)"]."x / měsíc</td>
                    <td bgcolor=$bgcolor>".( $radek2["time"]? timeToStr($radek["time"] - $radek2["time"]): '' )."</td>
                  </tr>";
                  
            $i++;
    }
    Echo "</table>"; 
     
     
     
    //////////////////////
    // Poslední návštěvy 
    //////////////////////
    Echo "<h3>Posledních 30 návštěv</h3>
          <table border=0 width=100%>
            <tr>
              <td bgcolor=#CFCFCF width=40><strong>id</strong></td> 
              <td bgcolor=#CFCFCF><strong>Název vyhledávače</strong></td>
              <td bgcolor=#CFCFCF width=160><strong>Datum</strong></td>     
            </tr>
          </table>
          <div style=\"\"> <!--height: 200px; overflow: auto;-->
          <table border=0 width=100%>
          ";
            
    $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]statRobots ORDER BY time DESC LIMIT 30");
    $i = 1;
    While($radek = mysql_fetch_assoc($dotaz)){
          $bgcolor = ($i%2)? "#EFEFEF": "#DFDFDF";
          Echo "<tr>
                  <td bgcolor=$bgcolor width=40>$radek[id]</td>
                  <td bgcolor=$bgcolor>".identifier($radek["user_agent"])."</td>
                  <td bgcolor=$bgcolor width=144>".date("j.n.Y G:i",$radek["time"])."</td>
                 </tr>";
          $i++;                            
    }
    Echo "</table>
          </div>"; 
}    



/***************/
/* VYHLEDÁVAČE */
/***************/
If( $_GET["n"]=='zbozi' ){
    $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]statProdukty join $CONF[sqlPrefix]zbozi on idProduktu=id ORDER BY pocet DESC LIMIT 30 ");
    Echo '<table border="0">
            <tr>
              <td bgcolor="#CFCFCF"><b>Zboží</b></td>
              <td bgcolor="#CFCFCF"><b>Počet prohlídnutí</b></td>
            </tr>';
    $i = 1;
    While($radek=mysql_fetch_assoc($dotaz)){
          $bgcolor = ($i%2)? "#EFEFEF": "#DFDFDF";    
          Echo '<tr>
                  <td bgcolor="'.$bgcolor.'">
                    <a href="'.ereg_replace("admin/$","",$CONF["absDir"]).'?a=produkty&produkt='.$radek["idProduktu"].'" target="_blank" style="text-decoration: none;">
                      '.$radek["produkt"].'
                    </a>
                  </td>
                  <td bgcolor="'.$bgcolor.'" align="right">'.$radek["pocet"].'</td>
                </tr>';
          $i++;
    }
}               
?>




<p>&nbsp;
<?php Include 'grafika_kon.inc'; ?>
