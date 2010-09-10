<?php
$html_nadpis='
              <table border="0" width="404" cellspacing="15">
                <tr>
                  <td width="8" align="center">&nbsp;</td>
                  <td width="40" align="center" valign="top">
                  <img border="0" src="images/objednavky.png"></td>
                  <td width="616" colspan="2">
                  <p align="left"><span style="font-weight: 700">
                  <font face="Arial" size="2">Vyplněné objednávky</font></span><font face="Arial" style="font-size: 8pt"><br>
                  Zde naleznete přehled odeslaných objednávek od vašich zákazníků.</font></td>
                </tr>
              </table>
              ';
Include '../class.php/Uzivatel.class.php';
Include '../class.php/Objednavka.class.php';
Include '../class.php/Faktura.class.php';
?>
<?php Include 'grafika_zac.inc'; ?>



<?php
/**********************/
/* Smazání objednávky */
/**********************/
If( $_GET["smaz_objednavku"] ){
    unset($objednavka);
    @$objednavka = new Objednavka($_GET["smaz_objednavku"]);

    If( @$objednavka->smazZDatabaze() ){
        Echo '<div style="color: #008000; border: 1px solid #008000; padding: 3px 3px 3px 6px;">Úspěšně smazáno.</div><p>';
    }
}

/*********************/
/* Vytvoření faktury */
/*********************/
If( $_GET["vystavitFakturu"] ){
    $faktura = new Faktura();
    If( $faktura->vytvorZObjednavky($_GET["vystavitFakturu"]) )
        Echo '<div style="color: #008000; border: 1px solid #008000; padding: 3px 3px 3px 6px;">Faktura úspěšně vytvořena.</div><p>';
    Else
        Echo '<div style="color: #008000; border: 1px solid #008000; padding: 3px 3px 3px 6px;">Při vytváření faktury došlo k chybě: <br>'.$faktura->getErrors().'</div><p>';
}
?>



<?php /* Změna admin emailu */
If( $_POST["zmena_emailu"] AND $_POST["email"] ){
    $email=$_POST["email"];
    If( Mysql_query("UPDATE $CONF[sqlPrefix]nastaveni SET  emailAdmin='$email' WHERE id=1") ){
      echo '
         <table><tr><td bgcolor="#FFDEDE">
         Email byl úspěšně změněn.
         </td></tr></table>
         ';
      $GLOBALS["emailAdmin"]=$_POST["email"];
      }else{
      echo '
         <table><tr><td bgcolor="#FFDEDE">
         Při změně emailu nastala neznámá chyba.
         </td></tr></table>
         ';
      }
}
?>



<?php /* Změna množství produktů */
if( is_array($_POST["mnozstvi"]) and $_POST["objednavka"] ){
    $objednavka_edit = new Objednavka($_POST["objednavka"]);
    foreach($_POST["mnozstvi"] as $zbozi_i=>$mnozstvi){
            $objednavka_edit->upravZbozi($zbozi_i, $mnozstvi);
    }
    $objednavka_edit->upravVDatabazi();
}
?>



<form method="post" action="">
<!--Vyhledat objednávku: <input type="text" name="vyhledavani" value="<?php Echo $_POST["vyhledavani"]; ?>" style="font-size: 80%;"> <input type="submit" name="odeslat" value="Vyhledat" style="font-size: 80%;">-->
<p>
<?php
/*************************/
/* Zobrazení stránkování */
/*************************/
If( !$_POST["vyhledavani"] ){
    $dotaz = Mysql_query("SELECT COUNT(jmeno) FROM $CONF[sqlPrefix]objednavky WHERE jmeno!='' ");
    $radek = @mysql_fetch_array($dotaz) ;
    $pocetObjednavek = $radek["COUNT(jmeno)"];

    $strankovani = '<center>';
    $i=1;
    While($i<$pocetObjednavek){
          If( $i%10==1 ){
              If($_GET["i_min"]==$i) $strankovani.= '<a href="?m=objednavky&i_min='.$i.'" style="text-decoration: none; font-weight: bold;">'.ceil($i/10).'</a> &nbsp;&nbsp; | &nbsp;&nbsp;';
              Else $strankovani.= '<a href="?m=objednavky&i_min='.$i.'">'.ceil($i/10).'</a> &nbsp;&nbsp; | &nbsp;&nbsp;';
          }
          $i++;
    }
    $strankovani = str_replace("| &nbsp;&nbsp; &end;","",$strankovani." &end;");
    $strankovani = str_replace("&end;","",$strankovani);
    $strankovani.= '</center>';

    Echo $strankovani;
}



/************************/
/* Zobrazení objednávek */
/************************/

/* -- Stránky -- */
$dotaz = Mysql_query("SELECT COUNT(uzivatelJmeno) FROM $CONF[sqlPrefix]objednavky WHERE uzivatelJmeno!='' ") or die(Mysql_error());
$radek = @mysql_fetch_array($dotaz) ;
$pocetObjednavek = $radek["COUNT(uzivatelJmeno)"];

$strankovani = '<center>';
$i=1;
While($i<$pocetObjednavek){
      If($i==161) $strankovani.='<span style="cursor: pointer;" onClick="ukazSkryj(\'divOdkazyDalsi\');">Starší »</span> <div id="divOdkazyDalsi" style="display: none;">';
      If( $i%10==1 ){
          If($_GET["i_min"]==$i) $strankovani.= '<a href="?m=objednavky&i_min='.$i.'" style="text-decoration: none; font-weight: bold;">'.ceil($i/10).'</a> &nbsp;&nbsp; | &nbsp;&nbsp;';
          Else $strankovani.= '<a href="?m=objednavky&i_min='.$i.'">'.ceil($i/10).'</a> &nbsp;&nbsp; | &nbsp;&nbsp;';
      }
      $i++;
}
If($i>161) $strankovani.='</div>';
$strankovani = str_replace("| &nbsp;&nbsp; &end;","",$strankovani." &end;");
$strankovani = str_replace("&end;","",$strankovani);
$strankovani.= '</center>';

Echo $strankovani;

/* -- Objednávky -- */
$i_min = $_GET["i_min"]?$_GET["i_min"]:1;
$i_min--;
If( $_POST["vyhledavani"] ){
    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]objednavky WHERE podrobnosti LIKE '%".$_POST["vyhledavani"]."%' ORDER BY date DESC LIMIT $i_min,10");
  }Else{
    $dotaz=Mysql_query("SELECT * FROM $CONF[sqlPrefix]objednavky ORDER BY date DESC LIMIT $i_min,10");
}
While($radek=mysql_fetch_array($dotaz)){
      unset($objednavka);
      $objednavka = new Objednavka($radek["id"]);

      $tmplPom = new GlassTemplate("../templates/$CONF[vzhled]/objednavka.html", "../templates/default/objednavka.html");
      Echo $objednavka->priradDoTmpl($tmplPom, "objednavkaAdmin", "../templates/$CONF[vzhled]/objednavka.html");

      Echo $objednavka->getErrors();
}
?>
</form>



<?php
/*******************************/
/* ZMĚNA EMAILU SPRÁVCE SRÁNEK */
/*******************************/
Echo '<b>Změna e-mailu pro upozornění na nové objednávky:</b>
      <form action="" method="post">
      <input type="hidden" name="zmena_emailu" value="ok">
      E-mail: <input type="text" name="email" value="'.zjisti_z("$CONF[sqlPrefix]nastaveni","emailAdmin","id=1").'">
      <input type="submit" name="tlacitko5" value="Změnit">
      </form>';  
?>



<?php Include 'grafika_kon.inc'; ?>
