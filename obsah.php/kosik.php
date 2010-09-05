<?php

/**************/
/* KÓD KOŠÍKU */
/**************/
If( $_GET["a"]=='kosik' OR $_POST["zpetDoKosiku"] ){

    ////////////////////////////
    // Přidání zboží do košíku
    ////////////////////////////
    If( $_GET["b"]=='pridat' AND ($_GET["produkt"] OR $_POST["produkt"] ) ){
        // Příprava proměnných
        $produkt = $_POST["produkt"]?$_POST["produkt"]:$_GET["produkt"];
        $mnozstvi = $_POST["mnozstvi"]?$_POST["mnozstvi"]:$_GET["mnozstvi"];

        // Vložení do košíku
        $kosik->pridejProdukt($produkt, $mnozstvi);
    }


    ///////////////////////////
    // Smazání zboží z košíku
    ///////////////////////////
    If( $_GET["b"]=='smazat' AND $_GET["produkt"] ){
        // Příprava proměnných
        $produkt = $_POST["produkt"]?$_POST["produkt"]:$_GET["produkt"];

        // Smazání z košíku
        $kosik->smazProdukt($_GET["produkt"]);
    }


    //////////////////
    // Editace zboží
    //////////////////
    If( $_POST["b"]=='editace' AND $_POST["produkt"] AND $_POST["mnozstvi"] ){
        // Editace
        $kosik->editujProdukt($_POST["produkt"], $_POST["mnozstvi"], $_POST["varianta"]);
    }


    ////////////////////////////
    // Zobrazení obsahu košíku
    ////////////////////////////
    Switch( $_GET["b"] ){
            Case 'editace':
                $kosikHtml = $kosik->priradDoTmplPolozku($tmpl, "editacePolozky", "templates/$CONF[vzhled]/kosik.html", $_GET["produkt"]);
                $tmpl->prirad("obsah", $kosikHtml);
                break;
            Default:
                $kosikHtml = $kosik->priradDoTmpl($tmpl, "kosikFull", "templates/$CONF[vzhled]/kosik.html");
                $tmpl->prirad("obsah", $kosikHtml);
                break;
    }


    /////////////
    // Navigace
    /////////////
    $tmpl->prirad("navigace", 'Košík');
}



/**************/
/* KOŠÍK MINI */
/**************/
$kosik->priradDoTmpl($tmpl, "kosikMini", "templates/$CONF[vzhled]/kosik.html");
?>