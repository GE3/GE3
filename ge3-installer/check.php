<h2>GE3 Installer - Kontrola</h2>

<h3>FTP práva k souborům</h3>
<?php
$files = array("../config.php",
               "../config.php/default.conf.php",
               "../admin/export",
               "../admin/templates",
               "../templates",
               "../userfiles",
               "../userfiles/galerie",
               "../zbozi/obrazky",
               "../zbozi/prilohy",
               "../Nette/temp",
               "../Nette/log",
               );
$error_files = array();
foreach($files as $value){
        if( !file_exists($value) or !is_writeable($value) ) 
            $error_files[]= $value;
}

foreach($error_files as $value) echo "$value <br>";
if(!count($error_files)) echo "Všechna práva jsou nastavena správně (OK)";
?>

<h3>Špatně nahrané soubory</h3>
<?php
$zero_files = array();
$files = array();
$file = "..";
while($file){
      $dir=opendir($file);
      While($actual=readdir($dir)){
            if( is_dir("$file/$actual") and $actual!='..' and $actual!='.' ){ 
                array_push($files, "$file/$actual");
            }
            elseif(!filesize("$file/$actual") and $actual!='..' and $actual!='.' ){
                array_push($zero_files, "$file/$actual");
            }
      }
      closedir($dir);
    
      $file = array_pop($files);
}

if(!count($zero_files)) echo "Žádné nulové soubory nenalezeny (OK)";
else{
  foreach($zero_files as $key=>$value){
    echo "$value &nbsp; (0 kB) <br>";
  }
}
?>

<h3>Safe_mode</h3>
<?php
if( ini_get('safe_mode') ){
    echo "Safe_mode je zapnutý (ERROR)";
}else{
    echo "Safe_mode je vyypnutý (OK)";
}
?>