@extends('layouts.app',['title' => 'Edit Size'])
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-6 text-left">
			<h4>Edit Size:</h4>
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
	<form action="{{ route('sizes.update',$size->id) }}" method="POST">
		@csrf
		<input name="_method" type="hidden" value="PATCH">
		<div class="form-group">
			<label for="name">Name:</label>
			<input type="text" class="form-control" name="name" placeholder="Size name" value="{{$size->name}}">
		</div>
		<div class="form-group">
			<label for="name">Sort Number (weight):</label>
			<input type="text" class="form-control" name="weight" placeholder="Numerical value" value="{{$size->weight}}">
		</div>
		<div class="form-group">
			<label for="color">Color (Hex value):</label>
			<input type="text" class="form-control" name="color" placeholder="#ffffff" value="{{$size->color}}">
		</div>
		<div class="form-group">
			<label for="color">Private:</label>
			<input type="checkbox" name="private" value="1" {{$size->private == 1 ? 'checked="checked"' : ''}}>
		</div>
		<button class="btn btn-primary" type="submit">Submit</button>
	</form>
</div>
@endsection