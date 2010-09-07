<?php /* Skiny */  
//Nastavení
$skiny=array();
$skiny["Afrika"]="afrika";
$skiny["Grafart"]="grafart";
$skiny["List"]="list";
$skiny["Obloha"]="obloha";
$skiny["Maskáč"]="maskac";
$skiny["Vánoce"]="vanoce";

//Pozadí
$skin=$_POST["zmena_skinu"]?$_POST["skin"]:$_COOKIE["skin"];
$pozadi=$skin?$skiny[$skin].".jpg":'';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Language" content="cs">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 
  <title>Admin sekce</title>
  <script type="text/javascript" src="fckeditor/fckeditor.js"></script>
  <script type="text/javascript" src="../javascripty.js/GlassScripts/GlassScripts.js"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="../javascripty.js/GlassScripts/css_default/glass_scripts.css" />
  <style>
  body,td{
    font-family: Arial;
    font-size: 12px;
    margin: 10px auto;
    text-align: left;
  }
  a{
    color: black;
    text-decoration: none;
  }
  h2{
    font-size: 11pt;
    font-weight: bold;
    color: #333333;
    margin-top: 12px;
    margin-bottom: 0px;
  }
  .stromKategorii{
    position: absolute; 
    top: 196px; 
    left: 6px; 
  }
  .stromKategorii a{
    text-decoration: none;
  }
  </style>
  <meta http-equiv='X-UA-Compatible' content='IE=7'>  
</head>

<body bgcolor="#FFCD82" background="pozadi/<?php Echo $pozadi;?>" style="background-attachment: fixed;">

<div align="center">
  <table border="0" width="691" cellpadding="0" cellspacing="0" background="images/tapeta-tabulky.png">
    <tr>
      <td width="398" height="140" style="padding-left: 12px; padding-top: 7px;">
      <a href="http://www.grafartstudio.cz">
      <img border="0" src="images/hlavicka.png"></a><br>
      </td>
      <td width="9">
      &nbsp;</td>
      <td width="278" rowspan="2">
      <div align="center">
        <table border="0" width="57%">
          <tr>
            <td>

            <p align="center">
            <a href="index.php">
            <img border="0" src="images/Home-128x128.png" width="128" height="128"></a></td>
          </tr>
          <tr>
            <td style="text-align: center;">
            <!--<p align="center">-->
            <font face="Arial"><a href="index.php">
            <span style="text-decoration: none; font-weight: 700">

            <font color="#808080" style="font-size: 9pt">&lt;&lt;&lt; Zpět do menu</font></span></a></font>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
      </div>
      </td>

    </tr>
    <tr>
      <td width="407" colspan="2" height="88">
      <?php Echo $html_nadpis;?>
      </td>
    </tr>
    <tr>
      <td width="685" colspan="3">

      <hr size="1" width="95%">
      <p>&nbsp;</td>
    </tr>
    <tr>
      <td width="685" colspan="3" height="223" valign="top">
      <div align="center">
        <table border="0" width="90%" height="294">
          <tr>
            <td valign="top">

