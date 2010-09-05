<?php
Function cestaKategorie($kategorie, $podkat1, $podkat2, $podkat3){
        $CONF = $GLOBALS["config"];
        $cesta = '';

        If( $podkat3 ) $dotaz="SELECT id,kategorie,podkat1,podkat2,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE id=$podkat3";
        Elseif($podkat2) $dotaz="SELECT id,kategorie,podkat1,podkat2,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE id=$podkat2";
        Elseif($podkat1) $dotaz="SELECT id,kategorie,podkat1,podkat2,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE id=$podkat1";
        Elseif($kategorie) $dotaz="SELECT id,kategorie,podkat1,podkat2,podkat3 FROM $CONF[sqlPrefix]zbozi WHERE id=$kategorie";
        
        $radek = mysql_fetch_assoc( Mysql_query($dotaz) );
        
        $cesta.= $kategorie? '<a href="?m=editace_zbozi&kategorie='.$radek["id"].'">'.$radek["kategorie"].'</a>': '';
        $cesta.= $podkat1? ' » <a href="?m=editace_zbozi&kategorie='.$radek["id"].'&podkat1='.$radek["id"].'">'.$radek["podkat1"].'</a>': '';
        $cesta.= $podkat2? ' » <a href="?m=editace_zbozi&kategorie='.$radek["id"].'&podkat1='.$radek["id"].'&podkat2='.$radek["id"].'">'.$radek["podkat2"].'</a>': '';
        $cesta.= $podkat3? ' » <a href="?m=editace_zbozi&kategorie='.$radek["id"].'&podkat1='.$radek["id"].'&podkat2='.$radek["id"].'&podkat3='.$radek["id"].'">'.$radek["podkat3"].'</a>': ''; 
        
        Return $cesta;
}



Function getFriendlyFilename($soubor, $nazevProduktu){
        //vrací název souboru pro obrázek podle názvu produktu
        $pripona = preg_replace("|^.*\.([a-zA-Z]+)$|", "$1", $soubor);

        $friendlyName = urlText($nazevProduktu);
        $i=0;
        Do{
          $i++;
          $jedinecnyNazev = $friendlyName.($i>1?"-$i":"").".$pripona";
        }While( file_exists("../zbozi/obrazky/$jedinecnyNazev") );

        Return $jedinecnyNazev;
}



Function stromKategorii(){
        $CONF = $GLOBALS["config"];
        $strom = '';

        $dotazKategorie = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi GROUP BY kategorie ORDER BY vaha DESC, kategorie ASC");
        // Zobrazení kategorií
        $strom.= '<div style="font-size: 10pt; padding-left: 6px;">';
        While($radekKategorie = mysql_fetch_array($dotazKategorie)){
              If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat1", "kategorie='".$radekKategorie["kategorie"]."' ORDER BY podkat1 DESC") ){  //Příprava vzhledu a JS funkcí
                  $onClick = 'onClick="ukazSkryj(\'divPodkat1_'.$radekKategorie["id"].'\'); plusMinus(\'imgPlusKategorie'.$radekKategorie["id"].'\');"';
                  $style = 'style="cursor: pointer;"';
                  $obrazek = '<img id="imgPlusKategorie'.$radekKategorie["id"].'" src="images/plus.png">';
                }
              Else{ $onClick = ''; $style = ''; $obrazek = '<span style="font-size: 1px; padding-right: 8px;">&nbsp;</span>'; }
              $strom.= '<div style="margin-top: 7px;">
                          <span '.$onClick.' '.$style.'>'.$obrazek.'</span> 
                          <a href="?m=editace_zbozi&kategorie='.$radekKategorie["id"].'" '.( $_GET["kategorie"]==$radekKategorie["id"]?'style="color: #666666;"':'' ).'>'.$radekKategorie["kategorie"].'</a>
                        </div>';  //Zobrazení názvu s odkazy pro změnu atd.
        
              // Zobrazení podkategorií
              If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat1", "kategorie='".$radekKategorie["kategorie"]."' ORDER BY podkat1 DESC") ){
                  $dotazPodkat1 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".$radekKategorie["kategorie"]."' AND podkat1!='' GROUP BY podkat1 ORDER BY podkat1 ");
                  $strom.= '<div id="divPodkat1_'.$radekKategorie["id"].'" style="font-size: 8pt; margin-left: 16px; display: none;">';
                  While($radekPodkat1 = Mysql_fetch_array($dotazPodkat1)){
                        If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat2", "kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' ORDER BY podkat2 DESC") ){  //Příprava vzhledu a JS funkcí
                            $onClick = 'onClick="ukazSkryj(\'divPodkat2_'.$radekPodkat1["id"].'\'); plusMinus(\'imgPlusPodkat1'.$radekPodkat1["id"].'\');"';
                            $style = 'style="cursor: pointer; "';
                            $obrazek = '<img id="imgPlusPodkat1'.$radekPodkat1["id"].'" src="images/plus.png">';
                          }
                        Else{ $onClick = ''; $style = ''; $obrazek = '<span style="font-size: 1px; padding-right: 8px;">&nbsp;</span>'; }
                        $strom.= '<span '.$onClick.' '.$style.'>'.$obrazek.'</span> 
                                  <a href="?m=editace_zbozi&kategorie='.$radekKategorie["id"].'&podkat1='.$radekPodkat1["id"].'" '.( $_GET["podkat1"]==$radekPodkat1["id"]?'style="color: #666666;"':'' ).'>
                                    '.$radekPodkat1["podkat1"].'
                                  </a><br>';  //Zobrazení názvu s odkazy pro změnu atd.
        
                        // Zobrazení podkategorií 2
                        If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat2", "kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' ORDER BY podkat2 DESC") ){
                            $dotazPodkat2 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' AND podkat2!='' GROUP BY podkat2 ORDER BY podkat2 ");
                            $strom.= '<div id="divPodkat2_'.$radekPodkat1["id"].'" style="font-size: 8pt; margin-left: 16px; display: '.( $_GET["podkat1"]==$radekPodkat1["id"]?'block':'none' ).'; ">';
                            While($radekPodkat2 = mysql_fetch_array($dotazPodkat2)){
                                  If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat3", "kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' AND podkat2='".$radekPodkat2["podkat2"]."' AND podkat3!='' ORDER BY podkat3 DESC") ){  //Příprava vzhledu a JS funkcí
                                      $onClick = 'onClick="ukazSkryj(\'divPodkat3_'.$radekPodkat2["id"].'\'); plusMinus(\'imgPlusPodkat2'.$radekPodkat2["id"].'\');"';
                                      $style = 'style="cursor: pointer; "';
                                      $obrazek = '<img id="imgPlusPodkat2'.$radekPodkat2["id"].'" src="images/plus.png">';
                                    }
                                  Else{ $onClick = ''; $style = ''; $obrazek = '<span style="font-size: 1px; padding-right: 8px;">&nbsp;</span>'; }
                                  $strom.= '<span '.$onClick.' '.$style.'>'.$obrazek.'</span> 
                                            <a href="?m=editace_zbozi&kategorie='.$radekKategorie["id"].'&podkat1='.$radekPodkat1["id"].'&podkat2='.$radekPodkat2["id"].'" '.( $_GET["podkat2"]==$radekPodkat2["id"]?'style="color: #666666;"':'' ).'>
                                              '.$radekPodkat2["podkat2"].'
                                            </a><br>';  //Zobrazení názvu s odkazy pro změnu atd.
        
                                  // Zobrazení podkategorií 3
                                  If( zjisti_z("$CONF[sqlPrefix]zbozi", "podkat3", "kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' AND podkat2='".$radekPodkat2["podkat2"]."' ORDER BY podkat3 DESC") ){
                                      $dotazPodkat3 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='".$radekKategorie["kategorie"]."' AND podkat1='".$radekPodkat1["podkat1"]."' AND podkat2='".$radekPodkat2["podkat2"]."' AND podkat3!='' GROUP BY podkat3 ORDER BY podkat3 ");
                                      $strom.= '<div id="divPodkat3_'.$radekPodkat2["id"].'" style="font-size: 8pt; margin-left: 16px; display: '.( $_GET["podkat2"]==$radekPodkat2["id"]?'block':'none' ).';">';
                                      While($radekPodkat3 = mysql_fetch_array($dotazPodkat3)){
                                            $strom.= '<span style="font-size: 1px; padding-right: 8px;">&nbsp;</span> 
                                                      <a href="?m=editace_zbozi&kategorie='.$radekKategorie["id"].'&podkat1='.$radekPodkat1["id"].'&podkat2='.$radekPodkat2["id"].'&podkat3='.$radekPodkat3["id"].'" '.( $_GET["podkat3"]==$radekPodkat3["id"]?'style="color: #666666;"':'' ).'>
                                                        '.$radekPodkat3["podkat3"].'
                                                      </a><br>';
                                      }
                                      $strom.= '</div>';
                                  }
        
                            }
                            $strom.= '</div>';
                        }
        
                  }
                  $strom.= '</div>';
              }
        
        }
        $strom.= '</div>';
        
        Return $strom;
}
?>