@extends('layouts.app',['title' => 'Manage Design'])
@section('styles')
	<style type="text/css">
		table.table-striped thead th,
		table.table-striped tbody td {vertical-align: middle; padding: 5px;}
		form * {line-height: normal;}
	</style>
@endsection
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-12 text-right"><h4>Shipping Rates Tester</h4></div>
	</div>
	<hr>
	<div class="row">
		<div class="col-5">
			<h4 class="text-left">Ship To</h4>
			<form id="js_shipping_rates" action="/api/getShippingRates" method="POST">
				<input type="hidden" name="admin_view" value="1">
				{{ csrf_field() }}
				<div class="row mb-3 align-items-center d-none">
					<div class="col-4">Country (code):</div>
					<div class="col-8"><input type="text" class="form-control" name="country_code" placeholder="Country" value="CA"></div>
				</div>
				<div class="row mb-3 align-items-center">
					<div class="col-4">Province (abbreviation):</div>
					<div class="col-8"><input type="text" class="form-control" name="state_province" placeholder="Province (abbreviation)"></div>
				</div>
				<div class="row mb-3 align-items-center">
					<div class="col-4">Postal Code:</div>
					<div class="col-8"><input type="text" class="form-control" name="postal_code" placeholder="Postal Code"></div>
				</div>
				<div class="row mb-3 align-items-center">
					<div class="col-4">Total Units:<br><small>(max 150 units)</small></div>
					<div class="col-8"><input type="number" class="form-control" name="units" min="1" max="150" value="" placeholder="Total # of Jerseys, Shorts, & Socks in Order (max 60)"></div>
				</div>
				<div class="row">
					<div class="col-12 text-right"><button class="btn btn-primary" type="submit">Calculate Rates</button></div>
				</div>
			</form>
		</div>
		<div class="col-7">
			<h4 class="text-left">Estimated Shipping Rates</h4>
			<table id="js_result_table" class="table table-striped">
				<thead class="thead-light text-center">
					<tr>
						<th rowspan="2">Carrier & Service</th>
						<th rowspan="2">Transit Time</th>
						<th colspan="2">Estimated Cost</th>
					</tr>
					<tr>
						<th>Low</th>
						<th>High</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	jQuery(document).ready(function($){

		var BJS = {
			els: {
				js_result_table: $('#js_result_table'),
			},
			Init: function(){
				this.initEvents();
			},
			initEvents: function(){
				$(document).on('submit', '#js_shipping_rates', BJS.get_shipping_rates);
			},
			get_shipping_rates: function(e){
				e.preventDefault();
				e.stopPropagation();

				var $form = $(this),
					form_data = $form.serializeArray();

				BJS.els.js_result_table.find('tbody').html('<tr><td colspan="4" class="text-center loader">Loading data.<br>Please wait...</td></tr>');

				$.ajax({
					url: $form.attr('action'),
					type: 'POST',
					data: form_data,
					success: function(response){
						BJS.els.js_result_table.find('tbody').html(response.html);
					}
				});

				return false;
			},
		};

		BJS.Init();

	});
</script>
@endsection
