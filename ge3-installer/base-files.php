<h2>GE3 Installer - Generátor základních souborů</h2>


<h3>Šablona administrace</h3>
<?php
if( $_POST["b"]=='template-admin' and $_POST["path"] ){
    if(!is_dir("../admin/templates/$CONF[vzhled]") ){
        umask(0000);
        mkdir("../admin/templates/$CONF[vzhled]", 0777);
    }
    
    $files = array();
    $file = "templates/$CONF[mod]/$_POST[path]/admin";
    while($file){
          $adresar=opendir($file);
          While($soubor=readdir($adresar)){
                if( $soubor!='..' and $soubor!='.' ){
                    if( is_file("$file/$soubor") ){
                        $from = "$file/$soubor";
                        $to = str_replace("templates/$CONF[mod]/$_POST[path]/admin", "../admin/templates/$CONF[vzhled]", "$file/$soubor");
                        copy($from, $to);
                        umask(0000);
                        chmod($to, 0777);
                    }
                    elseif(is_dir("$file/$soubor")){
                        umask(0000);
                        $new_dir = str_replace("templates/$CONF[mod]/$_POST[path]/admin", "../admin/templates/$CONF[vzhled]", "$file/$soubor");
                        mkdir($new_dir, 0777);                    
                        array_push($files, "$file/$soubor");
                    }
                }
          }
          closedir($adresar);
          
          $file = array_pop($files);
    }
    
    echo 'Šablona úspěšně vytvořena. ';    
}
else{
    $files = array();
    $adresar=opendir("templates/$CONF[mod]");
    While($soubor=readdir($adresar)){
          If( preg_match("#^v[0-9]+$#",$soubor) and is_dir("templates/$CONF[mod]/$soubor/admin") ){
              $files[]= $soubor;
          }
    }
    closedir($adresar);
    
    $options = '';
    rsort($files);
    foreach($files as $key=>$value){
            $options.= '<option value="'.$value.'">'.$value.'</option>';
    }    

    if(!file_exists("../admin/templates/$CONF[vzhled]/prihlasen-$CONF[mod].html") ){
        echo 'Šablona zatím neexistuje. <p>
              <form action="" method="post">
                <b>Vytvořit šablonu administrace</b> <br>
                Adresář se soubory: 
                <select name="path">
                '.$options.'
                </select> 
                <input type="hidden" name="b" value="template-admin">
                <input type="submit" name="odeslat" value="Vytvořit">
              </form>
              ';
    }
    else{
        echo 'Šablona je již vytvořena. <p>
              <form action="" method="post">
                <b>Vytvořit šablonu znovu</b> <br>
                Adresář se soubory: 
                <select name="path">
                '.$options.'
                </select> 
                <input type="hidden" name="b" value="template-admin">
                <input type="submit" name="odeslat" value="Vytvořit">
              </form>
              ';
    }
}
?>



<h3>Šablona uživatelské části</h3>
<?php
if( $_POST["b"]=='template-front' and $_POST["path"] ){
    if(!is_dir("../templates/$CONF[vzhled]") ){
        umask(0000);
        mkdir("../templates/$CONF[vzhled]", 0777);
    }
    
    $files = array();
    $file = "templates/$CONF[mod]/$_POST[path]/front";
    while($file){
          $adresar=opendir($file);
          While($soubor=readdir($adresar)){
                if( $soubor!='..' and $soubor!='.' ){
                    if( is_file("$file/$soubor") ){
                        $from = "$file/$soubor";
                        $to = str_replace("templates/$CONF[mod]/$_POST[path]/front", "../templates/$CONF[vzhled]", "$file/$soubor");
                        copy($from, $to);
                        umask(0000);
                        chmod($to, 0777);
                    }
                    elseif(is_dir("$file/$soubor")){
                        umask(0000);
                        $new_dir = str_replace("templates/$CONF[mod]/$_POST[path]/front", "../templates/$CONF[vzhled]", "$file/$soubor");
                        mkdir($new_dir, 0777);                    
                        array_push($files, "$file/$soubor");
                    }
                }
          }
          closedir($adresar);
          
          $file = array_pop($files);
    }
    
    echo 'Šablona úspěšně vytvořena. ';    
}
else{
    $files = array();
    $adresar=opendir("templates/$CONF[mod]");
    While($soubor=readdir($adresar)){
          If( preg_match("#^v[0-9]+$#",$soubor) and is_dir("templates/$CONF[mod]/$soubor/front") ){
              $files[]= $soubor;
          }
    }
    closedir($adresar);
    
    $options = '';
    rsort($files);
    foreach($files as $key=>$value){
            $options.= '<option value="'.$value.'">'.$value.'</option>';
    }
    

    if(!file_exists("../templates/$CONF[vzhled]/index.html") ){
        echo 'Šablona zatím neexistuje. <p>
              <form action="" method="post">
                <b>Vytvořit šablonu administrace</b> <br>
                Adresář se soubory: 
                <select name="path">
                '.$options.'
                </select> 
                <input type="hidden" name="b" value="template-front">
                <input type="submit" name="odeslat" value="Vytvořit">
              </form>
              ';
    }
    else{
        echo 'Šablona je již vytvořena. <p>
              <form action="" method="post">
                <b>Vytvořit šablonu znovu</b> <br>
                Adresář se soubory: 
                <select name="path">
                '.$options.'
                </select> 
                <input type="hidden" name="b" value="template-front">
                <input type="submit" name="odeslat" value="Vytvořit">
              </form>
              ';
    }
}
?>



<h3>Logo firmy</h3>
<?php
if( $_POST["b"]=='logo-firmy' ){
    if( preg_match("#\.(jpg|jpeg)$#i", $_FILES["logo"]["name"]) ){
        move_uploaded_file($_FILES["logo"]["tmp_name"], "../admin/templates/$CONF[vzhled]/logo_firmy.jpg");
        umask(0000);
        chmod("../admin/templates/$CONF[vzhled]/logo_firmy.jpg", 0777);
        echo "Obrázek úspěšně nahrán. <br>"; 
    }
    else echo "Špatný formát obrázku. <br>";
}

if( file_exists("../admin/templates/$CONF[vzhled]/logo_firmy.jpg") ){
    echo '<img src="../admin/templates/'.$CONF["vzhled"].'/logo_firmy.jpg">';
}
else{
    echo "Žádný obrázek zatím nebyl nahrán.";
}
?>

<p>
<form action="" method="post" enctype="multipart/form-data">
  Vložit nové logo (jpg): 
  <input type="file" name="logo"> <br>
  <input type="hidden" name="b" value="logo-firmy">
  <input type="submit" name="odeslat" value="Odeslat">
</form>