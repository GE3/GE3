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
If( $_FILES["obrazky"]["name"][1] ){
    $pripony = array("jpg", "jpeg", "bmp", "png", "gif", "JPG", "JPEG", "BMP", "PNG", "GIF");
    $nazevProduktu = zjisti_z("$CONF[sqlPrefix]zbozi", "produkt", "id=$_POST[id]");

    $obrazkyNazvy = '';
    Foreach($_FILES["obrazky"]["name"] as $key=>$value){
            $pripona = preg_replace("|^.*\.([a-zA-Z]+)$|", "$1", $value);
            If( $value AND in_array($pripona, $pripony) ){
                $nazevSouboru = getFriendlyFilename($_FILES["obrazky"]["name"][$key], $nazevProduktu);
                If( !move_uploaded_file($_FILES["obrazky"]["tmp_name"][$key], "../zbozi/obrazky/$nazevSouboru") ){
                    Echo 'CHYBA: Nepodařilo se nahrát některý ze souborů na ftp server.
                          <script type="text/javascript">
                          window.top.window.konecUploadu(0);
                          </script>';
                    die();                
                }
                $obrazkyNazvy.= "$nazevSouboru;";
            }
    }
                  //"UPDATE $CONF[sqlPrefix]zbozi SET obrazky=CONCAT(obrazky,'$obrazkyNazvy') WHERE id=$_POST[id]"
    $produkt = mysql_fetch_assoc( Mysql_query("SELECT produkt,obrazky FROM $CONF[sqlPrefix]zbozi WHERE id=$_POST[id]") );
    if($produkt["obrazky"]) $dotaz = "UPDATE $CONF[sqlPrefix]zbozi SET obrazky=CONCAT(obrazky,'$obrazkyNazvy') WHERE produkt='$produkt[produkt]'";
    else $dotaz = "UPDATE $CONF[sqlPrefix]zbozi SET obrazky='$obrazkyNazvy' WHERE produkt='$produkt[produkt]'";
    If( Mysql_query($dotaz) AND !mysql_error() ){
        Echo 'Nahrávání proběhlo úspěšně.
              <script type="text/javascript">
              window.top.window.konecUploadu(1);
              </script>';
    }
    Else{
        Echo 'CHYBA: Nelze provést SQL dotaz.
              <script type="text/javascript">
              window.top.window.konecUploadu(0);
              </script>';
    }
}
?>
