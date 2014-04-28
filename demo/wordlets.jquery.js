(function(window, $, undefined) {
$(function() {

var $wordlets = $('[data-wordlet]');
var i = 0;
var length = $wordlets.length;
var $wordlet;
var $a;
var configured;
var name;

for ( i = 0; i < length; i++ ) {
	$wordlet = $wordlets.eq(i);
	configured = $wordlet.data('wordlet-configured');
	name = $wordlet.data('wordlet-name');
	$a = $('<a href="#">')
		.html((configured?'Edit ':'Add ') + name)
		.addClass('wordlet_link')
		.prependTo($wordlet)
		;

}

});
})(window, jQuery)