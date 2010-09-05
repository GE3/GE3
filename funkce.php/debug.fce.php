<?php
/* -- Nastavení logování chyb na email -- */
function myErrorHandler($errno, $errstr, $errfile, $errline){
    /* Poznámky
    Uživatelské chyby lze uměle vyvolat – trigger_error().
    set_error_handler() – nastaví callback funkci, která se použije pro ošetření libovolné chyby v PHP
    debug_backtrace() vrací aktuální stav PHP zásobníku.
    error_log() posílá zprávu do logovacího systému. Logovací systém může zprávu zapsat do logu web. serveru, poslat zprávu přes TCP spojení, přes email, nebo ji zapsat do souboru.
    */

    // Nastavení
    $cfgLog = True;
    $cfgMail = False;
    $cfgEcho = True;
    
    $errortype = array (E_ERROR              => 'Error',
                        E_WARNING            => 'Warning',
                        E_PARSE              => 'Parsing Error',
                        E_NOTICE             => 'Notice',
                        E_CORE_ERROR         => 'Core Error',
                        E_CORE_WARNING       => 'Core Warning',
                        E_COMPILE_ERROR      => 'Compile Error',
                        E_COMPILE_WARNING    => 'Compile Warning',
                        E_USER_ERROR         => 'User Error',
                        E_USER_WARNING       => 'User Warning',
                        E_USER_NOTICE        => 'User Notice',
                        E_STRICT             => 'Runtime Notice',
                        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error');

    // Zapsání do logu
    If( $cfgLog ){
        $errorText = '<span style="color: #666666; font-size: 8pt;">'.date("j.n.Y G:i")." &raquo;</span> <b>".$errortype[$errno].":</b> $errstr in <b>$errfile</b> on line $errline. ";
        
        If( file_exists("_errlog.html") AND is_writable("_errlog.html") ){
            $log = @file_get_contents("_errlog.html");
            $os = fopen("_errlog.html", "w");
        }        
        If( file_exists("../_errlog.html") AND is_writable("../_errlog.html") ){
            $log = @file_get_contents("../_errlog.html");
            $os = fopen("../_errlog.html", "w");
        }
        fwrite($os, $errorText."<br>&nbsp;<br> \r\n\r\n".$log);
        fclose($os);
    }

    // Odeslání emailu
    If( $cfgMail ){
        $errorText = $errortype[$errno].": $errstr in $errfile on line $errline. ";
        odesliChybu($errorText);
    }
    
    // Zobrazení chyby
    If( $cfgEcho ){
        $errorText = "<b>".$errortype[$errno].":</b> $errstr in <b>$errfile</b> on line $errline. ";
        Echo "<p>» $errorText<br>";
        If($cfgMail) Echo "Údaje o chybě byly zaslány našim technikům a budeme ji co nejdříve řešit. Omlouváme se za potíže. <br>&nbsp;";        
    }    

    Return True;
}
//set_error_handler("myErrorHandler", error_reporting());


Function bugReport($text){ #dříve odesliChybu()
        /* -- Config -- */
        $emailAdr = "";
        
        /* -- Function body -- */
        $errorText = "Na stránce $_SERVER[HTTP_HOST]"."$_SERVER[REQUEST_URI] se vyskytla chyba: \n$text \n";
        $errorText.= "\n \n-- Výpis GET hodnot --\n";
        Foreach($_GET as $key=>$value){ 
                If( is_array($value) )
                    Foreach($value as $key2=>$value2) $errorText.= "$key»$key2: '$value2'\n"; 
                Else $errorText.= "$key: '$value'\n";
        }
                
        $errorText.= "\n \n-- Výpis POST hodnot --\n";
        Foreach($_POST as $key=>$value){ 
                If( is_array($value) )
                    Foreach($value as $key2=>$value2) $errorText.= "$key»$key2: '$value2'\n"; 
                Else $errorText.= "$key: '$value'\n";
        }
        
        $errorText.= "\n \n-- Výpis COOKIE hodnot --\n";
        Foreach($_COOKIE as $key=>$value){ 
                If( is_array($value) )
                    Foreach($value as $key2=>$value2) $errorText.= "$key»$key2: '$value2'\n"; 
                Else $errorText.= "$key: '$value'\n";
        }     

        $errorText.= "\n \n-- Výpis SESSION hodnot --\n";
        Foreach($_SESSION as $key=>$value){ 
                If( is_array($value) )
                    Foreach($value as $key2=>$value2) $errorText.= "$key»$key2: '$value2'\n"; 
                Else $errorText.= "$key: '$value'\n";
        }
                
        If( mail($emailAdr, "Automaticke hlaseni chyby PHP", $errorText, "Content-Type: text/plain; charset=utf-8\r\n") )
            Return True;
        Else Return False;
}


Function textareaPrint_r($values, $nazev=''){
        Echo '<textarea rows="6" cols="80">';
        If($nazev) Echo "$nazev = ";
        print_r($values);
        Echo '</textarea>';
}
?>