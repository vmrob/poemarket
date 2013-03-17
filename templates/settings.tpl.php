<div class="container">
	<div class="page-header">
		<h1>Settings</h1>
	</div>
	<div class="">
		<h3>Currency Weights (Everything is worth 1 Alch!)<h3>
	</div>
	<div class="span12">
		<table class="table">
			<?php
			for ($i = 0; $i < 23; ++$i) {
			?>
				<tr>
					<?php
					for ($j = 0; $j < 23; ++$j) {
						if ($i == 0 || $j == 0) {
							echo '<td><img src="http://pool.pathofexilewiki.com/w/images/Orb_of_Alchemy_icon.png" alt="test image"></td>';
						} else {
							echo '<td>1:1</td>';
						}
					}
					?>
				</tr>
			<?php
			}
			?>
		</table>
	</div>
	</div>
</div>
