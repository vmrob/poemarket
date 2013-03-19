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
var associations = [];
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
				relative_values[a_pool].push({ id: relative_values[b_pool][id] * b_scale });
			}
			relative_values.splice(b_pool, 1);
		} else if (a_pool >= 0) {
			// add b to a's pool
			var a_scale = association.a_quantity;
			for (var id in relative_values[a_pool]) {
				relative_values[a_pool][id] *= a_scale;
			}
			relative_values[a_pool][association.b_id] = relative_values[a_pool][association.a_id] * association.b_quantity;
		} else if (b_pool >= 0) {
			// add a to b's pool
			var b_scale = association.b_quantity;
			for (var id in relative_values[b_pool]) {
				relative_values[b_pool][id] *= b_scale;
			}
			relative_values[b_pool][association.a_id] = relative_values[b_pool][association.b_id] * association.a_quantity;
		}
	}
}

function update_associations_list() {
	$('#currency-associations').html('');
	
	for (var i = 0; i < associations.length; ++i) {
		var association = associations[i];
		var $line = $('<div data-association="' + i + '">' + association.a_quantity + ' ' + currencies[association.a_id].name + ' = ' + association.b_quantity + ' ' + currencies[association.b_id].name + '</div>');
		$line.append('<button class="btn remove-association-button">Remove</button>');
		$('#currency-associations').append($line);
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

function update_relative_value_table() {
	$('#relative-value-table').html('');
	
	var arrangement = [];

	var $head = $('<tr />');
	$head.append('<td />');

	for (var i = 0; i < relative_values.length; ++i) {
		for (var id in relative_values[i]) {
			arrangement.push(id);
			
			var $img = $('<img class="poe-item" data-poe-tooltip="base:' + id + '" />').attr('src', currencies[id].image);
			$head.append($('<td />').append($img));
		}
	}
	
	$('#relative-value-table').append($head);

	var pool = 0;
	for (var i = 0; i < arrangement.length; ++i) {
		var $row = $('<tr />');
		
		var id = arrangement[i];
		
		var $img = $('<img class="poe-item" data-poe-tooltip="base:' + id + '" />').attr('src', currencies[id].image);
		$row.append($('<td />').append($img));
		
		while (!(id in relative_values[pool])) { ++pool; }
		
		for (var j = 0; j < arrangement.length; ++j) {
			if (i == j) {
				// same currency
				$row.append('<td></td>');
			} else if (arrangement[j] in relative_values[pool]) {
				// we have a relation
				$row.append('<td>' + relative_values[pool][id] + ':' + relative_values[pool][arrangement[j]] + '</td>');
			} else {
				// no relation
				$row.append('<td>?</td>');
			}
		}
		
		$('#relative-value-table').append($row);
	}
}

function update_everything() {
	calculate_relative_values();
	update_associations_list();
	update_association_options();
	update_relative_value_table();
}

$(function() {
	$('#add-currency-association').click(function() {
		if (
			!$('#currency-a-id').val() || 
			!$('#currency-b-id').val() || 
			!$.isNumeric($('#currency-a-quantity').val()) || $('#currency-a-quantity').val() <= 0 ||
			!$.isNumeric($('#currency-b-quantity').val()) || $('#currency-b-quantity').val() <= 0
		) {
			alert('Check your input.');
			return;
		}

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
});

$(document).on('click', '.remove-association-button', function() {
	var index = $(this).parent().data('association');
	
	associations.splice(index, 1);
	
	update_everything();
});
</script>

<div class="container">
	<div class="page-header">
		<h1>Settings</h1>
	</div>
	<h3>Currency Weights</h3>
	<p>Make associations until the table below is complete.</p>
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
		</select>
		=
		<input type="text" class="input-mini" id="currency-b-quantity" placeholder="#" />
		<select id="currency-b-id"></select>
		<button class="btn" id="add-currency-association"><i class="icon-plus"></i></button>
	</div>
	<div id="currency-associations"></div>
	<table id="relative-value-table"></table>
</div>
