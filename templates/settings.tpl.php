<?php
function currency_name_cmp($a, $b) {
	return strcmp($a['name'], $b['name']);
}
$currency_data = array();
foreach ($this->currencies as $currency) {
	$currency_data[$currency['base_item_id']] = array(
		'name'  => $currency['display_info']->name,
		'image' => $currency['display_info']->image,
	);
}
uasort($currency_data, 'currency_name_cmp');
?>

<script>
var currencies = <?= json_encode($currency_data) ?>;
var associations = [
	{a_id:"65", a_quantity:"20", b_id:"67", b_quantity:"1"}, {a_id:"66", a_quantity:"20", b_id:"64", b_quantity:"1"}, 
	{a_id:"60", a_quantity:"5", b_id:"64", b_quantity:"4"}, {a_id:"60", a_quantity:"5", b_id:"61", b_quantity:"2"}, 
	{a_id:"61", a_quantity:"1", b_id:"58", b_quantity:"1"}, {a_id:"67", a_quantity:"6", b_id:"50", b_quantity:"1"}, 
	{a_id:"51", a_quantity:"3", b_id:"63", b_quantity:"1"}, {a_id:"59", a_quantity:"1", b_id:"52", b_quantity:"8"}, 
	{a_id:"56", a_quantity:"3", b_id:"67", b_quantity:"2"}, {a_id:"55", a_quantity:"20", b_id:"53", b_quantity:"1"}, 
	{a_id:"62", a_quantity:"1", b_id:"67", b_quantity:"1"}, {a_id:"64", a_quantity:"1", b_id:"54", b_quantity:"3"}, 
	{a_id:"53", a_quantity:"2", b_id:"63", b_quantity:"1"}, {a_id:"57", a_quantity:"1", b_id:"54", b_quantity:"1"}, 
	{a_id:"67", a_quantity:"1", b_id:"64", b_quantity:"8"}, {a_id:"57", a_quantity:"1", b_id:"52", b_quantity:"2"}, 
	{a_id:"54", a_quantity:"1", b_id:"53", b_quantity:"4"}
];
var relative_values = [];

function calculate_relative_values() {
	relative_values = [];

	for (var i = 0; i < associations.length; ++i) {
		var association = associations[i];
		
		// find the pools containing each currency
		
		var a_pool = -1;
		var b_pool = -1;
		
		for (var j = 0; j < relative_values.length; ++j) {
			if (a_pool == -1 && association.a_id in relative_values[j]) {
				a_pool = j;
				if (b_pool >= 0) {
					break;
				}
			}
			if (b_pool == -1 && association.b_id in relative_values[j]) {
				b_pool = j;
				if (a_pool >= 0) {
					break;
				}
			}
		}
		
		if (a_pool == -1 && b_pool == -1) {
			// neither of the currencies can be related to anything else. start a new pool
			relative_values.push({});
			relative_values[relative_values.length - 1][association.a_id] = association.a_quantity;
			relative_values[relative_values.length - 1][association.b_id] = association.b_quantity;
		} else if (a_pool == b_pool) {
			// this shouldn't happen
			// we can already relate the two currencies. discard whatever this mess is
		} else if (a_pool >= 0 && b_pool >= 0) {
			// both are in pools. merge them into a_pool
			var a_scale = relative_values[b_pool][association.b_id] * association.a_quantity;
			var b_scale = relative_values[a_pool][association.a_id] * association.b_quantity;
			for (var id in relative_values[a_pool]) {
				relative_values[a_pool][id] *= a_scale;
			}
			for (var id in relative_values[b_pool]) {
				relative_values[a_pool][id] = relative_values[b_pool][id] * b_scale;
			}
			relative_values.splice(b_pool, 1);
		} else if (a_pool >= 0) {
			// add b to a's pool
			var a_scale = association.a_quantity;
			var a_value = relative_values[a_pool][association.a_id] * association.b_quantity;
			for (var id in relative_values[a_pool]) {
				relative_values[a_pool][id] *= a_scale;
			}
			relative_values[a_pool][association.b_id] = a_value;
		} else if (b_pool >= 0) {
			// add a to b's pool
			var b_scale = association.b_quantity;
			var b_value = relative_values[b_pool][association.b_id] * association.a_quantity;
			for (var id in relative_values[b_pool]) {
				relative_values[b_pool][id] *= b_scale;
			}
			relative_values[b_pool][association.a_id] = b_value;
		}
	}
}

function update_associations_list() {
	$('.currency-associations').html('');
	
	for (var i = 0; i < associations.length; ++i) {
		var association = associations[i];
		var $line = $('<div data-association="' + i + '">' + association.a_quantity + ' ' + currencies[association.a_id].name + ' = ' + association.b_quantity + ' ' + currencies[association.b_id].name + '</div>');
		$line.append(' <a class="remove-association-button" href="#"><i class="icon-minus-sign"></i></a>');
		$('#currency-associations-column' + ((i % 3) + 1)).append($line);
	}
}

function update_association_options() {
	var b_value = $('#currency-b-id').val();
	$('#currency-b-id option').remove();

	if (!$('#currency-a-id').val()) {
		return;
	}

	// update b options
	var new_value = '';
	$('#currency-b-id').append('<option value="">Select another currency...</option>');

	var a_pool = -1;
	for (var i = 0; i < relative_values.length; ++i) {
		if ($('#currency-a-id').val() in relative_values[i]) {
			a_pool = i;
			break;
		}
	}

	for (var id in currencies) {
		// only options that aren't in a's pool are allowed
		if (id != $('#currency-a-id').val() && (a_pool == -1 || !(id in relative_values[a_pool]))) {
			if (id == b_value) {
				new_value = id;
			}
			$('#currency-b-id').append($('<option value="' + id + '"></option>').text(currencies[id].name));
		}
	}
	
	$('#currency-b-id').val(new_value);
}

function reset_association_input() {
	$('#currency-a-id').val('');
	$('#currency-a-quantity').val('1');
	$('#currency-b-id').val('');
	$('#currency-b-quantity').val('1');
}

function gcd(a, b) {
	return (b == 0 ? a : gcd(b, a % b));
}

function update_exchange_table() {
	$('#currency-exchange-table').html('');
	
	var arrangement = [];

	var $head = $('<tr />');
	$head.append('<th />');

	for (var i = 0; i < relative_values.length; ++i) {
		var ids = [];
		for (var id in relative_values[i]) {
			ids.push(id);
		}
		ids.sort(function(a, b) {
			return relative_values[i][b] - relative_values[i][a];
		});
		arrangement = arrangement.concat(ids);
	}
	
	for (var id in currencies) {
		if (arrangement.indexOf(id) < 0) {
			arrangement.push(id);
		}
	}
	
	for (var i = 0; i < arrangement.length; ++i) {
		var id = arrangement[i];
		var $img = $('<img class="poe-item" data-poe-tooltip="base:' + id + '" />').attr('src', currencies[id].image);
		$head.append($('<th />').append($img));
	}
	
	$('#currency-exchange-table').append($head);

	var pool = 0;
	for (var i = 0; i < arrangement.length; ++i) {
		var $row = $('<tr />');

		var id = arrangement[i];
		
		var $img = $('<img class="poe-item" data-poe-tooltip="base:' + id + '" />').attr('src', currencies[id].image);
		$row.append($('<th />').append($img));
		
		if (pool >= 0) {
			while (pool < relative_values.length && !(id in relative_values[pool])) { ++pool; }
			if (pool >= relative_values.length) { pool = -1; }
		}
		
		for (var j = 0; j < arrangement.length; ++j) {
			if (i == j) {
				// same currency
				$row.append('<td></td>');
			} else if (pool >= 0 && arrangement[j] in relative_values[pool]) {
				// we have a relation
				var a = relative_values[pool][id];
				var b = relative_values[pool][arrangement[j]];
				var d = gcd(a, b);
				$row.append('<td>' + (a / d) + ':' + (b / d) + '</td>');
			} else {
				// no relation
				$row.append('<td class="unknown">?</td>');
			}
		}
		
		$('#currency-exchange-table').append($row);
	}
}

function update_everything() {
	calculate_relative_values();
	update_associations_list();
	update_association_options();
	update_exchange_table();
}

$(function() {
	$('#settings-save-button').attr('disabled', 'disabled');

	$('#add-currency-association').click(function() {
		var a_quantity = $('#currency-a-quantity').val();
		var b_quantity = $('#currency-b-quantity').val();
		
		if (
			!$('#currency-a-id').val() || 
			!$('#currency-b-id').val() || 
			!$.isNumeric(a_quantity) || a_quantity <= 0 || Math.round(a_quantity) != a_quantity ||
			!$.isNumeric(b_quantity) || b_quantity <= 0 || Math.round(b_quantity) != b_quantity
		) {
			alert('Check your input.');
			return;
		}

		$('#settings-save-button').removeAttr('disabled');

		associations.push({
			"a_id": $('#currency-a-id').val(),
			"a_quantity": $('#currency-a-quantity').val(),
			"b_id": $('#currency-b-id').val(),
			"b_quantity": $('#currency-b-quantity').val(),
		});
		
		reset_association_input();
		update_everything();
	});

	$('#currency-a-id').change(function() {
		update_association_options();
	});
	
	reset_association_input();
	update_everything();	

	$('#settings-save-button').click(function() {
		alert('Sorry, this is still under construction.');
	});
});

$(document).on('click', '.remove-association-button', function() {
	$('#settings-save-button').removeAttr('disabled');

	var index = $(this).parent().data('association');
	
	associations.splice(index, 1);
	
	update_everything();
	
	return false;
});
</script>

<div class="container">
	<div class="page-header">
		<h1 class="pull-left">Settings</h1>
		<button class="btn btn-primary btn-large pull-right" id="settings-save-button">Save</button>
		<div class="clearfix"></div>
	</div>
	<p class="lead">Here you can set your currency exchange values. Simply create associations to complete the table below.</p>
	<div class="line-input">
		<input type="text" class="input-mini" id="currency-a-quantity" placeholder="#" />
		<select id="currency-a-id">
			<option value="" selected="selected">Select a currency...</option>
			<?php
			foreach ($currency_data as $id => $currency) {
				?>
				<option value="<?= htmlspecialchars($id) ?>"><?= htmlspecialchars($currency['name']) ?></option>
				<?php
			}
			?>
		</select> =
		<input type="text" class="input-mini" id="currency-b-quantity" placeholder="#" />
		<select id="currency-b-id"></select>
		<button class="btn" id="add-currency-association"><i class="icon-plus"></i></button>
	</div>
	<div class="row">
		<div id="currency-associations-column1" class="currency-associations span4"></div>
		<div id="currency-associations-column2" class="currency-associations span4"></div>
		<div id="currency-associations-column3" class="currency-associations span4"></div>
	</div>
	<table id="currency-exchange-table" class="table table-condensed table-bordered"></table>
</div>
