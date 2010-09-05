<?php
$kategorie=$produktKategorie;
$podkat1=$produktPodkat1;
If( $kategorie ){
    Echo '
       <select name="podkat1" style="font-size: 80%;" onChange="novaPodkat(this.value,1);skryjPodkat(3);if(this.value==\'\')skryjPodkat(2);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat2.php\',\'kategorie='.$kategorie.'&podkat1=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat2\');ukazPodkat(2);}">
          '.($podkat1?('<option value="'.$podkat1.'">'.$podkat1.'</option>'):'').'
          <option value="">-nová podkategorie- =&gt;</option>';

    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' ORDER BY podkat1 ASC");
    while($radek=mysql_fetch_array($dotaz)){
       if($kategorie_tmp!=$radek["podkat1"]){
          echo '
             <option value="'.$radek["podkat1"].'">'.$radek["podkat1"].'</option>
             ';
       }
       $kategorie_tmp=$radek["podkat1"];
    }

    echo '
       </select>
          ';
    If( !$podkat1 ){
        Echo '
           <input type="text" id="inputNovaPodkat1" name="nova_podkat1" value="'.$nova_podkat1.'" style="font-size: 80%;" onChange="skryjPodkat(3);if(this.value==\'\')skryjPodkat(2);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat2.php\',\'kategorie='.$kategorie.'&podkat1=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat2\');ukazPodkat(2);}">
              ';
    }
    Else{
        Echo '
           <input type="text" id="inputNovaPodkat1" name="nova_podkat1" value="'.$nova_podkat1.'" style="font-size: 80%; display: none;" onChange="skryjPodkat(3);if(this.value==\'\')skryjPodkat(2);if(this.value!=\'\'){easyAjax(\'ajax.php/ajax.podkat2.php\',\'kategorie='.$kategorie.'&podkat1=\'+this.value+\'&prefix='.$CONF["sqlPrefix"].'\',\'divPodkat2\');ukazPodkat(2);}">
              ';
    }
}
?>
