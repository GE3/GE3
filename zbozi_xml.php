<?php
/*********************/
/* Počáteční skripty */
/*********************/
Echo '<?xml version="1.0" encoding="utf-8"?'.'>'."\r\n";

Include "fce/fce.inc";

/* -- Include configu -- */
Include "config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");



/***************/
/* Výpis zboží */
/***************/
Echo '<SHOP>'."\r\n";
$dotaz=Mysql_query("SELECT *,MIN(cenaSDph),MIN(cenaBezDph) FROM $CONF[sqlPrefix]zbozi GROUP BY produkt ORDER BY kategorie ASC, id DESC, cenaSDph ASC") or die(mysql_error());
$historie="";
While($radek=Mysql_fetch_array($dotaz)){
      If( !ereg(";".$radek["produkt"].";",$historie) ){
          // Availability
          $availability="168";
          If( $radek["dostupnost"]=="ANO" ){$availability="0";}
          // Description
          $description="";
          $description=preg_replace("|<p><strong>.*</strong></p>|Ui","",$radek["popis1"]);
          $description=preg_replace("|<.*>|Ui"," ",$radek["popis1"]);
          $description=substr($description,0,180);
          // Obrázek
          $obrazek = ereg_replace("^;", "", $radek["obrazky"]);
          $obrazek = preg_replace("|^([^;]*).*$|", "$1", $radek["obrazky"]); 
          // Url
          If( $CONF["mod_rewrite"] ){
              //(informace pro nové url se berou ze stávající url. Pokud zde nejsou, zkusí se získat z db.)
              $kategorie = $_GET["kategorie"]? $_GET["kategorie"]: ($radek["kategorie"]? $radek["id"].'-'.urlText($radek["kategorie"]): '');
              $kategorie = $kategorie? ''.$kategorie.'/': '';
              $podkat1 = $_GET["podkat1"]? $_GET["podkat1"]: ($radek["podkat1"]? $radek["id"].'-'.urlText($radek["podkat1"]): '');
              $podkat1 = $podkat1? ''.$podkat1.'/': '';
              $podkat2 = $_GET["podkat2"]? $_GET["podkat2"]: ($radek["podkat2"]? $radek["id"].'-'.urlText($radek["podkat2"]): '');
              $podkat2 = $podkat2? ''.$podkat2.'/': '';
              $podkat3 = $_GET["podkat3"]? $_GET["podkat3"]: ($radek["podkat3"]? $radek["id"].'-'.urlText($radek["podkat3"]): '');
              $podkat3 = $podkat3? ''.$podkat3.'/': '';
              $produkt = ''.$radek["id"].'-'.urlText($radek["produkt"]).".html";
              $url = $CONF["absDir"]."produkty/".$kategorie.$podkat1.$podkat2.$podkat3.$produkt;
          }
          Else{
              //(informace pro nové url se berou ze stávající url. Pokud zde nejsou, zkusí se získat z db.)
              $kategorie = $_GET["kategorie"]? $_GET["kategorie"]: ($radek["kategorie"]? $radek["id"].'-'.urlText($radek["kategorie"]): '');
              $kategorie = $kategorie? '&kategorie='.$kategorie.'': '';
              $podkat1 = $_GET["podkat1"]? $_GET["podkat1"]: ($radek["podkat1"]? $radek["id"].'-'.urlText($radek["podkat1"]): '');
              $podkat1 = $podkat1? '&podkat1='.$podkat1.'': '';
              $podkat2 = $_GET["podkat2"]? $_GET["podkat2"]: ($radek["podkat2"]? $radek["id"].'-'.urlText($radek["podkat2"]): '');
              $podkat2 = $podkat2? '&podkat2='.$podkat2.'': '';
              $podkat3 = $_GET["podkat3"]? $_GET["podkat3"]: ($radek["podkat3"]? $radek["id"].'-'.urlText($radek["podkat3"]): '');
              $podkat3 = $podkat3? '&podkat3='.$podkat3.'': '';
              $produkt = '&produkt='.$radek["id"].'-'.urlText($radek["produkt"]);
              $url = $CONF["absDir"]."?a=produkty".$kategorie.$podkat1.$podkat2.$podkat3.$produkt;
          }   
          // Ceny
          $cenaSDph = $radek["MIN(cenaSDph)"]?$radek["MIN(cenaSDph)"]:($radek["MIN(cenaBezDph)"]*(1+$radek["dph"]/100));
          $cenaBezDph = $radek["MIN(cenaBezDph)"]?$radek["MIN(cenaBezDph)"]:($radek["MIN(cenaSDph)"]/(1+$radek["dph"]/100));
          $dph = $radek["dph"]?$radek["dph"]:($radek["cenaSDph"]/$radek["cenaBezDph"]*100-100);                          
          // XML
          Echo '
                <SHOPITEM>
                  <PRODUCT>'.$radek["produkt"].'</PRODUCT>
                  <DESCRIPTION>'.$description.'</DESCRIPTION>
                  <URL>'.$url.'</URL>
                  <ITEM_TYPE>new</ITEM_TYPE>
                  <AVAILABILITY>'.$availability.'</AVAILABILITY>
                  <IMGURL>'.$CONF["absDir"].'zbozi/obrazky/'.$obrazek.'</IMGURL>
                  <PRICE>'.round($cenaBezDph,"2").'</PRICE>
                  <PRICE_VAT>'.round($cenaSDph,"2").'</PRICE_VAT>
                </SHOPITEM>
                ';
          $historie.=";".$radek["produkt"].";";
      }
}
Echo "\r\n".'</SHOP>'."\r\n";
?>

