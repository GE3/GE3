<?php
If( $_GET["a"]=='clanky' ){
    Function vlozGalerii($obsah){
            $CONF = &$GLOBALS["config"];

            While( eregi('<img[^>]* alt="galery [0-9]+"[^>]*/>', $obsah) ){
                  $tmplGalerie = new GlassTemplate("templates/$CONF[vzhled]/galerie.html");
            
                  /* -- Získání ID galerie -- */
                  $idGalerie = preg_replace('|^.*<img[^>]* alt="galery ([0-9]+)"[^>]*/>.*$|Usi', "\\1", $obsah);
                  $idGalerie = preg_replace("|\s|Usi", "", $idGalerie);  //(nevim proč, ale php si tam přidávalo odřádkování)

                  
                  /* -- Vygenerování galerie -- */

                  //název
                  $radek = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]gal_kat WHERE id=$idGalerie AND skryta='ne'") );
                  $tmplGalerie->prirad("id", $radek["id"]);
                  $tmplGalerie->prirad("nazev", $radek["nazev"]);
                  
                  //fotky
                  $dotaz = Mysql_query("SELECT x.id as idKategorie, x.slozka, x.nazev, y.* FROM 
                                          $CONF[sqlPrefix]gal_kat x JOIN $CONF[sqlPrefix]galerie y ON x.id=y.kategorie
                                        WHERE x.id=$idGalerie AND x.skryta='ne' ORDER BY y.vaha ASC") or die(mysql_error());
                  While($radek=mysql_fetch_assoc($dotaz)){
                        $tmplGalerie->newBlok("obrazek");
                        
                        $tmplGalerie->prirad("obrazek.url", "$CONF[absDir]userfiles/galerie/$radek[slozka]/$radek[foto]");
                        $tmplGalerie->prirad("obrazek.urlNahled", "$CONF[absDir]userfiles/galerie/$radek[slozka]/nahledy/$radek[foto]");
                        $tmplGalerie->prirad("obrazek.kategorie", $radek["nazev"]);
                        $tmplGalerie->prirad("obrazek.popis", $radek["popis"]);                        
                  }
                  
                  /* -- Vložení do obsahu -- */
                  $obsahPred = $obsah;   
                  $obsah = preg_replace('|<img[^>]* alt="galery '.$idGalerie.'"[^>]*/>|Usi', $tmplGalerie->getHtml(), $obsah);
            }             
            
            Return $obsah;
    }
      
      
    /**
     * Zobrazení článku
     */
    $idClanku = preg_replace("|^([0-9]*)-.*$|","$1",$_GET["clanek"]);
    $tmplClanek = new GlassTemplate("templates/$CONF[vzhled]/clanek.html");

    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE id='$idClanku'");
    $radek=Mysql_fetch_assoc($dotaz);

    $tmplClanek->newBlok("clanek");
    $tmplClanek->prirad("clanek.nazev", $radek["nazev"]);
    $tmplClanek->prirad("clanek.datum", @date("j.n.Y G:i",$radek["datum"]));
    $tmplClanek->prirad("clanek.uvod", $radek["uvod"]);
    $tmplClanek->prirad("clanek.obsah", $radek["obsah"]);
    
    
    $tmpl->prirad("obsah", vlozGalerii($tmplClanek->getHtml()) );

    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", '<a href="'.$CONF["absDir"].'index.php">Úvodní strana</a> » '.$radek["nazev"]);
    
    
    //////////////
    // TitleInfo
    //////////////
    $tmpl->prirad("titleInfo", $radek["nazev"]);
}
?>