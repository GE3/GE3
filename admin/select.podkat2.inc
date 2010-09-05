<?php
$kategorie=$produktKategorie;
$podkat1=$produktPodkat1;
$podkat2=$produktPodkat2;
If( $podkat1 ){
    Echo '
       <select name="podkat2" style="font-size: 80%;" onChange="novaPodkat(this.value,2);skryjPodkat(4);if(this.value==\'\')skryjPodkat(3);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat3.php\',\'kategorie='.$kategorie.'&podkat1='.$podkat1.'&podkat2=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat3\');ukazPodkat(3);}">
          '.($podkat2?('<option value="'.$podkat2.'">'.$podkat2.'</option>'):'').'
          <option value="">-nová podkategorie- =&gt;</option>';

    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' ORDER BY podkat2 ASC");
    while($radek=mysql_fetch_array($dotaz)){
       if($kategorie_tmp!=$radek["podkat2"]){
          echo '
             <option value="'.$radek["podkat2"].'">'.$radek["podkat2"].'</option>
             ';
       }
       $kategorie_tmp=$radek["podkat2"];
    }

    echo '
       </select>
          ';
    If( !$podkat2 ){
        Echo '
              <input type="text" id="inputNovaPodkat2" name="nova_podkat2" value="'.$nova_podkat2.'" style="font-size: 80%;" onChange="skryjPodkat(4);if(this.value==\'\')skryjPodkat(3);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat3.php\',\'kategorie='.$kategorie.'&podkat1='.$podkat1.'&podkat1=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat3\');ukazPodkat(3);}">
              ';
    }
    Else{
        Echo '
              <input type="text" id="inputNovaPodkat2" name="nova_podkat2" value="'.$nova_podkat2.'" style="font-size: 80%; display: none;" onChange="skryjPodkat(4);if(this.value==\'\')skryjPodkat(3);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat3.php\',\'kategorie='.$kategorie.'&podkat1='.$podkat1.'&podkat1=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat3\');ukazPodkat(3);}">
              ';
    }
}
?>

