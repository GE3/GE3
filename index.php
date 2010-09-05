<?php
/*******************************************/
/* .-------------------------------------. */
/* | GrafartEshop3                       | */
/* |-------------------------------------| */
/* |       Version: 1.5.1                | */
/* | Last updating: 18.3.2010            | */
/* |-------------------------------------| */
/* |        Author: Michal Mikoláš       | */
/* |          Firm: Grafart studio       | */
/* |                www.grafartstudio.cz | */
/* '-------------------------------------' */
/*******************************************/
/**
 * U každého e-shopu zkontrolovat
 *  - ceny s/bez DPH v šablonách (objednávka, košík, objednávka - potvrzovací email, produkty, vyhledávání, detail produktu)
 *  - smazat pokusné produkty
 *  - při testech vrátit admin email zpátky na email majitele eshopu 
 *  - logo firmy v přihlašování do admin 
 * 
 * Dodělat
+*  - slevy (viz kompatibilita wh-shop) 
 *  - souborový systém v admin (např. kategorie.ajax.podkat1.php)
 *  - vložit config do ajaxových souborů
+*  - vložit zabespečení do ajaxových souborů (zahrnuje správů adminů přes mysql)
 *  - vytvořit admin nástroj pro správu homepage
 *  - dodělat statistiky
 *  - znovu si projít a zpřehlednit všechny zdrojové kódy
 *  - přejmenování produktu => přejmenování i všech jeho obrázků 
+*  - fce/ do funkce.php/ 
 *  - mysql_fetch_array => mysql_fetch_assoc 
 *  - přidat zboží - aby zůstaly kategorie (ajax) otevřené 
 *  - v easyAjaxech dodělat možnost pro více tagů <script> 
 *  - opravit chybu v GlassTemplatu s více bloky na jednom řádku 
 *  - ochranu proti SQL Injection 
 * Návrhy na nové funkce 
 *  - fórum pro články a produkty 
 *  - automatické zálohy 
 * 
 * Historie verzí
 * 1.9.0
 *  - skrývání kategorií
 *  - původní cena [1.9b.0]   
 * 1.8.0
 *  - rozšířen admin modul Kategorie o možnost vložení obrázku kategorie  
 * 1.7.0
 *  - přidán admin modul Facebook  
 * 1.6.0
 *  - přidán admin modul Správce souborů  
 * 1.5.0 (24.11.09) 
 *  - popisek ke kategorii zboží (obsah.php/produkty.php; admin/kategorie.php; admin/ajax.php/ajax.*_kat.php; do tabulky zbozi přibyl sloupeček popisKategorie)
 *  - opravena cyba s uvozovkami v názvu zboží [1.5.1]  
 * 1.4.0 (IDontKnow)
 *  - hafo nových změn, ani jsem to tu nestíhal psát 
 * 1.3.0 (24.8.09)
 *  - hlavní část administrace předělána do šablon 
 *  - v configu přibyla položka "mod", nastavující mód e-shopu buď na obchod (ge3) nebo na red. systém (dante)
 *  - nový sloupeček 'adminZobrazFunkce' v tabulce nastaveni v DB
 *  - opravena chyba v GlassTemplatu v metodě opravCestuSrc, která nepočítala se znakem '-' v cestě k šabloně [1.3.1]
 *  - opravena chyba ve funkci urlText, která nepočítala ze znakem procenta [1.3.2]
 * 1.2.0 (8.8.09) 
 *  - v administraci přibyly zašedlé ikony nových funkcí a zelené plusko, provázané s pridat_funkci.php
 *  - opravena chyba s prací se šablonou v mapaWebu.php [1.2.1]
!*  - přidán LIMIT v sql dotazu kvůli zaseknutí při hodně produktech (dodělat tři tečky pokud je produktů víc)
 * 1.1.0 (30.7.09) 
 *  - v objednávce nyní není povinná registrace (do šablony už se jako hodnota "registracePovinne" nemusí dávat 'heslo')
 *     (v šabloně uzivatel.html přibil blok 'registrace2') 
 *  - vylepšená funkce easyMail s lepším generováním altBody 
 *  - v modulu Objednávky je nyní stránkování
 *  - v modulu Objednávky odstraněna chyba se špatným seřazováním podle id (nyní podle time) [1.1.1]
 *  - opravena chyba s zobrazením patičky na homepagi [1.1.2]
 * 1.0.0 (26.7.09) 
 *  - přístupy do administrace jsou nyní uložené v databázi v zakódované formě 
 *  - opravena chyba v počítání celkových cen faktur 
 *  - přidána admin funkce Web mail 
 * 0.9.0 (19.7.09) 
 *  - nový modul obsah.php/poptat.php 
 *  - do autoMail.class.php přidáno načítání odchozí adresy z databáze 
 *  - obsah.popup.php přejmenován na clanek.popup.php 
 *  - e-shop je nyní celý v kódování utf-8 
 *  - do skriptu pro vyhledávání byl kvůli stabilitě e-shopu přidán limit načtení z DB 
 *  - v DB se nyní k oddělování obrázků a všech souborů používá středník 
 *  - přístupové údaje k databázi jsou nyní uloženy v konfiguračním souboru 
 *    a k DB se připojuje přímo v indexu 
 *  - opraveno pár chyb, už nevim v jakých souborech (bylo jich hodně)
 * 0.8.0 (17.7.09) 
 *  - možnost přidávat k zákazníkům poznámky s kladným, záporným, nebo neutrálním hodnocením 
 * 0.7.0 (6.7.09)
 *  - upravený šablonovací systém, změna v syntaxi šablon (modul miniPhp)
 *  - do html editoru přidáno tlačítko Vložit jako čistý text [0.7.1]
 *  - pravena chyba s načtením akčního zboží v obsah.php/homepage.php [0.7.2] 
 *  - opravena chyba se špatnou cestou k šabloně pro faktury [0.7.3]
 * 0.6.0 (24.6.09)
!*  - přidány statistiky (dodělat rozšiřující možnosti)
 *  - kompletně předělaný systém úpravy zboží 
 *  - změna vzhledu v Hromadná úprava cen a v Export produktů do excelu
 *  - smazán admin modul Správce produktových položek, jeho nastavení přesunuto 
 *    do Objednávky a do Editace produktů
 *  - vytvořena funkce Mapa webu
 *  - do detailu produktu přibyla možnost zobrazit kategorii [0.6.1]
 * 0.5.0 (14.6.09)
 *  - možnost přidávat k produktům přílohy
!*  - funkce exportu produktů do csv (dodělat template!)
 *  - oprava ve třídě AutoMail (šel odeslat nevyplněný email) [0.5.1]
 *  - opravena chyba v .htaccess při url /produkty/produkt.html [0.5.2]    
 * 0.4.0 (28.5.09)
 *  - nová položka pro produkty: číslo produktu
 *  - opravena chyba s division zero u prázdné kategorie [0.4.1]
 *  - oprava chyby s načtením configu u souboru ajax.status.php [0.4.2]
 *  - přidání chybějících údajů do Upravit produkt [0.4.3]
 *  - oprava ve funkci urlText, přidáno zpracování znaku & [0.4.4]  
 *  - opravena chyba v odkazu na produkt v detailu produktu [0.4.5] 
 * 0.3.0 (21.5.09)
 *  - přidána funkce odeslání emailu po dokončení objednávky jako kontrola objednávky
 *  - nová položka v configu (mailer)
 *  - odladění php funkce zjisti_z() [0.3.1]
 *  - odladění titleInfo [0.3.2]
 *  - opravena chyba s nezobrazováním ceny v admin»upravit [0.3.3]
 *  - odladění zobrazování novinek [0.3.4]
 *  - oprava chyby s nezobrazením formuláře pro nový článek v adminu [0.3.5]
 *  - oprava chyby se špatnou url detailu ve vyhledávání [0.3.6]
 *  - přidána možnost "noPicture" obrázku jako defaultního obrázku produktu [0.3.7]
 *  - opravená chyba se zobrazením popisu produktu v homepage.php 
 * 0.2.0 (20.5.09)
 *  - nové položky v configu (sqlPrefix, tmpl[saveCompile], admin[ctrlClankyVodorovne])
 *  - do DB přidány tabulky pro statistiky (statRobots)
 * 0.1.0 (17.5.09)
 *  - první nasazená verze, web diy-optika.cz
 */

/*********************/
/* POČÁTEČNÍ PŘÍKAZY */
/*********************/
/* -- Nette -- */
require_once('Nette/loader.php');
//NDebug::enable(NDebug::DEVELOPMENT);
define('APP_DIR', 'Nette');
require_once('Nette/dibi.php');

/* -- Sessions -- */
session_start();

/* -- Include funkcí -- */
/**///Include "fce/prevod_promennych.inc";
Include "fce/fce.inc";
Include "fce/easy_mail.inc";
Include 'funkce.php/debug.fce.php';
Include 'funkce.php/db.fce.php';

/* -- Include configu -- */
Include "config.php/default.conf.php";
$CONF = &$GLOBALS["config"];

/* -- SQL připojení -- */
mysql_connect($CONF["sqlHost"],$CONF["sqlUser"],$CONF["sqlPass"]);
mysql_select_db($CONF["dbName"]);
mysql_query("SET character_set_results=utf8, character_set_connection=utf8, character_set_client=utf8");
mysql_query("SET NAMES utf8");

/* -- Definice tříd -- */
Include 'class.php/GlassTemplate.class.php';
Include 'class.php/AutoMail.class.php';
Include 'class.php/Kosik.class.php';
Include 'class.php/Uzivatel.class.php';
Include 'class.php/Objednavka.class.php';
Include 'class.php/Faktura.class.php';

/* -- Vytvoření hlavních objektů -- */
if(!$_GET["tisk"]) $tmpl = new GlassTemplate("templates/$CONF[vzhled]/index.html", "templates/default/index.html");
else $tmpl = new GlassTemplate("templates/$CONF[vzhled]/index-tisk.html", "templates/default/index-tisk.html");
$kosik = new Kosik();
$uzivatel = new Uzivatel();



/***************/
/* KÓD E-SHOPU */
/***************/

/* -- Menu -- */
// (načte všechny soubory typu nazev.php z adresáře pro menu)
/**
 * soubory, které zobrazují menu s odkazy, nebo jeho součásti
 */
$adresar=opendir("menu.php");
While($soubor=readdir($adresar)){
      If( file_exists("menu.php/".$soubor) AND ereg("^[a-zA-Z0-9_\-]*\.php$",$soubor) ){
          Include "menu.php/$soubor";
      }
}
closedir($adresar);


/* -- Obsah -- */
// (načte všechny soubory typu nazev.php z adresáře pro obsah)
/**
 * soubory, které zobrazují hlavní obsah webu, jsou podmíněny proměnnýma v URL
 */
$adresar=opendir("obsah.php");
While($soubor=readdir($adresar)){
      If( file_exists("obsah.php/".$soubor) AND ereg("^[a-zA-Z0-9_\-]*\.php$",$soubor) ){
          Include "obsah.php/$soubor";
      }
}
closedir($adresar);


/* -- Ostatní -- */
// (načte všechny soubory typu nazev.php z adresáře pro nezařazené skripty)
/**
 * soubory, které buď nezobrazují nic (počítání statistik, realizace přihlášení, ...),
 * nebo zobrazují určený obsah neustále (přehled košíku, nejprodávanější zboží, ... [možná jim přiřadím vlatní složku])
 */
$adresar=opendir("ostatni.php");
While($soubor=readdir($adresar)){
      If( file_exists("ostatni.php/".$soubor) AND ereg("^[a-zA-Z0-9_\-]*\.php$",$soubor) ){
          Include "ostatni.php/$soubor";
      }
}
closedir($adresar);



/*************/
/* ZOBRAZENÍ */
/*************/
Echo $tmpl->getHtml();
?>

