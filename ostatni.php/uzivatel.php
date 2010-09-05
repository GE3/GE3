<?php
/*********************/
/* Obsluha uživatele */
/*********************/

/* -- Přihlášení -- */
If( $_POST["email"] AND $_POST["heslo"] ){
    $uzivatel->prihlasit($_POST["email"], $_POST["heslo"]);
}

/* -- Odhlášení -- */
If( $_POST["odhlasit"] ){
    $uzivatel->odhlasit();
}

/* -- Zobrazení uživatele mini -- */
If( $uzivatel->prihlasen()) $uzivatelMini = $uzivatel->priradDoTmpl($tmpl, "uzivatelMini.prihlasen", "templates/$CONF[vzhled]/uzivatel.html");
If(!$uzivatel->prihlasen()) $uzivatelMini = $uzivatel->priradDoTmpl($tmpl, "uzivatelMini.neprihlasen", "templates/$CONF[vzhled]/uzivatel.html");
$tmpl->prirad("uzivatelMini", $uzivatelMini);
?>

