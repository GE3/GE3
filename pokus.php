<?php
print_r($_SERVER);
?>



<?php
if(false){
Include 'fce/sql_connect.php';

$dotaz = Mysql_query("DROP FUNCTION IF EXISTS URLTEXT") or die("Chyba v prvním dotazu: ".mysql_error());
$dotaz = Mysql_query(
"CREATE FUNCTION URLTEXT(vstup VARCHAR(60)) RETURNS VARCHAR(60)
BEGIN
  DECLARE vystup VARCHAR(60);
  SET vystup = vstup;
  SET vystup = LOWER(vystup);

  /* Diakritika */
  SET vystup = REPLACE(vystup, 'á', 'a');
  SET vystup = REPLACE(vystup, 'č', 'c');
  SET vystup = REPLACE(vystup, 'ď', 'd');
  SET vystup = REPLACE(vystup, 'é', 'e');
  SET vystup = REPLACE(vystup, 'ě', 'e');
  SET vystup = REPLACE(vystup, 'í', 'i');
  SET vystup = REPLACE(vystup, 'ľ', 'l');
  SET vystup = REPLACE(vystup, 'ň', 'n');
  SET vystup = REPLACE(vystup, 'ó', 'o');
  SET vystup = REPLACE(vystup, 'ř', 'r');
  SET vystup = REPLACE(vystup, 'š', 's');
  SET vystup = REPLACE(vystup, 'ť', 't');
  SET vystup = REPLACE(vystup, 'ú', 'u');
  SET vystup = REPLACE(vystup, 'ů', 'u');
  SET vystup = REPLACE(vystup, 'ý', 'y');
  SET vystup = REPLACE(vystup, 'ž', 'z');
  SET vystup = REPLACE(vystup, 'Á', 'a');
  SET vystup = REPLACE(vystup, 'Č', 'c');
  SET vystup = REPLACE(vystup, 'Ď', 'd');
  SET vystup = REPLACE(vystup, 'É', 'e');
  SET vystup = REPLACE(vystup, 'Ě', 'e');
  SET vystup = REPLACE(vystup, 'Í', 'i');
  SET vystup = REPLACE(vystup, 'Ľ', 'l');
  SET vystup = REPLACE(vystup, 'Ň', 'n');
  SET vystup = REPLACE(vystup, 'Ó', 'o');
  SET vystup = REPLACE(vystup, 'Ř', 'r');
  SET vystup = REPLACE(vystup, 'Š', 's');
  SET vystup = REPLACE(vystup, 'Ť', 't');
  SET vystup = REPLACE(vystup, 'Ú', 'u');
  SET vystup = REPLACE(vystup, 'Ů', 'u');
  SET vystup = REPLACE(vystup, 'Ý', 'y');
  SET vystup = REPLACE(vystup, 'Ž', 'z');

  /* Znaky pro přeměnu na pomlčku */
  SET vystup = REPLACE(vystup, ' ', '-');
  SET vystup = REPLACE(vystup, '_', '-');
  SET vystup = REPLACE(vystup, ',', '-');
  SET vystup = REPLACE(vystup, '/', '-');
  SET vystup = REPLACE(vystup, '+', '-');

  /* Více pomlček za sebou */
  SET vystup = REPLACE(vystup, '----', '-');
  SET vystup = REPLACE(vystup, '---', '-');
  SET vystup = REPLACE(vystup, '--', '-');

  /* Znaky pro smazání */
  SET vystup = REPLACE(vystup, '(', '');
  SET vystup = REPLACE(vystup, ')', '');
  SET vystup = REPLACE(vystup, '.', '');
  SET vystup = REPLACE(vystup, '!', '');
  SET vystup = REPLACE(vystup, '\"', '');
  SET vystup = REPLACE(vystup, \"'\", '');

  /* Pomlčky na začátku a konce řetězce */
  SET vystup = CONCAT('&begin;', vystup, '&end;');
  SET vystup = REPLACE(vystup, '&begin;-', '');
  SET vystup = REPLACE(vystup, '-&end;', '');
  SET vystup = REPLACE(vystup, '&begin;', '');
  SET vystup = REPLACE(vystup, '&end;', '');

  RETURN vystup;
END") or die("Chyba v hlavním dotazu: <br>".mysql_error());
If( $dotaz ) Echo 'SQL Dotaz: Vytvoření funkce URLTEXT - OK <br>';
}



/*$dotaz = Mysql_query("SELECT URLTEXT(kategorie),URLTEXT(podkat1),URLTEXT(produkt),URLTEXT(varianta) FROM g3_zbozi WHERE URLTEXT(produkt)='kloboucek-khaki'");
If( $dotaz ){
    Echo "Dotaz byl úspěšný.";
    Echo "<table border=1>";
    $i = 0;
    While($radek=@mysql_fetch_array($dotaz)){
          If( $i==0 ){
              Echo "<tr>";
              Foreach($radek as $key=>$value){
                      If(!is_numeric($key)) Echo "<td>$key</td>";
              }
              Echo "</tr>";
          }

          Echo "<tr>";
          Foreach($radek as $key=>$value){
                  If(!is_numeric($key)) Echo "<td>$value</td>";
          }
          Echo "</tr>";
          $i++;
    }
    Echo "</table>";
}*/


?>
