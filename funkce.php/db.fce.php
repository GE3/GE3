<?php
Include_once 'debug.fce.php';


Function Db_query($dotaz, $sendErrors=True){ #přejmenovat na queryPlus()
        //(dodělat pojistku proti DELETE all, UPDATE all)
        $return = @Mysql_query($dotaz);
        If( mysql_error() ){
            // Mail error text
            if( $sendErrors ){
                bugReport("Chyba SQL: ".mysql_error()." \nV dotazu: $dotaz"); #přejmenovat na bugReport()
            }
            
            // User error text
            $errorText = "Chyba SQL: ".mysql_error()." <br>&nbsp;<br>V dotazu: $dotaz";
            If($sendErrors) $errorText.= "<br>&nbsp;<br>Chyba byla automaticky odeslána našim technikům a budeme ji co nejdříve řešit. Omlouváme se za potíže.";
            Die($errorText);
        }
        
        Return $return;
}

//zjisti_z()
Function zjistiZ($tabulka, $udaj, $podminka){
         $dotaz = Db_query("SELECT $udaj FROM $tabulka WHERE $podminka");
         $radek = @Mysql_fetch_assoc($dotaz);

         If(!mysql_error()) Return $radek[$udaj];
         If( mysql_error()) Return False;
}
?>