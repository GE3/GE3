<?php            
$tmplAnk = new GlassTemplate("templates/$CONF[vzhled]/ankety.html");

$dotaz = Db_query("SELECT id,otazka FROM $CONF[sqlPrefix]ankety WHERE aktivni=1 ORDER BY id DESC");
While($radek=mysql_fetch_assoc($dotaz)){
      $tmplAnk->newBlok("anketa");
            
      /* -- Přidání hlasu -- */
      Include_once 'ostatni.php/statistiky.funkce.php';   //umožňuje zjistit, jestli je návštěvník vyhledávací robot
      If( $_GET["anketa"]==$radek["id"] AND $_GET["odpoved"] ){
          // Promazání starých IP
          Db_query("DELETE FROM $CONF[sqlPrefix]anketyIp WHERE time<".(time()-60)."");
          // Hlasování
          $ip = $_SERVER["REMOTE_ADDR"]."/".$_SERVER["HTTP_X_FORWARDED_FOR"];
          If( !zjistiZ("$CONF[sqlPrefix]anketyIp", "id", "anketaId=$radek[id] AND ip='$ip'") AND !jeRobot() ){
              Db_query("INSERT INTO $CONF[sqlPrefix]anketyIp(anketaId,time,ip) VALUES ($radek[id], ".time().", '$ip')");
              Db_query("UPDATE $CONF[sqlPrefix]anketyOdpovedi SET pocet=(
                         (SELECT pocet FROM (SELECT pocet FROM $CONF[sqlPrefix]anketyOdpovedi WHERE id=$_GET[odpoved]) as pomTable LIMIT 1)+1) 
                        WHERE id=$_GET[odpoved]");
                       
              If(mysql_affected_rows()==1) $tmplAnk->prirad("anketa.hlaska", "Váš hlas byl započítán.");
              Else{$tmplAnk->prirad("anketa.hlaska", "Neznámá chyba."); bugReport("Chyba v hlasování v anketě, ovlivněných řádků: ".mysql_affected_rows());}

          }
          Else $tmplAnk->prirad("anketa.hlaska", "Nelze hlasovat vícekrát.");
      }
      
      /* -- Zobrazení -- */
      $tmplAnk->prirad("anketa.id", $radek["id"]);
      $tmplAnk->prirad("anketa.otazka", $radek["otazka"]);      
      /* -- Odpovědi -- */
      $dotaz2 = Db_query("SELECT * FROM $CONF[sqlPrefix]anketyOdpovedi WHERE anketaId=$radek[id] ORDER BY id ASC");
      $celkemHlasu = zjistiZ("$CONF[sqlPrefix]anketyOdpovedi","SUM(pocet)", "anketaId=$radek[id] GROUP BY anketaId");
      While($radek2=mysql_fetch_assoc($dotaz2)){
            $tmplAnk->newBlok("anketa.odpoved");
            $tmplAnk->prirad("anketa.odpoved.id", $radek2["id"]);
            $tmplAnk->prirad("anketa.odpoved.text", $radek2["odpoved"]);
            $tmplAnk->prirad("anketa.odpoved.url", $_SERVER["REQUEST_URL"]."?anketa=$radek[id]&odpoved=$radek2[id]");
            
            $tmplAnk->prirad("anketa.odpoved.pocetHlasu", $radek2["pocet"]);
            $tmplAnk->prirad("anketa.odpoved.procentHlasu", $celkemHlasu? round($radek2["pocet"]/$celkemHlasu*100,2): '0');
            $tmplAnk->prirad("anketa.odpoved.celkemHlasu", $celkemHlasu);
      }
      $tmplAnk->prirad("anketa.pocetOdpovedi", mysql_num_rows($dotaz2));
      $tmplAnk->prirad("anketa.celkemHlasu", $celkemHlasu);
}

$tmpl->prirad("ankety", $tmplAnk->getHtml());
?>