<?php
      If( $_GET["m"]=='rezervace' AND $_GET["b"]=='rezervace' ){
          $tmplRezerv->newBlok("rezervace.rezervace_blok");

          if($_GET["akce"]=='potvrdit'){
            $update=mysql_query("UPDATE $CONF[sqlPrefix]rezervRezervace SET potvrzeno='1' WHERE id='$_GET[rezervace]'");
          }
          if($_GET["akce"]=='zamitnout'){
            $update=mysql_query("UPDATE $CONF[sqlPrefix]rezervRezervace SET potvrzeno='-1' WHERE id='$_GET[rezervace]'");
          }


          $dotaz=mysql_query("SELECT $CONF[sqlPrefix]rezervRezervace.*, $CONF[sqlPrefix]rezervPokoje.nazev FROM $CONF[sqlPrefix]rezervRezervace LEFT JOIN $CONF[sqlPrefix]rezervPokoje ON $CONF[sqlPrefix]rezervRezervace.pokojId=$CONF[sqlPrefix]rezervPokoje.id WHERE datumEnd > NOW()");
          while($radek=mysql_fetch_assoc($dotaz)){
              $i=0;
              $rezervace[$i] = new Rezervace($radek["id"]);
              $tmplRezerv->newBlok("rezervace.rezervace_blok.rezervace_radek");
              $tmplRezerv->prirad("rezervace.rezervace_blok.rezervace_radek.datum", strtotime($rezervace[$i]->getDatum()));
              $tmplRezerv->prirad("rezervace.rezervace_blok.rezervace_radek.pokojNazev", "$radek[nazev]");
              $tmplRezerv->prirad("rezervace.rezervace_blok.rezervace_radek.datumOd", strtotime($rezervace[$i]->getDatumStart()));
              $tmplRezerv->prirad("rezervace.rezervace_blok.rezervace_radek.datumDo", strtotime($rezervace[$i]->getDatumEnd()));
              $tmplRezerv->prirad("rezervace.rezervace_blok.rezervace_radek.cena", $rezervace[$i]->getCena());
              $tmplRezerv->prirad("rezervace.rezervace_blok.rezervace_radek.potvrzeno", $rezervace[$i]->getPotvrzeno());
              $tmplRezerv->prirad("rezervace.rezervace_blok.rezervace_radek.id", "$radek[id]");


              $dotaz2=mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervRezervaceUdaje WHERE idRezervace='".$rezervace[$i]->getId()."'");
              while($radek2=mysql_fetch_assoc($dotaz2)){
                  $tmplRezerv->newBlok("rezervace.rezervace_blok.rezervace_radek.zakaznikUdaj");
                  $tmplRezerv->prirad("rezervace.rezervace_blok.rezervace_radek.zakaznikUdaj.hodnota", $rezervace[$i]->getUdaj($radek2["nazev"]));
              }
              $i++;
          }
      }


      Echo $tmplRezerv->getHtml();

?>