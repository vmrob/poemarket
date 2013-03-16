$(function() {
	var layOutTooltip = function($item) {
		var $tt = $item.data('poe-tooltip-element');
		$tt.css('position', 'absolute');

		var ttw = $tt.outerWidth();
		var tth = $tt.outerHeight();
		var itemw = $item.outerWidth();
		var itemh = $item.outerHeight();
		var iteml = $item.position().left;
		var itemt = $item.position().top;

		if (itemt >= tth) {
			// put it above
			$tt.css('left', iteml + (itemw / 2) - (ttw / 2));
			$tt.css('top', itemt - tth);
		} else if (iteml + itemw + ttw <= $(window).width()) {
			// put it to the right
			$tt.css('left', iteml + itemw);
			$tt.css('top', itemt + (itemh / 2) - (tth / 2));
		} else if (itemt + itemh + tth <= $(window).height()) {
			// put it below
			$tt.css('left', iteml + (itemw / 2) - (ttw / 2));
			$tt.css('top', itemt + itemh);
		} else if (iteml >= ttw) {
			// put it to the left
			$tt.css('left', iteml - ttw);
			$tt.css('top', itemt + (itemh / 2) - (tth / 2));
		} else {
			// if it can't entirely fit anywhere put it above
			$tt.css('left', iteml + (itemw / 2) - (ttw / 2));
			$tt.css('top', itemt - tth);
		}

		$tt.css('z-index', '10000');
	};
	$('.poe-item').hover(function() {
		// on enter
		if (!$(this).data('poe-tooltip-element')) {
			// tooltip doesn't exist yet. create it
			var $item = $(this);
			var $tt = $('<span class="unloaded-poe-tooltip-container">Loading...</span>');
			$item.data('poe-tooltip-element', $tt);
			$tt.load('ajax/poetooltip.php?'+encodeURIComponent($item.data('poe-tooltip')), function(response, status, request) {
				if (status != 'success' && status != 'notmodified') {
					$tt.text('Error loading tooltip.');
				}
				$tt.removeClass('unloaded-poe-tooltip-container');
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