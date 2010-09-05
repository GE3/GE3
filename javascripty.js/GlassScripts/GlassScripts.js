/*******************************************/
/* .-------------------------------------. */
/* | Glass scripts                       | */
/* |-------------------------------------| */
/* |       Version: 0.2.0                | */
/* | Last updating: 27.9.2009            | */
/* |-------------------------------------| */
/* |        Author: Michal Mikoláš       | */
/* |          Firm: Grafart studio       | */
/* |                www.grafartstudio.cz | */
/* '-------------------------------------' */
/*******************************************/
/**
 * Poznámky: 
 *  - 
 */ 



/******************************************************************************/
/*** JÁDRO ********************************************************************/
/******************************************************************************/
/** 
 * Následují funkce a algoritmy, na které pak navazují ostatní skripty 
 *  v tomto souboru
 **/

/******************/
/* GETELEMENTBYID */
/******************/
function $(id){
        element = null;
        try{
          element = document.getElementById(id);
          return element;
        }catch(e){
          return false;
        }
}
 
 
/*************/
/* ADD EVENT */
/*************/ 
function addEvent(obj, event, funct) {  
        if( obj.attachEvent ){ //IE  
            obj['e' + event + funct] = funct;  
            obj['x' + event + funct] = function() {  
                  obj['e' + event + funct](window.event);  
                }  
            obj.attachEvent('on' + event, obj['x' + event + funct]);  
        } else // other browser  
          obj.addEventListener(event, funct, false);  
}


/***************************/
/* ZACHYCOVÁNÍ POZICE MYŠI */
/***************************/
function getMousePosX(e){
        if(navigator.appName=='Microsoft Internet Explorer'){
           mouseX = event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
        }
        else{
             mouseX=e.pageX;
        }

        return mouseX ;
}
function getMousePosY(e){
        if(navigator.appName=='Microsoft Internet Explorer'){
           mouseY=event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
        }
        else{
             mouseY = e.pageY;
        }

        return mouseY ;
}
function mousePosition(e){
        mouseX = getMousePosX(e);
        mouseY = getMousePosY(e);
}
function getMouseX(){
        return mouseX;
}
function getMouseY(){
        return mouseY;
}
addEvent(document, "mousemove", mousePosition);



/******************************************************************************/
/*** SKRIPTY ******************************************************************/
/******************************************************************************/
/** 
 * Následují uživatelem použitelné js funkce
 **/


/**************/
/* UKAZ SKRYJ */
/**************/
function ukazSkryj(element){
    var srcElement = document.getElementById(element);

    if( srcElement!=null ){
        if( srcElement.style.display == "block" ){
            srcElement.style.display = 'none';
        }
        else{
            srcElement.style.display = 'block';
        }
        return true;
    }
    else{
        return false;}
}



/*********************/
/* MAX HEIGHT, WIDTH */
/*********************/
function jsMaxHeight(objekt, cislo){ //objekt.style.height=((objekt.scrollHeight > cislo)? (cislo+"px") : "auto");
        width = objekt.style.width? objekt.style.width.replace("px","")*1: objekt.offsetWidth;
        height = objekt.style.height? objekt.style.height.replace("px","")*1: objekt.offsetHeight;

        if( objekt && height>cislo ){
         	  zmenseni = cislo/height; 
            objekt.style.height = cislo+"px";
            objekt.style.width = (zmenseni*width)+"px";
        }
}
function jsMaxWidth(objekt, cislo){ //objekt.style.width=((objekt.scrollWidth > cislo)? (cislo+"px") : "auto");
        width = objekt.style.width? objekt.style.width.replace("px","")*1: objekt.offsetWidth;
        height = objekt.style.height? objekt.style.height.replace("px","")*1: objekt.offsetHeight;

        if( objekt && width>cislo ){
         	  zmenseni = cislo/width;
            objekt.style.width = cislo+"px";
         	  objekt.style.height = (zmenseni*height)+"px";
        }
}



/****************/
/* ODPOČÍTÁVÁNÍ */
/****************/
function odpocitavani(sekund, divId){
        sekund = sekund-1;
        document.getElementById(divId).innerHTML = sekund+1;
        if( sekund>(-1) ) setTimeout("odpocitavani('"+sekund+"','"+divId+"')", 1000);
}



/*************/
/* POPUP DIV */
/*************/
function popupDivInit(){
        var divElement;  
        divElement = document.createElement('div');  
        divElement.setAttribute('id', 'divPopupClona');
          
        divElement.style.position = 'absolute'; 
        divElement.style.top = '0px';
        divElement.style.left = '0px';
        divElement.style.display = 'none';  
        divElement.style.textAlign = 'center'; 
        
        /*
        <div id="divPopupRamecek">
          <div id="divPopupRamecekTop">&nbsp;</div>
          <div id="divPopupRamecekStred">
        
            <div id="divPopupVnitrni"> 
              <div id="divPopupClose" onClick="popupDivOff()" title="Zavřít">X
              </div>
              <div id="divPopupTitle" onMouseDown="posunStart(\'divPopupRamecek\');" onMouseUp="posunStop();" title="Přesunout">
                &nbsp;
              </div>
              <div class="clear"></div>
               
              <div id="divPopupObsah"> 
                Chyba v java scriptech. 
              </div> 
            </div>
        
          </div>
          <div id="divPopupRamecekBottom">&nbsp;</div>
        </div>
        */
        divElement.innerHTML = '<div id="divPopupRamecek"><div id="divPopupRamecekTop">&nbsp;</div><div id="divPopupRamecekStred"><div id="divPopupVnitrni"><div id="divPopupClose" onClick="popupDivOff()" title="Zavřít">X</div><div id="divPopupTitle" onMouseDown="posunStart(\'divPopupRamecek\');" onMouseUp="posunStop();" title="Přesunout"> &nbsp;</div><div class="clear"></div><div id="divPopupObsah"> Chyba v java scriptech.</div></div></div><div id="divPopupRamecekBottom">&nbsp;</div></div>';
        document.body.appendChild(divElement);                
}
addEvent(window, "load", popupDivInit);


function popupDiv(title, text){
        document.getElementById('divPopupClona').style.display = 'block';
        document.getElementById('divPopupClona').style.width = document.body.scrollWidth+'px';  //"100%"
        document.getElementById('divPopupClona').style.height = document.body.scrollHeight+'px';  //document.height; document.body.clientHeight
        
        document.getElementById('divPopupRamecek').style.marginTop = (document.body.scrollTop+60)+"px";
        document.getElementById('divPopupRamecek').style.position = "relative";
        document.getElementById('divPopupRamecek').style.top = "0px";
        document.getElementById('divPopupRamecek').style.left = "0px";           
        
        document.getElementById('divPopupTitle').innerHTML = title? title: '&nbsp;';
        document.getElementById('divPopupObsah').innerHTML = text;
}


function popupDivOff(){
        document.getElementById('divPopupClona').style.display='none';
}



/*****************************/
/* ZMĚNA POZITE TAŽENÍM MYŠÍ */
/*****************************/
posunId = 0;
mousePuvodniX = 0;
mousePuvodniY = 0;
elementPuvodniX = 0;
elementPuvodniY = 0;
function posunStart(id){
        posunId = id;
        mousePuvodniX = getMouseX();
        mousePuvodniY = getMouseY();
        elementPuvodniX = document.getElementById(id).style.left.replace("px","") * 1;
        elementPuvodniY = document.getElementById(id).style.top.replace("px","") * 1; 
        if( document.getElementById(id).style.position!='relative' || document.getElementById(id).style.position!='absolute' )
            document.getElementById(id).style.position = 'relative';            
        document.getElementById(id).onmousemove = zmenPozici;
}
function posunStop(){
        posunId = 0;
}
function zmenPozici(){
        if( posunId!=0 ){
            document.getElementById(posunId).style.left = ( elementPuvodniX + getMouseX()-mousePuvodniX ) + "px";
            document.getElementById(posunId).style.top = ( elementPuvodniY + getMouseY()-mousePuvodniY ) + "px";
        }
}



/*************/
/* EASY AJAX */
/*************/
function easyAjax(souborUrl, postVariables, divId){
        // Vytvoření Ajax objektu
        if( window.ActiveXObject ){
            ajax = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if(window.XMLHttpRequest){
                ajax = new XMLHttpRequest();
        }

        // Získání odpovědi ze souboru
        ajax.open("POST", souborUrl, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function(){
                                         document.getElementById(divId).innerHTML = "loading...";
                                         if( ajax.readyState==4 ){
                                             if( ajax.status==200 ){
                                                 // Zobrazení obsahu
                                                 document.getElementById(divId).innerHTML = ajax.responseText;
                                                 // Provedení javascripů
                                                 var scripts = ajax.responseText.replace(/\r/g, "");
                                                 scripts = scripts.replace(/\n/g, "");
                                                 scripts = scripts.replace(/^.*<script type="text\/javascript">(.*)<\/script>.*$/im, "$1");
                                                 eval(scripts);                                                  
                                             }
                                             else if(ajax.status==404){
                                                 document.getElementById(divId).innerHTML = '&nbsp;<br><b>&nbsp;Chyba 404:</b> Požadovaná stránka \''+souborUrl+'\' neexistuje!<br>&nbsp;';
                                             }
                                             else{
                                                 document.getElementById(divId).innerHTML = '&nbsp;<br><b>&nbsp;Chyba '+ajax.status+'</b><br>&nbsp;';
                                             }
                                         }
                                     };
        ajax.send(postVariables);
}
function easyAjax2(souborUrl, postVariables, divId){
        // Vytvoření Ajax objektu
        if( window.ActiveXObject ){
            ajax2 = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if(window.XMLHttpRequest){
                ajax2 = new XMLHttpRequest();
        }

        // Získání odpovědi ze souboru
        ajax2.open("POST", souborUrl, true);
        ajax2.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax2.onreadystatechange = function(){
                                         document.getElementById(divId).innerHTML = "loading...";
                                         if( ajax2.readyState==4 ){
                                             if( ajax2.status==200 ){
                                                 // Zobrazení obsahu
                                                 document.getElementById(divId).innerHTML = ajax2.responseText;
                                                 // Provedení javascripů
                                                 var scripts = ajax2.responseText.replace(/\r/g, "");
                                                 scripts = scripts.replace(/\n/g, "");
                                                 scripts = scripts.replace(/^.*<script type="text\/javascript">(.*)<\/script>.*$/im, "$1");
                                                 eval(scripts);                                                  
                                             }
                                             else if(ajax2.status==404){
                                                 document.getElementById(divId).innerHTML = '&nbsp;<br><b>&nbsp;Chyba 404:</b> Požadovaná stránka \''+souborUrl+'\' neexistuje!<br>&nbsp;';
                                             }
                                             else{
                                                 document.getElementById(divId).innerHTML = '&nbsp;<br><b>&nbsp;Chyba '+ajax2.status+'</b><br>&nbsp;';
                                             }
                                         }
                                     };
        ajax2.send(postVariables);
}
function easyAjax3(souborUrl, postVariables, divId){
        // Vytvoření Ajax objektu
        if( window.ActiveXObject ){
            ajax3 = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if(window.XMLHttpRequest){
                ajax3 = new XMLHttpRequest();
        }

        // Získání odpovědi ze souboru
        ajax3.open("POST", souborUrl, true);
        ajax3.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax3.onreadystatechange = function(){
                                         document.getElementById(divId).innerHTML = "loading...";
                                         if( ajax3.readyState==4 ){
                                             if( ajax3.status==200 ){
                                                 // Zobrazení obsahu
                                                 document.getElementById(divId).innerHTML = ajax3.responseText;
                                                 // Provedení javascripů
                                                 var scripts = ajax3.responseText.replace(/\r/g, "");
                                                 scripts = scripts.replace(/\n/g, "");
                                                 scripts = scripts.replace(/^.*<script type="text\/javascript">(.*)<\/script>.*$/im, "$1");
                                                 eval(scripts);                                                  
                                             }
                                             else if(ajax3.status==404){
                                                 document.getElementById(divId).innerHTML = '&nbsp;<br><b>&nbsp;Chyba 404:</b> Požadovaná stránka \''+souborUrl+'\' neexistuje!<br>&nbsp;';
                                             }
                                             else{
                                                 document.getElementById(divId).innerHTML = '&nbsp;<br><b>&nbsp;Chyba '+ajax3.status+'</b><br>&nbsp;';
                                             }
                                         }
                                     };
        ajax3.send(postVariables);
}



/*******************/
/* EASY AJAX POPUP */
/*******************/
function easyAjaxPopup(title, souborUrl, postVariables){
        // Vytvoření Ajax objektu
        if( window.ActiveXObject ){
            ajax = new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if(window.XMLHttpRequest){
                ajax = new XMLHttpRequest();
        }

        // Získání odpovědi ze souboru
        ajax.open("POST", souborUrl, true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        ajax.onreadystatechange = function(){
                                         popupDiv(title, '&nbsp;<br><b>&nbsp;loading...</b><br>&nbsp;');
                                         if( ajax.readyState==4 ){
                                             if( ajax.status==200 ){ 
                                                 // Zobrazení obsahu
                                                 popupDiv(title, ajax.responseText);
                                                 // Provedení javascripů
                                                 var scripts = ajax.responseText.replace(/\r/g, "");
                                                 scripts = scripts.replace(/\n/g, "");
                                                 scripts = scripts.replace(/^.*<script type="text\/javascript">(.*)<\/script>.*$/im, "$1");
                                                 eval(scripts);
                                             }
                                             else if(ajax.status==404){
                                                 popupDiv(title, '&nbsp;<br><b>&nbsp;Chyba 404:</b> Požadovaná stránka \''+souborUrl+'\' neexistuje!<br>&nbsp;');
                                             }
                                             else{
                                                 popupDiv(title, '&nbsp;<br><b>&nbsp;Chyba '+ajax.status+'</b><br>&nbsp;');
                                             }
                                         }
                                     };
        ajax.send(postVariables);
}



/*************/
/* COTOJATKO */
/*************/
function cotojatkoInit(){
        var divElement;  
        divElement = document.createElement('div');  
        divElement.setAttribute('id', 'divCotojatkoCover');
          
        divElement.style.position = 'absolute'; 
        divElement.style.top = '0px';
        divElement.style.left = '0px';
        divElement.style.display = 'none';  
        divElement.style.textAlign = 'center'; 
        
        divElement.innerHTML = '<div id="divCotojatkoObsah"></div>';
        document.body.appendChild(divElement);                
}
addEvent(window, "load", cotojatkoInit);

var cotojatkoTitleZaloha = '';
var cotojatkoElementZaloha = null;
function cotojatko(element){

        // Záloha title (title se bude nulovat)
        if( element.title!='' ){
            cotojatkoElementZaloha = element;
            cotojatkoTitleZaloha = element.title;         
            element.title = '';
        }
        
        // Zobrazení tooltipu
        document.getElementById('divCotojatkoObsah').innerHTML = cotojatkoTitleZaloha;        
        
        document.getElementById('divCotojatkoCover').style.display = 'block';
        document.getElementById('divCotojatkoCover').style.zIndex = 9;        
        document.getElementById('divCotojatkoCover').style.left = getMouseX()+12+'px';
        document.getElementById('divCotojatkoCover').style.top = getMouseY()-8+'px';
        addEvent(element, "mouseout", cotojatkoOff);
}

function cotojatkoOff(){
        // Obnovení původního title
        cotojatkoElementZaloha.title = cotojatkoTitleZaloha;
        // Vynulování tooltipu
        document.getElementById('divCotojatkoCover').style.display = 'none';
        document.getElementById('divCotojatkoCover').style.zIndex = 0;         
        document.getElementById('divCotojatkoCover').style.left = 0+'px';
        document.getElementById('divCotojatkoCover').style.top = 0+'px';
        document.getElementById('divCotojatkoObsah').innerHTML = '';
        cotojatkoTitleZaloha = '';
        cotojatkoElementZaloha = null;
}



/**************/
/* POPUP MENU */
/**************/
function popupMenuInit(){
        var divElement;  
        divElement = document.createElement('div');  
        divElement.setAttribute('id', 'divPopupMenu');
          
        divElement.style.position = 'absolute'; 
        divElement.style.top = '0px';
        divElement.style.left = '0px';
        divElement.style.display = 'none';  
        divElement.style.textAlign = 'center'; 
        
        document.body.appendChild(divElement);                
}
addEvent(window, "load", popupMenuInit);


var popupMenuTimer;
function popupMenu(odkazy){
        element = document.getElementById('divPopupMenu');
        element.style.left = getMouseX()+12+'px';
        element.style.top = getMouseY()-6+'px';
        element.style.display = 'block';
        
        // Nahrazení náhradních uvozovek
        odkazy = odkazy.replace(/´´/g, '"');
        odkazy = odkazy.replace(/´/g, '\'');

        // Problémy s nechtěným skrýváním
        element.innerHTML = odkazy.replace(/<a/g, '<a onMouseOver="clearTimeout(popupMenuTimer);"');
        
        element.onmouseout = function(){
                                     popupMenuTimer = setTimeout("popupMenuOff()", 150);
                             };
        element.onmouseover = function(){
                                     clearTimeout(popupMenuTimer);
                             };                             
}

function popupMenuOff(){
        element = document.getElementById('divPopupMenu');
        element.innerHTML = '';
        element.style.top = '0px';
        element.style.left = '0px';
        element.style.display = 'none';
}



/*****************/
/* POPUP TOOLTIP */
/*****************/
popupTooltipCasovac = false;
function popupTooltip(text, width, time){
        eTablePopupTooltip = $('tablePopupTooltip');
        
        $('divPopupTooltip').innerHTML = text;

        eTablePopupTooltip.style.position = 'absolute';
        eTablePopupTooltip.style.top = '-800px';        
        eTablePopupTooltip.style.display = 'block';
        eTablePopupTooltip.style.top = (-1)*(eTablePopupTooltip.offsetHeight+10) + 'px';
        eTablePopupTooltip.style.right = '0px';
        if(width>20) eTablePopupTooltip.style.width = width+'px';
        
        addEvent(eTablePopupTooltip, "mouseover", popupTooltipStopTimeout);
        addEvent(eTablePopupTooltip, "mouseout", popupTooltipGoTimeout);
        addEvent(eTablePopupTooltip, "click", skryjPopupTooltip);
        
        ukazPopupTooltip();
        if(time>=1) popupTooltipCasovac = setTimeout("skryjPopupTooltip()", time*1000);
}

function ukazPopupTooltip(){
        eTablePopupTooltip = $('tablePopupTooltip');

        eTablePopupTooltip.style.top = (eTablePopupTooltip.style.top.replace("px","")*1)+10 + 'px';        

        if( (eTablePopupTooltip.style.top.replace("px","")*1)<0 ) 
            setTimeout("ukazPopupTooltip()", 50);
}
function skryjPopupTooltip(){
        eTablePopupTooltip = $('tablePopupTooltip');

        eTablePopupTooltip.style.top = (eTablePopupTooltip.style.top.replace("px","")*1)-10 + 'px';        

        if( (eTablePopupTooltip.style.top.replace("px","")*1)>=(eTablePopupTooltip.offsetHeight*(-1)) ) 
            popupTooltipCasovac = setTimeout("skryjPopupTooltip()", 50);
}
function popupTooltipStopTimeout(){
        clearTimeout(popupTooltipCasovac);
}
function popupTooltipGoTimeout(){
        popupTooltipCasovac = setTimeout("skryjPopupTooltip()", 5*1000);
}