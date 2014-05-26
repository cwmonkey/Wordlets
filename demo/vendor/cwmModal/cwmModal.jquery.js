/* Simple modal

Usage:

var modal = $.cwmModal.getModal({name: 'myModal', clickToClose: false});

modal
	.ajax({href: url})
	.then(function() {
		// do stuff with modal.$modal
	})
	;

*/

(function(window, $, undefined) {

var defaults = {
	name: 'default', // Allows for multiple instances of modals. Prepends class names.
	clickToClose: true // Click overlay to close
};

var $body = $('body');
var $window = $(window);

var Modal = function(settings) {
	var self = this;
	this.name = settings.name;

	this.$overlay = $('<div/>')
		.addClass(this.class('overlay'))
		.css({
			width: '100%',
			height: '100%',
			position: 'fixed',
			left: 0,
			top: 0,
			display: 'none',
			opacity: 0
		})
		.appendTo($body)
		;

	if ( settings.clickToClose ) {
		this.$overlay.bind('click', function() {
			self.hideOverlay();
			self.hideModal();
			self.hideLoading();
		});
	}

	this.$modal = $('<div/>')
		.addClass(this.class('modal'))
		.css({
			display: 'none',
			position: 'fixed'
		})
		.attr({
			tabIndex: '-1'
		})
		.appendTo($body)
		;

	this.$content = $('<div/>')
		.addClass(this.class('content'))
		.appendTo(this.$modal)
		;

	self.$loading = $('<div/>')
		.addClass(this.class('loading'))
		.css({
			display: 'none',
			position: 'fixed',
			opacity: 0
		})
		.appendTo($body)
		;

	$window
		.bind('resize scroll', function() {
			if ( self.$modal.css('display') != 'none' ) {
				self.position(self.$modal);
			}

			if ( self.$loading.css('display') != 'none' ) {
				self.position(self.$loading);
			}
		})
		;
};

Modal.prototype.classPrepend = 'cwmModal';

Modal.prototype.ajax = function(href) {
	var self = this;

	this.hideModal();
	this.showOverlay();
	this.showLoading();

	var $promise = $.load(this.$content, href);

	$promise.then(function() {
		self.hideLoading();
		self.showModal();
	});

	return $promise;
};

Modal.prototype.class = function(c) {
	return this.name + '-' + c + ' ' + this.classPrepend + '-' + c;
};

// Center modal
Modal.prototype.position = function($el) {
	var mwidth = $el.outerWidth();
	var mheight = $el.outerHeight();

	var wwidth = $window.width();
	var wheight = $window.height();

	var left = wwidth / 2 - mwidth / 2;
	var top = wheight / 2 - mheight / 2;

	// Fixed position if modal fits
	if ( mwidth <= wwidth && mheight <= wheight ) {
		$el.css({position: 'fixed'});
	// If modal no longer fits, switch to absolute positioning
	} else if ( $el.css('position') == 'fixed' ) {
		$el.css({position: 'absolute'});

		// If height or width do not fit, move to top or left of visible screen
		if ( mwidth > wwidth ) {
			left = $window.scrollLeft();
			$el.css({
				left: left
			});
		} else {
			left += $window.scrollLeft();
			$el.css({
				left: left
			});
		}

		if ( mheight > wheight ) {
			top = $window.scrollTop();
			$el.css({
				top: top
			});
		} else {
			top += $window.scrollTop();
			$el.css({
				top: top
			});
		}
		return;
	}

	// If modal fits height or width, move to center
	// If scrolling left or up and modal does not fit, keep modal at left or top
	if ( $el.css('position') == 'absolute' ) {
		if ( mwidth <= wwidth ) {
			left += $window.scrollLeft();
			$el.css({
				left: left
			});
		} else if ( parseInt($el.css('left')) > $window.scrollLeft() ) {
			left = $window.scrollLeft();
			$el.css({
				left: left
			});
		}

		if ( mheight <= wheight ) {
			top += $window.scrollTop();
			$el.css({
				top: top
			});
		} else if ( parseInt($el.css('top')) > $window.scrollTop() ) {
			top = $window.scrollTop();
			$el.css({
				top: top
			});
		}
		return;
	}

	// If modal fits height and width, center
	$el.css({
		left: left,
		top: top
	});
};

Modal.prototype.showOverlay = function() {
	var $overlay = this.$overlay;

	$overlay
		.addClass(this.class('show'))
		.css({display: 'block'});

	setTimeout(function() {
		$overlay.css({opacity: ''});
	}, 0);
};

Modal.prototype.showLoading = function() {
	this.showElement(this.$loading);
};

Modal.prototype.showModal = function() {
	this.showElement(this.$modal);
};

Modal.prototype.showElement = function($el) {
	$el
		.addClass(this.class('show'))
		.css({display: 'block'});
	this.position($el);

	setTimeout(function() {
		$el.css({opacity: 1});
	}, 0);
}

Modal.prototype.hideOverlay = function() {
	this.$overlay
		.removeClass(this.class('show'))
		.css({opacity: 0, display: 'none'});
};

Modal.prototype.hideLoading = function() {
	this.$loading
		.removeClass(this.class('show'))
		.css({opacity: 0, display: 'none'});
};

Modal.prototype.hideModal = function() {
	this.$modal
		.removeClass(this.class('show'))
		.css({opacity: 0, display: 'none'});
};

$.cwmModal = {
	modals: [],
	ajax: function(params) {
		var settings = $.extend({}, defaults, params);
		var href = settings.href;
		var modal = this.getModal(params);

		return modal.ajax(href);
	},
	getModal: function(params) {
		var settings = $.extend({}, defaults, params);
		var modal = this.modals[settings.name];

		if ( modal == undefined ) {
			modal = this.modals[settings.name] = new Modal(settings);
		}

		return modal;
	}
};

})(window, jQuery);