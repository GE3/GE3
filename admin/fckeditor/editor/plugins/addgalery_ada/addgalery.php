<?php
Include '../../../../../fce/sql_connect.php';
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
<body scroll="no" style="overflow-x: hidden; overflow: auto;">

<div style="clear: both;"></div>



<?php
$dotaz = Mysql_query("SELECT * FROM $GLOBALS[prefix]gal_kat WHERE skryta='ne' ORDER BY nazev ASC");
$i = 0;
While($radek=mysql_fetch_assoc($dotaz)){
      //fotky
      $dotaz2 = Mysql_query("SELECT * FROM $GLOBALS[prefix]galerie WHERE kategorie=$radek[id] ORDER BY vaha DESC");
      
      $i++;
      Echo '<div style="float: left; padding: 0px 6px 12px 0px;">
              <div class="boxGalery" onClick="addGalery('.$radek["id"].');" onMouseOver="zvyrazniGalerii('.$i.');" onMouseOut="odvyrazniGalerii('.$i.');">
                <div class="boxNahled">';
      While($radek2 = mysql_fetch_assoc($dotaz2)){
            Echo '<img style="float: left; margin-bottom: 2px;" src="/galerie/'.$radek["slozka"].'/nahledy/'.$radek2["foto"].'" onLoad="if(this.offsetWidth>128)this.style.width = \'128px\'; if(this.offsetHeight>64)this.style.height = \'64px\';"> ';
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
?>

<div style="clear: both;"></div>

</body>
</html>