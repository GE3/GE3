<?php
/**********************/
/* ZÁKLADNÍ NASTAVENÍ */
/**********************/

/* -- Obecné nastavení -- */
$GLOBALS["config"]["mod_rewrite"] = 1;        // zapíná/vypíná "hezké url" [1/0]
$GLOBALS["config"]["vzhled"] = '{vzhled}';   // který vzhled se má použít [složka se vzhledem]
$GLOBALS["config"]["pocitadloUrl"] = '?m=statistiky'; // url na stráku se statistikami [http://...]
$GLOBALS["config"]["neprehlMax"] = 3;         // maximální počet zobrazených produktů v Nepřehlédněte [číslo]
$GLOBALS["config"]["nejprodMax"] = 4;         // maximální počet zobrazených produktů v Nejprodávanější [číslo]
$GLOBALS["config"]["mailer"] = '{mailer}'; // identifikace odesílatele pro odchozí emaily [textový řetězec]

$GLOBALS["config"]["mailLogin"] = ''; // přihlašovací e-mail do active24
$GLOBALS["config"]["mailPass"] = ''; // přihlašovací heslo do emailu active24

/* -- Databáze -- */
$GLOBALS["config"]["sqlHost"] = '{sqlHost}';
$GLOBALS["config"]["sqlUser"] = '{sqlUser}';
$GLOBALS["config"]["sqlPass"] = '{sqlPass}';
$GLOBALS["config"]["dbName"] = '{dbName}';
$GLOBALS["config"]["sqlPrefix"] = '{sqlPrefix}';      // předpona před názvem tabulek v databázi [textový řetězec]

/* -- Template -- */
$GLOBALS["config"]["tmpl"]["saveCompile"] = 1;  // zapíná/vypíná ukládání zkompilované šablony (urychlí celý systém) [1/0]
#...další nastavení přímo v souboru GlassTemplate.class.php)


/****************/
/* ADMINISTRACE */
/****************/
$GLOBALS["config"]["mod"] = '{mod}';  // nastavuje vzhled administrace [ge3/dante/thalia]

/* -- Články -- */
$GLOBALS["config"]["admin"]["ctrlClankyVodorovne"] = {admin[ctrlClankyVodorovne]};  // zapíná/vypíná možnost v administraci spravovat hlavní(vodorovný) články [1/0]
$GLOBALS["config"]["admin"]["ctrlClankyNovinky"] = {admin[ctrlClankyNovinky]};
/* -- Faktury -- */
$GLOBALS["config"]["faktura"]["dodavatel"] = "{faktura[dodavatel]}";
$GLOBALS["config"]["faktura"]["konstSymb"] = '008';

/* -- Admin moduly -- */
$GLOBALS["config"]["adminKategorie"] = {adminKategorie};
$GLOBALS["config"]["adminVelkoobchod"] = {adminVelkoobchod};
$GLOBALS["config"]["adminHromadneCeny"] = {adminHromadneCeny};
$GLOBALS["config"]["adminFotogalerie"] = {adminFotogalerie};
$GLOBALS["config"]["adminAnkety"] = {adminAnkety};
$GLOBALS["config"]["adminFora"] = {adminFora};
$GLOBALS["config"]["adminFacebook"] = {adminFacebook};
$GLOBALS["config"]["adminSoubory"] = {adminSoubory};
$GLOBALS["config"]["adminFaktury"] = {adminFaktury}; 
$GLOBALS["config"]["adminZakaznici"] = {adminZakaznici};
$GLOBALS["config"]["adminExportProduktu"] = {adminExportProduktu};

$GLOBALS["config"]["adminKalendar"] = 0;
$GLOBALS["config"]["adminWebmail"] = -1; 
$GLOBALS["config"]["adminKalkulacka"] = 1; 
$GLOBALS["config"]["adminManual"] = 1;




/***********************/
/* AUTOMATICKÉ HODNOTY */
/***********************/
/**
 * Následující hodnoty měňte jen když tomu rozumíte ;-)
 */
$GLOBALS["config"]["absDir"] = "http://".$_SERVER["HTTP_HOST"].preg_replace("#/[^/]*$#","/",$_SERVER["SCRIPT_NAME"]);
?>

