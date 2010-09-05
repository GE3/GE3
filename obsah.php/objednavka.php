<?php
If( $_GET["a"]=='objednavka' ){

    If( !$_POST["zpetDoKosiku"] ){
        Include "obsah.php/objednavka.krok1.php";
    }
    Else{
        $uzivatel->odhlasit();
    }

}
?>
