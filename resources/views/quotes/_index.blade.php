@extends('layouts.app',['title' => 'Manage Quotes'])
@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"/>
{{--<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"/>--}}
@endsection
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-12 text-center"><h4>Show all Quotes Request:</h4></div>
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
		<table class="table table-striped text-center" id="table">
			<thead class="thead-dark">
				<tr>
					<th>Reference #</th>
					<th>Name</th>
					<th>Organization</th>
					<th>Date Submitted</th>
					<th>Total Qty</th>
					<th>View</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				@forelse($quotes as $quote)
				<tr>
					<td>{{ $quote->id }}</td>
					<td>{{ $quote->client->name }}</td>
					<td>{{ $quote->client->company }}</td>
					<td>{{ $quote->created_at->format('M d, Y') }}</td>
					<td>{{ $quote->styles->sum('quantity') }}</td>
					<td>
						<a href="{{ route('quotes.show',$quote->id) }}" class="btn btn-primary">View Details</a>
					<!--<a href="{{ route('test.emails.admin',$quote->id) }}" class="btn btn-success">Render Email admin</a>
						<a href="{{ route('test.emails.client',$quote->id) }}" class="btn btn-success">Render Email CLient</a>-->
					</td>
					<td>
						<button class="btn btn-danger" data-toggle="modal" data-target="#myModal_{{$quote->id}}" title="Delete"><i class="fa fa-trash"></i></button>
						<div id="myModal_{{$quote->id}}" class="modal fade" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header bg-dark text-center">
										<h4 class="modal-title text-light">You want delete this Quote?</h4>
										<button type="button" class="close text-light" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-body">
										<form action="{{ route('quotes.destroy',$quote->id) }}" method="POST">
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
@endsection
@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#table').DataTable({
			"order": [[ 0, "desc" ]],
			"pageLength": 25,
			"lengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
		});
	});
</script>
@endsection
