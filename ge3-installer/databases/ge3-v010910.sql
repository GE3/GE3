-- phpMyAdmin SQL Dump
-- version 2.8.2.4
-- http://www.phpmyadmin.net
-- 
-- Počítač: localhost:3306
-- Vygenerováno: Neděle 15. srpna 2010, 02:10
-- Verze MySQL: 5.0.32
-- Verze PHP: 5.2.6
-- 
-- Databáze: `ge3`
-- 

-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}ankety`
-- 

CREATE TABLE `{sqlPrefix}ankety` (
  `id` int(5) NOT NULL auto_increment,
  `otazka` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `aktivni` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}ankety`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}anketyIp`
-- 

CREATE TABLE `{sqlPrefix}anketyIp` (
  `id` int(5) NOT NULL auto_increment,
  `anketaId` int(5) NOT NULL,
  `time` int(10) NOT NULL,
  `ip` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}anketyIp`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}anketyOdpovedi`
-- 

CREATE TABLE `{sqlPrefix}anketyOdpovedi` (
  `id` int(5) NOT NULL auto_increment,
  `anketaId` int(5) NOT NULL,
  `odpoved` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `pocet` int(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}anketyOdpovedi`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}clanky`
-- 

CREATE TABLE `{sqlPrefix}clanky` (
  `id` tinyint(3) NOT NULL auto_increment,
  `nazev` varchar(60) collate cp1250_czech_cs NOT NULL,
  `datum` varchar(30) collate cp1250_czech_cs NOT NULL,
  `uvod` text collate cp1250_czech_cs NOT NULL,
  `obsah` text collate cp1250_czech_cs NOT NULL,
  `typ` varchar(30) collate cp1250_czech_cs NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=15 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}clanky`
-- 

INSERT INTO `{sqlPrefix}clanky` (`id`, `nazev`, `datum`, `uvod`, `obsah`, `typ`) VALUES (2, 'Článek 2', '1279288277', '<p>Stránce zatím nebyl vytvořen perex...</p>', '<p>Proin viverra gravida felis in accumsan. Maecenas sagittis pretium felis, in convallis quam dapibus at. Nulla et iaculis lacus. Maecenas neque nisl, congue sit amet ullamcorper in, eleifend id massa. Aliquam semper lacus et nibh tempus in posuere tellus ornare. Donec porttitor tempor risus. Fusce leo felis, euismod vel vehicula vel, porttitor vitae dui. Vestibulum volutpat massa non leo porttitor faucibus. Vestibulum eu leo at erat tristique mattis. Nam vel mollis sem. Integer erat lectus, scelerisque a gravida eget, ultrices eu sapien. Donec at diam a nulla rhoncus venenatis quis in urna. Sed lacus urna, vulputate ut ornare quis, vestibulum ac nulla. Suspendisse malesuada, ligula quis iaculis rutrum, enim turpis blandit urna, eget porttitor nunc tortor at eros. Duis laoreet, magna eu ornare commodo, leo nibh ultricies turpis, eu rhoncus turpis erat non augue. Vivamus id augue urna. Cras eget magna nunc.</p>', 'vodorovne'),
(3, 'Článek 3', '1277995246', '<p>Stránce zatím nebyl vytvořen perex...</p>', '<p>Cras commodo cursus tortor vitae bibendum. Duis eu erat a mi suscipit tempus non non risus. Nunc condimentum lacus vitae nibh placerat adipiscing. Nulla pharetra pharetra leo, non gravida metus ullamcorper ut. In hac habitasse platea dictumst. Proin fermentum, mi quis suscipit viverra, sapien ante ultrices sapien, sit amet auctor urna lacus eu sapien. Aenean nec metus lacus, fermentum convallis velit. Proin lacus nunc, bibendum sed euismod eu, viverra et nisi. Pellentesque cursus lacus non nisl tincidunt id eleifend elit tempus. Etiam pulvinar, quam eu auctor ultricies, diam tellus suscipit mauris, nec sagittis dolor ligula sed tortor.</p>', 'vodorovne'),
(4, 'Článek 4', '1277147894', '<p>Stránce zatím nebyl vytvořen perex...</p>', '<p>Curabitur mattis, ipsum a pharetra bibendum, massa magna dignissim lorem, ac vulputate nulla diam quis nulla. Quisque sodales pharetra adipiscing. Phasellus convallis risus ac erat cursus tempor. Suspendisse in quam eget nibh elementum imperdiet. Sed at magna eros. Morbi commodo sapien sed nunc ultrices facilisis. Aliquam erat volutpat. In a libero massa, a bibendum magna. Duis velit magna, sagittis eget iaculis id, ultricies eu lectus. Cras sollicitudin felis consequat erat tristique sit amet facilisis nibh rutrum. Suspendisse sagittis felis pretium turpis tempor vel eleifend nulla tristique. Suspendisse fringilla imperdiet feugiat. Maecenas ante quam, mattis et egestas at, placerat ultrices diam. Etiam felis eros, lobortis non ultrices eu, gravida in orci. Phasellus sit amet nunc ac enim malesuada vehicula tincidunt vitae tellus. Nullam justo nulla, condimentum non lobortis quis, ullamcorper id sapien. Donec neque tellus, aliquam hendrerit cursus sed, facilisis vel nisi. In rutrum nisl a nulla tempor eu sodales urna fermentum.</p>', 'vodorovne'),
(5, 'Článek 5', '1281353885', '<p>Stránce zatím nebyl vytvořen perex...</p>', '<p>Suspendisse porttitor, diam sed aliquet mattis, mi massa tempor lorem, a pretium risus velit et magna. In elementum facilisis nulla. Cras at massa in mauris placerat pellentesque. Suspendisse volutpat lacinia vestibulum. Cras lectus justo, tincidunt eget mattis sed, molestie non diam. Ut euismod, neque a auctor tincidunt, tortor augue tincidunt ligula, sed venenatis ligula purus ut neque. Sed volutpat est libero. Praesent est nisi, tincidunt dapibus adipiscing sit amet, commodo quis diam. Nunc venenatis posuere adipiscing. Proin tortor tellus, ultrices ac imperdiet sed, convallis quis magna. Maecenas ultrices, velit in facilisis laoreet, mauris tellus cursus sapien, at mollis purus massa sed lectus. Nulla malesuada, arcu non egestas mattis, magna magna consectetur ante, id sodales lacus enim sed turpis. Pellentesque commodo sodales blandit. Morbi non ipsum enim, id tincidunt lectus. Vestibulum ante massa, dapibus vel placerat vitae, dapibus eu urna. Fusce consectetur vulputate purus, id auctor est bibendum non. Pellentesque eu orci sit amet felis hendrerit malesuada ut vel sem. Duis sed turpis at ligula blandit rhoncus vitae nec metus. Curabitur placerat lacinia diam sit amet venenatis.</p>', 'vodorovne'),
(6, 'Článek 6', '1277147725', '<p>Stránce zatím nebyl vytvořen perex...</p>', '<p>Nam libero odio, commodo et rutrum eu, congue sit amet metus. Aenean id nulla lacus. Phasellus sed tellus et diam cursus iaculis eleifend elementum purus. Nam facilisis dui eu est euismod et facilisis risus tincidunt. Aenean non lacus nec tortor bibendum auctor. Vivamus eu nunc mi. Curabitur ultricies arcu a lectus condimentum nec tristique ante volutpat. Aenean eros leo, tincidunt sed eleifend sed, placerat sit amet dui. Nunc at varius sapien. Morbi elementum, ligula vitae scelerisque aliquam, erat enim vestibulum nibh, vel condimentum dui lectus sit amet sem. Pellentesque tincidunt laoreet tempor. Vivamus adipiscing, neque ac luctus consequat, dolor tortor ultricies nibh, sed dictum est massa vitae purus. Mauris sit amet augue non odio tincidunt porta. Cras tristique porttitor vehicula. Duis varius aliquam nibh a molestie. Vestibulum neque odio, euismod eu blandit eu, convallis et ipsum. Sed at nibh lectus.</p>', 'vodorovne'),
(7, 'Článek 7', '1277937558', '<p>Stránce zatím nebyl vytvořen perex...</p>', '<p>Mauris sapien metus, congue a porttitor et, semper vitae sem. Suspendisse eget justo risus, ut lacinia augue. Mauris et ligula libero. In hac habitasse platea dictumst. Mauris et metus eget leo tincidunt hendrerit a et nulla. Proin nunc mauris, vehicula id cursus aliquet, placerat eu justo. Nulla id ligula dui, sed malesuada lacus. Vivamus fermentum metus ut purus tempus sed ultricies quam laoreet. Quisque fringilla elementum consectetur. Donec tincidunt, nisi ac feugiat tempor, lorem nisi luctus risus, volutpat cursus ligula dui ut tortor. Curabitur at orci erat. Aenean id est magna, eu sollicitudin massa.</p>', 'vodorovne'),
(9, 'Obchodní podmínky', '1280405129', '<p>Stránce zatím nebyl vytvořen perex...</p>', '<p>Donec id condimentum nisl. Fusce eget mauris non nunc rhoncus suscipit at ut nibh. Maecenas lorem mauris, porttitor nec ullamcorper ac, blandit non odio. Aenean mauris nisl, rhoncus non fringilla quis, mattis ac risus. Proin mi tortor, malesuada id lobortis eget, hendrerit at urna. Sed interdum, turpis ut ultricies tempus, dolor mauris consequat metus, sit amet euismod felis leo sed libero. Vestibulum sed ligula est, vitae congue dolor. Aliquam eu mauris quam. Suspendisse potenti. Pellentesque dui nisi, lobortis sed malesuada quis, mollis quis ante. Morbi tempus leo pretium nisl condimentum feugiat. Nam eu tempus libero. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>', 'vodorovne'),
(1, 'Homepage', '1281561785', '<p>Stránce zatím nebyl vytvořen perex...</p>', '<p>Ut ut massa a metus hendrerit auctor. Cras quis nisi arcu. Cras eros diam, ullamcorper nec condimentum ac, auctor rhoncus turpis. Mauris nisi metus, ullamcorper sit amet aliquet vitae, sagittis id lectus. Sed pretium vulputate magna, eu molestie libero pharetra sodales. Vivamus mattis porttitor nibh et congue. Nam non quam a lacus suscipit bibendum vel auctor nisl. Fusce sed mattis quam. Nunc ullamcorper faucibus eros sed commodo. Morbi a ornare velit. Vestibulum pulvinar vestibulum nulla id gravida. Cras non enim at urna ullamcorper imperdiet. Donec posuere scelerisque enim, at malesuada justo posuere ut. Sed dictum pretium risus et sagittis.</p>', 'vodorovne');

-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}doprava`
-- 

CREATE TABLE `{sqlPrefix}doprava` (
  `id` int(11) NOT NULL auto_increment,
  `firma` varchar(60) collate cp1250_czech_cs NOT NULL,
  `zpusob_platby` varchar(60) collate cp1250_czech_cs NOT NULL,
  `cena` int(6) NOT NULL,
  `prvni` varchar(6) collate cp1250_czech_cs NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=5 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}doprava`
-- 

INSERT INTO `{sqlPrefix}doprava` (`id`, `firma`, `zpusob_platby`, `cena`, `prvni`) VALUES (1, 'PPL', 'dobírka', 129, '1'),
(3, 'Česká pošta', 'dobírka - obyčejný balík', 129, ''),
(4, 'Česká pošta', 'dobírka - obchodní balík', 159, '');

-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}facebook`
-- 

CREATE TABLE `{sqlPrefix}facebook` (
  `id` int(5) NOT NULL auto_increment,
  `typ` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `hodnota` text character set utf8 collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `typ` (`typ`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}facebook`
-- 

INSERT INTO `{sqlPrefix}facebook` (`id`, `typ`, `hodnota`) VALUES (1, 'facebook_clanky', ''),
(2, 'facebook_produkty', '');

-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}faktury`
-- 

CREATE TABLE `{sqlPrefix}faktury` (
  `id` int(5) NOT NULL auto_increment,
  `uzivatelId` int(6) NOT NULL,
  `uzivatelJmeno` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelPrijmeni` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelMesto` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelUlice` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelPsc` varchar(9) collate cp1250_czech_cs NOT NULL,
  `uzivatelTelefon` varchar(17) collate cp1250_czech_cs NOT NULL,
  `uzivatelEmail` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelFirma` varchar(60) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelIco` varchar(12) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelDic` varchar(18) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelVelkoobchodId` int(4) NOT NULL,
  `uzivatelJmeno_2` varchar(60) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelPrijmeni_2` varchar(60) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelFirma_2` varchar(60) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelUlice_2` varchar(60) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelMesto_2` varchar(60) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelPsc_2` varchar(6) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelIco_2` varchar(12) character set utf8 collate utf8_czech_ci NOT NULL,
  `uzivatelDic_2` varchar(18) character set utf8 collate utf8_czech_ci NOT NULL,
  `date` varchar(30) collate cp1250_czech_cs NOT NULL,
  `stav` varchar(255) collate cp1250_czech_cs NOT NULL,
  `doprava` varchar(60) collate cp1250_czech_cs NOT NULL,
  `zpusob_platby` varchar(60) collate cp1250_czech_cs NOT NULL,
  `zbozi` text collate cp1250_czech_cs NOT NULL,
  `varianty` text collate cp1250_czech_cs NOT NULL,
  `mnozstvi` text collate cp1250_czech_cs NOT NULL,
  `cenySDph` text collate cp1250_czech_cs NOT NULL,
  `cenyBezDph` text collate cp1250_czech_cs NOT NULL,
  `dph` text collate cp1250_czech_cs NOT NULL,
  `objednavkaId` int(5) NOT NULL,
  `faktCislo` int(4) NOT NULL,
  `dodavatel` text collate cp1250_czech_cs NOT NULL,
  `date2` varchar(30) collate cp1250_czech_cs NOT NULL,
  `varSymb` varchar(12) collate cp1250_czech_cs NOT NULL,
  `konstSymb` varchar(12) collate cp1250_czech_cs NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}faktury`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}forum`
-- 

CREATE TABLE `{sqlPrefix}forum` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `id_odpovedi` int(5) unsigned default NULL,
  `forum` varchar(8) character set utf8 collate utf8_czech_ci NOT NULL,  
  `datum` int(10) unsigned NOT NULL,
  `jmeno` varchar(40) character set utf8 collate utf8_czech_ci NOT NULL,
  `email` varchar(60) character set utf8 collate utf8_czech_ci NOT NULL,
  `text` text character set utf8 collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}forum`
--


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}forums`
-- 

CREATE TABLE `{sqlPrefix}forums` (
  `id` int(8) NOT NULL auto_increment,
  `clanek` int(8) NOT NULL,
  `tema` text NOT NULL,
  `globals` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}forums`
--  




-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}mailforms`
-- 

CREATE TABLE `{sqlPrefix}mailforms` (
  `id` int(8) NOT NULL auto_increment,
  `nazev` varchar(60) NOT NULL,
  `popis` text NOT NULL,
  `od` varchar(80) NOT NULL,
  `komu` varchar(80) NOT NULL,
  `predmet` varchar(80) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}mailforms`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}mailforms_items`
-- 

CREATE TABLE `{sqlPrefix}mailforms_items` (
  `id` int(8) NOT NULL auto_increment,
  `mailform` int(8) NOT NULL,
  `typ` text NOT NULL,
  `obsah` text NOT NULL,
  `nazev` text NOT NULL,
  `polozka` int(8) NOT NULL,
  `poradi` int(8) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}mailforms_items`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}mailforms_rozdeleni`
-- 

CREATE TABLE `{sqlPrefix}mailforms_rozdeleni` (
  `clanek` int(8) NOT NULL,
  `mailform` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}mailforms_rozdeleni`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}gal_kat`
-- 

CREATE TABLE `{sqlPrefix}gal_kat` (
  `id` int(5) NOT NULL auto_increment,
  `slozka` varchar(50) NOT NULL,
  `nazev` varchar(50) NOT NULL,
  `skryta` enum('ano','ne') NOT NULL default 'ne',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `slozka` (`slozka`),
  UNIQUE KEY `nazev` (`nazev`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}gal_kat`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}galerie`
-- 

CREATE TABLE `{sqlPrefix}galerie` (
  `id` int(11) NOT NULL auto_increment,
  `vaha` tinyint(3) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `kategorie` int(5) NOT NULL,
  `popis` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `kategorie` (`kategorie`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}galerie`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}homepage`
-- 

CREATE TABLE `{sqlPrefix}homepage` (
  `id` int(3) NOT NULL auto_increment,
  `clanek` int(4) NOT NULL,
  `novinky` varchar(255) collate cp1250_czech_cs NOT NULL,
  `pocet_zbozi` int(3) NOT NULL,
  `zapati` int(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=2 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}homepage`
-- 

INSERT INTO `{sqlPrefix}homepage` (`id`, `clanek`, `novinky`, `pocet_zbozi`, `zapati`) VALUES (1, 1, '', 6, 0);

-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}nastaveni`
-- 

CREATE TABLE `{sqlPrefix}nastaveni` (
  `id` int(3) NOT NULL auto_increment,
  `emailAdmin` varchar(255) collate cp1250_czech_cs NOT NULL,
  `adminZobrazFunkce` varchar(60) collate cp1250_czech_cs NOT NULL,
  `zbozi_puvodni_cena_active` tinyint(1) NOT NULL,
  `zbozi_usetrite_jednotky` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,  
  `title` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `objednavky_autosender` tinyint(1) NOT NULL,
  `podobne_produkty_active` tinyint(1) NOT NULL,  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=2 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}nastaveni`
-- 

INSERT INTO `{sqlPrefix}nastaveni` (`id`, `emailAdmin`, `adminZobrazFunkce`, `zbozi_puvodni_cena_active`, `zbozi_usetrite_jednotky`, `title`, `objednavky_autosender`, `podobne_produkty_active`) VALUES (1, '{emailAdmin}', 'vse', 0, 'Kč',	'{title}',	1, 0);

-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}novinky_emailem`
-- 

CREATE TABLE `{sqlPrefix}novinky_emailem` (
  `id` int(4) NOT NULL auto_increment,
  `email` varchar(60) character set cp1250 collate cp1250_czech_cs NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}novinky_emailem`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}objednavky`
-- 

CREATE TABLE `{sqlPrefix}objednavky` (
  `id` int(5) NOT NULL auto_increment,
  `uzivatelId` int(6) NOT NULL,
  `uzivatelJmeno` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelPrijmeni` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelMesto` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelUlice` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelPsc` varchar(9) collate cp1250_czech_cs NOT NULL,
  `uzivatelTelefon` varchar(17) collate cp1250_czech_cs NOT NULL,
  `uzivatelEmail` varchar(120) collate cp1250_czech_cs NOT NULL,
  `uzivatelFirma` varchar(60) collate cp1250_czech_cs NOT NULL,
  `uzivatelIco` varchar(12) collate cp1250_czech_cs NOT NULL,
  `uzivatelDic` varchar(18) collate cp1250_czech_cs NOT NULL,
  `uzivatelVelkoobchodId` int(4) NOT NULL,
  `uzivatelJmeno_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `uzivatelPrijmeni_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `uzivatelFirma_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `uzivatelUlice_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `uzivatelMesto_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `uzivatelPsc_2` varchar(6) collate cp1250_czech_cs NOT NULL,
  `uzivatelIco_2` varchar(12) collate cp1250_czech_cs NOT NULL,
  `uzivatelDic_2` varchar(18) collate cp1250_czech_cs NOT NULL,
  `date` varchar(30) collate cp1250_czech_cs NOT NULL,
  `stav` varchar(255) collate cp1250_czech_cs NOT NULL,
  `doprava` varchar(60) collate cp1250_czech_cs NOT NULL,
  `zpusobPlatby` varchar(60) collate cp1250_czech_cs NOT NULL,
  `ids` text character set utf8 collate utf8_czech_ci NOT NULL,
  `cisla` text character set utf8 collate utf8_czech_ci NOT NULL,
  `zbozi` text collate cp1250_czech_cs NOT NULL,
  `varianty` text collate cp1250_czech_cs NOT NULL,
  `mnozstvi` text collate cp1250_czech_cs NOT NULL,
  `cenySDph` text collate cp1250_czech_cs NOT NULL,
  `cenyBezDph` text collate cp1250_czech_cs NOT NULL,
  `dph` text collate cp1250_czech_cs NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}objednavky`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}poptavky`
-- 

CREATE TABLE `{sqlPrefix}poptavky` (
  `id` int(5) NOT NULL auto_increment,
  `datum` datetime NOT NULL,
  `jmeno` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `adresa` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `telefon` varchar(32) character set utf8 collate utf8_czech_ci NOT NULL,
  `email` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `dotaz` text character set utf8 collate utf8_czech_ci NOT NULL,
  `obsah` text character set utf8 collate utf8_czech_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}poptavky`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}statNavstevy`
-- 

CREATE TABLE `{sqlPrefix}statNavstevy` (
  `id` int(7) NOT NULL auto_increment,
  `ip` varchar(64) character set cp1250 collate cp1250_czech_cs NOT NULL,
  `rok` int(4) NOT NULL,
  `mesic` int(2) NOT NULL,
  `den` int(2) NOT NULL,
  `hodina` int(2) NOT NULL,
  `time` int(10) NOT NULL,
  `os` text character set cp1250 collate cp1250_czech_cs NOT NULL,
  `prohlizec` varchar(255) character set cp1250 collate cp1250_czech_cs NOT NULL,
  `pocet` int(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}statNavstevy`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}statProdukty`
-- 

CREATE TABLE `{sqlPrefix}statProdukty` (
  `idProduktu` int(4) NOT NULL,
  `pocet` int(6) NOT NULL,
  PRIMARY KEY  (`idProduktu`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}statProdukty`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}statReferer`
-- 

CREATE TABLE `{sqlPrefix}statReferer` (
  `id` int(6) NOT NULL auto_increment,
  `server` varchar(60) collate cp1250_czech_cs NOT NULL,
  `url` varchar(255) collate cp1250_czech_cs NOT NULL,
  `fraze` varchar(255) collate cp1250_czech_cs NOT NULL,
  `pocet` int(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}statReferer`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}statRobots`
-- 

CREATE TABLE `{sqlPrefix}statRobots` (
  `id` int(4) NOT NULL auto_increment,
  `user_agent` varchar(255) collate cp1250_czech_cs NOT NULL,
  `time` int(12) NOT NULL,
  `pocet` int(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}statRobots`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}uzivatele`
-- 

CREATE TABLE `{sqlPrefix}uzivatele` (
  `id` int(3) NOT NULL auto_increment,
  `jmeno` varchar(60) collate cp1250_czech_cs NOT NULL,
  `heslo` varchar(32) collate cp1250_czech_cs NOT NULL,
  `prava` varchar(255) collate cp1250_czech_cs NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=3 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}uzivatele`
-- 

INSERT INTO `{sqlPrefix}uzivatele` (`id`, `jmeno`, `heslo`, `prava`) VALUES (1, 'root', 'b31b981d9066fa7d98d8edee22a0f6c8', ''),
(2, '{jmeno}', MD5('{heslo}'), '');

-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}velkoobchody`
-- 

CREATE TABLE `{sqlPrefix}velkoobchody` (
  `id` int(4) NOT NULL auto_increment,
  `nazev` varchar(120) character set utf8 collate utf8_czech_ci NOT NULL,
  `sleva` float NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}velkoobchody`
-- 

INSERT INTO `{sqlPrefix}velkoobchody` (`id`, `nazev`, `sleva`) VALUES (1, 'Velkoobchod 1', 25),
(2, 'Velkoobchod 2', 10),
(3, 'Velkoobchod 3', 45);

-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}vyhledavani`
-- 

CREATE TABLE `{sqlPrefix}vyhledavani` (
  `id` int(5) NOT NULL auto_increment,
  `text` varchar(255) collate cp1250_czech_cs NOT NULL,
  `rating` int(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}vyhledavani`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}zakaznici`
-- 

CREATE TABLE `{sqlPrefix}zakaznici` (
  `id` int(6) NOT NULL auto_increment,
  `email` varchar(60) collate cp1250_czech_cs NOT NULL,
  `telefon` varchar(60) collate cp1250_czech_cs NOT NULL,
  `heslo` varchar(60) collate cp1250_czech_cs NOT NULL,
  `jmeno` varchar(60) collate cp1250_czech_cs NOT NULL,
  `prijmeni` varchar(60) collate cp1250_czech_cs NOT NULL,
  `firma` varchar(60) collate cp1250_czech_cs NOT NULL,
  `ulice` varchar(60) collate cp1250_czech_cs NOT NULL,
  `mesto` varchar(60) collate cp1250_czech_cs NOT NULL,
  `psc` varchar(6) collate cp1250_czech_cs NOT NULL,
  `ico` varchar(12) collate cp1250_czech_cs NOT NULL,
  `dic` varchar(18) collate cp1250_czech_cs NOT NULL,
  `velkoobchodId` int(4) NOT NULL,
  `jmeno_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `prijmeni_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `firma_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `ulice_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `mesto_2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `psc_2` varchar(6) collate cp1250_czech_cs NOT NULL,
  `ico_2` varchar(12) collate cp1250_czech_cs NOT NULL,
  `dic_2` varchar(18) collate cp1250_czech_cs NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}zakaznici`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}zakazniciPoznamky`
-- 

CREATE TABLE `{sqlPrefix}zakazniciPoznamky` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) character set cp1250 collate cp1250_czech_cs NOT NULL,
  `poznamka` text character set cp1250 collate cp1250_czech_cs NOT NULL,
  `typ` varchar(36) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}zakazniciPoznamky`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}zbozi`
-- 

CREATE TABLE `{sqlPrefix}zbozi` (
  `id` int(4) NOT NULL auto_increment,
  `cislo` varchar(32) collate cp1250_czech_cs NOT NULL,
  `kategorie` varchar(60) collate cp1250_czech_cs NOT NULL,
  `obrazekKategorie` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `popisKategorie` text collate cp1250_czech_cs NOT NULL,
  `kategorieAktivni` enum('ano','ne') character set utf8 collate utf8_czech_ci NOT NULL default 'ano',
  `vaha` int(3) NOT NULL,
  `podkat1` varchar(60) collate cp1250_czech_cs NOT NULL,
  `obrazekPodkat1` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `podkat1Aktivni` enum('ano','ne') character set utf8 collate utf8_czech_ci NOT NULL default 'ano',
  `vaha_podkat1` int(3) NOT NULL,
  `podkat2` varchar(60) collate cp1250_czech_cs NOT NULL,
  `obrazekPodkat2` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `podkat3` varchar(60) collate cp1250_czech_cs NOT NULL,
  `obrazekPodkat3` varchar(255) character set utf8 collate utf8_czech_ci NOT NULL,
  `produkt` text collate cp1250_czech_cs,
  `vahaProduktu` int(3) NOT NULL,
  `varianta` varchar(120) collate cp1250_czech_cs NOT NULL,
  `vyrobce` varchar(255) collate cp1250_czech_cs NOT NULL,
  `obrazky` text character set utf8 collate utf8_czech_ci NOT NULL,
  `prilohy` varchar(255) collate cp1250_czech_cs NOT NULL,
  `cenaSDph` float NOT NULL,
  `cenaBezDph` float NOT NULL,
  `dph` tinyint(3) NOT NULL,
  `puvodni_cena_s_dph` double NOT NULL,
  `puvodni_cena_bez_dph` double NOT NULL,  
  `popis` text collate cp1250_czech_cs,
  `akce` int(1) NOT NULL,
  `neprehlednete` int(1) NOT NULL,
  `nejprodavanejsi` int(9) NOT NULL,
  `dostupnost` varchar(255) collate cp1250_czech_cs NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_czech_cs AUTO_INCREMENT=1 ;

-- 
-- Vypisuji data pro tabulku `{sqlPrefix}zbozi`
-- 


-- --------------------------------------------------------

-- 
-- Struktura tabulky `{sqlPrefix}zbozi_podobne`
-- 

CREATE TABLE `{sqlPrefix}zbozi_podobne` (
  `id` int(5) NOT NULL auto_increment,
  `zbozi` int(5) NOT NULL,
  `podobne` int(5) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;
