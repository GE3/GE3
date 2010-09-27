<?php /* POČÁTEČNÍ PŘÍKAZY */
session_start();
include "../fce/fce.inc";

/* -- Skiny -- */
If( $_POST["zmena_skinu"] ){
    setcookie("skin",$_POST["skin"],time()+9999999);
}

/* -- Include funkcí -- */
Include '../funkce.php/debug.fce.php';
Include '../funkce.php/db.fce.php';

/* -- Include configu -- */
Include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

/* -- Skryvani funkci -- */
If( $_POST["zobrazFunkce"] ){
    Mysql_query("UPDATE $CONF[sqlPrefix]nastaveni SET adminZobrazFunkce='$_POST[zobrazFunkce]' WHERE id=1") or die(mysql_error());
}

/* -- Definice tříd -- */
Include '../class.php/GlassTemplate.class.php';

/* -- Přihlášení -- */
if( zjisti_z("$CONF[sqlPrefix]uzivatele","jmeno","heslo=MD5('$_POST[heslo]')") AND $_GET["m"]!="odhlasit_se" ){
    $_SESSION["heslo"] = $_POST["heslo"];
}
if( $_GET["m"]=="odhlasit_se" ){
    $_SESSION["heslo"] = '';
}

/* -- tohle musí taky zmiznout -- */
$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]nastaveni WHERE id=1");
$radek=Mysql_fetch_array($dotaz);
$GLOBALS["emailAdmin"]=$radek["email_admin"];



/*
$dotaz = mysql_query("SELECT * FROM $CONF[sqlPrefix]faktury WHERE uzivatelJmeno!=''");
While($radek=mysql_fetch_assoc($dotaz)){
      $jmeno = eregi_replace("^(.*) .*$", "\\1", $radek["uzivatelJmeno"]);
      $prijmeni = eregi_replace("^.* (.*)$", "\\1", $radek["uzivatelJmeno"]);
      If($jmeno AND $prijmeni) Mysql_query("UPDATE $CONF[sqlPrefix]faktury SET uzivatelJmeno='$jmeno', uzivatelPrijmeni='$prijmeni' WHERE id=$radek[id]") or die(mysql_error());
      Echo "$radek[uzivatelJmeno]: $jmeno » $prijmeni <br>";
}
*/
/*
$dotaz = mysql_query("SELECT * FROM $CONF[sqlPrefix]objednavky WHERE uzivatelJmeno!=''");
While($radek=mysql_fetch_assoc($dotaz)){
      $jmeno = eregi_replace("^(.*) .*$", "\\1", $radek["uzivatelJmeno"]);
      $prijmeni = eregi_replace("^.* (.*)$", "\\1", $radek["uzivatelJmeno"]);
      If($jmeno AND $prijmeni) Mysql_query("UPDATE $CONF[sqlPrefix]objednavky SET uzivatelJmeno='$jmeno', uzivatelPrijmeni='$prijmeni' WHERE id=$radek[id]") or die(mysql_error());
      Echo "$radek[uzivatelJmeno]: $jmeno » $prijmeni <br>";
}
*/
/*
$dotaz = mysql_query("SELECT * FROM $CONF[sqlPrefix]zakaznici WHERE jmeno!=''");
While($radek=mysql_fetch_assoc($dotaz)){
      $jmeno = eregi_replace("^(.*) .*$", "\\1", $radek["jmeno"]);
      $prijmeni = eregi_replace("^.* (.*)$", "\\1", $radek["jmeno"]);
      If($jmeno AND $prijmeni) Mysql_query("UPDATE $CONF[sqlPrefix]zakaznici SET jmeno='$jmeno', prijmeni='$prijmeni' WHERE id=$radek[id]") or die(mysql_error());
      Echo "$radek[jmeno]: $jmeno » $prijmeni <br>";
}
*/
?>







<?php
if( $_SESSION["heslo"] AND $_GET["m"]!="odhlasit_se" ){

    /* Úvodní stránka */
    if(!$_GET["m"]){
        $tmpl = new GlassTemplate("templates/$CONF[vzhled]/prihlasen-$CONF[mod].html", "templates/default/prihlasen-$CONF[mod].html");
        
        $radekNastaveni = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]nastaveni WHERE id=1") );
        $tmpl->prirad("zobrazFunkce", $radekNastaveni["adminZobrazFunkce"]);
        
        // Admin vzkaz
        $adminVzkaz = file_get_contents("http://www.grafart.cz/globals/admin_vzkaz.htm");
        $adminVzkaz = iconv("WINDOWS-1250", "UTF-8", $adminVzkaz);        
        $tmpl->prirad("adminVzkaz", $adminVzkaz);
        
        // Zobrazování modulů
        If($CONF["adminAnkety"]==1) $tmpl->prirad("ankety.zobraz", true);
        Elseif($CONF["adminAnkety"]==0) $tmpl->prirad("anketyGrey.zobraz", true);
        
        If($CONF["adminFotogalerie"]==1) $tmpl->prirad("fotogalerie.zobraz", true);
        Elseif($CONF["adminFotogalerie"]==0) $tmpl->prirad("fotogalerieGrey.zobraz", true);
        
        If($CONF["adminKalendar"]==1) $tmpl->prirad("kalendar.zobraz", true);
        Elseif($CONF["adminKalendar"]==0) $tmpl->prirad("kalendarGrey.zobraz", true);
        
        If($CONF["adminFaktury"]==1) $tmpl->prirad("faktury.zobraz", true);
        Elseif($CONF["adminFaktury"]==0) $tmpl->prirad("fakturyGrey.zobraz", true);
        
        If($CONF["adminWebmail"]==1) $tmpl->prirad("webmail.zobraz", true);
        Elseif($CONF["adminWebmail"]==0) $tmpl->prirad("webmailGrey.zobraz", true);
        
        If($CONF["adminKalkulacka"]==1) $tmpl->prirad("kalkulacka.zobraz", true);
        Elseif($CONF["adminKalkulacka"]==0) $tmpl->prirad("kalkulackaGrey.zobraz", true);
        
        If($CONF["adminExportProduktu"]==1) $tmpl->prirad("exportProduktu.zobraz", true);
        Elseif($CONF["adminExportProduktu"]==0) $tmpl->prirad("exportProduktuGrey.zobraz", true);                                                

        If($CONF["adminHromadneCeny"]==1) $tmpl->prirad("hromadneCeny.zobraz", true);
        Elseif($CONF["adminHromadneCeny"]==0) $tmpl->prirad("hromadneCenyGrey.zobraz", true);

        If($CONF["adminVelkoobchod"]==1) $tmpl->prirad("velkoobchod.zobraz", true);
        Elseif($CONF["adminVelkoobchod"]==0) $tmpl->prirad("velkoobchodGrey.zobraz", true);
        
        If($CONF["adminKategorie"]==1) $tmpl->prirad("kategorie.zobraz", true);
        Elseif($CONF["adminKategorie"]==0) $tmpl->prirad("kategorieGrey.zobraz", true);
        
        If($CONF["adminManual"]==1) $tmpl->prirad("manual.zobraz", true);
        Elseif($CONF["adminManual"]==0) $tmpl->prirad("manualGrey.zobraz", true);                

        If($CONF["adminFacebook"]==1) $tmpl->prirad("facebook.zobraz", true);
        Elseif($CONF["adminFacebook"]==0) $tmpl->prirad("facebookGrey.zobraz", true);      

        If($CONF["adminZakaznici"]==1) $tmpl->prirad("zakaznici.zobraz", true);
        Elseif($CONF["adminZakaznici"]==0) $tmpl->prirad("zakazniciGrey.zobraz", true);

        If($CONF["adminSoubory"]==1) $tmpl->prirad("soubory.zobraz", true);
        Elseif($CONF["adminSoubory"]==0) $tmpl->prirad("souboryGrey.zobraz", true);

        If($CONF["adminMakler"]==1) $tmpl->prirad("makler.zobraz", true);
        Elseif($CONF["adminMakler"]==0) $tmpl->prirad("maklerGrey.zobraz", true);

        If($CONF["adminNastaveni"]==1) $tmpl->prirad("nastaveni.zobraz", true);
        Elseif($CONF["adminNastaveni"]==0) $tmpl->prirad("nastaveniGrey.zobraz", true);

        If($CONF["adminForums"]==1) $tmpl->prirad("fora.zobraz", true);
        Elseif($CONF["adminForums"]==0) $tmpl->prirad("foraGrey.zobraz", true);

        If($CONF["adminFormulare"]==1) $tmpl->prirad("formulare.zobraz", true);
        Elseif($CONF["adminFormulare"]==0) $tmpl->prirad("formulareGrey.zobraz", true);
        
        Echo $tmpl->getHtml();
    }
    
    
    /* Include */
    if($_GET["m"]=="pridat_zbozi"){
       include 'pridat.php';
       }

    if($_GET["m"]=="editace_zbozi"){
       include 'upravit.php';
       }

    if($_GET["m"]=="kategorie"){
       include 'kategorie.php';
       }

    if($_GET["m"]=="faktury"){
       include 'faktury.php';
       }

    if($_GET["m"]=="dopravne"){
       include 'dopravne.php';
       }

    if($_GET["m"]=="export"){
       include 'export.php';
       }

    if($_GET["m"]=="software"){
       include 'software.php';
       }

    if($_GET["m"]=="clanky"){
       include 'clanky.php';
       }
    if($_GET["m"]=="ceny"){
       include 'hromadne_ceny.php';
       }
    if($_GET["m"]=="pridat-funkci"){
       include 'pridat_funkci.php';
       }
    if($_GET["m"]=="kontakt"){
       include 'rychly_kontakt.php';
       }

    if($_GET["m"]=="objednavky"){
       include 'objednavky.php';
       }

    if($_GET["m"]=="zakaznici"){
       include 'zakaznici.php';
       }
       
    if($_GET["m"]=="statistiky"){
       include 'statistiky.php';
       }       

    if($_GET["m"]=="export_produktu"){
       include 'export_produktu.php';
       }
       
    if($_GET["m"]=="web_mail"){
       include 'web_mail.php';
       }          

    if($_GET["m"]=="velkoobchod"){
       include 'velkoobchod.php';
       }
       
    if($_GET["m"]=="forum"){
       include 'forum.php';
       }   
       
    if($_GET["m"]=="fotogalerie"){
       include 'galerie.php';
       }             
       
    if($_GET["m"]=="poptavka"){
       include 'poptavka.php';
       }  

    if($_GET["m"]=="soubory"){
       include 'soubory.php';
       }
       
    if($_GET["m"]=="sdileni"){
       include 'sdileni.php';
       }
       
    if($_GET["m"]=="facebook"){
       include 'facebook.php';
       }
       
    if($_GET["m"]=="makler"){
       include 'makler.php';
       }
       
    if($_GET["m"]=="nastaveni"){
       include 'nastaveni.php';
       }
       
    if($_GET["m"]=="forums"){
       include 'forums.php';
       }
       
    if($_GET["m"]=="mailforms"){
       include 'mailforms.php';
       }              
       
    //nové moduly se samostatným řízením
    Include 'ankety.php';           
}
Else{
    $tmpl = new GlassTemplate("templates/$CONF[vzhled]/neprihlasen-$CONF[mod].html", "templates/default/neprihlasen-$CONF[mod].html");
    Echo $tmpl->getHtml();
}

?>

</BODY>
</HTML>
