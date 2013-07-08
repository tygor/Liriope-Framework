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

// 
// SearchBox object
// used for autocomplete functionality in the page search input boxes
// 
var SearchBox = {
  url: 'search/autocomplete',

  init: function(id) {
    // get the jQuery version of the passed search input box
    this.inputBox = $(id);
    // create the div for the results to be dumped into and give them a class
    resultsBox = document.createElement('div');
    resultsBox.className = 'search-suggestions';
    // get the jQuery version of the resultsBox
    this.resultsBox = $(resultsBox);
    // get the jQuery version of the parent form element
    this.form = this.inputBox.closest('form');
    // drop in the resultsBox after the search input and hide it.
    this.inputBox.after(this.resultsBox.hide());
    // position the resultsBox
    inputOffset = this.inputBox.position();
    inputHeight = this.inputBox.css("height").replace(/[^-\d\.]/g, '') * 1;
console.log(inputOffset.top);
console.log(inputHeight);
console.log(inputOffset.top + inputHeight + 'px');
    this.resultsBox.css('top', inputOffset.top + inputHeight + 'px');
    this.resultsBox.css('left', inputOffset.left);
    this.resultsBox.css('width', this.inputBox.css('width'));
    // when the inputbox is clicked, types into, or changed, get suggestions
    this.inputBox.on('keyup mouseup change', null, this, function(event) {
      event.data.suggest()
    });
    // when focus is removed from the input box, close the autocomplete box
    this.inputBox.on('blur', this, function(event) {
      event.data.closeSuggestions();
    });
    // when the results are clicked on, fill the input box with the value clicked
    // and fire the submit action.
    this.resultsBox.mouseup(this, function(event) {
      event.data.guessSelect(event.target.innerHTML);
    });
  },
  hasQuery: function() {
    if(this.inputBox.val().length===0) {
      return false;
    }
    return true;
  },
  suggest: function() {
    if(this.hasQuery()) {
      this.resultsBox.load(this.url,{'q': this.inputBox.val()});
      if(this.resultsBox.css('display')==='none') {
        this.resultsBox.slideDown();
      }
    } else {
      this.closeSuggestions();
    }
  },
  guessSelect: function(item) {
    this.inputBox.val(item);
    this.form.submit();
  },
  closeSuggestions: function () {
    this.resultsBox.slideUp();
  }
};

// create a prototypal constructor for our SearchBox object
// this is the IE8 compatiblie version of the new Object.create() method.
function searchBox(id) {
  function F() {};
  F.prototype = SearchBox;
  var f = new F();
  f.init(id);
  return f;
}

$(function() {
  var search1 = new searchBox('#search-input');
  var search2 = new searchBox('#results-search-input');
});