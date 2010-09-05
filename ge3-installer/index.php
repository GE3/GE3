<?php
/**
 * GE3 Installer 
 * 
 * @author  Michal Mikoláš
 * @version 0.0.1   
 **/ 

// Nette
require_once('../Nette/loader.php');
NDebug::enable(NDebug::DEVELOPMENT);
define('APP_DIR', '../Nette');
require_once('../Nette/dibi.php');

// Config
if(file_exists("../config.php/default.conf.php")) include "../config.php/default.conf.php";
$CONF = &$GLOBALS["config"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="generator" content="PSPad editor, www.pspad.com">
  
  <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
  <link rel="stylesheet" type="text/css" href="nifty.css">
  <script type="text/javascript" src="nifty2.js"></script>   
  
  <title>GE3 Installer</title>
</head>
<body>

  <div id="main-menu">
    <a href="?a=check" id="check" class="<?= ($_GET["a"]=='check' or !$_GET["a"])? 'active': ''; ?> rounded">1. Kontrola</a>
    <a href="?a=config" class="<?= ($_GET["a"]=='config')? 'active': ''; ?> rounded">2. Konfigurační soubor</a>
    <a href="?a=database" class="<?= ($_GET["a"]=='database')? 'active': ''; ?> rounded">3. Tvorba databáze</a>
    <a href="?a=base-files" class="<?= ($_GET["a"]=='base-files')? 'active': ''; ?> rounded">4. Generátor souborů</a>
    <a href="?a=content" class="<?= ($_GET["a"]=='content')? 'active': ''; ?> rounded">5. Základní obsah</a>
    <div style="clear: both;"></div>
  </div>
  
  <?php
  $_GET["a"] = $_GET["a"]? $_GET["a"]: 'check';
  if( preg_match("#^[a-zA-Z0-9\\-.]+$#", $_GET["a"]) and file_exists("$_GET[a].php") )
      require("$_GET[a].php");
  else echo "Chyba, špatná akce!";
  ?>
  
</body>
</html>