// JavaScript Document
// nrhcTemplateSpice.js

jQuery.noConflict();
jQuery(document).ready( function() {
  (function($) {

  /**
   * Remove the no-js body class letting us know that javascript is enabled
   */
  $('html').removeClass('no-js');

	/**
	* Search Box spice
	* set the inital fade to 50% transparent
	* then when clicked, remove the deault value label and fade to 100%
	* and if left empty, when focus is removed, return to inital fade with label
  * ================================================================================
	*/

	// fade the search box text until clicked
	$('#search').fadeTo(0, 0.5);
	defaultValue = $('#search > .inputbox').val();

	// clear the search box when it is clicked and remove fade
	$('#search > .inputbox').click(function() {
		if ( this.value == defaultValue )
		{
			$(this).val("");
			$(this).fadeTo('fast', 1);
		}
	});

	// when focus is removed and if the box is blank, remove to inital state
	$('#search > .inputbox').blur(function() {
		if ( this.value == "" )
		{
			$(this).fadeTo('fast', 0.5, function() {
				$(this).val(defaultValue);
			});
		}
	});

	/**
	* Menu spice
	* hide all child <ul> tags
	* when clicked, toggle reveal on any child <ul> tags
  * ================================================================================
  */

	// prepare hoverIntent config and callback functions
	var hoverMenu = {
		over: slideOpen,
		timeout: 500,
		out: slideClosed
	};

  $( "#global-nav #global-menu li" ).hoverIntent( hoverMenu );
  function slideOpen() { $( this ).find("> ul").slideDown( 375, 'swing' ); };
  function slideClosed() { $( this ).find( "> ul" ).slideUp( 375, 'swing'); };

  // prepare the mobile menu expand

  var globalMenuFullHeight = $("#global-menu").height();
  var globalMenuStartHeight = '3.14em';
  $("#global-menu").css( 'height', globalMenuStartHeight);

  $( "#global-nav-expand" ).toggle(
    function() {
      $( "#global-search" ).slideDown( 375, 'swing' );
      $("#global-menu").animate( { height: globalMenuFullHeight }, 500 );    
    },
    function() {
      $( "#global-search" ).slideUp( 375, 'swing');
      $("#global-menu").animate( { height: globalMenuStartHeight }, 500 );    
    }
  );

	/**
	 * Question Answer pairs
	 * hides the <dd> tags under <dl.question-answer>
   * ================================================================================
	 */

	// find and hide all <dl.question-answer> <dd> tags
	$('dl.question-answer dd').css('display', 'none');

	function qaOpenOrClose(event) {
		$(this).next("dd").slideToggle(200);
	};

	function qaOpenOrCloseSelf(event) {
		$(this).slideToggle(200);
	};

	// when the <dt> is clicked, slide toggle the following <dd> tag
	$('dl.question-answer dt').click(qaOpenOrClose);

	// when the <dd> is clicked, toggle it's slide
	$('dl.question-answer dd').click(qaOpenOrCloseSelf);

  })(jQuery);
});

