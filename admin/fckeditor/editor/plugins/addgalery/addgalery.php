<?php
session_start(1);
include "../../../../../fce/fce.inc";

/* -- Skiny -- */
If( $_POST["zmena_skinu"] ){
    setcookie("skin",$_POST["skin"],time()+9999999);
}

/* -- Include funkcí -- */
Include '../../../../../funkce.php/debug.fce.php';
Include '../../../../../funkce.php/db.fce.php';

/* -- Include configu -- */
Include "../../../../../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
  <title>Přidat galerii</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <script language="javascript">
  var oEditor	= window.parent.InnerDialogLoaded();
  var FCK = oEditor.FCK;
  
  var addGalery = function(cislo){
                          absDir = '<?php $absDir = "http://".$_SERVER["HTTP_HOST"].ereg_replace("/[^/]*$","/",$_SERVER["SCRIPT_NAME"]); Echo $absDir ?>';
                          FCK.InsertHtml('<img width="196" height="196" alt="galery '+cislo+'" title="galery '+cislo+'" src="'+absDir+'galery.png" />');
                          window.parent.Cancel();
  }
  
  function zvyrazniGalerii(cislo){
          eDivGalerieNazev = document.getElementById('divGalerie'+cislo+'Nazev');
          eDivGalerieNazev.style.backgroundColor = '#FFFFDD';
  }
  function odvyrazniGalerii(cislo){
          eDivGalerieNazev = document.getElementById('divGalerie'+cislo+'Nazev');
          eDivGalerieNazev.style.backgroundColor = '#DDDDDD';
  }  
  </script>
  <style type="text/css">
  .boxGalery{
    text-align: center; 
    border: 1px solid #999999; 
    cursor: pointer; 
    font-weight: bold; 
    width: 140px; 
    height: 140px; 
  }
  .boxNahled{
    width: 140px; 
    height: 123px;
    _height: 125px;
    #height: 125px;
    overflow-y: hidden;
    padding: 2px 2px 2px 2px;
  }
  .boxNazev{
    clear: both;
    width: 140px; 
    height: 13px; 
    background-color: #DDDDDD; 
    white-space: nowrap; 
    overflow: hidden;
  }
  </style>
</head>
<body style="overflow: auto; overflow-x: hidden;">

<div style="clear: both;"></div>



<?php
If( $CONF["adminFotogalerie"]==1 ){
    $dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]gal_kat WHERE skryta='ne' ORDER BY nazev ASC");
    $i = 0;
    While($radek=mysql_fetch_assoc($dotaz)){
          //fotky
          $dotaz2 = Mysql_query("SELECT * FROM $CONF[sqlPrefix]galerie WHERE kategorie=$radek[id] ORDER BY vaha DESC");
          
          $i++;
          Echo '<div style="float: left; padding: 0px 6px 12px 0px;">
                  <div class="boxGalery" onClick="addGalery('.$radek["id"].');" onMouseOver="zvyrazniGalerii('.$i.');" onMouseOut="odvyrazniGalerii('.$i.');">
                    <div class="boxNahled">';
          While($radek2 = mysql_fetch_assoc($dotaz2)){
                Echo '<img style="float: left; margin-bottom: 2px;" src="../../../../../userfiles/galerie/'.$radek["slozka"].'/nahledy/'.$radek2["foto"].'" onLoad="if(this.offsetWidth>128)this.style.width = \'128px\'; if(this.offsetHeight>64)this.style.height = \'64px\';"> ';
          }
          Echo '    </div>
                    <div id="divGalerie'.$i.'Nazev" class="boxNazev">
                      '.$radek["nazev"].'
                    </div>
                  </div>
                </div>';
    }
    If( $i==0 ){
        Echo '<p style="text-align: center; font-weight: bold;">&nbsp;<br>Žádná fotogalerie nenalezena.</p>';
    }
}
Else{
    Echo '<p style="text-align: center; font-weight: bold;">&nbsp;<br>Modul fotogalerie zatím nemáte aktivní.</p>';
}
?>

<div style="clear: both;"></div>

</body>
</html>