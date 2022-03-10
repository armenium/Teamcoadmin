@extends('layouts.app',['title' => 'View Reference #'.$roster->id])
@section('styles')
<style type="text/css">
	.fa-square:before {text-shadow: 1px 1px 2px #000000;}
	table.table {width: auto; border-collapse: collapse;border-spacing: 0; max-width: 800px; margin: 0;}
	table.table td,
	table.table th {border: 1px solid #c0c0c0; text-align: left; font: normal 12px Arial, sans-serif; color: #000; padding: 5px 15px;}
	table.table thead td,
	table.table thead th {font-weight: bold;}
	table.table td.text_left {text-align: left;}
	table.table td.text_right {text-align: right;}
	table.table td.text_center {text-align: center;}
	table.table td.text_bold {font-weight: bold;}
	h1 {font: bold 17px Arial, sans-serif;color: #4e4e4e; text-align: left;}
	h2 {color: #a33c4a; font: bold 14px Arial, sans-serif;padding: 5px 0; text-align: left; text-transform: uppercase;}
	.container {width:990px;color: #4e4e4e;}
	.clear {clear: both;}
	.roster_attach {font: bold 16px Arial, sans-serif;font-style: italic;color: #a33c4a;text-align:left;}
	#print-area.row [class^="col-"] {margin-bottom: 20px;}
</style>
@endsection
@section('scripts')
<script type="text/javascript">
	jQuery(document).ready(function($){

		$("#print_tables").click(function(){
			//$('#all_calendar_data').printElement();
			$("#print-area").print({
				globalStyles: true,
				mediaPrint: true,
				//stylesheet: '/wp-content/plugins/awebooking/assets/css/admin.css',
				noPrintSelector: ".no-print",
				iframe: true,
				append: null,
				prepend: null,
				manuallyCopyFormValues: true,
				deferred: $.Deferred(),
				timeout: 750,
				title: '',
				doctype: '<!doctype html>'
			});
		});

	});
</script>
@endsection
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-6 text-left">
			<h4>Reference #{{ $roster->id }}</h4>
		</div>
		<div class="col-md-6 text-right">
			<input type="button" id="print_tables" value="Print" class="btn btn-danger">
		</div>
	</div>
	<hr>
	@if (session('status'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('status') }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
		</button>
	</div>
	@endif
	<div class="row" id="print-area">
		<div class="col-md-8 offset-md-3">
			<h1>ROSTER FORM # {{$roster->id}} - {{ $roster->client->name }} - {{$roster->client->company}}</h1>
		</div>
		<div class="col-md-8 offset-md-3">
			<h2>1. Contact and shipping Information:</h2>
			<table class="table table-bordered min-padding">
				<tr>
					<td class="font-weight-bold">Reference #:</td>
					<td> {{ $roster->id }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Name:</td>
					<td> {{ $roster->client->name ?? 'No Name' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Company / Org:</td>
					<td> {{ $roster->client->company ?? 'No Company' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Address:</td>
					<td> {{ $roster->client->address ?? 'No Address' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Address 2:</td>
					<td> {{ $roster->client->address_2 ?? 'No Address' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">City:</td>
					<td> {{ $roster->client->city ?? 'No City' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Prov/State::</td>
					<td> {{ $roster->client->state ?? 'No State' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Postal Code:</td>
					<td> {{ $roster->client->zip ?? 'No Postal Code' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Country:</td>
					<td> {{ $roster->client->country ?? 'No Country' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Email:</td>
					<td> {{ $roster->client->email ?? 'No Email' }}</td>
				</tr>
				<tr>
					<td class="font-weight-bold">Phone:</td>
					<td> {{ $roster->client->phone ?? 'No Phone' }}</td>
				</tr>
			</table>
		</div>
		<div class="col-md-6 offset-md-3">
			<h2>2.Jersey Details</h2>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<td><b>Style Code:</b></td>
						<td>{{ $roster->jersey->style_code }}</td>
					</tr>
					@if(isset($jersey_detail))
						@forelse($jersey_detail as $detail)
							<tr>
								<td>
									<b>Color {{ $loop->iteration }}:</b>
								</td>
								<td>
									{{ ucfirst($detail) }}
								</td>
							</tr>
						@empty
						@endforelse
					@endif
				</tbody>
			</table>
		</div>
		<div class="col-md-6 offset-md-3">
			<h2>3. Accessory Items:</h2>
			<div class="mb-1">{{ $roster->accessory_items ?? 'No accessory items' }}</div>
		</div>
		<div class="col-md-6 offset-md-3">
			<h2>4. Numbers Colors:</h2>
			<table class="table table-bordered">
				<tr>
					<td><b>Number Colors:</b></td>
					<td>{{ $roster->number_color }}</td>
				</tr>
				<!--
				<tr>
					<td><b>Inside color:</b></td>
					<td>{{ $roster->inside_color }}</td>
				</tr>
				<tr>
					<td><b>Outside color:</b></td>
					<td>{{ $roster->outside_color }}</td>
				</tr>
				-->
			</table>
		</div>
		<div class="col-md-6 offset-md-3">
			<h2>5. Artwork placement and comments:</h2>
			<div class="mb-1">{{ $roster->comments ?? 'No comments' }}</div>
		</div>
		<div class="col-md-6 offset-md-3">
			<h2>6. Jersey Quantities:</h2>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text_left">Size</th>
						<th class="text_right">Quantity</th>
					</tr>
				</thead>
				<tbody>
					@forelse($roster->quantities as $quantity)
						@if($quantity->type == "top")
					<tr>
						<td class="text_left">{{ $quantity->size }}</td>
						<td class="text_right">{{ $quantity->quantity }}</td>
					</tr>
						@endif
					@empty
					<tr>
						<td colspan="2">
							No info.
						</td>
					</tr>
					@endforelse
				</tbody>
				<tfoot>
				<tr>
					<td class="text_left">Total Quantity:</td>
					<td class="text_right">{{ $roster->quantitySumByType('top') }}</td>
				</tr>
				</tfoot>
			</table>
		</div>
		<div class="col-md-6 offset-md-3">
			<h2>7. Shorts or Socks Quantities:</h2>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text_left">Size</th>
						<th class="text_right">Quantity</th>
					</tr>
				</thead>
				<tbody>
					@forelse($roster->quantities as $quantity)
						@if($quantity->type == "short")
					<tr>
						<td class="text_left">{{ $quantity->size }}</td>
						<td class="text_right">{{ $quantity->quantity }}</td>
					</tr>
						@endif
					@empty
					<tr>
						<td colspan="2">
							No info.
						</td>
					</tr>
					@endforelse
				</tbody>
				<tfoot>
					<tr>
						<td class="text_left">Total Quantity:</td>
						<td class="text_right">{{ $roster->quantitySumByType('short') }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<div class="col-md-6 offset-md-3">
			<h2>8. Team Roster:</h2>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th> - </th>
						<th>Size</th>
						<th>Number</th>
						<th>Name</th>
						<th>Note</th>
						<th>Short</th>
					</tr>
				</thead>
				<tbody>
					@forelse($roster->teams as $team)
						<tr style="background-color: {{ $team->rowcolor }} !important;">
							<td class="text_left" style="background-color: {{ $team->rowcolor }} !important;">{{ $loop->iteration }}</td>
							<td class="text_center" style="background-color: {{ $team->rowcolor }} !important;">{{ $team->size }} </td>
							<td class="text_center" style="background-color: {{ $team->rowcolor }} !important;">{{ $team->number }}</td>
							<td class="text_center" style="background-color: {{ $team->rowcolor }} !important;">{{ $team->name }}</td>
							<td class="text_center" style="background-color: {{ $team->rowcolor }} !important;">{{ $team->note }}</td>
							<td class="text_center" style="background-color: {{ $team->rowcolor }} !important;">{{ $team->shortsize }}</td>
						</tr>
					@empty
					<tr>
						<td colspan="4"> No info.</td>
					</tr>
					@endforelse	
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection