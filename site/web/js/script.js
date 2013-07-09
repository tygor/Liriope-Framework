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
// - only show the suggestions box if minLenght is satisfied
// - if a suggestion is clicked, replace the input box with the suggestions and fire submit()
// - if the input is blurred, close the suggestion box, but not if the blur is because
//   of clicking on the suggestions
// - enable up and down arrows to highlight the suggestions
// - enable tab and enter to utilize the highlighted suggestion in the input box
//   - tab will replace the input text with the suggestion
//   - enter will replace and submit the form
// 
var SearchBox = {
  url: 'search/autocomplete',
  animationSpeed: 100,
  minLength: 2,

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
    // position the results box and listen for window resizing to do the same
    $(window).on('resize', this, function(event) {
      event.data.positionResults();
    });
    // when the inputbox is clicked, types into, or changed, get suggestions
    this.inputBox.on('keyup mouseup change', null, this, function(event) {
      event.data.suggest()
    });
  },
  hasQuery: function() {
    if(this.inputBox.val().length < this.minLength) {
      return false;
    }
    return true;
  },
  suggest: function() {
    if(this.hasQuery()) {
      this.resultsBox.load(this.url,{'q': this.inputBox.val()});
      if(this.resultsBox.css('display')==='none') {
        this.openSuggestions();
      }
    } else {
      this.closeSuggestions();
    }
  },
  guessSelect: function(item) {
    this.inputBox.val(item);
    this.form.submit();
  },
  openSuggestions: function() {
    this.positionResults();
    this.resultsBox.slideDown(this.animationSpeed);
  },
  closeSuggestions: function () {
    this.resultsBox.slideUp(this.animationSpeed);
  },
  positionResults: function() {
    // position the resultsBox
    var inputOffset = this.inputBox.position();
    var inputHeight = this.inputBox.css('height');
    if(inputHeight) {
      var inputHeight = inputHeight.replace(/[^-\d\.]/g, '') * 1;
      this.resultsBox.css('top', inputOffset.top + inputHeight + 'px');
      this.resultsBox.css('left', inputOffset.left);
      this.resultsBox.css('width', this.inputBox.css('width'));
    }
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