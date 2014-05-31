(function(window, $, undefined) {

$.wordlets = {};

// Allow for overriding form opn method
$.wordlets.open = function(href) {
	window.open(href);
};

$(function() {

var $wordlets = $('[data-wordlet]');
var i = 0;
var c = 0;
var length = $wordlets.length;
var $container;
var $body = $('body');
var $last_wordlet;
var $wordlet;
var $child;
var display;
var prevent_open = false;

$container = $('<span>')
	.css({
		position: 'absolute'
	})
	.addClass('wordlet_links')
	.hide()
	.appendTo($body)
	;

// Show container over wordlet
var open_menu = function($wordlet) {
	$last_wordlet = $wordlet;
	var $edit;
	var $configure;
	var configured = $wordlet.data('wordlet-configured');
	var name = $wordlet.data('wordlet-name');
	var edit_url = $wordlet.data('wordlet-edit');
	var configure_url = $wordlet.data('wordlet-configure');

	$container.empty();

	if ( !edit_url && !configure_url ) return;

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

	// Put child links in the dropdown so they can be navigated to
	var $as = $wordlet.find('a[href]');
	if ( $wordlet.parent().is('a[href]') ) $as = $as.add($wordlet.parent());
	if ( $wordlet.is('a[href]') ) $as = $as.add($wordlet);
	if ( $as.length ) {
		var $other_links = $('<div>').addClass('wordlets_other_links');

		for ( i = 0; i < $as.length; i++ ) {
			var $a = $as.eq(i);
			var $newa = $('<a href="#">')
				.attr({href: $a.attr('href')})
				.html($a.text())
				.appendTo($other_links)
				;

		}

		$other_links.appendTo($container);
	}

	// position and show container
	var offset = $wordlet.offset();
	var left = offset.left;
	var top = offset.top;

	$container
		.css({
			left: left,
			top: top,
			zIndex: 10000
		})
		.show()
		;
};

// Close container with delay
var wordletOffTO;
var close_menu = function() {
	clearTimeout(wordletOffTO);
	wordletOffTO = setTimeout(function() {
		_close_menu();
	}, 1000);
};

var _close_menu = function() {
	$container
		.hide()
		;
};

for ( i = 0; i < length; i++ ) {
	$wordlet = $wordlets.eq(i);

	// Make wordlets tabbable
	$wordlet.attr('tabIndex', 0);

	// See if there are any non-inline elements and add a class letting the css know
	var $children = $wordlet.find('*');

	for ( c = 0; c < $children.length; c++ ) {
		$child = $children.eq(c);
		display = $child.css('display');
		if ( display != 'inline' && display != 'inline-block' ) {
			$wordlet.addClass('wordlet-has-block');
			break;
		}
	}
}

$container
	.bind('mousemove focus focusin', function() {
		clearTimeout(wordletOffTO);
	})
	.bind('focusout mouseout', close_menu)
	// When tabbing off, hide container, select wordlet again, but don't show container
	.delegate('> a:first-child', 'keydown', function(e) {
		if ( e.keyCode == 9 && e.shiftKey ) {
			prevent_open = true;
			e.preventDefault();
			_close_menu();
			$last_wordlet.focus();
		}
	})
	.delegate('a:last-child', 'keydown', function(e) {
		if ( e.keyCode == 9 && !e.shiftKey ) {
			prevent_open = true;
			e.preventDefault();
			_close_menu();
			$last_wordlet.focus();
		}
	})
	;

$body
	// Don't re-open container on tabbing to a wordlet's child link
	// TODO: Form elements?
	.delegate('[data-wordlet] a', 'focus', function(e) {
		prevent_open = true;
	})
	// Upon tabbing to a wordlet, open container and select first link
	.delegate('[data-wordlet]', 'focus', function(e) {
		if ( prevent_open ) {
			setTimeout(function() {
				prevent_open = false;
			}, 0);
			return;
		}
		clearTimeout(wordletOffTO);
		_close_menu();
		open_menu($(this));

		$container.find('> a:first-child').focus();
	})
	.delegate('[data-wordlet]', 'mouseover', function(e) {
		clearTimeout(wordletOffTO);
		_close_menu();
		open_menu($(this));
	})
	.delegate('[data-wordlet]', 'mouseout blur focusout', close_menu)
	// Open wordlet link in modal when wordlet is clicked
	.delegate('[data-wordlet]', 'click', function(e) {
		var $wordlet = $(this);
		var edit_url = $wordlet.data('wordlet-edit');
		var configure_url = $wordlet.data('wordlet-configure');
		var href = edit_url || configure_url;
		if ( !href ) return;
		e.preventDefault();
		e.stopPropagation();
		$.wordlets.open(href);
		_close_menu();
	})
	// open wordlet links in modal
	.delegate('.wordlet_links > a', 'click', function(e) {
		var href = $(this).attr('href');
		if ( !href ) return;
		e.preventDefault();
		e.stopPropagation();
		$.wordlets.open(href);
	})
	;

});
})(window, jQuery)