<?php 
?>

@extends('layouts.app')

@section('content')

<div class="container">
	<div class="form-area">
		<form id="form" method="POST">
			<input type="hidden" name="id" id="product-id">
			<div class="form-group">
    			<label for="product-name">Product Name</label>
    			<input type="text" class="form-control" id="product-name" name="name" placeholder="Product Name">
  			</div>
  			<div class="form-group">
    			<label for="quantity">Quantity in stock</label>
    			<input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity">
  			</div>
  			<div class="form-group">
    			<label for="price">Price per item</label>
    			<input type="number" class="form-control" id="price" name="price" placeholder="Price">
  			</div>
  			
  			<div class="form-group">
  				<button type="submit" class="btn btn-primary">Save</button>
  			</div>
		</form>
	</div>
	
	<div class="list-area">
		<table class="table">
			<thead>
				<tr>
					<th>Product Name</th>
					<th>Quantity in stock</th>
					<th>Price per item</th>
					<th>Date submitted</th>
					<th>Total Value</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$total_sum = 0;
			foreach($list as $t):
				$total_sum +=  $t['quantity'] * $t['price'];
			?>
				<tr>
					<td class="p_name">{{ $t['name'] }}</td>
					<td class="p_quantity">{{ $t['quantity'] }}</td>
					<td class="p_price">{{ $t['price'] }}</td>
					<td class="p_date">{{ $t['date'] }}</td>
					<td class="p_total">{{ $t['quantity'] * $t['price'] }}</td>					
					<td>
						<button type="button" class="btn btn-xs button-edit" data-id="{{ $t['id'] }}">Edit</button>
					</td>
				</tr>
			<?php endforeach;?>
				
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4">Total</td>
					<td colspan="2" id="sum-cell">
						{{ $total_sum }}
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

@endsection

@section('scripts')
<script>
var $rowToEdit = null;

$(function() {
	$('#form').submit(function(e) {
		e.preventDefault();

		$.ajax({
			type: 'POST',
			url: '<?= url('/product/save') ?>',
			data: $('#form').serialize(),
			dataType: 'json',
			success: function(data) {
				if (data.res == 'success') {
					if ($rowToEdit == null) {
						$tr = $("<tr></tr>");
						$tr.append('<td>' + data.name + '</td>');
						$tr.append('<td>' + data.quantity + '</td>');
						$tr.append('<td>' + data.price + '</td>');
						$tr.append('<td>' + data.date + '</td>');
						$tr.append('<td>' + data.quantity * data.price + '</td>');
						$tr.append('<td><button type="button" class="btn btn-xs button-edit" data-id="' + data.id + '">Edit</button></td>');
						
						$('.table tbody').append($tr);
					}
					else {
						$rowToEdit.find('.p_name').html(data.name);
						$rowToEdit.find('.p_quantity').html(data.quantity);
						$rowToEdit.find('.p_price').html(data.price);
						$rowToEdit.find('.p_date').html(data.date);
						$rowToEdit.find('.p_total').html(data.quantity * data.price);
					}

					$('#sum-cell').html(data.total);
				}
			},
			error: function(error) {
				console.log(error);
			}
		});
	});

	$('.button-edit').click(function(e) {
		$rowToEdit = $(this).parents('tr');

		$('#product-id').val($(this).data('id'));
		$('#product-name').val($rowToEdit.find('.p_name').html());
		$('#quantity').val($rowToEdit.find('.p_quantity').html());
		$('#price').val($rowToEdit.find('.p_price').html());		
	});
});
</script>
@endsection