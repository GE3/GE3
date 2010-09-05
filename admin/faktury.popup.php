<?php /* POČÁTEČNÍ PŘÍKAZY */
session_start(1);

/* -- Include configu -- */
Include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

Include "../fce/fce.inc";

/* -- Include tříd -- */
Include '../class.php/GlassTemplate.class.php';
Include '../class.php/Uzivatel.class.php';
Include '../class.php/Objednavka.class.php';
Include '../class.php/Faktura.class.php';



If( $_SESSION["heslo"] ){

    /* --Provedení změn-- */
    If( $_POST["odeslat"] ){
        If( Mysql_query("UPDATE $CONF[sqlPrefix]faktury SET dodavatel='".$_POST["dodavatel"]."',date2='".$_POST["date2"]."',varSymb='".$_POST["varSymb"]."',konstSymb='".$_POST["konstSymb"]."' WHERE id=".$_GET["faktura"]." ") ){
              //Echo '<div style="color: #008000; border: 1px solid #008000; padding: 3px 3px 3px 6px;">Změny úspěšně uloženy.</div><p>';
        }
    }

    /* -- Změna množství zboží -- */
    If( $_POST["zmenit_mnozstvi"] ){
        $radek = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]faktury WHERE id=$_GET[faktura] LIMIT 1") );

        // Objednávka obsahuje slevu (musí se přepočítat)
        If( stripos($radek["zbozi"], "Sleva:") ){
            //načtení informací do polí
            $zbozi = explode(";", $radek["zbozi"]);
            $cenySDph = explode(";", $radek["cenySDph"]);
            $cenyBezDph = explode(";", $radek["cenyBezDph"]);
            $dph = explode(";", $radek["dph"]);  
            $mnozstvi = ";".implode(";", $_POST["mnozstvi"]);
            $mnozstvi = explode(";", $mnozstvi);
            //cyklus pro spočítání nové slevy
            $cenaCelkem = 0;
            Foreach($zbozi as $key=>$value){
                    If( $value!="Dopravné" ){
                        //počítání cen zboží
                        $cenaSDph = $cenySDph[$key]? $cenySDph[$key]: ($cenyBezDph[$key]*(1+$dph[$key]/100));
                        If($value!="Sleva:") $cenaCelkem+= $cenaSDph*$mnozstvi[$key];
                        Else $cenySDph[$key] = round((-1) * $cenaCelkem * 0.05, 2);
                    }
            }
            //zapsání do databáze
            $dbZbozi = implode(";", $zbozi);
            $dbCenySDph = implode(";", $cenySDph);
            $dbDph = implode(";", $dph);
            $dbMnozstvi = implode(";", $mnozstvi);
            Mysql_query("UPDATE $CONF[sqlPrefix]faktury SET zbozi='$dbZbozi', cenySDph='$dbCenySDph', dph='$dbDph', mnozstvi='$dbMnozstvi' WHERE id=$_GET[faktura]") or die(mysql_error());
        }
        
        // Objednávka neobsahuje slevu
        Else{
            //kontrola
            $kontrola = false;
            If(is_numeric($_POST["mnozstvi"][2])) $kontrola = True;
            Foreach($_POST["mnozstvi"] as $key=>$value){
                    If( !is_numeric($value) ){ 
                        $kontrola = False;
                    }
            }
            //sestavení řetězce "mnozstvi"
            $mnozstvi = ";".implode(";", $_POST["mnozstvi"]);
            //odeslání dotazu
            If( $kontrola ){
                Mysql_query("UPDATE $CONF[sqlPrefix]faktury SET mnozstvi='$mnozstvi' WHERE id=$_GET[faktura]") or die(mysql_error());
            }
        }
    } 

    /* --Zobrazení faktury-- */
    $faktura = new Faktura($_GET["faktura"]);
    //Echo $faktura->getHtmlFull();

    $tmplPom = new GlassTemplate("../templates/$CONF[vzhled]/faktura.html", "../templates/default/faktura.html");
    Echo $faktura->priradDoTmpl($tmplPom, "fakturaAdminFull", "../templates/$CONF[vzhled]/faktura.html");
}
?>
