/* -- Hello World! Plugin for FCKEditor -- */


// Create object which can use the FCK.InsertHtml() command
var TextInserter = function(text){
  this.text = text;
}
// Define what happen, when button is pressed
TextInserter.prototype.Execute = function(){
  FCK.InsertHtml(this.text);
}


// Create function
FCKCommands.RegisterCommand('HelloWorldCommand', new TextInserter("<b>Hello world!</b>") );

// Create button
var oHelloWorldItem = new FCKToolbarButton('HelloWorldCommand', "Add 'Hello word!' text");
oHelloWorldItem.IconPath = FCKConfig.PluginsPath + 'helloworld/button.png';
FCKToolbarItems.RegisterItem('HelloWorld', oHelloWorldItem); // 'HelloWorld' is the name used in the Toolbar config.