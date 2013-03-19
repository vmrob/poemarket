<?php
require_once dirname(dirname(dirname(__FILE__))).'/support/startup.inc.php';

$display_info = NULL;

$parts = explode(':', urldecode($_SERVER['QUERY_STRING']));

if ($parts[0] == 'base') {
	$result = $_->sql->query("SELECT * FROM {$_->config['mysql_table_prefix']}base_items WHERE base_item_id = '{$_->sql->escape_string($parts[1])}'");
	if ($row = $_->sql->fetch_assoc($result)) {
		$display_info = json_decode($row['base_item_display_info']);
	}
}

if ($display_info) {
	$needs_hr = false;
	?>
	<div class="poe-tooltip poe-<?= $display_info->type ?>-tooltip">
		<div class="head">
			<span class="head-left"></span>
			<span class="head-right"></span>
			<?= $display_info->name ?>
		</div>
		<div class="body">
			<?php
			if ($display_info->explicit_mods) {
				if ($needs_hr) { echo '<hr />'; } else { $needs_hr = true; }
			}
			foreach ($display_info->explicit_mods as $mod) {
				?>
				<div class="explicit-mod"><?= htmlspecialchars($mod) ?></div>
				<?php
			}
			if ($display_info->instructions) {
				if ($needs_hr) { echo '<hr />'; } else { $needs_hr = true; }
				?>				
				<div class="instructions"><?= htmlspecialchars($display_info->instructions) ?></div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
} else {
	// just send back a fireball :P
	?>
	<div class="poe-tooltip poe-gem-tooltip">
		<div class="head">
			<span class="head-left"></span>
			<span class="head-right"></span>
			Fireball
		</div>
		<div class="body">
			<div class="property">Fire, Projectile, Spell, AoE</div>
			<div class="property">Level: <span class="value">1</span></div>
			<div class="property">Mana Cost: <span class="value">5</span></div>
			<div class="property">Cast Time: <span class="value">0.85 Secs</span></div>
			<div class="property">Critical Strike Chance: <span class="value">6%</span></div>
			<hr />
			<div class="requirements">Requires Level <span class="value">1</span></div>
			<hr />
			<div class="description">Unleashes a burning ball of fire towards a target which explodes, damaging nearby foes.</div>
			<hr />
			<div class="explicit-mod">Deals 4-7 fire damage</div>
			<hr />
			<div class="instructions">Place into an item socket of the right colour to gain this skill. Right click to remove from a socket.</div>
		</div>
	</div>
	<?php
}
?>