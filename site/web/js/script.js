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

/* --------------------------------------------------
   Twitter feed widget
   ----------------------------------------------- */
function getTweets(user,limit) {
  // Create a Script Tag
  var script=document.createElement('script');
  script.type='text/javascript';
  script.src= "https://api.twitter.com/1/statuses/user_timeline.json?screen_name="+user+
    "&count="+limit+
    "&callback=processTweets&_="+ new Date().getTime();
  // Add the Script to the Body element, which will in turn load the script and run it.
  $("body").append(script);
}

function wrapHash(s) {
  var hashRegex = /#.+?(\s|$)/gi;
  var matches = s.match(hashRegex);
  var tag = '<a href="http://twitter.com/search/?q={0}&src=hash" target="_blank">{1}</a>';
  if( matches==null ) {
    return s;
  }
  for( var i=0, len = matches.length; i<len; i++) {
    matches[i] = matches[i].replace(/^\s+|\s+$/g,'');
    var cleaned = matches[i].replace('#', '%23' );
    var to = tag.format(cleaned,matches[i]);
    s = s.replace(matches[i], to);
  }
  return s;
}

function wrapURL(s) {
  var urlRegex = /(f|ht)tps?:\/\/.+?(\s|$)/gi;
  var matches = s.match(urlRegex);
  if( matches==null ) {
    return s;
  }
  for( var i=0, len = matches.length; i<len; i++) {
    matches[i] = matches[i].replace(/^\s+|\s+$/g,'');
    var to = '<a href="' + matches[i] + '" target="_blank">' + matches[i]+ "</a>";
    s = s.replace(matches[i], to);
  }
  return s;
}

function processTweets(jsonData){
  var shtml = '';
  if(jsonData){
    // if there are results (it should be an array), loop through it with a jQuery function
    var wrapper = "<p class='tweet'><span class='author'><a href='http://twitter.com/{0}' target='_blank'>@{0}</a></span>: {1}</p>";
    $.each(jsonData, function(index,value){
      shtml += wrapper.format( value.user.screen_name, wrapHash(wrapURL(value.text)));
    });

    // Load the HTML in the #tweet_stream div
    $("#tweets").html( shtml ).addClass('loaded');
  } else {
    $("#tweets").html( "Sorry. No tweets were available." );
  }
}

String.prototype.format = function() {
  var args = arguments;
  return this.replace(/{(\d+)}/g, function(match, number) { 
    return typeof args[number] != 'undefined'
      ? args[number]
      : match
    ;
  });
};

var SearchBox = {
  resultsBox: $('<div class="search-results" style="position: absolute; width: 600px; border: 1px solid #000; background: #eee; z-index: 10;"></div>'),
  url: 'search/autocomplete',

  init: function(id) {
    this.inputBox = $(id);
    this.form = this.inputBox.closest('form');
    this.inputBox.after(this.resultsBox.hide());
    this.inputBox.on('keyup mouseup change', null, this, function(event) {
      event.data.suggest()
    });
    this.resultsBox.mouseup(this, function(event) {
      event.data.guessSelect(event.target.innerHTML);
    });
    return this;
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
      this.resultsBox.slideUp();
    }
  },
  guessSelect: function(item) {
    this.inputBox.val(item);
    this.form.submit();
  }
};

// create a prototypal constructor for our SearchBox object
function searchBox(id) {
  function F() {};
  F.prototype = SearchBox;
  var f = new F();
  f.init(id);
  return f;
}

$(function() {
  // SearchBox.init('#results-search-input');
  // SearchBox.init('#search-input');
  // var search1 = SearchBox.initialize('#search-input');
  // var search2 = SearchBox.initialize('#results-search-input');
  var search1 = searchBox('#search-input');
  search1.init('#search-input');
  var search2 = searchBox('#results-search-input');
  search2.init('#results-search-input');
});