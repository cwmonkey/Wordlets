(function(window, $, undefined) {

$('body')
	.delegate('.new input, .new select', 'change', function() {
		$(this).closest('.new').removeClass('new');
	})
	;

})(window, jQuery);