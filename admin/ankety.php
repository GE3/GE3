<?php            
If( $_GET["m"]=='ankety' ){
    $tmpl = new GlassTemplate("templates/$CONF[vzhled]/modul.html");
    $tmplAnk = new GlassTemplate("templates/$CONF[vzhled]/ankety.html");
    
    /* -- Hlavička -- */
    $tmpl->prirad("hlavicka.ikona", "templates/$CONF[vzhled]/images_dante/ankety.png");
    $tmpl->prirad("hlavicka.nadpis", "Ankety");
    $tmpl->prirad("hlavicka.popis", "Tato umožní vložit do stránek anketu, abyste měli lepší zpětnou vazbu s Vašimi potencionálními klienty.");    
    
    
    ///////////////////
    // Provedení změn
    ///////////////////

    /* -- Vytvoření nové ankety -- */
    If( $_POST["akce"]=='nova' AND $_POST["otazka"] AND $_POST["odpovedi"][1] ){
        Db_query("INSERT INTO $CONF[sqlPrefix]ankety(otazka,aktivni) VALUES ('$_POST[otazka]', $_POST[aktivni])");
        $affectedRows = mysql_affected_rows();
        $anketaId = mysql_insert_id();
        Foreach($_POST["odpovedi"] as $key=>$value){
                If( $key AND $value ){
                    Db_query("INSERT INTO $CONF[sqlPrefix]anketyOdpovedi(anketaId,odpoved) VALUES ($anketaId, '$value')");
                    $affectedRows+= mysql_affected_rows();
                }
        }
        
        If($affectedRows>1) $tmplAnk->prirad("hlaska", "Anketa byla úspěšně vytvořena.");
        Else{$tmplAnk->prirad("hlaska", "Anketa byla úspěšně vytvořena."); bugReport("Chyba v přidávání ankety: ovlivněných řádků: $affectedRows");}
    }
    
    /* -- Editace ankety -- */
    If( $_POST["akce"]=='edit' AND $_POST["anketaId"] ){
        If( $_POST["otazka"] AND count($_POST["odpovedi"]) ){
            Db_query("UPDATE $CONF[sqlPrefix]ankety SET aktivni='$_POST[aktivni]',otazka='$_POST[otazka]' WHERE id=$_POST[anketaId]");
            $affectedRows = mysql_affected_rows();
            Foreach($_POST["odpovedi"] as $key=>$value){
                    Db_query("UPDATE $CONF[sqlPrefix]anketyOdpovedi SET odpoved='$value' WHERE id=$key");
                    $affectedRows+= mysql_affected_rows();
            }
            If($affectedRows>=1) $tmplAnk->prirad("hlaska", "Anketa úspěšně upravena.");
            Else $tmplAnk->prirad("hlaska", "Žádné údaje nebyly změněny.");
        }
        Else $tmplAnk->prirad("hlaska", "Nejsou vyplněné některé důležité údaje.");
    }
    
    /* -- Smazání ankety -- */
    If( $_GET["akce"]=='smazat' AND $_GET["anketaId"] AND zjistiZ("$CONF[sqlPrefix]ankety", "id", "id=$_GET[anketaId]") ){
        Db_query("DELETE FROM $CONF[sqlPrefix]ankety WHERE id=$_GET[anketaId]");
        $affectedRows = mysql_affected_rows();
        Db_query("DELETE FROM $CONF[sqlPrefix]anketyOdpovedi WHERE anketaId=$_GET[anketaId]");
        $affectedRows+= mysql_affected_rows();
        
        If(mysql_affected_rows()>1) $tmplAnk->prirad("hlaska", "Anketa byla smazána.");
        Else{$tmplAnk->prirad("hlaska", "Vyskytla se neznámá chyba."); bugReport("Chyba při mazání ankety.");}
    }
    
    /* -- Vynulování ankety -- */
    If( $_GET["akce"]=='vynulovat' AND $_GET["anketaId"] ){
        Db_query("UPDATE $CONF[sqlPrefix]anketyOdpovedi SET pocet=0 WHERE anketaId=$_GET[anketaId]");
        If(mysql_affected_rows()>=1) $tmplAnk->prirad("hlaska", "Anketa byla vynulována.");
        Else{$tmplAnk->prirad("hlaska", "Vyskytla se neznámá chyba."); bugReport("Chyba při nulování ankety.");}
    }        
    

    
    //////////////
    // Zobrazení
    //////////////
    
    /* -- Výpis všech anket -- */
    $dotaz = Db_query("SELECT id,otazka,aktivni FROM $CONF[sqlPrefix]ankety ORDER BY id DESC");
    While($radek=mysql_fetch_assoc($dotaz)){
          $tmplAnk->newBlok("anketa");
          
          /* -- Zobrazení -- */
          $tmplAnk->prirad("anketa.id", $radek["id"]);
          $tmplAnk->prirad("anketa.otazka", $radek["otazka"]);
          $tmplAnk->prirad("anketa.aktivni", $radek["aktivni"]);       
          /* -- Odpovědi -- */
          $dotaz2 = Db_query("SELECT * FROM $CONF[sqlPrefix]anketyOdpovedi WHERE anketaId=$radek[id] ORDER BY id ASC");
          $celkemHlasu = zjistiZ("$CONF[sqlPrefix]anketyOdpovedi","SUM(pocet)", "anketaId=$radek[id] GROUP BY anketaId");      
          $i=1;
          While($radek2=mysql_fetch_assoc($dotaz2)){
                $tmplAnk->newBlok("anketa.odpoved");
                $tmplAnk->prirad("anketa.odpoved.i", $i);
                $tmplAnk->prirad("anketa.odpoved.id", $radek2["id"]);
                $tmplAnk->prirad("anketa.odpoved.text", $radek2["odpoved"]);
                
                $tmplAnk->prirad("anketa.odpoved.pocetHlasu", $radek2["pocet"]);
                If($celkemHlasu) $tmplAnk->prirad("anketa.odpoved.procentHlasu", round($radek2["pocet"]/$celkemHlasu*100,2));
                
                $i++;            
          }
          $tmplAnk->prirad("anketa.pocetOdpovedi", mysql_num_rows($dotaz2));
          $tmplAnk->prirad("anketa.celkemHlasu", $celkemHlasu);      
    }

    
    
    
    /* -- Zobrazení -- */
    $tmpl->prirad("obsah", $tmplAnk->getHtml());
    Echo $tmpl->getHtml();
}
?>