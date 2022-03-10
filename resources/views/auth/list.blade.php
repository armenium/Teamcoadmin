@extends('layouts.external',['title' => 'List Users'])
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"/>
{{--<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"/>--}}
@endsection
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header bg-dark">
					<div class="row">
						<div class="col-md-6 text-left">
							<span class="text-light">List of Users</span>
						</div>
						<div class="col-md-6 text-right">
							<a href="{{route('register')}}" class="btn btn-primary"><i class="fa fa-user-plus" title="add user"></i>Add User</a>
						</div>
					</div>
					
				</div>
					@if(session('status'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
	                    {{ session('status') }}
	                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>	
					@endif
					<div class="card-body">
						<table class="table table-striped text-center display" id="table">
								<thead>
									<tr>
										<th>Name</th>
										<th>Email</th>
										<th>Edit</th>
										<th>Remove</th>
									</tr>
								</thead>
								<tbody>
									@forelse($users as $user)
										<tr>
										<td>{{ $user->name }}</td>
										<td>{{ $user->email }}</td>
										<td>  <a class="btn btn-info text-light" href="{{route('edit.user',$user->id)}}" title="Edit"><i class="fa fa-edit"></i></a></td>
										<td>
											<button class="btn btn-danger" data-toggle="modal" data-target="#myModal_{{$user->id}}" title="Delete"><i class="fa fa-trash"></i></button>
											<div id="myModal_{{$user->id}}" class="modal fade" role="dialog">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header bg-dark text-center">
														<h4 class="modal-title text-light">You want delete this User?</h4>
														<button type="button" class="close text-light" data-dismiss="modal">&times;</button>
													</div>
													<div class="modal-body">
														<form action="{{ route('destroy.user',$user->id) }}" method="POST">
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
									@endforelse
								</tbody>
							</table>	
					</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#table').DataTable({
			"order": [[ 0, "asc" ]],
			"pageLength": 50,
			"lengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]]
		});
	});
</script>
@endsection