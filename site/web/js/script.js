/* Author: 

*/

window.onload = function () {
	emailDecode();
}

/* --------------------------------------------------
   Convert all anchor tags mailto: contents
   that have a obf class name
   ----------------------------------------------- */
function emailDecode() {
  var anchor = document.getElementsByTagName('a');
  var map = rot13init();
  for( var l=0 ; l < anchor.length ; l++ ) {
    if( anchor[l].className.indexOf('obf') > -1 ) {
      var href = anchor[l].getAttribute('href');
      var address = href.replace(/.*mail\/([a-z0-9._%-]+)\+([a-z0-9._%-]+)\+([a-z.]+)(.+)/i, '$1' + '@' + '$2' + '.' + '$3$4' );
      if( href != address ) {
        params = address.indexOf('?');
        var extra = '';
        if( params > -1 ) {
          extra   = address.substr(params);
          address = address.substr(0,params);
        }
        var newhref = 'mailto:' + str_rot13(address,map) + extra;
        anchor[l].setAttribute('href', newhref);
      }
    }
  }
}

function rot13init() {
  var map = new Array();
  var s = 'abcdefghijklmnopqrstuvwxyz';
  for( var i=0 ; i < s.length ; i++ ) {
    map[s.charAt(i)] = s.charAt((i+13)%26);
  }
  for( var i=0 ; i < s.length ; i++ ) {
    map[s.charAt(i).toUpperCase()] = s.charAt((i+13)%26).toUpperCase();
  }
  return map;
}

function str_rot13( a, map ) {
  var s = "";
  for( var i=0 ; i < a.length ; i ++ ) {
    var b = a.charAt(i);
    s += (b>='A' && b<='Z' || b>='a' && b<='z' ? map[b] : b );
  }
  return s;
}
