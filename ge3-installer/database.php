<h2>GE3 Installer - Tvorba databáze</h2>

<?php
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

function getValue($key){
        $web_name = preg_replace("#^(http://)?(www\\.)?([^.]+)\\..+$#", "\\3", $_SERVER["HTTP_HOST"]);

        $defaults = array("jmeno" => $web_name, 
                          "emailAdmin" => "info@$web_name.cz", 
                          );
  
        return isset($_POST[$key])? $_POST[$key]: $defaults[$key];
}
?>


<?php
if( $_POST["file"] ){
    $handle = fopen("databases/$_POST[file]", 'r');

    $count=0;
    $sql='';
    while(!feof($handle)){
          $s = fgets($handle);
          $sql.= $s;
          if( substr(rtrim($s),-1)===';' ){
          
              $sql = str_replace('{sqlPrefix}', $CONF["sqlPrefix"], $sql);
              $sql = str_replace('{jmeno}', $_POST["jmeno"], $sql);
              $sql = str_replace('{heslo}', $_POST["heslo"], $sql);
              $sql = str_replace('{emailAdmin}', $_POST["emailAdmin"], $sql);
              $sql = str_replace('{title}', $_POST["title"], $sql);
          
              mysql_query($sql) or die("MySQL error: <br>".mysql_error()." <p>in query: <br>$sql <p>");
              $sql = '';
              $count++;
          }
    }
    fclose($handle);
    
    echo "<b>Databáze úspěšně vytvořena.</b>";
}
else{
    $nalezeno = FALSE;
    $resource = mysql_list_tables($CONF["dbName"]);
    while($table = mysql_fetch_assoc($resource)){
          foreach($table as $key=>$value){
                  if($value=="$CONF[sqlPrefix]clanky") $nalezeno=TRUE;
          }
    }
    if( $nalezeno )
        echo "<b>Databáze již existuje.</b>";
    else echo "<b>Databáze zatím nebyla vytvořena.</b>";
    print_r($tables);
}
?>

<p>
<form action="" method="post">
  <table border="0">
    <tr>
      <td colspan="2"><h3>Vytvořit novou</h3></td>
    </tr>
    <tr>
      <td>Title webu: </td>
      <td><input type="text" name="title" value="<?= getValue("title"); ?>"></td>
    </tr>
    <tr>
      <td>Administrátor: </td>
      <td><input type="text" name="jmeno" value="<?= getValue("jmeno"); ?>"></td>
    </tr>
    <tr>
      <td>Heslo administrátora: </td>
      <td><input type="text" name="heslo" value="<?= getValue("heslo"); ?>"></td>
    </tr>
    <tr>
      <td>E-mail pro mailformy: </td>
      <td><input type="text" name="emailAdmin" value="<?= getValue("emailAdmin"); ?>"></td>
    </tr>
    <tr>
      <td>Šablona databáze: </td>
      <td>
        <select name="file">
        <?php
        $files = array();
        $adresar=opendir("databases");
        While($soubor=readdir($adresar)){
              If( is_file("databases/".$soubor) and preg_match("#^ge3-v[0-9]+\\.sql$#",$soubor) ){
                  $files[]= $soubor;
              }
        }
        closedir($adresar);
        
        rsort($files);
        foreach($files as $key=>$value){
                echo '<option value="'.$value.'">'.$value.'</option>';
        }
        ?>
        </select>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input type="submit" name="odeslat" value="Vytvořit novou databázi"></td>
    </tr>
  </table>
</form>