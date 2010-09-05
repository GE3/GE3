<?php
Function getPozadi(){
        $CONF = $GLOBALS["config"];
        
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
        
        Return "templates/$CONF[vzhled]/pozadi/$pozadi";      
}

Function dnesniDen(){
        $dny=array();
        $dny[1]="Pondělí";
        $dny[2]="Úterý";
        $dny[3]="Středa";
        $dny[4]="Čtvrtek";
        $dny[5]="Pátek";
        $dny[6]="Sobota";
        $dny[0]="Neděle";
        Return $dny[date("w")];
}

Function getProhlizec($userAgent=''){
        //(funkce převzata z http://programy.wz.cz/clanky/24-php-zjisteni-prohlizece-a-os-z-useragent/)
        $ua = $userAgent? $userAgent: $_SERVER["HTTP_USER_AGENT"];        
      	$BrowserList =array("Internet Explorer \\1" => "#MSIE ([a-zA-Z0-9\.]+)#i",
                          	"Mozilla Firefox \\2" => "#(Firefox|Phoenix|Firebird)/([a-zA-Z0-9\.]+)#i",
                          	"Opera \\1" => "#Opera[ /]([a-zA-Z0-9\.]+)#i",
                          	"Netscape \\1" => "#Netscape[0-9]?/([a-zA-Z0-9\.]+)#i",
                          	"Safari \\1" => "#Safari/([a-zA-Z0-9\.]+)#i",
                          	"Flock \\1" => "#Flock/([a-zA-Z0-9\.]+)#i",
                          	"Epiphany \\1" => "#Epiphany/([a-zA-Z0-9\.]+)#i",
                          	"Konqueror \\1" => "#Konqueror/([a-zA-Z0-9\.]+)#i",
                          	"Maxthon \\1" => "#Maxthon ?([a-zA-Z0-9\.]+)?#i",
                          	"K-Meleon \\1" => "#K-Meleon/([a-zA-Z0-9\.]+)#i",
                          	"Lynx \\1" => "#Lynx/([a-zA-Z0-9\.]+)#i",
                          	"Links \\1" => '#Links .{2}([a-zA-Z0-9\.]+)#i',
                          	"ELinks \\3" => '#ELinks([/ ]|(.{2}))([a-zA-Z0-9\.]+)#i',
                          	"Debian IceWeasel \\1" => "#(iceweasel)/([a-zA-Z0-9\.]+)#i",
                          	"Mozilla SeaMonkey \\1" => "#(SeaMonkey)/([a-zA-Z0-9\.]+)#i",
                          	"OmniWeb" => "#OmniWeb#i",
                          	"Mozilla \\1" => "#^Mozilla/5\.0.*rv:([a-zA-Z0-9\.]+).*#i",
                          	"Netscape Navigator \\1" => "#^Mozilla/([a-zA-Z0-9\.]+)#i",
                          	"PHP" => "/PHP/i",
                          	"SymbianOS \\1" => "#symbianos/([a-zA-Z0-9\.]+)#i",
                          	"Avant Browser" => "/avantbrowser\.com/i",
                          	"Camino \\1" => "#(Camino|Chimera)[ /]([a-zA-Z0-9\.]+)#i",
                          	"Anonymouse" => "/anonymouse/i",
                          	"Danger HipTop" => "/danger hiptop/i",
                          	"W3M \\1" => "#w3m/([a-zA-Z0-9\.]+)#i",
                          	"Shiira \\1" => "#Shiira[ /]([a-zA-Z0-9\.]+)#i",
                          	"Dillo \\1" => "#Dillo[ /]([a-zA-Z0-9\.]+)#i",
                          	"Openwave UP.Browser \\1" => "#UP.Browser/([a-zA-Z0-9\.]+)#i",
                          	"DoCoMo \\1" => "#DoCoMo/(([a-zA-Z0-9\.]+)[/ ]([a-zA-Z0-9\.]+))#i",
                          	"Unbranded Firefox \\1" => "#(bonecho)/([a-zA-Z0-9\.]+)#i",
                          	"Kazehakase \\1" => "#Kazehakase/([a-zA-Z0-9\.]+)#i",
                          	"Minimo \\1" => "#Minimo/([a-zA-Z0-9\.]+)#i",
                          	"MultiZilla \\1" => "#MultiZilla/([a-zA-Z0-9\.]+)#i",
                          	"Sony PSP \\2" => "/PSP \(PlayStation Portable\)\; ([a-zA-Z0-9\.]+)/i",
                          	"Galeon \\1" => "#Galeon/([a-zA-Z0-9\.]+)#i",
                          	"iCab \\1" => "#iCab/([a-zA-Z0-9\.]+)#i",
                          	"NetPositive \\1" => "#NetPositive/([a-zA-Z0-9\.]+)#i",
                          	"NetNewsWire \\1" => "#NetNewsWire/([a-zA-Z0-9\.]+)#i",
                          	"Opera Mini \\1" => "#opera mini/([a-zA-Z0-9]+)#i",
                          	"WebPro \\2" => "#WebPro(/([a-zA-Z0-9\.]+))?#i",
                          	"Netfront \\1" => "#Netfront/([a-zA-Z0-9\.]+)#i",
                          	"Xiino \\1" => "#Xiino/([a-zA-Z0-9\.]+)#i",
                          	"Blackberry \\1" => "#Blackberry([0-9]+)?#i",
                          	"Orange SPV \\1" => "#SPV ([0-9a-zA-Z\.]+)#i",
                          	"LG \\1" => "#LGE-([a-zA-Z0-9]+)#i",
                          	"Motorola \\1" => "#MOT-([a-zA-Z0-9]+)#i",
                          	"Nokia \\1" => "#Nokia ?([0-9]+)#i",
                          	"Nokia N-Gage" => "#NokiaN-Gage#i",
                          	"Blazer \\1" => "#Blazer[ /]?([a-zA-Z0-9\.]*)#i",
                          	"Siemens \\1" => "#SIE-([a-zA-Z0-9]+)#i",
                          	"Samsung \\4" => "#((SEC-)|(SAMSUNG-))((S.H-[a-zA-Z0-9]+)|([a-zA-Z0-9]+))#i",
                          	"SonyEricsson \\1" => "#SonyEricsson ?([a-zA-Z0-9]+)#i",
                          	"J2ME/MIDP Browser" => "#(j2me|midp)#i",
                          	"Neznámý" => "/(.*)/" );
      	Foreach($BrowserList as $Browser => $regexp){
            		If( preg_match($regexp, $ua, $matches) ){
              			If( $matches ) 
                        For($i=0;$i<=count($matches);$i++) $Browser=str_replace("\\$i",$matches[$i],$Browser);
              			    break;
            		}
      	}
      	Return trim($Browser);
}
?>
