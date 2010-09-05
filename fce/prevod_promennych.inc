<?php /* POST */

//vyhledávání v levém panelu
$vyhledavani=$_POST["vyhledavani"];
$hledat=$_POST["hledat"];

//přihlášení
$uz_prihl=$_POST["uz_prihl"];
$uz_prihl_email=$_POST["uz_prihl_email"];
$uz_prihl_heslo=$_POST["uz_prihl_heslo"];

//odeslání košíku
if($_GET["kosik_odeslat"])$kosik_odeslat=$_GET["kosik_odeslat"];
if($_POST["kosik_odeslat"])$kosik_odeslat=$_POST["kosik_odeslat"];

//smazání zboží z košíku
$kosik_smazat=$_POST["kosik_smazat"]?$_POST["kosik_smazat"]:$_GET["kosik_smazat"];
$kosik_smazat_potvrdit=$_POST["kosik_smazat_potvrdit"]?$_POST["kosik_smazat_potvrdit"]:$_GET["kosik_smazat_potvrdit"];

//editace zboží v košíku
$kosik_edit_id=$_POST["kosik_edit_id"]?$_POST["kosik_edit_id"]:$_GET["kosik_edit_id"];
$kosik_edit_id2=$_POST["kosik_edit_id2"]?$_POST["kosik_edit_id2"]:$_GET["kosik_edit_id2"];
$kosik_zmenit=$_POST["kosik_zmenit"]?$_POST["kosik_zmenit"]:$_GET["kosik_zmenit"];
$kosik_smazat=$_POST["kosik_smazat"]?$_POST["kosik_smazat"]:$_GET["kosik_smazat"];
$kosik_zmenit_variantu=$_POST["kosik_zmenit_variantu"]?$_POST["kosik_zmenit_variantu"]:$_GET["kosik_zmenit_variantu"];
$kosik_zmenit_mnozstvi=$_POST["kosik_zmenit_mnozstvi"]?$_POST["kosik_zmenit_mnozstvi"]:$_GET["kosik_zmenit_mnozstvi"];

//přidání do košíku
$detail_akce=$_POST["detail_akce"]?$_POST["detail_akce"]:$_GET["detail_akce"];
$detail_id=$_POST["optnvalu01"]?$_POST["optnvalu01"]:$_POST["detail_id"];
$detail_mnozstvi=$_POST["prodquan"]?$_POST["prodquan"]:$_POST["detail_mnozstvi"];

//registrace
$reg_osloveni=$_POST["reg_osloveni"];
$reg_titul=$_POST["reg_titul"];
$reg_jmeno=$_POST["reg_jmeno"];
$reg_mobil=$_POST["reg_mobil"];
$reg_email=$_POST["reg_email"];
$reg_heslo=$_POST["reg_heslo"];
$reg_heslo2=$_POST["reg_heslo2"];
$reg_novinky=$_POST["reg_novinky"];
$reg_spolecnost=$_POST["reg_spolecnost"];
$reg_ico=$_POST["reg_ico"];
$reg_dic=$_POST["reg_dic"];
$reg_ulice=$_POST["reg_ulice"];
$reg_cislo_popisne=$_POST["reg_cislo_popisne"];
$reg_mesto=$_POST["reg_mesto"];
$reg_psc=$_POST["reg_psc"];
$reg_stat=$_POST["reg_stat"];
$reg_telefon=$_POST["reg_telefon"];
$reg_fax=$_POST["reg_fax"];
$reg_jin_ulice=$_POST["reg_jin_ulice"];
$reg_jin_cislo_popisne=$_POST["reg_jin_cislo_popisne"];
$reg_jin_mesto=$_POST["reg_jin_mesto"];
$reg_jin_psc=$_POST["reg_jin_psc"];
$reg_jin_stat=$_POST["reg_jin_stat"];
$reg_jin_kontaktni_osoba=$_POST["reg_jin_kontaktni_osoba"];
$reg_jin_telefon=$_POST["reg_jin_telefon"];
$reg_jin_fax=$_POST["reg_jin_fax"];
$reg_pozn=$_POST["reg_pozn"];
?>

<?php /* GET */
$kategorie=$_GET["kategorie"];
$produkt=$_GET["produkt"];
$produkt_id=$_GET["produkt_id"];
$podkat1=$_GET["podkat1"];
$podkat2=$_GET["podkat2"];
$podkat3=$_GET["podkat3"];
?>

<?php /* Nové */
//Stránkování
$pocet=$_POST["pocet"]?$_POST["pocet"]:$_GET["pocet"];
$pocet=$pocet?$pocet:9;
$i_min=$_GET["i_min"]?$_GET["i_min"]:1;

//Filtrace
$seradit_podle=$_POST["seradit_podle"]?$_POST["seradit_podle"]:$_GET["seradit_podle"];
$vyrobce=$_POST["vyrobce"]?$_POST["vyrobce"]:$_GET["vyrobce"];
?>
