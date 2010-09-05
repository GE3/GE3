<?php
If( $_GET["a"]=='forum' ){
    $tmplForum = new GlassTemplate("templates/$CONF[vzhled]/forum.html");
    
    /* -- Přidání přízpěvku -- */
    if($_POST["odeslat"] == "odeslat" && $_POST["jmeno"] != "" && $_POST["zprava"] != ""){
       $h = mysql_fetch_array(mysql_query("SELECT heslo FROM ".$CONF["sqlPrefix"]."hesla WHERE typ = 'forum' LIMIT 1"));
       if($_POST["odpoved"] != "" && md5($_POST["heslo"]) != $h["heslo"]){
          $tmplForum->prirad("hlaska", "<p>Pro odpověď musíte zadat platné heslo!</p>");
       }
       else{
          $_POST["zprava"] = nl2br(htmlspecialchars($_POST["zprava"]));
          $odp = ($_POST["odpoved"] == "" ? "NULL" : "'".$_POST["odpoved"]."'");
          $datum = time();
          
          $sql = mysql_query("INSERT INTO ".$CONF["sqlPrefix"]."forum VALUES (NULL, $odp, '$datum', '$_POST[jmeno]', '$_POST[email]', '$_POST[zprava]')");
          if($sql){
              $tmplForum->prirad("hlaska", "<p>Váš příspěvek byl úspěšně vložen.</p>");
          }
          else{
              $tmplForum->prirad("hlaska", "<p>Při ukládání Vašeho příspěvku došlo k chybě.</p>");
        }
       }
    }
    elseif($_POST["jmeno"] == "" OR $_POST["zprava"] == ""){
      
    }


  /* -- Zobrazení přízpěvků -- */
  $sql = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."forum WHERE id_odpovedi IS NULL ORDER BY id DESC");
  
  while($p = mysql_fetch_array($sql)){
    $tmplForum->newBlok("prizpevek");
    
    $email = ($p["email"] != "" ? " <a href=\"mailto:".$p["email"]."\" class=\"mensi\">[email]</a>" : "");
    $datum = date("j.n.Y H:i", $p["datum"]);
    
    $tmplForum->prirad("prizpevek.id", $p["id"]);
    $tmplForum->prirad("prizpevek.jmeno", $p["jmeno"]);
    $tmplForum->prirad("prizpevek.email", $email);
    $tmplForum->prirad("prizpevek.datum", $datum);
    $tmplForum->prirad("prizpevek.obsah", $p["text"]);
    
    $pod = mysql_query("SELECT * FROM ".$CONF["sqlPrefix"]."forum WHERE id_odpovedi = '$p[id]'");
    while($od = mysql_fetch_array($pod)){
          $tmplForum->newBlok("prizpevek.odpoved");    
    
          $email_1 = ($od["email"] != "" ? " <a href=\"mailto:".$od["email"]."\" class=\"mensi\">[email]</a>" : "");
          $datum_1 = date("j.n.Y H:i", $od["datum"]);
        
          $tmplForum->prirad("prizpevek.odpoved.id", $od["id"]);
          $tmplForum->prirad("prizpevek.odpoved.jmeno", $od["jmeno"]);
          $tmplForum->prirad("prizpevek.odpoved.email", $email_1);
          $tmplForum->prirad("prizpevek.odpoved.datum", $datum_1);
          $tmplForum->prirad("prizpevek.odpoved.obsah", $od["text"]);        
    }
  }


  /* -- Zobrazení -- */
  $tmpl->prirad("obsah", $tmplForum->getHtml());
}
?>
