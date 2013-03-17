<script>
	$(function() {
		$('#inventory-update-button').removeAttr('disabled');
		
		$('#inventory-update-button').click(function() {
			$(this).attr('disabled', 'disabled');
			
			$('#inventory-update-container').show();
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
		<p class="lead" id="inventory-update-status">Initializing update...</p>
		<div class="progress progress-striped active">
			<div class="bar" style="width: 5%;"></div>
		</div>
	</div>

	<div id="inventory-container">
		<p class="lead">I don't actually know what you have yet, but I'm gonna take a shot in the dark and assume you have at least one Fireball gem. Here ya go:</p>
	
		<img class="poe-item" data-poe-tooltip="base:1" src="http://webcdn.pathofexile.com/image/Art/2DItems/Gems/Fireball.png" />
	
		<br /><br />
		<p>If you actually want me to fetch your inventory, click the button above that says "Fetch My Inventory".</p>		
	</div>	
</div>
