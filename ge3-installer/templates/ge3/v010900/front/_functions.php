<?php
function _nahodnyObrazek($path=''){
        $path = $path? $path: 'userfiles/headers';
        
        $obrazky = array();
        $adresar=opendir($path);
        While($soubor=readdir($adresar)){
              If( file_exists("$path/$soubor") AND preg_match("#\\.(jpg|jpeg|png|gif|bmp)$#i", $soubor) ){
                  $obrazky[]= "$path/$soubor";
              }
        }
        closedir($adresar);
        
        return $GLOBALS["config"]["absDir"].$obrazky[rand(0, count($obrazky)-1)];        
}

Function _vlozGalerii($obsah){
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

Function getPodclanky($typ){
        $CONF = &$GLOBALS["config"];
        
        $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]clanky WHERE typ='$typ'");
        $podclanky = '';
        While($radek=mysql_fetch_assoc($dotaz)){
              $podclanky.= '<li><a href="'.$CONF["absDir"].'../clanky/'.$radek["id"].'-'.urlText($radek["nazev"]).'.html">'.$radek["nazev"].'</a></li>';
        }
        
        Return $podclanky? '<div class="hover"><ul>'.$podclanky.'</ul></div>': '';
}

Function neumiFlash(){
        If( eregi("iPhone", $_SERVER["HTTP_USER_AGENT"]) ) Return True;
        Else Return False;
}

Function _ikonaPoznamky($obsah, $typ){
        $ikona = 'info.png';
        If($obsah=='') $ikona='info-prazdne.png';
        If($typ=='kladná') $ikona='info-kladne.png';
        If($typ=='záporná') $ikona='info-zaporne.png';
        Return $ikona;
}

Function _ikonaSouboru($nazevSouboru){
        $CONF = &$GLOBALS["config"];

        $pripona = eregi_replace("^.*\.([a-z]*)$", "\\1", $nazevSouboru);
        $pripona = strtolower($pripona);

        If(!file_exists("templates/$CONF[vzhled]/ikony/$pripona.png")) 
           $pripona="default";  
        
        Return "$pripona.png";
}



Function _hezkaCena($cena){
        $cena = number_format($cena, 2, ",", " ");
        $cena = ereg_replace(",00", ",-", $cena);

        Return $cena;
}
Function _hezka_cena($cena){
        $cena = number_format($cena, 2, ",", " ");
        $cena = ereg_replace(",00", ",-", $cena);

        Return $cena;
}
Function _normalniCena($cena){
        $cena = number_format($cena, 2, ".", "");
        $cena = str_replace(".00", "", $cena);

        Return $cena;
}



Function _stylCeny($cena){
        $cena = _hezkaCena($cena);
        $styl='';
        
        If(strlen($cena)>9) $styl='style="font-size: 7pt;"';
        If(strlen($cena)>10)$styl='style="font-size: 7pt; overflow: visible;"';
        If(strlen($cena)>11)$styl='style="font-size: 10px; overflow: visible;"';                
        
        Return $styl;
}
?>
