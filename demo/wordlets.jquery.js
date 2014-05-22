(function(window, $, undefined) {

$.wordlets = {};

// Allow for overriding form opn method
$.wordlets.open = function(href) {
	window.open(href);
};

$(function() {

var $wordlets = $('[data-wordlet]');
var i = 0;
var length = $wordlets.length;
var $wordlet;
var $edit;
var $configure;
var $container;
var configured;
var name;
var edit_url;
var configure_url;

for ( i = 0; i < length; i++ ) {
	$wordlet = $wordlets.eq(i);
	configured = $wordlet.data('wordlet-configured');
	name = $wordlet.data('wordlet-name');
	edit_url = $wordlet.data('wordlet-edit');
	configure_url = $wordlet.data('wordlet-configure');

	if ( !edit_url && !configure_url ) continue;

	$container = $('<span>')
		.addClass('wordlet_links')
		.prependTo($wordlet);

	if ( edit_url ) {
		$edit = $('<a href="#">')
			.html('Edit ' + name)
			.attr({href: edit_url})
			.addClass('wordlet-edit')
			.appendTo($container)
			;
	}

	if ( configure_url ) {
		$configure = $('<a href="#">')
			.html('Configure ' + name)
			.attr({href: configure_url})
			.addClass('wordlet-configure')
			.appendTo($container)
			;
	}

}

$('body')
	.delegate('[data-wordlet]', 'click', function(e) {
		var href = $(this).find('.wordlet_links a').attr('href');
		if ( !href ) return;
		e.preventDefault();
		e.stopPropagation();
		$.wordlets.open(href);
	})
	.delegate('[data-wordlet] .wordlet_links a', 'click', function(e) {
		var href = $(this).attr('href');
		if ( !href ) return;
		e.preventDefault();
		e.stopPropagation();
		$.wordlets.open(href);
	})
	;
});
})(window, jQuery)