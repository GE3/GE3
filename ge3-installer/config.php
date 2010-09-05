<?php
function getValue($key){
        $web_name = preg_replace("#^(http://)?(www\\.)?([^.]+)\\..+$#", "\\3", $_SERVER["HTTP_HOST"]);

        $defaults = array("vzhled" => $web_name, 
                          "mailer" => "info@$web_name.cz", 
                          "sqlHost" => "localhost",
                          "sqlUser" => substr($web_name."cz", 0, 10),
                          "sqlPass" => "Soov5doe",
                          "dbName" => substr($web_name."cz", 0, 10),
                          "sqlPrefix" => substr($web_name, 0, 2)."_", 
                          "adminKategorie" => "1", 
                          "adminFotogalerie" => "1", 
                          "adminSoubory" => "1",
                          );
                          
        //speciální případy
        if( $key=='admin[ctrlClankyVodorovne]' ){
            if(file_exists("../config.php/default.conf.php")) return $GLOBALS["config"]["admin"]["ctrlClankyVodorovne"];
            else return $defaults["admin"]["ctrlClankyVodorovne"];        
        }
        if( $key=='admin[ctrlClankyNovinky]' ){
            if(file_exists("../config.php/default.conf.php")) return $GLOBALS["config"]["admin"]["ctrlClankyNovinky"];
            else return $defaults["admin"]["ctrlClankyNovinky"];        
        } 
        if( $key=='faktura[dodavatel]' ){
            if(file_exists("../config.php/default.conf.php")) return $GLOBALS["config"]["faktura"]["dodavatel"];
            else return $defaults["faktura"]["dodavatel"];        
        }                
        //defaultní hodnoty
        if(file_exists("../config.php/default.conf.php")) return $GLOBALS["config"][$key];
        else return $defaults[$key];
}
?>

<h2>GE3 Installer - Konfigurační soubor</h2>

<?php
if( $_POST["mod"] ){
    $template = file_get_contents("templates/default.conf.php");
    foreach($_POST as $key=>$value){
            if(!is_array($value) ){ 
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            else{ 
                foreach($value as $key2=>$value2){
                        $template = str_replace('{'.$key.'['.$key2.']}', $value2, $template);                
                }
            }
    }
    
    $template = preg_replace('#"\{[^{}]+\}"#U', '""', $template);
    $template = preg_replace("#'\\{[^{}]+\\}'#U", "''", $template);
    $template = preg_replace("#\\{[^{}]+\\}#U", "0", $template);    
    
    file_put_contents("../config.php/default.conf.php", $template);
    
    require '../config.php/default.conf.php';
    
    echo "<b>Konfigurační soubor vytvořen</b>";
}
?>

<form method="post" action="">
  <table border="0">
    <tr>
      <td colspan="2"><h3>Základní údaje</h3></td>
    </tr>
    <tr>
      <td>Mód: </td>
      <td>
        <select name="mod">
          <option value="ge3">GE3</option>
          <option value="dante" <?= getValue("mod")=='dante'? 'selected': ''; ?>>Dante</option>
          <option value="thalia" <?= getValue("mod")=='thalia'? 'selected': ''; ?>>Thalia</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Název vzhledu: </td>
      <td><input type="text" name="vzhled" value="<?= getValue("vzhled"); ?>"></td>
    </tr>
    <tr>
      <td>Identifikace odchozích e-mailů: </td>
      <td><input type="text" name="mailer" value="<?= getValue("mailer"); ?>"></td>
    </tr>
    <tr>
      <td>Fakturační adresa: </td>
      <td><input type="text" name="faktura[dodavatel]" size="60" value="<?= getValue("faktura[dodavatel]"); ?>"></td>
    </tr>    
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>    

    <tr>
      <td colspan="2"><h3>Databáze</h3></td>
    </tr>
    <tr>
      <td>Server: </td>
      <td><input type="text" name="sqlHost" value="<?= getValue("sqlHost"); ?>"></td>
    </tr>
    <tr>
      <td>Uživatel: </td>
      <td><input type="text" name="sqlUser" value="<?= getValue("sqlUser"); ?>"></td>
    </tr>
    <tr>
      <td>Heslo: </td>
      <td><input type="password" name="sqlPass" value="<?= getValue("sqlPass"); ?>"></td>
    </tr>
    <tr>
      <td>Název databáze: </td>
      <td><input type="text" name="dbName" value="<?= getValue("dbName"); ?>"></td>
    </tr>
    <tr>
      <td>Prefix tabulek: </td>
      <td><input type="text" name="sqlPrefix" value="<?= getValue("sqlPrefix"); ?>"></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="2"><h3>Administrace a práva</h3></td>
    </tr>
    <tr>
      <td>Spravovat hlavní články: </td>
      <td><input type="checkbox" name="admin[ctrlClankyVodorovne]" value="1" <?= getValue("admin[ctrlClankyVodorovne]")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Spravovat novinky: </td>
      <td><input type="checkbox" name="admin[ctrlClankyNovinky]" value="1" <?= getValue("admin[ctrlClankyNovinky]")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Kategorie: </td>
      <td><input type="checkbox" name="adminKategorie" value="1" <?= getValue("adminKategorie")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Velkoobchod: </td>
      <td><input type="checkbox" name="adminVelkoobchod" value="1" <?= getValue("adminVelkoobchod")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Hromadná změna cen: </td>
      <td><input type="checkbox" name="adminHromadneCeny" value="1" <?= getValue("adminHromadneCeny")? 'checked': ''; ?>></td>
    </tr>  
    <tr>
      <td>Modul Fotogalerie: </td>
      <td><input type="checkbox" name="adminFotogalerie" value="1" <?= getValue("adminFotogalerie")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Ankety: </td>
      <td><input type="checkbox" name="adminAnkety" value="1" <?= getValue("adminAnkety")? 'checked': ''; ?>></td>
    </tr>    
    <tr>
      <td>Modul Diskusní fórum: </td>
      <td><input type="checkbox" name="adminFora" value="1" <?= getValue("adminFora")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Facebook: </td>
      <td><input type="checkbox" name="adminFacebook" value="1" <?= getValue("adminFacebook")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Správce souborů: </td>
      <td><input type="checkbox" name="adminSoubory" value="1" <?= getValue("adminSoubory")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Faktury: </td>
      <td><input type="checkbox" name="adminFaktury" value="1" <?= getValue("adminFaktury")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Informace o návštěvnících…: </td>
      <td><input type="checkbox" name="adminZakaznici" value="1" <?= getValue("adminZakaznici")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td>Modul Export produktů: </td>
      <td><input type="checkbox" name="adminExportProduktu" value="1" <?= getValue("adminExportProduktu")? 'checked': ''; ?>></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
      <td colspan="2" align="center"><input type="submit" name="odeslat" value="Vygenerovat default.conf.php"></td>
    </tr>    
 
  </table>
</form>