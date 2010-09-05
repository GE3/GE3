<?php
If( $uzivatel->prihlasen() AND $uzivatel->getData("id") AND zjisti_z("$CONF[sqlPrefix]velkoobchody","sleva","id=".$uzivatel->getData("velkoobchodId")."") ){
    $sleva = zjisti_z("$CONF[sqlPrefix]velkoobchody","sleva","id=".$uzivatel->getData("velkoobchodId")."")/100;
    
    // Smazání slevy
    Foreach($_SESSION["kosikProdukty"] as $key=>$value){
            If($value["nazev"]=='Sleva:') unset($_SESSION["kosikProdukty"][$key]);
    } 
    
    // Hledání ceny, celkové součty
    $celkovaCena = 0;
    Foreach($_SESSION["kosikProdukty"] as $key=>$value){
            $celkovaCena+= $value["cenaSDph"]*$value["mnozstvi"];
    }
    
    // Přidání slevy
    $kosik->pridejProdukt("0", "1", "Sleva:", "", ((-1)*($celkovaCena*$sleva)), ((-1)*($celkovaCena*$sleva)/1.2), 20);
}
Else{
    Foreach($_SESSION["kosikProdukty"] as $key=>$value){
            If($value["nazev"]=='Sleva:') unset($_SESSION["kosikProdukty"][$key]);
    }
}



//(tohle je skript pro wh-shop, který už stejně nevedeme)
If( False AND $WHSHOP ){
    /* -- Je přihlášen a registrován -- */
    If( $uzivatel->prihlasen() AND $uzivatel->getData("id") AND $uzivatel->getData("heslo") ){
    
        // Smazání slevy
        Foreach($_SESSION["kosikProdukty"] as $key=>$value){
                If($value["nazev"]=='Sleva:') unset($_SESSION["kosikProdukty"][$key]);
        } 
        
        // Hledání ceny, celkové součty
        $celkovaCena = 0;
        Foreach($_SESSION["kosikProdukty"] as $key=>$value){
                $celkovaCena+= $value["cenaSDph"]*$value["mnozstvi"];
        }
        
        // Přidání slevy
        $kosik->pridejProdukt("0", "1", "Sleva:", "", ((-1)*($celkovaCena*0.05)), ((-1)*($celkovaCena*0.05)/1.2), 20);
    }
    /* -- Není registrován -- */
    Else{
        Foreach($_SESSION["kosikProdukty"] as $key=>$value){
                If($value["nazev"]=='Sleva:') unset($_SESSION["kosikProdukty"][$key]);
        }    
    }
}
?>

