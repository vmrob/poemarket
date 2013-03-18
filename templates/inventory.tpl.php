<script>
	var initial_queue_position = 1;

	function update_inventory_update_status(data) {
		if (data.status == 'waiting') {
			$('#inventory-update-status').text(data.status_text);
			var percentage = 10 + (80 - 80 * (data.queue_position / initial_queue_position));
			$('#inventory-update-progress').css('width', percentage + '%');
		} else if (data.status == 'assigned') {
			$('#inventory-update-status').text(data.status_text);
			$('#inventory-update-progress').css('width', '90%');
		} else if (data.status == 'failed') {
			$('#inventory-update-status').text(data.status_text);
			$('#inventory-update-progress').css('width', '0%');
			$('#inventory-update-button').removeAttr('disabled');
		} else if (data.status == 'completed') {
			$('#inventory-update-status').text('Update complete! Refresh the page to see it.');
			$('#inventory-update-progress').css('width', '100%');
			$('#inventory-update-container > .progress').removeClass('active');
		}

		if (data.status == 'waiting' || data.status == 'assigned') {
			setTimeout(function() {
				$.get('ajax/inventoryupdate.php', function(response, status, request) {
					if (status != 'success') {
						$('#inventory-update-status').text('An error occurred while checking on your inventory update. It should still happen, but we can\'t show its progress anymore.');
						$('#inventory-update-progress').css('width', '0%');
						$('#inventory-update-button').removeAttr('disabled');
					} else {
						update_inventory_update_status(response);
					}
				}, 'json');
			}, 1500);
		}
	}

	$(function() {
		$('#inventory-update-button').removeAttr('disabled');
		
		$('#inventory-update-button').click(function() {
			$(this).attr('disabled', 'disabled');
			
			$('#inventory-update-status').text('Initiating update...');
			$('#inventory-update-progress').css('width', '5%');
			$('#inventory-update-container').slideDown();
			
			$.get('ajax/inventoryupdate.php?initiate', function(response, status, request) {
				if (status != 'success') {
					$('#inventory-update-status').text('Failed to initiate update. Please try again later.');
					$('#inventory-update-progress').css('width', '0%');
					$('#inventory-update-button').removeAttr('disabled');
				} else {
					initial_queue_position = response.queue_position;
					update_inventory_update_status(response);
				}
			}, 'json');
		});
	});
</script>

<div class="container">
	<div class="page-header">
		<h1 class="pull-left">Inventory</h1>
		<button class="btn btn-primary btn-large pull-right" id="inventory-update-button">Fetch My Inventory</button>
		<div class="clearfix"></div>
	</div>

	<div id="inventory-update-container" class="well" style="display: none;">
		<p class="lead" id="inventory-update-status"></p>
		<div class="progress progress-striped active">
			<div class="bar" id="inventory-update-progress" style="width: 0%;"></div>
		</div>
	</div>

	<div id="inventory-container">
		<p class="lead">I don't actually know what you have yet, but I'm gonna take a shot in the dark and assume you have at least one Fireball gem. Here ya go:</p>
	
		<img class="poe-item" data-poe-tooltip="base:1" src="http://webcdn.pathofexile.com/image/Art/2DItems/Gems/Fireball.png" />
	
		<br /><br />
		<p>If you actually want me to fetch your inventory, click the button above that says "Fetch My Inventory".</p>		
	</div>	
</div>
