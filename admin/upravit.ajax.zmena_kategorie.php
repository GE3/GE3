<?php
// Include functions
include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");
?>



<?php
/**********/
/* FUNKCE */
/**********/
Function selectPodkat1($kategorie, $podkat1){
        $CONF = &$GLOBALS["config"];
        $select = '';

        If( $kategorie ){
            $select.= '
               <select name="podkat1" id="selectPodkat1" style="font-size: 80%;" onChange="novaPodkat(this.value,1);skryjPodkat(3);if(this.value==\'\')skryjPodkat(2);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat2.php\',\'kategorie='.$kategorie.'&podkat1=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat2\');ukazPodkat(2);}">
                  <option value="">-nová podkategorie- =&gt;</option>';
        
            $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' GROUP BY podkat1 ORDER BY podkat1 ASC");
            While($radek=mysql_fetch_array($dotaz)){
                  $select.= '
                     <option value="'.$radek["podkat1"].'" '.(($radek["podkat1"]==$podkat1 AND $podkat1)? 'selected': '').'>'.$radek["podkat1"].'</option>
                     ';
            }
        
            $select.= '
               </select>
                  ';
            If( !$podkat1 ){
                $select.= '
                   <input type="text" id="inputNovaPodkat1" name="nova_podkat1" value="'.$nova_podkat1.'" style="font-size: 80%;" onChange="skryjPodkat(3);if(this.value==\'\')skryjPodkat(2);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat2.php\',\'kategorie='.$kategorie.'&podkat1=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat2\');ukazPodkat(2);}">
                      ';
            }
            Else{
                $select.= '
                   <input type="text" id="inputNovaPodkat1" name="nova_podkat1" value="'.$nova_podkat1.'" style="font-size: 80%; display: none;" onChange="skryjPodkat(3);if(this.value==\'\')skryjPodkat(2);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat2.php\',\'kategorie='.$kategorie.'&podkat1=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat2\');ukazPodkat(2);}">
                      ';
            }
        }
        
        Return $select;
}

Function selectPodkat2($kategorie, $podkat1, $podkat2){
        $CONF = &$GLOBALS["config"];
        $select = '';

        If( $podkat1 ){
            $select.= '
               <select name="podkat2" id="selectPodkat2" style="font-size: 80%;" onChange="novaPodkat(this.value,2);if(this.value==\'\')skryjPodkat(3);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat3.php\',\'kategorie='.$kategorie.'&podkat1='.$podkat1.'&podkat2=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat3\');ukazPodkat(3);}">
                  <option value="">-nová podkategorie- =&gt;</option>';
        
            $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' GROUP BY podkat2 ORDER BY podkat2 ASC");
            While($radek=mysql_fetch_array($dotaz)){
                  $select.= '
                     <option value="'.$radek["podkat2"].'" '.(($radek["podkat2"]==$podkat2 AND $podkat2)? 'selected': '').'>'.$radek["podkat2"].'</option>
                     ';
            }
        
            $select.= '
               </select>
                  ';
            If( !$podkat2 ){
                $select.= '
                   <input type="text" id="inputNovaPodkat2" name="nova_podkat2" value="'.$nova_podkat2.'" style="font-size: 80%;" onChange="if(this.value==\'\')skryjPodkat(3);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat3.php\',\'kategorie='.$kategorie.'&podkat1='.$podkat1.'&podkat2=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat3\');ukazPodkat(3);}">
                      ';
            }
            Else{
                $select.= '
                   <input type="text" id="inputNovaPodkat2" name="nova_podkat2" value="'.$nova_podkat2.'" style="font-size: 80%; display: none;" onChange="if(this.value==\'\')skryjPodkat(3);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat3.php\',\'kategorie='.$kategorie.'&podkat1='.$podkat1.'&podkat2=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat3\');ukazPodkat(3);}">
                      ';
            }
        }
        
        Return $select;
}

Function selectPodkat3($kategorie, $podkat1, $podkat2, $podkat3){
        $CONF = &$GLOBALS["config"];
        $select = '';

        If( $podkat2 ){
            $select.= '
               <select name="podkat3" id="selectPodkat3" style="font-size: 80%;" onChange="novaPodkat(this.value,3);">
                  <option value="">-nová podkategorie- =&gt;</option>';
        
            $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' AND podkat2='$podkat2' GROUP BY podkat3 ORDER BY podkat3 ASC");
            while($radek=mysql_fetch_array($dotaz)){
                  $select.= '
                     <option value="'.$radek["podkat3"].'" '.(($radek["podkat3"]==$podkat3 AND $podkat3)? 'selected': '').'>'.$radek["podkat3"].'</option>
                     ';
            }
        
            $select.= '
               </select>
                  ';
            If( !$podkat3 ){
                $select.= '
                      <input type="text" id="inputNovaPodkat3" name="nova_podkat3" value="'.$nova_podkat3.'" style="font-size: 80%;">
                      ';
            }
            Else{
                $select.= '
                      <input type="text" id="inputNovaPodkat3" name="nova_podkat3" value="'.$nova_podkat3.'" style="font-size: 80%; display: none;">
                      ';
            }
        }
        
        Return $select;
}



/*********************/
/* PROVEDENÍ SKRIPTU */
/*********************/

/* -- Zjištění informací -- */
$produkt = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi x,$CONF[sqlPrefix]zbozi y  WHERE x.produkt=y.produkt AND y.id=$_POST[id] LIMIT 1") );


/* -- Provedení změny -- */
$info = 'Zde můžete změnit kategorii produktu \'<i>'.$produkt["produkt"].'</i>\'.';
If( $_POST["kategorie"] ){
    If( Mysql_query("UPDATE $CONF[sqlPrefix]zbozi SET kategorie='".str_replace("\"","´",$_POST["kategorie"])."', podkat1='".str_replace("\"","´",$_POST["podkat1"])."', podkat2='".str_replace("\"","´",$_POST["podkat2"])."', podkat3='".str_replace("\"","´",$_POST["podkat3"])."'
                     WHERE produkt=(SELECT produkt FROM (SELECT * FROM $CONF[sqlPrefix]zbozi) as pomTable WHERE id=$_POST[id]) ") ){
        $info = '<span style="color: #008000;">
                 Kategorie produktu byla úspěšně změněna.
                 </span>
                 <p>
                 <span style="color: #808080;">
                 Pro pokračování klikněte <span onClick="location.replace(location.href);" style="text-decoration: underline; cursor: pointer;">zde</span>, 
                 nebo počkejte <span id="spanOdpocitavani">10</span> sekund.
                 </span>
                 <script type="text/javascript">
                 odpocitavani(10,\'spanOdpocitavani\');
                 setTimeout("location.replace(location.href)", 10000);
                 </script>';

        //znovunačtení informací po změně
        $produkt = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi x,$CONF[sqlPrefix]zbozi y WHERE x.produkt=y.produkt AND y.id=$_POST[id] LIMIT 1") );                     
    }
}


/* -- Zobrazení -- */
Echo '<form action="" method="post">
        <script type="text/javascript">
        novaKategorie = function(hodnota){
                 if(hodnota==""){
                    document.getElementById("inputNovaKategorie").value="";
                    document.getElementById("inputNovaKategorie").style.display=\'none\';
                 }
                 else{
                    document.getElementById("inputNovaKategorie").value="";
                    document.getElementById("inputNovaKategorie").style.display=\'inline\';
                 }
        };
        skryjPodkat = function(cislo){
                 if( cislo<=3 ){
                     document.getElementById("spanPodkat3").innerHTML="";
                     document.getElementById("divPodkat3").innerHTML="";
                 }
                 if( cislo<=2 ){
                     document.getElementById("spanPodkat2").innerHTML="";
                     document.getElementById("divPodkat2").innerHTML="";
                 }
                 if( cislo==1 ){
                     document.getElementById("spanPodkat1").innerHTML="";
                     document.getElementById("divPodkat1").innerHTML="";
                 }
        };
        ukazPodkat = function(cislo){
                 document.getElementById("spanPodkat"+cislo).innerHTML="&nbsp;Podkategorie"+cislo+": ";
        };
        novaKategorie = function(kategorieValue){
                 if( kategorieValue=="" ){
                     document.getElementById("inputNovaKategorie").style.display="inline";
                 }else{
                       document.getElementById("inputNovaKategorie").style.display="none";
                       document.getElementById("inputNovaKategorie").value="";
                 }
        };
        novaPodkat = function(hodnota,cislo){
                 if(hodnota==""){
                    document.getElementById("inputNovaPodkat"+cislo).value="";
                    document.getElementById("inputNovaPodkat"+cislo).style.display="inline";
                 }
                 else{
                    document.getElementById("inputNovaPodkat"+cislo).value="";
                    document.getElementById("inputNovaPodkat"+cislo).style.display="none";
                 }
        };
        
        odesliZmenuKategorie = function(){
                var parametry = \'id='.$_POST["id"].'\';
                /*kategorie*/
                if( document.getElementById(\'inputNovaKategorie\').value.length>0 )
                    parametry = parametry + \'&kategorie=\' + document.getElementById(\'inputNovaKategorie\').value;
                else
                    parametry = parametry + \'&kategorie=\' + document.getElementById(\'selectKategorie\').value;
                /*podkat1*/
                try{
                    if( document.getElementById(\'inputNovaPodkat1\').value.length>0 )
                        parametry = parametry + \'&podkat1=\' + document.getElementById(\'inputNovaPodkat1\').value;
                    else
                        parametry = parametry + \'&podkat1=\' + document.getElementById(\'selectPodkat1\').value;
                }catch(er){}
                /*podkat2*/
                try{
                    if( document.getElementById(\'inputNovaPodkat2\').value.length>0 )
                        parametry = parametry + \'&podkat2=\' + document.getElementById(\'inputNovaPodkat2\').value;
                    else
                        parametry = parametry + \'&podkat2=\' + document.getElementById(\'selectPodkat2\').value;
                }catch(er){}
                /*podkat3*/
                try{
                    if( document.getElementById(\'inputNovaPodkat3\').value.length>0 )
                        parametry = parametry + \'&podkat3=\' + document.getElementById(\'inputNovaPodkat3\').value;
                    else
                        parametry = parametry + \'&podkat3=\' + document.getElementById(\'selectPodkat3\').value;
                }catch(er){}
                 
                easyAjaxPopup(\'Změna kategorie\', \'upravit.ajax.zmena_kategorie.php\', parametry);
        };        
        </script>
  
  
        <form method="post" action="">
        '.$info.'
        
        <br>&nbsp;
        <table>
          <tr><td><b>Kategorie: </b></td>
            <td>
              <select name="kategorie" id="selectKategorie" onChange="novaKategorie(this.value);skryjPodkat(2);if(this.value==\'\')skryjPodkat(1);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat1.php\',\'kategorie=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat1\');ukazPodkat(1);}">
                <option value="">-nová kategorie- =&gt;</option>
                <option value=""> </option>
        ';
  
     $dotaz2=Mysql_query("SELECT DISTINCT kategorie FROM $CONF[sqlPrefix]zbozi ORDER BY kategorie ASC");
     While($radek2=Mysql_fetch_array($dotaz2)){
           If( $radek2["kategorie"] ){
               Echo '<option value="'.$radek2["kategorie"].'" '.($produkt["kategorie"]==$radek2["kategorie"]? 'selected': '').'>'.$radek2["kategorie"].'</option>';
           }
     }
  
Echo '
              </select>
              <input type="text" id="inputNovaKategorie" size="15" name="kategorieNova" onChange="skryjPodkat(2);if(this.value==\'\')skryjPodkat(1);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat1.php\',\'kategorie=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat1\');ukazPodkat(1);}">
            </td></tr>
            <tr><td>
                  <span style="font-size: 80%; font-weight: bolder;" id="spanPodkat1">'.(($produkt["kategorie"])?"&nbsp;Podkategorie1":'').'</span></td><td>
                     <div id="divPodkat1">'.selectPodkat1($produkt["kategorie"], $produkt["podkat1"]).'</div></td></tr>
                  <tr><td>
                  <span style="font-size: 80%; font-weight: bolder;" id="spanPodkat2">'.(($produkt["podkat1"])?"&nbsp;Podkategorie2":'').'</span></td><td>
                     <div id="divPodkat2">'.selectPodkat2($produkt["kategorie"], $produkt["podkat1"], $produkt["podkat2"]).'</div></td></tr>
                  <tr><td>
                  <span style="font-size: 80%; font-weight: bolder;" id="spanPodkat3">'.(($produkt["podkat2"])?"&nbsp;Podkategorie3":'').'</span></td><td>
                     <div id="divPodkat3">'.selectPodkat3($produkt["kategorie"], $produkt["podkat1"], $produkt["podkat2"], $produkt["podkat3"]).'</div></td></tr>
                  <tr><td>
            </td>
          </tr>
        </table>
        <div style="text-align: right;"><p>
          <input type="button" name="odeslat" value="Změnit" onClick="odesliZmenuKategorie();">
        </div>
        </form>
      </form>';
?>
