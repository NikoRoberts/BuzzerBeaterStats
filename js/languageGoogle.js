google.load('language', "1");
google.setOnLoadCallback(initialize);
function initialize() {
  var v = {type:'vertical'};
  google.language.getBranding('poweredby');
}