(function(window, $, undefined) {

$('body')
	.delegate('#menu a', 'click', function(e) {
		e.preventDefault();
		$.get(this.href, function() {
			document.location.reload();
		});
	})
	;

$(function() {

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
	/*var jqm = $modal.data('jqm');
	jqm.ajax = href.replace(' ', '%20');

	$modal.data('jqm', jqm);
	$modal.jqmShow();*/
};

});

})(window, jQuery);