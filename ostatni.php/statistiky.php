<?php
/**
 * Tento skript pouze zapisuje data o příchozích návštěvnících do databáze. 
 * O prohlížení statistik se stará skript v administraci. 
 */
  
/*
1) statistiky vyhledávání na webu (nejvyhledávanější, nej-nenalezeno)


2) z jakých webů zde přišli + slova z vyhledávačů Google, Seznam a Jyxo
http://search.seznam.cz/?q=SLOVA
http://www.google.cz/search?q=SLOVA
http://www.google.com/search?q=SLOVA
http://jyxo.1188.cz/s?q=SLOVA

statReferer
(id, server, url, ?fraze?, pocet)

mysql_insert_id — Vrací generovanou hodnotu id posledního příkazu INSERT
mysql_num_rows — Vrací počet záznamů ve výsledku


3) běžné statistiky (návštěv dnes, celkem, za měsíc + graf)
statNavstevy
(id, ip, rok, mesic, den, hodina, time, os, prohlizec, pocet)



4) nejprodávanější zboží


5) Nejnavštěvovanější zboží
statProdukty(id, pocet)


6) roboty i na zbozi_xml


date("j.n.Y G:i");
*/

/**********/
/* FUNKCE */
/**********/
Include_once 'ostatni.php/statistiky.funkce.php';



/********************/
/* BĚŽNÉ STATISTIKY */
/********************/

/* -- Připravení informací -- */
$ip = $_SERVER['REMOTE_ADDR'].'/'.$_SERVER['HTTP_X_FORWARDED_FOR'];
$rok = date("Y");
$mesic = date("n");
$den = date("j");
$hodina = date("G");
$os = jeRobot()? '<i>vyhledávač: '.str_replace("'","\\'",getNazevRobota()).'</i>': getOs();
$prohlizec = getProhlizec();

/* -- Zapsání do databáze - Člověk -- */
If( $_SESSION["statNavstevyId"] ){
    // Uživatel už zde byl
    Mysql_query("UPDATE $CONF[sqlPrefix]statNavstevy SET pocet=((SELECT pocet 
                                                                 FROM (SELECT * FROM $CONF[sqlPrefix]statNavstevy WHERE id=$_SESSION[statNavstevyId]) as pomTable
                                                                 )+1) 
                 WHERE id=$_SESSION[statNavstevyId] ") or die(mysql_error());
}
Elseif(!jeRobot()){
    // Zřejmě je zde poprvé
    Mysql_query("INSERT INTO $CONF[sqlPrefix]statNavstevy(ip, rok, mesic, den, hodina, time, os, prohlizec, pocet) 
                 VALUES('$ip', '$rok', '$mesic', '$den', '$hodina', '".time()."', '$os', '$prohlizec', 1) ") or die(mysql_error());
    $_SESSION["statNavstevyId"] = mysql_insert_id();
}

/* -- Zapsání do databáze - Robot -- */
Elseif(jeRobot()){
    // Je robot a tím pádem nepřijímá cookies
    $kontrola = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]statNavstevy WHERE time>".(time()-60*15)." AND ip='$ip' ORDER BY time DESC LIMIT 1") );
    If( $kontrola["id"] ){
        // Robot už zde byl
        $id = $kontrola["id"];
        Mysql_query("UPDATE $CONF[sqlPrefix]statNavstevy SET pocet=((SELECT pocet 
                                                                     FROM (SELECT * FROM $CONF[sqlPrefix]statNavstevy WHERE id=$id) as pomTable
                                                                     )+1) 
                     WHERE id=$id ") or die(mysql_error());
    }
    Else{
        // Zřejmě je zde poprvé
        Mysql_query("INSERT INTO $CONF[sqlPrefix]statNavstevy(ip, rok, mesic, den, hodina, time, os, prohlizec, pocet) 
                     VALUES('$ip', '$rok', '$mesic', '$den', '$hodina', '".time()."', '$os', '$prohlizec', 1) ") or die(mysql_error());
    }
}



/****************************/
/* STATISTIKY Z REFERER_URL */
/****************************/

/////////////////
// Odkud přišli 
/////////////////
If( $_SERVER['HTTP_REFERER'] AND strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])===False ){
    
    /* -- Z vyhledávače -- */
    If( eregi("\?q=", $_SERVER['HTTP_REFERER']) OR eregi("search\?p=", $_SERVER['HTTP_REFERER']) OR eregi("(&q=)|(&amp;q=)", $_SERVER['HTTP_REFERER']) ){
        // Zjištění informací z URL
        $server = preg_replace(">^http://(www\.)?([^/?]*).*$>i", "\\2", $_SERVER['HTTP_REFERER']); 
        $url = $_SERVER['HTTP_REFERER'];
        $fraze = urldecode( preg_replace(">^.*?(q|p)=([^&]*).*$>i", "\\2", $_SERVER['HTTP_REFERER']) );
        // Kontrola jestli už stejný záznam existuje
        $kontrola = @mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]statReferer WHERE LOWER(server)=LOWER('$server') AND LOWER(fraze)=LOWER('$fraze') LIMIT 1") );
        If( $kontrola["id"] ){
            //pokud už záznam existuje
            $dotaz = Mysql_query("UPDATE $CONF[sqlPrefix]statReferer SET pocet=(pocet+1) WHERE id=$kontrola[id]") or die(mysql_error());
        }
        Else{
            //pokud záznam neexistuje
            $dotaz = Mysql_query("INSERT INTO $CONF[sqlPrefix]statReferer(server, url, fraze, pocet) VALUES('$server', '$url', '$fraze', 1) ") or die(mysql_error());
        }
    }
    
    /* -- Z nějaké jiné stránky -- */
    Else{
        // Zjištění informací z URL
        $server = preg_replace(">^http://(www\.)?([^/?]*).*$>i", "\\2", $_SERVER['HTTP_REFERER']); 
        $url = $_SERVER['HTTP_REFERER'];  
        $url2 = str_replace("http://www.", "http://", $_SERVER['HTTP_REFERER']);  //url bez 'www'
        $fraze = ''; 
        // Kontrola jestli už stejný záznam existuje
        $kontrola = @mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]statReferer WHERE LOWER(url)=LOWER('$url') OR LOWER(url)=LOWER('$url2') LIMIT 1") );
        If( $kontrola["id"] ){
            //pokud už záznam existuje
            $dotaz = Mysql_query("UPDATE $CONF[sqlPrefix]statReferer SET pocet=(pocet+1) WHERE id=$kontrola[id]") or die(mysql_error());
        }
        Else{
            //pokud záznam neexistuje
            $dotaz = Mysql_query("INSERT INTO $CONF[sqlPrefix]statReferer(server, url, fraze, pocet) VALUES('$server', '$url', '$fraze', 1) ") or die(mysql_error());
        }           
    }
}

///////////////////////
// Roboti vyhledávačů 
///////////////////////
If( jeRobot() ){
    // Zjištění jestli zde nebyl před chvílí
    $robot = mysql_fetch_assoc(Mysql_query("SELECT * FROM $CONF[sqlPrefix]statRobots WHERE user_agent='$_SERVER[HTTP_USER_AGENT]' AND time>".(time()-15*60)." LIMIT 1"));

    If( !$robot["id"] ){  //pokud zde tento robot v posledních minutách nebyl
        // Zapsání příchodu robota
        Mysql_query("INSERT INTO $CONF[sqlPrefix]statRobots(user_agent, time, pocet) VALUES ('$_SERVER[HTTP_USER_AGENT]', ".time().", 1)");
    }
    Else{   //pokud zde byl před chvílí
        // Aktualizace počtu
        Mysql_query("UPDATE $CONF[sqlPrefix]statRobots SET pocet=".($robot["pocet"]+1)." WHERE id=$robot[id]");
    }
}



/****************************/
/* NEJ-ZOBRAZOVANĚJŠÍ ZBOŽÍ */
/****************************/
If( $_GET["a"]=='produkty' AND $_GET["produkt"] ){
    $idProduktu = preg_replace("|^([0-9]*)-.*$|si","$1",$_GET["produkt"]);
    $kontrola = mysql_fetch_assoc( Mysql_query("SELECT idProduktu FROM $CONF[sqlPrefix]statProdukty WHERE idProduktu=$idProduktu LIMIT 1 ") );
    // Produkt uz v databázi je
    If( $kontrola["idProduktu"] ){
        Mysql_query("UPDATE $CONF[sqlPrefix]statProdukty 
                     SET pocet=((SELECT pocet FROM (SELECT * FROM $CONF[sqlPrefix]statProdukty) as pomTable WHERE idProduktu=$idProduktu ) + 1) 
                     WHERE idProduktu=$idProduktu") or die(mysql_error());
    }
    // Produkt zde ještě není
    Else{
        Mysql_query("INSERT INTO $CONF[sqlPrefix]statProdukty(idProduktu, pocet) VALUES ($idProduktu, 1) ") or die(mysql_error());
    }
}
?>
