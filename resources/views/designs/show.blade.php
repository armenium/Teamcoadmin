@extends('layouts.app', ['title' => 'Custom Design '.$design->id])
@section('styles')
<style type="text/css">
	.fa-square:before {text-shadow: 1px 1px 2px #000000;}
	@media print {
		table,
		table th,
		table thead th,
		table tbody th,
		table td,
		table tbody td,
		table tfoot td,
		table tr {border: 1px solid #000 !important;}
		.print-mt-big {margin-top: 80px;}
	}
</style>
@endsection
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-12 text-center"><h4>Design #{{ $design->id }}</h4></div>
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
	<div class="row">
		<div class="col-md-6 offset-md-3 mb-20">
			<h4 class="font-weight-bold font-red font-18">1. Contact and Billing Information:</h4>
			<table class="table table-bordered border-balck narrow-first-col slim-td-padding">
				<tbody>
					<tr>
						<td><b>Reference #:</b></td>
						<td>{{ $design->id }}</td>
					</tr>
					<tr>
						<td><b>Name:</b></td>
						<td>{{ $design->client->name ?? 'No Name' }}</td>
					</tr>
					<tr>
						<td><b>Company / Org:</b></td>
						<td>{{ $design->client->company ?? 'No Company' }}</td>
					</tr>
					<tr>
						<td><b>Address:</b></td>
						<td>{{ $design->client->address ?? 'No Address' }}</td>
					</tr>
					<tr>
						<td><b>Address 2:</b></td>
						<td>{{ $design->client->address_2 ?? 'No Address' }}</td>
					</tr>
					<tr>
						<td><b>City:</b></td>
						<td>{{ $design->client->city ?? 'No City' }}</td>
					</tr>
					<tr>
						<td><b>Prov/State:</b></td>
						<td>{{ $design->client->state ?? 'No State' }}</td>
					</tr>
					<tr>
						<td><b>Postal Code:</b></td>
						<td>{{ $design->client->zip ?? 'No Postal Code' }}</td>
					</tr>
					<tr>
						<td><b>Country:</b></td>
						<td>{{ $design->client->country ?? 'No Country' }}</td>
					</tr>
					<tr>
						<td><b>Email:</b></td>
						<td>{{ $design->client->email ?? 'No Email' }}</td>
					</tr>
					<tr>
						<td><b>Phone:</b></td>
						<td>{{ $design->client->phone ?? 'No Phone' }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6 offset-md-3 mb-20">
			<h4 class="font-weight-bold font-red font-18">2. Jersey Details:</h4>
			<table class="table table-bordered border-balck narrow-first-col slim-td-padding">
				<tbody>
					<tr>
						<td><b>Type of jerseys:</b></td>
						<td>{{ $design->type_of_jerseys }}</td>
					</tr>
					<tr>
						<td><b>Accessory Items:</b></td>
						<td>{{ $design->accessory_items ?? 'No Items' }}</td>
					</tr>
					<tr>
						<td><b>Quantity Required:</b></td>
						<td>{{ $design->quantity_required ?? 'No Quantity' }}</td>
					</tr>
					<tr>
						<td><b>Date Required:</b></td>
						<td>{{ $design->date_required ?? 'No Date' }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-6 offset-md-3 mb-20">
			<h4 class="font-weight-bold font-red font-18">3. Design Details:</h4>
			<table class="table table-bordered border-balck narrow-first-col slim-td-padding">
				<tbody>
					<tr>
						<td><b>Description of Design:</b></td>
						<td>{!! $design->description ? nl2br($design->description) : 'No description' !!}</td>
					</tr>
					<tr>
						<td><b>Artwork Placement:</b></td>
						<td>{!! $design->artwork ? nl2br($design->artwork) : 'No Artwork' !!}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection