<?php
$kategorie=$produktKategorie;
$podkat1=$produktPodkat1;
$podkat2=$produktPodkat2;
$podkat3=$produktPodkat3;
If( $podkat2 ){
    Echo '
       <select name="podkat3" style="font-size: 80%;" onChange="novaPodkat(this.value,3);">
          '.($podkat3?('<option value="'.$podkat3.'">'.$podkat3.'</option>'):'').'
          <option value="">-nová podkategorie- =&gt;</option>';

    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]zbozi WHERE kategorie='$kategorie' AND podkat1='$podkat1' AND podkat2='$podkat2' ORDER BY podkat3 ASC");
    while($radek=mysql_fetch_array($dotaz)){
       if($kategorie_tmp!=$radek["podkat3"]){
          echo '
             <option value="'.$radek["podkat3"].'">'.$radek["podkat3"].'</option>
             ';
          }
       $kategorie_tmp=$radek["podkat3"];
       }

    echo '
       </select>
          ';
    If( !$podkat3 ){
        Echo '
              <input type="text" id="inputNovaPodkat3" name="nova_podkat3" value="'.$nova_podkat3.'" style="font-size: 80%;">
              ';
    }
    Else{
        Echo '
              <input type="text" id="inputNovaPodkat3" name="nova_podkat3" value="'.$nova_podkat3.'" style="font-size: 80%; display: none;">
              ';
    }
}
?>

