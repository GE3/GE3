<?php
If( $_GET["a"]=='produkty' AND $_GET["produkt"] ){
    /**
     * tento skript se aktivuje jen při požadavku detailu produktu
     */
    $tmplProdukt = new GlassTemplate("templates/$CONF[vzhled]/produkty.html");
    $tmplProdukt->newBlok("detailProduktu");

    //////////////
    // SQL Dotaz
    //////////////
    $idProduktu = preg_replace("|^([0-9]*)-.*$|si","$1",$_GET["produkt"]);
    $dotaz = Mysql_query("SELECT x.* FROM $CONF[sqlPrefix]zbozi x, $CONF[sqlPrefix]zbozi y WHERE x.produkt=y.produkt AND y.id=$idProduktu ORDER BY id ASC");
    $radek = mysql_fetch_array($dotaz);



    ////////////////////
    // Detail produktu
    ////////////////////

    /* -- Základní údaje -- */
    // Url
    If( $CONF["mod_rewrite"] ){
        $kategorie = $_GET["kategorie"]? ''.$_GET["kategorie"].'/': '';
        $podkat1 = $_GET["podkat1"]? ''.$_GET["podkat1"].'/': '';
        $podkat2 = $_GET["podkat2"]? ''.$_GET["podkat2"].'/': '';
        $podkat3 = $_GET["podkat3"]? ''.$_GET["podkat3"].'/': '';
        $produkt = "".$radek["id"]."-".urlText($radek["produkt"]).".html";
        $tmplProdukt->prirad("detailProduktu.url", $CONF["absDir"]."produkty/".$kategorie.$podkat1.$podkat2.$podkat3.$produkt);
    }
    Else{
        $kategorie = $_GET["kategorie"]? '&kategorie='.$_GET["kategorie"]:'';
        $podkat1 = $_GET["podkat1"]? '&podkat1='.$_GET["podkat1"]: '';
        $podkat2 = $_GET["podkat2"]? '&podkat2='.$_GET["podkat2"]: '';
        $podkat3 = $_GET["podkat3"]? '&podkat3='.$_GET["podkat3"]: '';
        $produkt = "&produkt=".$radek["id"]."-".urlText($radek["produkt"]);
        $tmplProdukt->prirad("detailProduktu.url", "?a=produkty".$kategorie.$podkat1.$podkat2.$podkat3.$produkt);
    }
    // ID
    $tmplProdukt->prirad("detailProduktu.id", $radek["id"]);  //pozor, produkt může mít více variant = více id
    // Název
    $tmplProdukt->prirad("detailProduktu.nazev", $radek["produkt"]);
    // Číslo
    $tmplProdukt->prirad("detailProduktu.cislo", $radek["cislo"]);
    // Kategorie
    $tmplProdukt->prirad("detailProduktu.kategorie", $radek["kategorie"]);        
    // Výrobce
    $tmplProdukt->prirad("detailProduktu.vyrobce", $radek["vyrobce"]);
    // Popis
    $tmplProdukt->prirad("detailProduktu.popis", $radek["popis"]);
    // Dostupnost
    $tmplProdukt->prirad("detailProduktu.dostupnost", $radek["dostupnost"]);
    // Ceny
    //dopočítání nezadaných cen
    $cenaSDph = $radek["cenaSDph"]? $radek["cenaSDph"]: round($radek["cenaBezDph"]*(1+$radek["dph"]/100),2);
    $cenaBezDph = $radek["cenaBezDph"]? $radek["cenaBezDph"]: round($radek["cenaSDph"]/(1+$radek["dph"]/100),2);
    @$dph = $radek["dph"]? $radek["dph"]: round($radek["cenaSDph"]/$radek["cenaBezDph"]*100-100);
    //zobrazení cen
    $tmplProdukt->prirad("detailProduktu.cenaSDph", $cenaSDph);
    $tmplProdukt->prirad("detailProduktu.cenaBezDph", $cenaBezDph);
    $tmplProdukt->prirad("detailProduktu.dph", $dph);
    if( ($radek["puvodni_cena_s_dph"] or $radek["puvodni_cena_bez_dph"]) and zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_puvodni_cena_active","id=1") ){
        $puvodni_cena_s_dph = $radek["puvodni_cena_s_dph"]? $radek["puvodni_cena_s_dph"]: ($radek["puvodni_cena_bez_dph"]*(1+$radek["dph"]/100));
        $puvodni_cena_bez_dph = $radek["puvodni_cena_bez_dph"]? $radek["puvodni_cena_bez_dph"]: ($radek["puvodni_cena_s_dph"]/(1+$radek["dph"]/100));
        $tmplProdukt->prirad("detailProduktu.puvodni_cena.hodnota_s_dph", $puvodni_cena_s_dph);
        $tmplProdukt->prirad("detailProduktu.puvodni_cena.hodnota_bez_dph", $puvodni_cena_bez_dph);
        $usetrite_kc = $radek["cenaSDph"]? $puvodni_cena_s_dph-$cenaSDph: $puvodni_cena_bez_dph-$cenaSDph;
        $usetrite_procent = $radek["cenaSDph"]? ($puvodni_cena_s_dph-$cenaSDph)/$puvodni_cena_s_dph*100: ($puvodni_cena_bez_dph-$cenaBezDph)/$puvodni_cena_bez_dph*100;
        $usetrite_procent = round($usetrite_procent);
        $tmplProdukt->prirad("detailProduktu.puvodni_cena.usetrite", zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_usetrite_jednotky","id=1")=='Kč'? $usetrite_kc: $usetrite_procent);
        $tmplProdukt->prirad("detailProduktu.puvodni_cena.jednotky", zjisti_z("$CONF[sqlPrefix]nastaveni","zbozi_usetrite_jednotky","id=1"));
    }       


    /* -- Varianty -- */
    $dotaz2 = Mysql_query("SELECT x.* FROM $CONF[sqlPrefix]zbozi x, $CONF[sqlPrefix]zbozi y WHERE x.produkt=y.produkt AND y.id=$idProduktu ORDER BY id ASC");
    $pocetVariant = 0;
    While($radek2=mysql_fetch_array($dotaz2)){
          //dopočítání nezadaných cen
          $cenaSDph = $radek2["cenaSDph"]? $radek2["cenaSDph"]: round($radek2["cenaBezDph"]*(1+$radek2["dph"]/100),2);
          $cenaBezDph = $radek2["cenaBezDph"]? $radek2["cenaBezDph"]: round($radek2["cenaSDph"]/(1+$radek2["dph"]/100),2);
          @$dph = $radek2["dph"]? $radek2["dph"]: round($radek2["cenaSDph"]/$radek2["cenaBezDph"]*100-100);

          //zobrazení
          $tmplProdukt->newBlok("detailProduktu.varianta");
          $tmplProdukt->prirad("detailProduktu.varianta.id", $radek2["id"]);
          $tmplProdukt->prirad("detailProduktu.varianta.nazev", $radek2["varianta"]);
          $tmplProdukt->prirad("detailProduktu.varianta.cenaSDph", $cenaSDph);
          $tmplProdukt->prirad("detailProduktu.varianta.cenaBezDph", $cenaBezDph);
          $tmplProdukt->prirad("detailProduktu.varianta.dph", $dph);

          //počet variant
          If($radek2["varianta"]) $pocetVariant++;
    }
    $tmplProdukt->prirad("detailProduktu.pocetVariant", $pocetVariant);


    /* -- Fotogalerie -- */
    // (názvy obrázků jsou uloženy ve formátu 'nazev1.jpg,nazev2.jpg,...')
    //oprava při špatném uložení názvů
    $obrazky = ereg_replace("^;","",$radek["obrazky"]);
    $obrazky = str_replace(array(';;;',';;'),";",$obrazky);
    $obrazky = ereg_replace(";$","",$obrazky);
    //rozdělení obrázků do pole
    $obrazky = explode(";",$obrazky);
    //vložení všech obrázků
    $i=1;
    Foreach($obrazky as $key=>$value){
            If( $value ){
                $tmplProdukt->newBlok("detailProduktu.fotogalerie.obrOdkaz");
                $tmplProdukt->prirad("detailProduktu.fotogalerie.obrOdkaz.i", $i);
                $tmplProdukt->prirad("detailProduktu.fotogalerie.obrOdkaz.url", "$CONF[absDir]zbozi/obrazky/$value");
                $tmplProdukt->prirad("detailProduktu.fotogalerie.obrOdkaz.alt", $value);
                $i++;
            }
    }
    //nastavení aktivního obrázku
    If( $obrazky[0] ){
        $tmplProdukt->newBlok("detailProduktu.fotogalerie.obrActive");
        $tmplProdukt->prirad("detailProduktu.fotogalerie.obrActive.url", "$CONF[absDir]zbozi/obrazky/".$obrazky[0]);
        $tmplProdukt->prirad("detailProduktu.fotogalerie.obrActive.alt", $obrazky[0]);
    }
    
    
    /* -- Přílohy -- */
    // (názvy příloh jsou uloženy ve formátu 'document1.doc;katalog.pdf;...')
    //oprava při špatném uložení názvů
    $prilohy = ereg_replace("^,", "", $radek["prilohy"]);
    $prilohy = str_replace(array(';;;',';;'), ";", $prilohy);
    $prilohy = ereg_replace("^;", "", $prilohy);
    $prilohy = ereg_replace(";$", "", $prilohy);
    //rozdělení příloh do pole
    $prilohy = explode(";", $prilohy);
    //vložení všech příloh
    $i=1;
    Foreach($prilohy as $key=>$value){
            If( $value ){
                $tmplProdukt->newBlok("detailProduktu.priloha");
                $tmplProdukt->prirad("detailProduktu.priloha.i", $i);
                $tmplProdukt->prirad("detailProduktu.priloha.url", "$CONF[absDir]zbozi/prilohy/$value");
                $tmplProdukt->prirad("detailProduktu.priloha.nazev", $value);
                $i++;
            }
    }
    $tmplProdukt->prirad("detailProduktu.pocetPriloh", ($i-1));    



    ///////////////////////////////
    // Odeslání dotazu na produkt
    ///////////////////////////////
    If( ($_POST["mailKomu"] OR $_POST["mailPredmet"]) AND !$_POST["spam"] ){
        $autoMail = new AutoMail();

        If( $autoMail->posli() ){
            $tmplProdukt->newBlok("detailProduktu.zpravaOk");
            $tmplProdukt->prirad("detailProduktu.zpravaOk.text", "Dotaz byl úspěšně odeslán.");
        }
        Else{
            $tmplProdukt->newBlok("detailProduktu.zpravaError");
            $tmplProdukt->prirad("detailProduktu.zpravaError.text", "Při odesílání došlo k chybě.<br>".$autoMail->getError());
        }
    }
    Elseif($_POST["mailKomu"] OR $_POST["mailPredmet"]) print_r($_POST);
    
    $tmpl->prirad("obsah", $tmplProdukt->getHtml());



    /////////////
    // Navigace
    /////////////
    If( $CONF["mod_rewrite"] ){
        $navigace = '<a href="'.$CONF["absDir"].'">Úvodní strana</a>';
        $navigace.= $_GET["kategorie"]? ' » <a href="'.$CONF["absDir"].'produkty/'.$_GET["kategorie"].'/">'.$radek["kategorie"].'</a>': '';
        $navigace.= $_GET["podkat1"]? ' » <a href="'.$CONF["absDir"].'produkty/'.$_GET["kategorie"].'/'.$_GET["podkat1"].'/">'.$radek["podkat1"].'</a>': '';
        $navigace.= $_GET["podkat2"]? ' » <a href="'.$CONF["absDir"].'produkty/'.$_GET["kategorie"].'/'.$_GET["podkat1"].'/'.$_GET["podkat2"].'/">'.$radek["podkat2"].'</a>': '';
        $navigace.= $_GET["podkat3"]? ' » <a href="'.$CONF["absDir"].'produkty/'.$_GET["kategorie"].'/'.$_GET["podkat1"].'/'.$_GET["podkat2"].'/'.$_GET["podkat3"].'/">'.$radek["podkat3"].'</a>': '';
        $navigace.= ' » '.$radek["produkt"];
        $tmpl->prirad("navigace", $navigace);
    }
    Else{
        $navigace = '<a href="index.php">Úvodní strana</a>';
        $navigace.= $_GET["kategorie"]? ' » <a href="?a=produkty&kategorie='.$_GET["kategorie"].'">'.$radek["kategorie"].'</a>': '';
        $navigace.= $_GET["podkat1"]? ' » <a href="?a=produkty&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'">'.$radek["podkat1"].'</a>': '';
        $navigace.= $_GET["podkat2"]? ' » <a href="?a=produkty&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'&podkat2='.$_GET["podkat2"].'">'.$radek["podkat2"].'</a>': '';
        $navigace.= $_GET["podkat3"]? ' » <a href="?a=produkty&kategorie='.$_GET["kategorie"].'&podkat1='.$_GET["podkat1"].'&podkat2='.$_GET["podkat2"].'&podkat3='.$_GET["podkat3"].'">'.$radek["podkat3"].'</a>': '';
        $navigace.= ' » '.$radek["produkt"];
        $tmpl->prirad("navigace", $navigace);
    }



    //////////////
    // TitleInfo
    //////////////
    $titleInfo = $radek["kategorie"]." » ".$radek["produkt"];
    $tmpl->prirad("titleInfo", $titleInfo);


}
?>