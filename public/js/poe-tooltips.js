$(function() {
	var layOutTooltip = function($item) {
		var $tt = $item.data('poe-tooltip-element');
		$tt.css('position', 'absolute');
		$tt.css('left', $item.position().left + ($item.width() / 2) - ($tt.width() / 2));
		$tt.css('top', $item.position().top - $tt.outerHeight());
		$tt.css('z-index', '100');
	};
	$('.poe-item').hover(function() {
		// on enter
		if (!$(this).data('poe-tooltip-element')) {
			// tooltip doesn't exist yet. create it
			var $item = $(this);
			var $tt = $('<span style="display: none;">Loading...</span>');
			$item.data('poe-tooltip-element', $tt);
			$tt.load('ajax/poe-tooltip.php?'+encodeURIComponent($item.data('poe-tooltip')), function(response, status, request) {
				if (status != "success" && status != "notmodified") {
					$tt.text('Error loading tooltip.');
				}
				layOutTooltip($item);
			});
			$('body').append($tt);
		}
		layOutTooltip($(this));
		$(this).data('poe-tooltip-element').show();
	}, function() {
		// on leave
		if ($(this).data('poe-tooltip-element')) {
			$(this).data('poe-tooltip-element').hide();
		}
	});
});