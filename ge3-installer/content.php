<h2>GE3 Installer - Základní obsah (neotestováno)</h2>
<?php
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

function lipsum($pocet){
        $lipsums = array("Sed rutrum malesuada arcu, condimentum ultrices augue fringilla eget. ",
                         "Pellentesque ultrices, metus in elementum convallis, neque diam laoreet justo, vel tristique diam sapien at dui. ", 
                         "Etiam accumsan diam dolor. ",
                         "Vivamus eu sem sed quam cursus pretium vel tincidunt velit. ",
                         "Nunc auctor tellus eu tellus cursus facilisis. ", 
                         "Aenean ultrices suscipit orci eget fermentum. ", 
                         "Pellentesque auctor tristique enim, nec posuere est porttitor vel. ", 
                         "Nulla faucibus lacus eu dolor feugiat sodales. ", 
                         "Praesent aliquet nisi faucibus dolor cursus iaculis. ", 
                         "Donec non elit quis turpis mattis lobortis. ", 
                         "Phasellus vestibulum purus non metus posuere tincidunt. ", 
                         "Donec urna dolor, sollicitudin non ultrices a, pulvinar non lorem. ", 
                         "Mauris ultrices condimentum aliquam. ", 
                         "Aenean gravida luctus ultrices. ", 
                         "Aenean ultrices pretium velit, vitae tristique urna semper vel. ", 
                         "Nam at felis sit amet lacus pretium tincidunt. ", 
                         "Integer in ante elit, ut rhoncus sapien. ",  
                         "Suspendisse tristique feugiat lorem quis facilisis. ",
                         "Quisque massa libero, aliquam sit amet euismod in, posuere nec diam. ",
                         "Sed sagittis semper est, quis blandit orci iaculis eget. ",
                         "Phasellus purus orci, malesuada quis viverra in, placerat eu turpis. ",
                         );
        $lipsum = '';
        for($i=0; $i<count($lipsums); $i++){
            $lipsum.= $lipsums[rand(0, count($lipsums)-1)];
        }
        
        return $lipsum;                
}
?>


<h3>Vygenerovat články</h3>
<?php
if( $_POST["b"]=='articles' ){
    foreach($_POST["nazev"] as $key=>$value){
            mysql_query("INSERT INTO $CONF[sqlPrefix]clanky(id,time,nazev,perex,obsah,typ) 
                         VALUES ('".$_POST["id"][$key]."', '".time()."', '".$_POST["nazev"][$key]."', '<p>".lipsum(2)."</p>', '<p>".lipsum(15)."</p>', '".$_POST["typ"][$key]."')");
    }
    echo "<p>Články úspěšně vytvořeny.</p>";
}

function getClanekTr($id, $typ){
        $default_names = array(1 => "Homepage", 
                               9 => "Obchodní podmínky",
                               );
        $clanek = mysql_fetch_assoc( mysql_query("SELECT * FROM ".$GLOBALS["config"]["sqlPrefix"]."clanky WHERE id='$id'") );
        echo mysql_error();
        if( $clanek["nazev"] ){
            return '
                    <tr>
                      <td><input type="text" name="id['.$id.']" size="1" value="'.$id.'" disabled></td>
                      <td>
                        <input type="text" name="nazev_ignore['.$id.']" value="'.$clanek["nazev"].'" disabled>
                        <input type="hidden" name="typ['.$id.']" value="vodorovne">
                      </td>
                    </tr>
                    ';
        }
        else{
            return '
                    <tr>
                      <td><input type="text" name="id['.$id.']" size="1" value="'.$id.'"></td>
                      <td>
                        <input type="text" name="nazev['.$id.']" value="'.$default_names[$id].'">
                        <input type="hidden" name="typ['.$id.']" value="vodorovne">
                      </td>
                    </tr>
                    ';        
        }
}
?>
<form action="" method="post">
  <table style="float: left; margin-right: 24px;">
    <tr>
      <th>id</th>
      <th>Název článku</th>
    </tr>
    <?php
    for($i=1; $i<10; $i++){
        echo getClanekTr($i, 'vodorovne');
    }
    ?>  
  </table>

  <table>
    <tr>
      <th rowspan="10" style="border-left: 1px solid #7F9DB9; padding-left: 24px;">&nbsp;</th>
      <th>id</th>
      <th>Název novinky</th>
    </tr>
    <?php
    for($i=11; $i<20; $i++){
        echo getClanekTr($i, 'novinka');
    }
    ?>    
  </table>
  <input type="submit" name="odeslat" value="Odeslat">    
</form>


<h3>Ukázkové produkty</h3>
<?php
$pocet = mysql_fetch_assoc( mysql_query("SELECT COUNT(*) FROM $CONF[sqlPrefix]zbozi") );
$pocet = $pocet["COUNT(*)"];
if($pocet) echo '<p>Tento web již produkty obsahuje (celkem '.$pocet.')</p>
                 <form action="" method="post">
                   <input type="hidden" name="b" value="products">
                   <input type="submit" name="odeslat" value="Vložit ještě jednou">
                 </form>
                 ';
else echo '<p>Žádné produkty zatím nebyly vloženy.</p>
           <form action="" method="post">
             <input type="hidden" name="b" value="products">
             <input type="submit" name="odeslat" value="Vložit ukázkové produkty">
           </form>
           ';
?>