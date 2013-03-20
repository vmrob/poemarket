<div class="container">
	<div class="page-header">
		<h1>Market</h1>
	</div>

	<div class="row">
		<div class="tabbable tabs-left span2">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#currency-tab" data-toggle="tab">Currency</a></li>
				<li><a href="#gems-tab" data-toggle="tab">Gems</a></li>
			</ul>
		</div>
		<div class="tab-content span10">
			<div class="tab-pane active row" id="currency-tab">
				<div class="span6">
					<?php
					foreach ($this->currencies as $currency) {
						?>
						<a href="#"><img src="<?= $currency['display_info']->image ?>" class="poe-item" data-poe-tooltip="base:<?= $currency['base_item_id'] ?>"/></a>
						<?php
					}
					?>
				</div>
				<div class="span4">
					<div class="well well-large">
						<h3>Orb of Alchemy</h3>
						<table class="table">
							<tr><td>Current Price:</td><td>0.12 GCP</td></tr>
							<tr><td>Today:</td><td>0.15 GCP</td></tr>
							<tr><td>Last Week:</td><td>0.15 GCP</td></tr>
						</table>
						<div class="text-center">
							<button class="btn btn-primary btn-block">Buy</button>
							<button class="btn btn-block">Sell</button>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane row" id="gems-tab">
				<div class="span6">
					<table class="table">
						<?php
						for ($i = 0; $i < 23; ++$i) {
							?>
							<tr>
								<td><img src="http://webcdn.pathofexile.com/image/Art/2DItems/Gems/Fireball.png" /><a href="#">Skill Gem <?= $i ?></a></td>
								<td><img src="http://webcdn.pathofexile.com/image/Art/2DItems/Gems/Fireball.png" /><a href="#">Skill Gem <?= $i ?></a></td>
								<td><img src="http://webcdn.pathofexile.com/image/Art/2DItems/Gems/Fireball.png" /><a href="#">Skill Gem <?= $i ?></a></td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
				<div class="span4">
					<div class="well well-large">
						<h3>Freezing Pulse</h3>
						<table class="table">
							<tr><td>Current Price:</td><td>0.12 GCP</td></tr>
							<tr><td>Today:</td><td>0.15 GCP</td></tr>
							<tr><td>Last Week:</td><td>0.15 GCP</td></tr>
						</table>
						<div class="text-center">
							<button class="btn btn-primary btn-block">Buy</button>
							<button class="btn btn-block">Sell</button>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</div>
</div>
