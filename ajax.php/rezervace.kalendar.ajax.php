<?php
/* -- Include functions -- */
Include '../fce/fce.inc';

/* -- Include configu -- */
Include "../config.php/default.conf.php";
Include "../class.php/Kalendar.class.php";
Include "../class.php/GlassTemplate.class.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

/*----------------------------------------------------------------------------*/

/* -- Příprava proměnných -- */
$rok = $_POST["rok"]? $_POST["rok"]: date("Y");
$mesic = $_POST["mesic"]? $_POST["mesic"]: date("n");
$pokoj = $_POST["pokoj"];
$minYear = $_POST["minDate"]? date("Y",strtotime($_POST["minDate"])): date("Y");
$minMonth = $_POST["minDate"]? date("n",strtotime($_POST["minDate"])): date("n");
$minDay = $_POST["minDate"]? date("j",strtotime($_POST["minDate"])): date("j");

/* -- Kalendář -- */
$kalendar = new Kalendar($rok, $mesic);
// Volné a Zablokované dny 
For($i=1; $i<=cal_days_in_month(CAL_GREGORIAN,$mesic,$rok); $i++){
    If( ($i>=$minDay AND $mesic==$minMonth AND $rok==$minYear) OR ($mesic>$minMonth AND $rok==$minYear) OR ($rok>$minYear) ) 
        $kalendar->setDenTyp($i, "volno");
}
// Obsazené dny 
//$dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervRezervace WHERE pokojId=$pokoj AND (MONTH(datumStart)=$mesic OR MONTH(datumEnd)=$mesic) AND (YEAR(datumStart)=$rok OR YEAR(datumEnd)=$rok) AND potvrzeno=1 ORDER BY datumStart ASC");
$dotaz = Mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervRezervace WHERE pokojId=$pokoj AND (datumStart<='$rok-$mesic-31' AND datumEnd>=$rok-$mesic-01) AND potvrzeno=1 ORDER BY datumStart ASC");
While($radek=mysql_fetch_assoc($dotaz)){
      $timeStart = strtotime($radek["datumStart"]);
      $timeEnd = strtotime($radek["datumEnd"]);
      
      /*If($_POST["typ"]=='prijezd') $timeEnd-= 24*60*60;
      If($_POST["typ"]=='odjezd') $timeStart+= 24*60*60;*/
      
      While($timeStart<=$timeEnd){
            If( date("n",$timeStart)==$mesic AND date("Y",$timeStart)==$rok ) $kalendar->setDenTyp(date("j",$timeStart), "obsazeno");
            $timeStart+= 26*60*60;   //raději přidávám více hodin než 24 kvůli přestupným dnům, o příkaz níže se to srovná 
            $timeStart = strtotime( date("Y",$timeStart)."-".date("n",$timeStart)."-".date("j",$timeStart) );            
      }
}
// Zablokované dny 
For($i=1; $i<=cal_days_in_month(CAL_GREGORIAN,$mesic,$rok); $i++){
    // Zablokované dny před 
    If( ($i>=$minDay AND $mesic==$minMonth AND $rok==$minYear) OR ($mesic>$minMonth AND $rok==$minYear) OR ($rok>$minYear) ) 
        Null;
    Else $kalendar->setDenTyp($i, "zablokovano");
    // Zablokované dny za 
    If( $_POST["typ"]=='odjezd' AND $minYear AND $minMonth AND $minDay ){
        $radek = mysql_fetch_assoc( Mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervRezervace WHERE datumStart>'$minYear-$minMonth-$minDay' AND pokojId=$pokoj AND potvrzeno=1 ORDER BY datumStart ASC LIMIT 1") );
        $maxTime = strtotime($radek["datumStart"]);
        If( $maxTime AND $radek["id"] ){
            If( ($i>date("j",$maxTime) AND $mesic==date("n",$maxTime) AND $rok==date("Y",$maxTime)) OR ($mesic>date("n",$maxTime) AND $rok==date("Y",$maxTime)) OR ($rok>date("Y",$maxTime)) ){
                $kalendar->setDenTyp($i, "zablokovano");
            }
        }
    }
}

Echo $kalendar->getHtml("../templates/$CONF[vzhled]/rezervace.html", "ajaxKalendar");

?>