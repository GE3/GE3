<?php
      If( $_GET["m"]=='rezervace' AND $_GET["b"]=='slevy' ){
          $tmplRezerv->newBlok("rezervace.slevy");

          if($_POST["submit"]){
            Foreach($_POST["sleva"] as $id=>$value){
              $update=mysql_query("UPDATE $CONF[sqlPrefix]rezervSlevy SET sleva='$value' WHERE id='$id'");
            }
          }

          $dotaz=mysql_query("SELECT * FROM $CONF[sqlPrefix]rezervSlevy");
          while($radek=mysql_fetch_assoc($dotaz)){
            if($radek["aktivni"]==1){
                $tmplRezerv->newBlok("rezervace.slevy.sleva");
                $tmplRezerv->prirad("rezervace.slevy.sleva.nazev", "$radek[nazev]");
                $tmplRezerv->prirad("rezervace.slevy.sleva.typ", "$radek[typ]");
                $tmplRezerv->prirad("rezervace.slevy.sleva.hodnota", "$radek[hodnota]");
                $tmplRezerv->prirad("rezervace.slevy.sleva.slevaId", "$radek[id]");
                $tmplRezerv->prirad("rezervace.slevy.sleva.sleva", "$radek[sleva]");
                $tmplRezerv->prirad("rezervace.slevy.sleva.slevaJednotky", "$radek[slevaJednotky]");
            }
          }
      }

      Echo $tmplRezerv->getHtml();

?>