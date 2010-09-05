<?php
session_start(1);

/* -- Include configu -- */
Include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

Include "../fce/fce.inc";



If( $_POST["email"] ){
    If( $_POST["poznamkaZmenit"] ){
        $poznamka = $_POST["poznamka"];
        $typ = $_POST["typ"]; 
        Mysql_query("UPDATE $CONF[sqlPrefix]zakazniciPoznamky SET poznamka='$poznamka',typ='$typ' WHERE email='$_POST[email]'");
        If( mysql_affected_rows()<1 ){
            Mysql_query("INSERT INTO $CONF[sqlPrefix]zakazniciPoznamky (email,poznamka,typ) VALUES ('$_POST[email]','$poznamka','$typ')");        
        }
        Echo '<script type="text/javascript">
              location.replace(location.href);
              </script>';
    }

    $zakaznik = @mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]zakazniciPoznamky WHERE email='$_POST[email]'") );
    Echo '<table border="0">
            <tr>
              <td><b>E-mail: </b></td>
              <td>'.$_POST["email"].'</td>
            </tr>
            <tr>
              <td>Typ: </td>
              <td>
                <select id="selectTyp">
                  <option value="neutrální">neutrální</option>
                  <option value="kladná" '.($zakaznik["typ"]=='kladná'? 'selected': '').'>kladná</option>
                  <option value="záporná" '.($zakaznik["typ"]=='záporná'? 'selected': '').'>záporná</option>
                </select>
              </td>
            </tr>
            <tr>
              <td valign="top" style="padding-top: 2px;"><b>Poznámka: </b></td>
              <td>
                <textarea id="textareaPoznamka" style="width: 256px; height: 96px;">'.$zakaznik["poznamka"].'</textarea>
                <input type="button" value="Změnit" onClick="easyAjaxPopup(\'Poznámka k zákazníkovi\', \'zakazniciPoznamky.ajax.php\', \'email='.$_POST["email"].'&poznamkaZmenit=true&poznamka=\'+document.getElementById(\'textareaPoznamka\').value+\'&typ=\'+document.getElementById(\'selectTyp\').value);"> 
              </td>
            </tr>
          </table>';
}
?>