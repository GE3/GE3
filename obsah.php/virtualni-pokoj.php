<?php
If( $_GET["a"]=='virtualni-pokoj' ){
    $tmplVP = new GlassTemplate("templates/$CONF[vzhled]/virtualni-pokoj.html");
    
    $tmpl->prirad("obsah", $tmplVP->getHtml());
}
?>
