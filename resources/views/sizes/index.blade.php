@extends('layouts.app',['title' => 'Manage Sizes'])
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.2.5/css/rowReorder.bootstrap4.min.css"/>
{{--<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"/>--}}
@endsection
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-6 text-left"><h4>Manage Sizes:</h4></div>
		<div class="col-md-6 text-right"><a href="{{ route('sizes.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>Add Size</a></div>
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
		<table class="table table-striped text-center display" id="table" width="100%">
			<thead class="thead-dark">
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Weight</th>
					<th>Color</th>
					<th>Preview</th>
					<th>Private</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@forelse($Sizes as $size)
				<tr>
					<td>{{ $loop->iteration }}</td>
					<td>{{ $size->name }}</td>
					<td>{{ $size->weight }}</td>
					<td>{{ $size->color }}</td>
					<td>
						<i class="fa fa-square" style="color:{{$size->color}};font-size: 30px;"></i>
					</td>
					<td>{{ $size->private == 1 ? "Yes" : "No" }}</td>
					<td><a href="{{route('sizes.edit',$size->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
					<td>
						<button class="btn btn-danger" data-toggle="modal" data-target="#myModal_{{$size->id}}" title="Delete"><i class="fa fa-trash"></i></button>
						<div id="myModal_{{$size->id}}" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header bg-dark text-center">
										<h4 class="modal-title text-light">You want delete this Size?</h4>
										<button type="button" class="close text-light" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-body">
										<form action="{{ route('sizes.destroy',$size->id) }}" method="POST">
											@csrf
											@method('DELETE')
											<div class="row">
												<div class="col-md-6 text-center"><button type="submit" class="btn btn-danger">Delete</button></div>
												<div class="col-md-6 text-center"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
											</div>
										</form>
									</div>
									<div class="modal-footer">
									</div>
								</div>
							</div>
						</div>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="4">
						Empty
					</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@endsection