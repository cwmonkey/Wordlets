(function(window, $, undefined) {

$('body')
	.delegate('#menu a.config', 'click', function(e) {
		e.preventDefault();
		$.get(this.href, function() {
			document.location.reload();
		});
	})
	.delegate('a', 'click focus hover', function() {
		if ( !this.href.match(/^mailto:/) 
			&& (this.hostname != document.location.hostname) ) {
			this.target = '_blank';
		}
	})
	;

$(function() {

if ( $.wordlets == undefined ) return;

var $modal = $.cwmModal.getModal().$modal.addClass('cms');

$.wordlets.open = function(href) {
	$.cwmModal
		.ajax({href: href.replace(' ', '%20')})
		.then(function() {
			var $form = $modal.find('form').bind('submit', function(e) {
				e.preventDefault();

				$.ajax({
					url: this.action,
					data: $form.serialize(),
					type: this.method,
					success: function(data) {
						document.location.reload();
					}
				});

				$form.find('input, button, select, textarea').prop({disabled: true});
			});
		})
		;
};

});

})(window, jQuery);