<?php
// Include functions
Include "../fce/fce.inc";
Include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

?>



<?php
Include 'upravit.funkce.php';

/* -- Přidání -- */
If( is_array($_FILES["prilohy"]["name"]) ){
    $priponyZakazane = array("php", "php2", "php3", "php4");
    Foreach($_FILES["prilohy"]["name"] as $key=>$value){
            If( $value AND !in_array($pripona, $priponyZakazane) ){
                $nazevSouboruBezPripony = eregi_replace("^(.*)\.[a-z]+$", "\\1", $_FILES["prilohy"]["name"][$key]);
                $nazevSouboru = getFriendlyFilename($_FILES["prilohy"]["name"][$key], $nazevSouboruBezPripony);
    
                If( !file_exists("../zbozi/prilohy/$nazevSouboru") ){
                    If( Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET prilohy=CONCAT(prilohy,'$nazevSouboru;') WHERE produkt=
                                  (SELECT produkt FROM (SELECT * FROM $CONF[sqlPrefix]zbozi) as pomTable WHERE id=$_POST[id]) 
                                ") ){
                        If( move_uploaded_file($_FILES["prilohy"]["tmp_name"][$key], "../zbozi/prilohy/$nazevSouboru") ){
                            Echo 'Nahrávání proběhlo úspěšně.
                                  <script type="text/javascript">
                                  window.top.window.konecUploadu(1);
                                  </script>
                                  ';
                        }
                        Else{
                            Echo 'CHYBA: Nepovedlo se soubor uložit na ftp serveru.
                                  <script type="text/javascript">
                                  window.top.window.konecUploadu(0);
                                  </script>
                                  ';                    
                        }                    
                    }
                    Else{
                        Echo 'CHYBA: Nepovedlo se provést SQL dotaz.
                              <script type="text/javascript">
                              window.top.window.konecUploadu(0);
                              </script>
                              '; 
                    }
                }
                Else{
                    Echo 'CHYBA: Soubor již existuje.
                          <script type="text/javascript">
                          window.top.window.konecUploadu(0);
                          </script>
                          ';                 
                }    
            }            
    }
}
?>
