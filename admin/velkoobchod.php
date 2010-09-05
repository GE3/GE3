<?php            
/*******************************************************************************
zakaznici
- id, ..., velkoobchodId

velkoobchody
- id, nazev, sleva



/* -- Přidání velkoobchodu -- /
<form method="post" action="">
<input type="hidden" name="akce" value="new">
<table>
  <tr>
    <td>Název: </td>
    <td><input type="text" name="nazev"></td>
  </tr>
  <tr>
    <td>Sleva: </td>
    <td><input type="text" name="sleva" size="2">%</td>
  </tr>
  <tr>
    <td colspan="2" align="right"><input type="submit" name="odeslat" name="Vytvořit"></td>
  </tr>
</table>
</form>

If( $_POST["nazev"] AND $_POST["akce"]=='new' ){
    If( !zjisti_z("CONF[sqlPrefix]velkoobchody","id","nazev='$_POST[nazev]'") ){
        Db_query("INSERT INTO $CONF[sqlPrefix]velkoobchody(nazev,sleva) VALUES ('$_POST[nazev]',$_POST[sleva])");
        If(mysql_num_rows()==1) $tmplVO->prirad("hlaska","Úspěšně přidáno.");
        Else{$tmplVO->prirad("hlaska","Neznámá chyba."); bugReport("Neznámá chyba při přidávání velkoobchodu. Počet vložených řádků: ".mysql_num_rows());}
    }
    Else $tmplVO->prirad("hlaska","Velkoobchod s tímto názvem již existuje.");
}



/* -- Smazání velkoobchodu -- /
<div>
<form>

</form>
</div>

If( $_GET["akce"]=='smazat' AND $_GET["velkoobchodId"] AND zjisti_z("$CONF[sqlPrefix]","id","id=$_GET[velkoobchodId]")){
    Db_query("DELETE FROM $CONF[sqlPrefix]velkoobchody WHERE id=$_GET[velkoobchodId]");
    $affected_rows = mysql_num_rows();
    Db_query("UPDATE $CONF[sqlPrefix]zakaznici SET velkoobchodId=0 WHERE velkoobchodId=$_GET[velkoobchodId]");
    $affected_rows+= mysql_num_rows();

    If($affected_rows>0) $tmplVO->prirad("hlaska","Velkoobchod úspěšně smazán.");
    Else{$tmplVO->prirad("hlaska","Neznámá chyba."); bugReport("Neznámá chyba při mazání velkoobchodu. Ovlivněných řádků: ".$affected_rows);}
}


+ klik na obrázek = detail
+ ruzovy ramecek detail obrazek
+ padding košík + objednávka
+ nadpisy objednávka veliké nadpisy IE
+ objednávka neodeslána?
*******************************************************************************/


                 
If( $_GET["m"]=='velkoobchod' ){
    $tmpl = new GlassTemplate("templates/$CONF[vzhled]/modul.html");
    $tmplVO = new GlassTemplate("templates/$CONF[vzhled]/velkoobchod.html");
    
    /* -- Hlavička -- */
    $tmpl->prirad("hlavicka.ikona", "templates/$CONF[vzhled]/images/velkoobchod.png");
    $tmpl->prirad("hlavicka.nadpis", "Velkoobchod");
    $tmpl->prirad("hlavicka.popis", "Organizace zákazníků do skupin velkoobchodů, nastavování slev.");    
    
    
    ///////////////////
    // Provedení změn
    ///////////////////
    
    /* -- Nový velkoobchod -- */
    If( $_POST["nazev"] AND $_POST["akce"]=='novy' ){
        If( !zjisti_z("$CONF[sqlPrefix]velkoobchody","id","nazev='$_POST[nazev]'") ){
            Db_query("INSERT INTO $CONF[sqlPrefix]velkoobchody(nazev,sleva) VALUES ('$_POST[nazev]','$_POST[sleva]')");
            If(mysql_affected_rows()==1) $tmplVO->prirad("hlaska","Úspěšně přidáno.");
            Else{$tmplVO->prirad("hlaska","Neznámá chyba."); bugReport("Neznámá chyba při přidávání velkoobchodu. Počet vložených řádků: ".mysql_num_rows());}
        }
        Else $tmplVO->prirad("hlaska","Velkoobchod s tímto názvem již existuje.");
    }
    
    /* -- Přiřazení uživatele do velkoobchodu -- */
    If( $_POST["akce"]=="zakaznikEdit" AND $_POST["zakaznikId"] ){
        Db_query("UPDATE $CONF[sqlPrefix]zakaznici SET velkoobchodId='$_POST[velkoobchodId]' WHERE id=$_POST[zakaznikId]");
        If(mysql_affected_rows()==1) $tmplVO->prirad("hlaska","Zákazník byl úspěšně přesunut.");
        Else $tmplVO->prirad("hlaska","Nebyly změněny žádné údaje."); 
    }
    
    /* -- Editace velkoobchodu -- */
    If( $_POST["akce"]=='edit' AND $_POST["velkoobchodId"] AND $_POST["nazev"] ){
        Db_query("UPDATE $CONF[sqlPrefix]velkoobchody SET nazev='$_POST[nazev]',sleva='$_POST[sleva]' WHERE id=$_POST[velkoobchodId]");
        If(mysql_affected_rows()==1) $tmplVO->prirad("hlaska","Úspěšně editováno.");
        Else $tmplVO->prirad("hlaska","Nebyly změněny žádné údaje.");
    }
    
    /* -- Smazání velkoobchodu -- */
    If( $_GET["akce"]=='smazat' AND $_GET["velkoobchodId"] AND zjisti_z("$CONF[sqlPrefix]velkoobchody","id","id=$_GET[velkoobchodId]")){
        Db_query("DELETE FROM $CONF[sqlPrefix]velkoobchody WHERE id=$_GET[velkoobchodId]");
        $affected_rows = mysql_affected_rows();
        Db_query("UPDATE $CONF[sqlPrefix]zakaznici SET velkoobchodId=0 WHERE velkoobchodId=$_GET[velkoobchodId]");
        $affected_rows+= mysql_affected_rows();
    
        If($affected_rows>0) $tmplVO->prirad("hlaska","Velkoobchod úspěšně smazán.");
        Else{$tmplVO->prirad("hlaska","Neznámá chyba."); bugReport("Neznámá chyba při mazání velkoobchodu. Ovlivněných řádků: ".$affected_rows);}
    }    
     
    

    
    //////////////
    // Zobrazení
    //////////////
    
    /* -- Výpis všech velkoobchodů -- */
    $dotaz = Db_query("SELECT * FROM $CONF[sqlPrefix]velkoobchody ORDER BY nazev ASC");
    While($radek=mysql_fetch_assoc($dotaz)){
          $tmplVO->newBlok("velkoobchod");
          
          /* -- Zobrazení -- */
          $tmplVO->prirad("velkoobchod.id", $radek["id"]);
          $tmplVO->prirad("velkoobchod.nazev", $radek["nazev"]);
          $tmplVO->prirad("velkoobchod.sleva", $radek["sleva"]);       
          /* -- Členové -- */
          $dotaz2 = Db_query("SELECT * FROM $CONF[sqlPrefix]zakaznici WHERE velkoobchodId=$radek[id] ORDER BY prijmeni ASC");
          $i=1;
          While($radek2=mysql_fetch_assoc($dotaz2)){
                $tmplVO->newBlok("velkoobchod.zakaznik");
                $tmplVO->prirad("velkoobchod.zakaznik.i", $i);
                $tmplVO->prirad("velkoobchod.zakaznik.id", $radek2["id"]);
                $tmplVO->prirad("velkoobchod.zakaznik.jmeno", $radek2["jmeno"]);
                $tmplVO->prirad("velkoobchod.zakaznik.prijmeni", $radek2["prijmeni"]);
                $tmplVO->prirad("velkoobchod.zakaznik.email", $radek2["email"]);
                $tmplVO->prirad("velkoobchod.zakaznik.velkoobchod", $radek["nazev"]);                

                $dotaz3 = Db_query("SELECT * FROM $CONF[sqlPrefix]velkoobchody ORDER BY nazev ASC");
                While($radek3=mysql_fetch_assoc($dotaz3)){
                      $tmplVO->newBlok("velkoobchod.zakaznik.velkoobchodOption");
                      $tmplVO->prirad("velkoobchod.zakaznik.velkoobchodOption.id", $radek3["id"]);
                      $tmplVO->prirad("velkoobchod.zakaznik.velkoobchodOption.nazev", $radek3["nazev"]);
                      $tmplVO->prirad("velkoobchod.zakaznik.velkoobchodOption.velkoobchodNazev", $radek["nazev"]);                      
                }

                $i++;            
          }
          $tmplVO->prirad("velkoobchod.celkemZakazniku", mysql_num_rows($dotaz2));      
    }
    
    
    /* -- Výpis zákazníků -- */
    $orderBy = ($_GET["orderBy"]?$_GET["orderBy"]:'v.nazev')." ASC";
    $dotaz = Db_query("SELECT z.*, v.nazev as velkoobchodNazev FROM $CONF[sqlPrefix]zakaznici z LEFT JOIN $CONF[sqlPrefix]velkoobchody v ON z.velkoobchodId=v.id
                       WHERE z.email!='' ORDER BY $orderBy");
    While($radek=mysql_fetch_assoc($dotaz)){
          $tmplVO->newBlok("zakaznik");
          $tmplVO->prirad("zakaznik.id", $radek["id"]);
          $tmplVO->prirad("zakaznik.jmeno", $radek["jmeno"]);
          $tmplVO->prirad("zakaznik.prijmeni", $radek["prijmeni"]);
          $tmplVO->prirad("zakaznik.email", $radek["email"]);
          $tmplVO->prirad("zakaznik.velkoobchodNazev", $radek["velkoobchodNazev"]);
          $tmplVO->prirad("zakaznik.velkoobchodId", $radek["velkoobchodId"]);
          
          $dotaz2 = Db_query("SELECT * FROM $CONF[sqlPrefix]velkoobchody ORDER BY nazev ASC");
          While($radek2=mysql_fetch_assoc($dotaz2)){
                $tmplVO->newBlok("zakaznik.velkoobchod");
                $tmplVO->prirad("zakaznik.velkoobchod.id", $radek2["id"]);
                $tmplVO->prirad("zakaznik.velkoobchod.nazev", $radek2["nazev"]);
                $tmplVO->prirad("zakaznik.velkoobchod.zakaznikVelkoobchodId", $radek["velkoobchodId"]);
          }
    }

    
    
    
    /* -- Zobrazení -- */
    $tmpl->prirad("obsah", $tmplVO->getHtml());
    Echo $tmpl->getHtml();
}
?>