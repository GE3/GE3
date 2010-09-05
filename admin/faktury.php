<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/faktury.png"></td>
                  <td width="616" colspan="2">
                  <p align="left"><span style="font-weight: 700">
                  <font face="Arial" size="2">Vystavené faktury</font></span><font face="Arial" style="font-size: 8pt"><br>
                  Zde naleznete všechny vystavené faktury z objednávek Vašeho portálu.</font></td>
                </tr>
              </table>
              ';
Include '../class.php/Uzivatel.class.php';
Include '../class.php/Objednavka.class.php';
Include '../class.php/Faktura.class.php';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php
/*******************/
/* Smazání faktury */
/*******************/
If( $_GET["smaz_fakturu"] ){
      unset($faktura);
      @$faktura = new Faktura($_GET["smaz_fakturu"]);

      If( @$faktura->smazZDatabaze() )
          Echo '<div style="color: #008000; border: 1px solid #008000; padding: 3px 3px 3px 6px;">Úspěšně smazáno.</div><p>';
      Else
          Echo '<div style="color: #008000; border: 1px solid #008000; padding: 3px 3px 3px 6px;">Při mazání nastala chyba: <br>'.$faktura->getErrors().'</div><p>';
}

/************************/
/* Úprava stavu faktury */
/************************/
If( $_POST["idFaktury"] AND $_POST["stav"] ){
    If( Mysql_query("UPDATE $CONF[sqlPrefix]faktury SET stav='".$_POST["stav"]."' WHERE id=".$_POST["idFaktury"]." ") ){
        //Echo '<div style="color: #008000; border: 1px solid #008000; padding: 3px 3px 3px 6px;">Stav úspěšně změněn.</div><p>';
    }
}
?>



<p>
<?php
/*******************/
/* Odkazy pro roky */
/*******************/

//Načtení z db
$dotaz = Mysql_query("SELECT date FROM $CONF[sqlPrefix]faktury WHERE uzivatelEmail!='' ORDER BY date DESC");

$roky = array();
While($radek=mysql_fetch_array($dotaz)){
      $roky[] = date("Y",$radek["date"]);
}
$roky = array_unique($roky);

//Vypsání
Foreach($roky as $key=>$value){
        $rok = $_GET["rok"]?$_GET["rok"]:$roky[0];

        If( $rok==$value )
            $odkazyRoky.= '<a href="?m=faktury&rok='.$value.'" style="font-weight: bold; text-decoration: none;">'.$value.'</a> &nbsp;&nbsp; | &nbsp;&nbsp;';
        Else
            $odkazyRoky.= '<a href="?m=faktury&rok='.$value.'">'.$value.'</a> &nbsp;&nbsp; | &nbsp;&nbsp;';
}
$odkazyRoky = str_replace("| &nbsp;&nbsp; &end;","",$odkazyRoky." &end;");
$odkazyRoky = str_replace("&end;","",$odkazyRoky);
Echo "<center>".$odkazyRoky."</center>";



/*********************/
/* Odkazy pro měsíce */
/*********************/
$rok = $_GET["rok"]?$_GET["rok"]:$roky[0];
$dateMin = strtotime($rok."-01-01 00:00:00");
$dateMax = strtotime($rok."-12-31 23:59:59");

$nazvyMesicu = array();
$nazvyMesicu[1] = "Leden";
$nazvyMesicu[2] = "Únor";
$nazvyMesicu[3] = "Březen";
$nazvyMesicu[4] = "Duben";
$nazvyMesicu[5] = "Květen";
$nazvyMesicu[6] = "Červen";
$nazvyMesicu[7] = "Červenec";
$nazvyMesicu[8] = "Srpen";
$nazvyMesicu[9] = "Září";
$nazvyMesicu[10] = "Říjen";
$nazvyMesicu[11] = "Listopad";
$nazvyMesicu[12] = "Prosinec";

//Načtení z db
$dotaz = Mysql_query("SELECT date FROM $CONF[sqlPrefix]faktury WHERE (date>'$dateMin' AND date<'$dateMax' AND uzivatelEmail!='') ORDER BY date DESC");

$mesice = array();
While($radek=mysql_fetch_array($dotaz)){
      $mesice[] = date("n",$radek["date"]);
}
$mesice = array_unique($mesice);

//Vypsání
Foreach($mesice as $key=>$value){
        $mesic = $_GET["mesic"]?$_GET["mesic"]:$mesice[0];

        If( $mesic==$value )
            $odkazyMesice.= '<a href="?m=faktury&rok='.$rok.'&mesic='.$value.'" style="font-weight: bold; text-decoration: none;">'.$nazvyMesicu[$value].'</a> &nbsp;&nbsp; | &nbsp;&nbsp;';  //strtotime($value."-01-01 00:00:00")
        Else
            $odkazyMesice.= '<a href="?m=faktury&rok='.$rok.'&mesic='.$value.'">'.$nazvyMesicu[$value].'</a> &nbsp;&nbsp; | &nbsp;&nbsp;';  //strtotime($value."-01-01 00:00:00")
}
$odkazyMesice = str_replace("| &nbsp;&nbsp; &end;","",$odkazyMesice." &end;");
$odkazyMesice = str_replace("&end;","",$odkazyMesice);
Echo "<center>".$odkazyMesice."</center>";



/********************/
/* Zobrazení faktur */
/********************/
$rok = $_GET["rok"]?$_GET["rok"]:$roky[0];
$mesic = $_GET["mesic"]?$_GET["mesic"]:$mesice[0];

$pocetDnu = @cal_days_in_month(CAL_GREGORIAN, $mesic, $rok);
$dateMin = strtotime("$rok-$mesic-01 00:00:00");
$dateMax = strtotime("$rok-$mesic-$pocetDnu 23:59:59");

$dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]faktury WHERE (date>'$dateMin' AND date<'$dateMax' AND uzivatelJmeno!='') ORDER BY date DESC");
While($radek=mysql_fetch_array($dotaz)){
      unset($faktura);
      $faktura = new Faktura($radek["id"]);

      $tmplPom = new GlassTemplate("../templates/$CONF[vzhled]/faktura.html", "../templates/default/faktura.html");
      Echo $faktura->priradDoTmpl($tmplPom, "fakturaAdminMini", "../templates/$CONF[vzhled]/faktura.html");

      Echo $faktura->getErrors();
}
?>



<?php Include 'grafika_kon.inc'; ?>
