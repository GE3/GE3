<?php
$TmplModul = new GlassTemplate("templates/$CONF[vzhled]/modul.html");
$TmplNastaveni = new GlassTemplate("templates/$CONF[vzhled]/nastaveni.html");

//Hlavička
$TmplModul->prirad("hlavicka.ikona", "templates/$CONF[vzhled]/images/nastaveni.png");
$TmplModul->prirad("hlavicka.nadpis", "Nastavení");
$TmplModul->prirad("hlavicka.popis", "Umožňuje spravovat základní nastavení vašich stránek.");

//Editace nastavení
If( $_POST["akce"]=='zakladni-nastaveni' ){
    Mysql_query("UPDATE $CONF[sqlPrefix]nastaveni 
                 SET emailAdmin='$_POST[emailAdmin]', title='$_POST[title]', objednavky_autosender='$_POST[objednavky_autosender]' 
                 WHERE id=1") or die(Mysql_error());
}
If( $_POST["old_pass"] and $_POST["new_pass"] ){
    if( $_POST["old_pass"]==$_SESSION["heslo"] ){
        if( $_POST["new_pass"]==$_POST["new_pass_2"] ){
            $uzivatel = mysql_fetch_assoc( mysql_query("SELECT * FROM $CONF[sqlPrefix]uzivatele WHERE heslo=MD5('$_POST[old_pass]')") );
            if( $uzivatel["id"] ){
                Mysql_query("UPDATE $CONF[sqlPrefix]uzivatele SET heslo=MD5('$_POST[new_pass]') WHERE heslo=MD5('$_POST[old_pass]')");
                $_SESSION["heslo"] = $_POST["new_pass"];
                $TmplNastaveni->prirad("hlaska_heslo.text", "Heslo úspěšně změněno");
            }Else $TmplNastaveni->prirad("hlaska_heslo.text", "Špatně zadané staré heslo");
        }Else $TmplNastaveni->prirad("hlaska_heslo.text", "Hesla nesouhlasí");
    }Else $TmplNastaveni->prirad("hlaska_heslo.text", "Špatně zadané staré heslo");
    
    Mysql_query("UPDATE $CONF[sqlPrefix]nastaveni 
                 SET emailAdmin='$_POST[emailAdmin]', title='$_POST[title]', objednavky_autosender='$_POST[objednavky_autosender]' 
                 WHERE id=1") or die(Mysql_error());
}

//Zobrazení aktuálních informací
$radek = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]nastaveni") );
foreach($radek as $key=>$value){
      $TmplNastaveni->prirad($key, $value);
}


$TmplModul->prirad("obsah", $TmplNastaveni->getHtml());
Echo $TmplModul->getHtml();
?>