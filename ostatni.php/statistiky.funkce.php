<?php
Function jeRobot(){
        //(funkce převzata z http://seo.nawebu.cz/200301/0287.html)
        $robot = 0;
        $agent_test = " " . StrToLower ($_SERVER["HTTP_USER_AGENT"]);

        If( !StrPos($agent_test,"mozilla") &&
            !StrPos($agent_test,"opera") &&
            !StrPos($agent_test,"links") &&
            !StrPos($agent_test,"lynx") &&
            !StrPos($agent_test,"icab") &&
            !StrPos($agent_test,"reqwireless") ) $robot=1;
        Else{
          If( StrPos($agent_test,"@") ) $robot=1;
          If( StrPos($agent_test,"search") ) $robot=1;
          If( StrPos($agent_test,"crawl") ) $robot=1;
          If( StrPos($agent_test,"bot") ) $robot=1;
          If( StrPos($agent_test,"spider") ) $robot=1;
          If( StrPos($agent_test,"jeeves") ) $robot=1;
        }

        Return $robot;
}



Function getNazevRobota($userAgent=''){
        $user_agent = $userAgent? $userAgent: $_SERVER["HTTP_USER_AGENT"]; 
        
        $nazev = 'neznámý [<span title="'.str_replace('"','´',$user_agent).'" style="cursor: pointer;" onMouseOver="this.style.textDecoration=\'underline\';" onMouseOut="this.style.textDecoration=\'none\';">?</span>]';
        
        If( eregi("googlebot-image", $user_agent) ) $nazev = '<a href="http://images.google.cz/imghp?hl=cs&tab=wi" target="_blank" style="text-decoration: none;">Google-Images</a>';
        Elseif( eregi("google", $user_agent) ) $nazev = '<a href="http://www.google.cz" target="_blank" style="text-decoration: none;">Google</a>';
        Elseif( eregi("seznam", $user_agent) ) $nazev = '<a href="http://www.seznam.cz" target="_blank" style="text-decoration: none;">Seznam</a>';        
        Elseif( eregi("jyxo", $user_agent) ) $nazev = '<a href="http://www.jyxo.cz" target="_blank" style="text-decoration: none;">Jyxo</a>';
        Elseif( eregi("centrum", $user_agent) ) $nazev = '<a href="http://www.centrum.cz" target="_blank" style="text-decoration: none;">Centrum</a>';
        Elseif( eregi("yahoo", $user_agent) ) $nazev = '<a href="http://www.yahoo.com" target="_blank" style="text-decoration: none;">Yahoo</a>';
        Elseif( eregi("msnbot-media", $user_agent) ) $nazev = '<a href="http://www.msn.com" target="_blank" style="text-decoration: none;">MSN-Media</a>';
        Elseif( eregi("msn", $user_agent) ) $nazev = '<a href="http://www.msn.com" target="_blank" style="text-decoration: none;">MSN</a>';
        
        Return $nazev;                                              
}



Function getOs($userAgent=''){
        //(funkce převzata z http://crazydog.cz/pro-web/php-scripty/zjisteni-operacniho-systemu-v-php/)
        $agent = $userAgent? $userAgent: $_SERVER["HTTP_USER_AGENT"];
        if( strpos($agent, "Win") !== false ) {
            /* Windows */
            if(strpos($agent, "Windows Vista") !== false OR strpos($agent, "Windows NT 6.0") !== false) {
            $os = "Windows Vista";
            } elseif(strpos($agent, "Windows XP") !== false OR strpos($agent, "Windows NT 5.1") !== false) {
            $os = "Windows XP";
            } elseif(strpos($agent, "Windows NT 5.2")) {
            $os = "Windows 2003";
            } elseif(strpos($agent, "Windows NT 5.0") !== false OR strpos($agent, "Windows 2000")) {
            $os = "Windows 2000";
            } elseif(strpos($agent, "Win 9x 4.90") !== false OR strpos($agent, "Windows ME") !== false) {
            $os = "Windows ME";
            } elseif(strpos($agent, "Windows 98") !== false OR strpos($agent, "Win98") OR strpos($agent, "Windows 4.10") !== false) {
            $os = "Windows 98";
            } elseif(strpos($agent, "Windows 95") !== false OR strpos($agent, "Win95") !== false) {
            $os = "Windows 95";
            } elseif(strpos($agent, "Windows CE") !== false) {
            $os = "Windows CE";
            } else {
            $os = "Windows";
            }
        /* Mac */
        } elseif(strpos($agent, "Mac") !== false) {
            if( strpos($agent, "Mac OS X") !== false ) {
                $os = "Mac OS X";
            } else {
                $os = "Macintosh";
            }
        /* Linux */
        } elseif(strpos($agent, "Linux") !== false) {
            if(strpos($agent, "Ubuntu") !== false) {
                $os = "Ubuntu Linux";
            } elseif(strpos($agent, "Kubuntu") !== false) {
                $os = "Kubuntu Linux";
            } elseif(strpos($agent, "Xubuntu") !== false) {
                $os = "Xubuntu Linux";
            } elseif(strpos($agent, "Debian") !== false) {
                $os = "Debian Linux";
            } elseif(strpos($agent, "Fedora") !== false) {
                $os = "Fedora Core Linux";
            } elseif(strpos($agent, "Gentoo") !== false) {
                $os = "Gentoo Linxu";
            } elseif(strpos($agent, "SuSE") !== false OR strpos($agent, "SUSE") !== false) {
                $os = "SuSE Linux";
            } else {
               $os = "Linux";
            }
        /* Symbian */
        } elseif(strpos($agent, "Symbian") !== false OR strpos($agent, "S60") !== false) {
            $os = "Symbian";
        } else {
            $os = "";
        }
        
        Return $os; 
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


