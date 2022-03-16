@extends('layouts.app', ['title' => 'Option '.$model->id])
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
		<div class="col-md-12 text-center"><h4>Option #{{ $model->id }}</h4></div>
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
			<h4 class="font-weight-bold font-red font-18">Option data:</h4>
			<table class="table table-bordered border-balck narrow-first-col slim-td-padding">
				<tbody>
					<tr>
						<td><b>ID:</b></td>
						<td>{{ $model->id }}</td>
					</tr>
					<tr>
						<td><b>Name:</b></td>
						<td>{{ $model->name ?? 'No Name' }}</td>
					</tr>
					<tr>
						<td><b>Value:</b></td>
						<td>{{ $model->value ?? 'No Value' }}</td>
					</tr>
					<tr>
						<td><b>Active:</b></td>
						<td>{{ $model->active ? 'Yes' : 'No' }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection