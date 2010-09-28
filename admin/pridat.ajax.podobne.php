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



<form id="form_vyhledavani" method="post" action="">
  Vyhledávání: <input type="text" name="search" id="search" style="font-size: 8pt;"> 
  <input type="button" id="vyhledavani_button" name="ok" value="OK">
</form>
<div id="search_result">zadejte vyhledávaný text</div>


<script type="text/javascript">
$(function(){
  $('#vyhledavani_button').click(function(){
                           $.post('pridat.ajax.podobne.vyhledavani.php', 
                                  {search: $('#search').val(), input_id: '<?php echo $_POST["input_id"]; ?>', kategorie: '<?php echo $_POST["kategorie"]; ?>', podkat1: '<?php echo $_POST["podkat1"]; ?>', podkat2: '<?php echo $_POST["podkat2"]; ?>', produkt: '<?php echo $_POST["produkt"]; ?>'}, 
                                  function(data){
                                           $('#search_result').html(data);
                                  }
                                  );      
  });
});
</script>
