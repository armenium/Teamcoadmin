@extends('layouts.app',['title' => 'Create Colour'])
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-6 text-left">
			<h4>Add Colour:</h4>
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
	@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<form action="{{ route('color.store') }}" method="POST">
		{{ csrf_field() }}
		<div class="form-group">
			<label for="name">Name:</label>
			<input type="text" class="form-control" name="name" placeholder="White">
		</div>
		<div class="form-group">
			<label for="value_code">Hex value:</label>
			<input type="text" class="form-control" name="value_code" placeholder="#ffffff">
		</div>
		<button class="btn btn-primary" type="submit">Submit</button>
	</form>
</div>
@endsection