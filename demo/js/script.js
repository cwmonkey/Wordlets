(function(window, $, undefined) {

$('body')
	.delegate('#menu a', 'click', function(e) {
		e.preventDefault();
		$.get(this.href, function() {
			document.location.reload();
		});
	})
	;

})(window, jQuery);