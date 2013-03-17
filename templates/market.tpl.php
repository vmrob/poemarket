<script src="js/holder.js"></script>
<div class="container">
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
					<ul class="thumbnails">
						<?php
						for ($i = 0; $i < 23; ++$i) {
							?>
							<li>
								<a href="#" class="thumbnail">
									<img src="http://pool.pathofexilewiki.com/w/images/Orb_of_Alchemy_icon.png" class="currency-market-thumbnail" alt="test image">
								</a>
							</li>
							<?php
						}
						?>
					</ul>
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
							<button class="btn btn-large half-width-btn">Buy</button>
							<button class="btn btn-large half-width-btn">Sell</button>
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
								<td><img src="http://pool.pathofexilewiki.com/w/images/Orb_of_Alchemy_icon.png" class="gem-market-thumbnail" alt="skill gem"><a href="#">Skill Gem <?= $i ?></a></td>
								<td><img src="http://pool.pathofexilewiki.com/w/images/Orb_of_Alchemy_icon.png" class="gem-market-thumbnail" alt="skill gem"><a href="#">Skill Gem <?= $i ?></a></td>
								<td><img src="http://pool.pathofexilewiki.com/w/images/Orb_of_Alchemy_icon.png" class="gem-market-thumbnail" alt="skill gem"><a href="#">Skill Gem <?= $i ?></a></td>
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
							<button class="btn btn-large half-width-btn">Buy</button>
							<button class="btn btn-large half-width-btn">Sell</button>
						</div>
					</div>
				</div>			
			</div>
		</div>
	</div>
</div>
