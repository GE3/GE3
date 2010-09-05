<?php
      If( $_GET["m"]=='rezervace' AND ($_GET["b"]=='pokoje-a-obdobi' OR !$_GET["b"])){
          if($_POST["submit"]){
            $cena=$_POST["cena"];
            Foreach($cena as $pokojId=>$cenaMezi){
                Foreach($cenaMezi as $obdobiId=>$cenaNew){
                     If( zjisti_z("$CONF[sqlPrefix]rezervPokojeObdobi","id","idPokoje='$pokojId' AND idObdobi='$obdobiId'") )
                         $update=mysql_query("UPDATE $CONF[sqlPrefix]rezervPokojeObdobi SET cena='$cenaNew' WHERE idPokoje='$pokojId' AND idObdobi='$obdobiId'");
                     Else
                         $update=mysql_query("INSERT INTO $CONF[sqlPrefix]rezervPokojeObdobi(idPokoje,idObdobi,cena) VALUES ('$pokojId', '$obdobiId', '$cenaNew')");
                }
            }


          }

          $tmplRezerv->newBlok("rezervace.pokoje_a_obdobi");

          $dotaz3=mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervObdobi");
          while($radek3=mysql_fetch_assoc($dotaz3)){
                $tmplRezerv->newBlok("rezervace.pokoje_a_obdobi.obdobi");
                $tmplRezerv->prirad("rezervace.pokoje_a_obdobi.obdobi.nazev", "$radek3[nazev]");
          }


          $dotaz=mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervPokoje");
          while($radek=mysql_fetch_assoc($dotaz)){
                  $tmplRezerv->newBlok("rezervace.pokoje_a_obdobi.pokoj");
                  $tmplRezerv->prirad("rezervace.pokoje_a_obdobi.pokoj.nazev", "$radek[nazev]");

                  $dotaz2=mysql_query("SELECT po.*, o.id as idObdobi 
                                        FROM $CONF[sqlPrefix]rezervObdobi o LEFT JOIN (SELECT * FROM $CONF[sqlPrefix]rezervPokojeObdobi WHERE idPokoje='$radek[id]') po ON o.id=po.idObdobi");
                  while($radek2=mysql_fetch_assoc($dotaz2)){
                        $tmplRezerv->newBlok("rezervace.pokoje_a_obdobi.pokoj.obdobi");
                        $tmplRezerv->prirad("rezervace.pokoje_a_obdobi.pokoj.obdobi.pokojId", "$radek[id]");
                        $tmplRezerv->prirad("rezervace.pokoje_a_obdobi.pokoj.obdobi.id", "$radek2[idObdobi]");
                        $tmplRezerv->prirad("rezervace.pokoje_a_obdobi.pokoj.obdobi.cena", "$radek2[cena]");
                  }
          }

          $dotaz4=mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervObdobi");
          while($radek4=mysql_fetch_assoc($dotaz4)){
                $tmplRezerv->newBlok("rezervace.pokoje_a_obdobi.obdobi_datum");
                $tmplRezerv->prirad("rezervace.pokoje_a_obdobi.obdobi_datum.nazev", "$radek4[nazev]");
                $radek4["datumStart"] = str_replace("0000", "1971", $radek4["datumStart"]);
                $radek4["datumEnd"] = str_replace("0000", "1971", $radek4["datumEnd"]);
                $tmplRezerv->prirad("rezervace.pokoje_a_obdobi.obdobi_datum.datumStart", strtotime($radek4["datumStart"]));
                $tmplRezerv->prirad("rezervace.pokoje_a_obdobi.obdobi_datum.datumEnd", strtotime($radek4["datumEnd"]));
          }
      }

      Echo $tmplRezerv->getHtml();

?>