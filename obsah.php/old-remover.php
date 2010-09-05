<?php
/**
 * Přesměrovává starý formát odkazů na nový
 */

/* -- Kategorie produktů -- */
If( eregi("\?kategorie=",$_SERVER['REQUEST_URI']) ){
    $nazevKategorie = zjisti_z("$CONF[sqlPrefix]zbozi","kategorie","id=$_GET[kategorie]");
    $seoNazevKategorie = urlText($nazevKategorie);
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://www.nabytek-furniture.cz/produkty/$_GET[kategorie]-$seoNazevKategorie/");
    header("Connection: close");
} 
?>

