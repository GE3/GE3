/** 
 * Oprava max-width a max-height
 */
$(function(){  
  $('img').each(function(){
                          old_css_maxHeight = ($(this).css('maxHeight')).replace('px', '');
                          old_css_maxWidth = ($(this).css('maxWidth')).replace('px', '');
                          if( old_css_maxHeight>0 && old_css_maxWidth>0 ){
                              //záloha starých hodnot
                              old_css_height = $(this).css('height');
                              old_css_width = $(this).css('width');
                              old_css_maxHeight = ($(this).css('maxHeight')).replace('px', '');
                              old_css_maxWidth = ($(this).css('maxWidth')).replace('px', '');
                              old_html_height = $(this).attr('height');
                              old_html_width = $(this).attr('width'); 
                              
                              //odstranění hodnot
                              $(this).css('height', 'auto');
                              $(this).css('width', 'auto');
                              $(this).css('maxHeight', '');
                              $(this).css('maxWidth', '');
                              $(this).attr('height', '');
                              $(this).attr('width', '');
                              
                              //zjištění velikosti obrázku  
                              max_height = $(this).outerHeight();
                              max_width = $(this).outerWidth();
                              
                              //nastavení nových rozměrů
                              pomer_height = max_height / old_css_maxHeight;
                              pomer_width = max_width / old_css_maxWidth;
                              if( pomer_height > pomer_width && pomer_height > 1 ){
                                  //zmenšování podle height
                                  $(this).css('height', old_css_maxHeight+'px');
                                  $(this).css('width', Math.round(max_width/pomer_height)+'px');
                              }
                              else if(pomer_width>1){
                                  //zmenšování podle width
                                  $(this).css('width', old_css_maxWidth+'px');
                                  $(this).css('height', Math.round(max_height/pomer_width)+'px');
                              }
                              else{
                                  //není co zmenšovat
                              }                               

                          }
                        });
});



/**
 * implementace lightboxu pro bloky s třídou `gallery`
 */ 
$(function(){
  $('.gallery a').lightBox({fixedNavigation:true});
});