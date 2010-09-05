<?php
/**********************/
/* ZÁKLADNÍ NASTAVENÍ */
/**********************/

/* -- Obecné nastavení -- */
$GLOBALS["config"]["mod_rewrite"] = 1;        // zapíná/vypíná "hezké url" [1/0]
$GLOBALS["config"]["vzhled"] = 'marobot';   // který vzhled se má použít [složka se vzhledem]
$GLOBALS["config"]["pocitadloUrl"] = '?m=statistiky'; // url na stráku se statistikami [http://...]
$GLOBALS["config"]["neprehlMax"] = 3;         // maximální počet zobrazených produktů v Nepřehlédněte [číslo]
$GLOBALS["config"]["nejprodMax"] = 4;         // maximální počet zobrazených produktů v Nejprodávanější [číslo]
$GLOBALS["config"]["mailer"] = 'info@marobot.cz'; // identifikace odesílatele pro odchozí emaily [textový řetězec]

$GLOBALS["config"]["mailLogin"] = ''; // přihlašovací e-mail do active24
$GLOBALS["config"]["mailPass"] = ''; // přihlašovací heslo do emailu active24

/* -- Databáze -- */
$GLOBALS["config"]["sqlHost"] = 'localhost';
$GLOBALS["config"]["sqlUser"] = 'marobotcz';
$GLOBALS["config"]["sqlPass"] = 'Soov5doe';
$GLOBALS["config"]["dbName"] = 'marobotcz';
$GLOBALS["config"]["sqlPrefix"] = 'mr_';      // předpona před názvem tabulek v databázi [textový řetězec]

/* -- Template -- */
$GLOBALS["config"]["tmpl"]["saveCompile"] = 1;  // zapíná/vypíná ukládání zkompilované šablony (urychlí celý systém) [1/0]
#...další nastavení přímo v souboru GlassTemplate.class.php)


/****************/
/* ADMINISTRACE */
/****************/
$GLOBALS["config"]["mod"] = 'ge3';  // nastavuje vzhled administrace [ge3/dante/thalia]

/* -- Články -- */
$GLOBALS["config"]["admin"]["ctrlClankyVodorovne"] = 0;  // zapíná/vypíná možnost v administraci spravovat hlavní(vodorovný) články [1/0]
$GLOBALS["config"]["admin"]["ctrlClankyNovinky"] = 1;
/* -- Faktury -- */
$GLOBALS["config"]["faktura"]["dodavatel"] = "Marek Švehla <p>Červené Vršky 1594 <br>256 01 Benešov <p>IČO: 76555089";
$GLOBALS["config"]["faktura"]["konstSymb"] = '008';

/* -- Admin moduly -- */
$GLOBALS["config"]["adminAnkety"] = 0;   // zapíná/vypíná/skrývá modul Ankety [1/0/-1]
$GLOBALS["config"]["adminFora"] = 1; // zapíná/vypíná/skrývá modul Fóra [1/0/-1]
$GLOBALS["config"]["adminFotogalerie"] = 0;
$GLOBALS["config"]["adminKalendar"] = 0;   
$GLOBALS["config"]["adminFaktury"] = 1; 
$GLOBALS["config"]["adminWebmail"] = 0; 
$GLOBALS["config"]["adminKalkulacka"] = 1; 
$GLOBALS["config"]["adminExportProduktu"] = 0;
$GLOBALS["config"]["adminKategorie"] = 0;
$GLOBALS["config"]["adminManual"] = 1;
$GLOBALS["config"]["adminFacebook"] = 1;
$GLOBALS["config"]["adminHromadneCeny"] = 1;
$GLOBALS["config"]["adminVelkoobchod"] = 1;
$GLOBALS["config"]["adminZakaznici"] = 1;
$GLOBALS["config"]["adminSoubory"] = 1;




/***********************/
/* AUTOMATICKÉ HODNOTY */
/***********************/
/**
 * Následující hodnoty měňte jen když tomu rozumíte ;-)
 */
$GLOBALS["config"]["absDir"] = "http://".$_SERVER["HTTP_HOST"].preg_replace("#/[^/]*$#","/",$_SERVER["SCRIPT_NAME"]);
?>

