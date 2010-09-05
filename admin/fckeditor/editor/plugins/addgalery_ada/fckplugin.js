/*******************************************/
/* .-------------------------------------. */
/* | Add Galery                          | */
/* |  plugin for FCKEditor               | */
/* |-------------------------------------| */
/* |       Version: 0.1.0                | */
/* | Last updating: 31.8.2009            | */
/* |-------------------------------------| */
/* |        Author: Michal Mikoláš       | */
/* |                MichalMT@seznam.cz   | */
/* '-------------------------------------' */
/*******************************************/

// Vytvoření funkce
FCKCommands.RegisterCommand( 'addgaleryCommand' , new FCKDialogCommand( 'addgalery', 'Vyberte galerii', FCKConfig.PluginsPath + 'addgalery_ada/addgalery.php', 614, 464 ) ) ;

// Vytvoření tlačítka
var oAddGaleryItem = new FCKToolbarButton( 'addgaleryCommand', "Vložit galerii" ) ;
oAddGaleryItem.IconPath = FCKConfig.PluginsPath + 'addgalery_ada/tlacitko.png' ;
FCKToolbarItems.RegisterItem( 'AddGalery', oAddGaleryItem ) ; // 'AddGalery' is the name used in the Toolbar config.
