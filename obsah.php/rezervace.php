<?php
If( $_GET["a"]=='rezervace' ){
    Include 'class.php/Rezervace.class.php';
    Include 'class.php/RezervNastaveni.class.php';
    $rNastaveni = new RezervNastaveni();
    $tmplRezerv = new GlassTemplate("templates/$CONF[vzhled]/rezervace.html");
    
    
    /************/
    /* FORMULÁŘ */
    /************/
    If( !$_POST["odeslat"] ){
        $tmplRezerv->newBlok("rezervace");
        /* -- Načtení pokojů -- */
        $dotaz = Db_query("SELECT * FROM $CONF[sqlPrefix]rezervPokoje ORDER BY id ASC");
        While($radek=mysql_fetch_assoc($dotaz)){
              $tmplRezerv->newBlok("rezervace.pokoj");
              $tmplRezerv->prirad("rezervace.pokoj.id", $radek["id"]);
              $tmplRezerv->prirad("rezervace.pokoj.nazev", $radek["nazev"]);
              $tmplRezerv->prirad("rezervace.pokoj.seoNazev", $radek["seoNazev"]);
              $tmplRezerv->prirad("rezervace.pokoj.typ", $radek["typ"]);
        }
    }
    
    
    
    /****************/
    /* REKAPITULACE */
    /****************/
    If( $_POST["odeslat"] ){
        $tmplRezerv->newBlok("rekapitulace");
        $cenaCelkem = 0;
    
        /////////////
        // Položky 
        /////////////
        
        /* -- Pokoje -- */
        //název pokoje
        $pokojNazev = zjisti_z("$CONF[sqlPrefix]rezervPokoje", "nazev", "id=$_POST[pokoj]");
        $tmplRezerv->newBlok("rekapitulace.polozka");
        $tmplRezerv->prirad("rekapitulace.polozka.nazev", $pokojNazev);
        //cena v korunách
        $timeStart = strtotime($_POST["datumStart"]);
        $timeEnd = strtotime($_POST["datumEnd"]);
        $cenaPokoj = 0;
        $pocetNoci = 0;
        While($timeStart<$timeEnd){
              $rok=date("Y",$timeStart); $mesic=date("m",$timeStart); $den=date("d",$timeStart);  
              $radek = mysql_fetch_assoc( Db_query("SELECT po.cena FROM $CONF[sqlPrefix]rezervPokoje p, $CONF[sqlPrefix]rezervPokojeObdobi po, $CONF[sqlPrefix]rezervObdobi o
                                                      WHERE p.id=po.idPokoje AND po.idObdobi=o.id 
                                                        AND o.datumStart<='0000-$mesic-$den' AND o.datumEnd>='0000-$mesic-$den'
                                                        AND p.id=$_POST[pokoj]
                                                      LIMIT 1") );
                                                      /*AND (MONTH(o.datumStart)<=$mesic OR (MONTH(o.datumStart)=$mesic AND DAY(o.datumStart)<=$den)) AND (MONTH(o.datumEnd)>=$mesic OR (MONTH(o.datumEnd)=$mesic AND DAY(o.datumEnd)>=$den))*/
              $cenaPokoj+= $radek["cena"];
              //Echo "$den.$mesic: +$radek[cena] = $cenaPokoj <br>";
              $pocetNoci++;  
              $timeStart+= 26*60*60;
              $timeStart = strtotime( date("Y",$timeStart)."-".date("n",$timeStart)."-".date("j",$timeStart) ); 
        }
        $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkem", $cenaPokoj);
        //cena v eurech
        $kurzEura = str_replace(",", ".", $rNastaveni->getHodnota("kurz-eura"));
        $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkemEuro", $cenaPokoj/$kurzEura);
        //cena celkem
        $cenaCelkem+= $cenaPokoj;
        
        /* -- Slevy -- */
        //allRooms
        $dotaz = Db_query("SELECT * FROM $CONF[sqlPrefix]rezervSlevy WHERE typ='allRooms' AND aktivni=1 ORDER BY id ASC");
        While($radek=mysql_fetch_assoc($dotaz)){
              If( $radek["slevaJednotky"]=='Kč' ){
                  $sleva = (-1)*$radek["sleva"]*$pocetNoci;
                  $tmplRezerv->newBlok("rekapitulace.polozka");
                  $tmplRezerv->prirad("rekapitulace.polozka.nazev", $radek["nazev"]);
                  $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkem", $sleva);
                  $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkemEuro", $sleva/$kurzEura);                  
              }
              Elseif($radek["slevaJednotky"]=='%'){
                  $sleva = (-1)*$radek["sleva"]/100*$cenaCelkem;
                  $tmplRezerv->newBlok("rekapitulace.polozka");
                  $tmplRezerv->prirad("rekapitulace.polozka.nazev", $radek["nazev"]);
                  $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkem", $sleva);
                  $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkemEuro", $sleva/$kurzEura);                  
              }
              $cenaCelkem+= $sleva;
        }
        //countClients
        $dotaz = Db_query("SELECT * FROM $CONF[sqlPrefix]rezervSlevy WHERE typ='countClients' AND aktivni=1 ORDER BY id ASC");
        While($radek=mysql_fetch_assoc($dotaz)){
              If( $radek["hodnota"]==$_POST["pocetHostu"] ){
                  If( $radek["slevaJednotky"]=='Kč' ){
                      $sleva = (-1)*$radek["sleva"];
                      $tmplRezerv->newBlok("rekapitulace.polozka");
                      $tmplRezerv->prirad("rekapitulace.polozka.nazev", $radek["nazev"]);
                      $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkem", $sleva);
                      $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkemEuro", $sleva/$kurzEura);                  
                  }
                  Elseif($radek["slevaJednotky"]=='%'){
                      $sleva = (-1)*$radek["sleva"]/100*$cenaCelkem;
                      $tmplRezerv->newBlok("rekapitulace.polozka");
                      $tmplRezerv->prirad("rekapitulace.polozka.nazev", $radek["nazev"]);
                      $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkem", $sleva);
                      $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkemEuro", $sleva/$kurzEura);                  
                  }
                  $cenaCelkem+= $sleva;
              }
        }
        //Xth_day
        $dotaz = Db_query("SELECT * FROM $CONF[sqlPrefix]rezervSlevy WHERE typ='Xth_day' AND aktivni=1 ORDER BY id ASC");
        While($radek=mysql_fetch_assoc($dotaz)){
              If( $radek["slevaJednotky"]=='Kč' ){
                  $sleva = (-1)*(ceil($pocetNoci/$radek["hodnota"])-1)*$radek["sleva"];
                  $tmplRezerv->newBlok("rekapitulace.polozka");
                  $tmplRezerv->prirad("rekapitulace.polozka.nazev", $radek["nazev"]);
                  $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkem", $sleva);
                  $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkemEuro", $sleva/$kurzEura);                  
              }
              Elseif($radek["slevaJednotky"]=='%'){
                  $timeStart = strtotime($_POST["datumStart"]);
                  $timeEnd = strtotime($_POST["datumEnd"]);
                  $i = 0;
                  $sleva = 0;
                  While($timeStart<$timeEnd){
                        $i++;
                        $rok=date("Y",$timeStart); $mesic=date("m",$timeStart); $den=date("d",$timeStart);  
                        $radek2 = mysql_fetch_assoc( Db_query("SELECT po.cena FROM $CONF[sqlPrefix]rezervPokoje p, $CONF[sqlPrefix]rezervPokojeObdobi po, $CONF[sqlPrefix]rezervObdobi o
                                                                  WHERE p.id=po.idPokoje AND po.idObdobi=o.id 
                                                                    AND o.datumStart<='0000-$mesic-$den' AND o.datumEnd>='0000-$mesic-$den'
                                                                    AND p.id=$_POST[pokoj]
                                                                  LIMIT 1") );
                                                               /*AND (MONTH(o.datumStart)<=$mesic OR (MONTH(o.datumStart)=$mesic AND DAY(o.datumStart)<=$den)) AND (MONTH(o.datumEnd)>=$mesic OR (MONTH(o.datumEnd)=$mesic AND DAY(o.datumEnd)>=$den))*/
                        If( $i%$radek["hodnota"]==0 ){
                            //Echo "...sleva» ($i) $den.$mesic: +".($radek2["cena"]*$radek["sleva"]/100)." <br>";
                            $sleva+= $radek2["cena"]*$radek["sleva"]/100;
                        }
                        $timeStart+= 26*60*60;
                        $timeStart = strtotime( date("Y",$timeStart)."-".date("n",$timeStart)."-".date("j",$timeStart) ); 
                  }
                  $sleva = (-1)*$sleva;
                  $tmplRezerv->newBlok("rekapitulace.polozka");
                  $tmplRezerv->prirad("rekapitulace.polozka.nazev", $radek["nazev"]);
                  $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkem", $sleva);
                  $tmplRezerv->prirad("rekapitulace.polozka.cenaCelkemEuro", $sleva/$kurzEura);                                  
              }
              $cenaCelkem+= $sleva;
        }
        
        /* -- Součty cen -- */
        $tmplRezerv->prirad("rekapitulace.cenaCelkemSoucet", $cenaCelkem);
        $tmplRezerv->prirad("rekapitulace.cenaCelkemSoucetEuro", $cenaCelkem/$kurzEura);
        
        
        ///////////
        // Údaje 
        ///////////
        
        /* -- Základní údaje -- */
        $tmplRezerv->newBlok("rekapitulace.udaj");        
        $tmplRezerv->prirad("rekapitulace.udaj.nazev", "Pokoj/Room");
        $tmplRezerv->prirad("rekapitulace.udaj.name", "pokoj");
        $tmplRezerv->prirad("rekapitulace.udaj.hodnota", zjisti_z("$CONF[sqlPrefix]rezervPokoje","nazev","id=$_POST[pokoj]"));
        $tmplRezerv->prirad("rekapitulace.udaj.hodnotaHidden", $_POST["pokoj"]);
        
        $tmplRezerv->newBlok("rekapitulace.udaj");
        $tmplRezerv->prirad("rekapitulace.udaj.nazev", "Termín příjezdu/Arrival Date");
        $tmplRezerv->prirad("rekapitulace.udaj.name", "datumStart");
        $tmplRezerv->prirad("rekapitulace.udaj.hodnota", $_POST["datumStart"]);
        $tmplRezerv->prirad("rekapitulace.udaj.hodnotaHidden", $_POST["datumStart"]);
        
        $tmplRezerv->newBlok("rekapitulace.udaj");
        $tmplRezerv->prirad("rekapitulace.udaj.nazev", "Termín odjezdu/Departure Date");
        $tmplRezerv->prirad("rekapitulace.udaj.name", "datumEnd");
        $tmplRezerv->prirad("rekapitulace.udaj.hodnota", $_POST["datumEnd"]);
        $tmplRezerv->prirad("rekapitulace.udaj.hodnotaHidden", $_POST["datumEnd"]);
        
        $tmplRezerv->newBlok("rekapitulace.udaj");
        $tmplRezerv->prirad("rekapitulace.udaj.nazev", "Počet hostů/Number of guests");
        $tmplRezerv->prirad("rekapitulace.udaj.name", "pocetHostu");
        $tmplRezerv->prirad("rekapitulace.udaj.hodnota", $_POST["pocetHostu"]);
        $tmplRezerv->prirad("rekapitulace.udaj.hodnotaHidden", $_POST["pocetHostu"]);
        
        /* -- Rozšiřující údaje -- */
        Foreach($_POST["udaje"] as $key=>$value){
                $tmplRezerv->newBlok("rekapitulace.udaj");
                $tmplRezerv->prirad("rekapitulace.udaj.nazev", str_replace('_',' ',$key));
                $tmplRezerv->prirad("rekapitulace.udaj.name", 'udaje['.$key.']');
                $tmplRezerv->prirad("rekapitulace.udaj.hodnota", $value);
                $tmplRezerv->prirad("rekapitulace.udaj.hodnotaHidden", $value);                
        }
        
        
        ////////////////////////
        // Odeslání rezervace 
        ////////////////////////
        If( $_POST["potvrzeno"] ){
            /* -- Zapsání do databáze -- */
            $rezervace = new Rezervace();
            $rezervace->setPokoj($_POST["pokoj"]);
            $rezervace->setDatumStart($_POST["datumStart"]);
            $rezervace->setDatumEnd($_POST["datumEnd"]);
            $rezervace->setPocetHostu($_POST["pocetHostu"]);
            $rezervace->setDetaily($tmplRezerv->getHtml());
            $rezervace->setCena($cenaCelkem);
            
            Foreach($_POST["udaje"] as $key=>$value){
                    $rezervace->setUdaj(str_replace('_',' ',$key), $value);
            }            
            
            If( $rezervace->vytvorVDb() ){ 
                //e-mail
                $mailBody = $tmplRezerv->getHtml();
                $mailBody = str_replace('&nbsp;', ' ', $mailBody);
                $mailBody = eregi_replace('<form[^>]*>', '', $mailBody);
                $mailBody = str_replace('</form>', '', $mailBody);
                easyMail("$CONF[mailer]", $rNastaveni->getHodnota('email-admin').','.$_POST["udaje"]["E-mail"], eregi_replace("^.*//([^/]+)/.*","\\1",$CONF["absDir"])." - Potvrzení rezervace", $mailBody);
                //hláška
                $tmplRezerv->prirad("rekapitulace.hlaska", "Údaje byly úspěšně odeslány [<a href=\"$CONF[absDir]?a=rezervace\">ok</a>] ");
            }
            Else $tmplRezerv->prirad("rekapitulace.hlaska", "Při odesílání nastala neznámá chyba. ");
        }
        
    }
    
    
    $tmpl->prirad("obsah", $tmplRezerv->getHtml());
}
?>