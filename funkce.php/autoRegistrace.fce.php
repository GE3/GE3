<?php
Function autoRegistrace($tmplRegCesta='', $tmplRegBlok=''){
    /**
     * Automatická registrace podle zaslaných $_POST dat
     */
    $CONF = &$GLOBALS["config"];

    /* -- Config -- */
    $noValues = array();  //názvy proměnných, které se neukládají do databáze
    $noValues[] = "registracePovinne";
    $noValues[] = "registracePodminky";
    $noValues[] = "registraceNeukladat";
    $noValues[] = "submit";

    $tmplRegBlok = $tmplRegBlok?$tmplRegBlok:'registrace';


    ////////////////////////
    // Zobrazení formuláře
    ////////////////////////
    $tmplRegistrace = new GlassTemplate($tmplRegCesta, "templates/default/uzivatel.html");

    $tmplRegistrace->newBlok("$tmplRegBlok");
    $tmplRegistrace->prirad("$tmplRegBlok.zobraz", "ano");



    $data = $_POST;
    If( $data["registracePovinne"] AND !zjisti_z("$CONF[sqlPrefix]zakaznici", "id", "email='".$data["email"]."'") AND !$data["registraceNeukladat"] ){
        ///////////////////////////
        // Automatická registrace
        ///////////////////////////

        $error = '';


        /* -- Povinné položky -- */
        // Získání názvů
        $povinne = explode(";",$data["registracePovinne"].";heslo");  //heslo je u registrace vždy povinné

        // Kontrola
        Foreach($povinne as $key=>$value){
                If( $value AND !$data[$value] ){
                    $error = "Nevyplnili jste všechny povinné položky. ";
                }
        }


        /* -- Sestavení dotazu -- */
        If( !$error ){
            $dotaz_part1 = "INSERT INTO $CONF[sqlPrefix]zakaznici(";
            $dotaz_part2 = "VALUES (";
            Foreach($data as $key=>$value){
                    If( !in_array($key,$noValues) AND $value ){
                        $dotaz_part1.= $key.",";
                        $dotaz_part2.= "'$value',";
                    }
            }
            $dotaz_part1 = ereg_replace(",$",") ",$dotaz_part1);
            $dotaz_part2 = ereg_replace(",$",") ",$dotaz_part2);

            $dotaz = Mysql_query($dotaz_part1.$dotaz_part2) or die(mysql_error());

            $tmplRegistrace->newBlok("$tmplRegBlok.zpravaOk");
            $tmplRegistrace->prirad("$tmplRegBlok.zpravaOk.text", "Úspěšně jste se zaregistroval.");
        }
        Else{
            $tmplRegistrace->newBlok("$tmplRegBlok.zpravaError");
            $tmplRegistrace->prirad("$tmplRegBlok.zpravaError.text", $error);
        }
    }
    Elseif( $data["registraceNeukladat"] AND !zjisti_z("$CONF[sqlPrefix]zakaznici", "id", "email='".$data["email"]."'") ){
        $error = '';


        /* -- Povinné položky -- */
        // Získání názvů
        $povinne = explode(";",$data["registracePovinne"]);

        // Kontrola
        Foreach($povinne as $key=>$value){
                If( $value AND !$data[$value] AND $value!='heslo' ){
                    $error = "Nevyplnili jste všechny povinné položky. ";
                }
        }
        
            
        /* -- Sestavení dotazu -- */
        If( !$error ){
            $_SESSION["uzvatelId"] = '';
            Foreach($data as $key=>$value){
                    If( !in_array($key,$noValues) AND $value ){
                        $_SESSION["uzivatel".ucfirst($key)] = $value;
                    }
            }
        }
        Else{
            $tmplRegistrace->newBlok("$tmplRegBlok.zpravaError");
            $tmplRegistrace->prirad("$tmplRegBlok.zpravaError.text", $error);
        }            
    }
    Elseif( $data["registracePovinne"] ){
            $tmplRegistrace->newBlok("$tmplRegBlok.zpravaError");
            $tmplRegistrace->prirad("$tmplRegBlok.zpravaError.text", "Tento uživatel již je registrován.");
    }



    Return $tmplRegistrace->getHtml();
}
?>

