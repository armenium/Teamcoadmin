@extends('layouts.app',['title' => 'Add new Builder'])
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css" />
{{--<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}"/>--}}
@endsection
@section('content')
<div class="card-body">
	<div class="row">
		<div class="col-md-12 text-center">
			<h4>Add New Builder:</h4>
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
	<div class="row">
		<div class="form-group center-form">
			<label for="svg">1.Type in/Select product to add builder to:</label>
			<input type="text" id="basics" class="form-control">
		</div>
	</div>
	<div class="row invisible" id="showForm">
		<form action="{{route('builder.store')}}" method="POST" enctype="multipart/form-data" class="center-form">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="name">2.Upload SVG file:</label>
				<input type="hidden" name="shopify_id" id="idProduct">
				<input type="file" class="form-control-file border" name="uploadSVG" >
			</div>
			<button class="btn btn-primary" type="submit">Submit</button>
		</form>
	</div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js"></script>
<script type="text/javascript">

	// Commented by Armen / Original version
	/*var options = {
		url: "/api/info",
		getValue: function(element){
			return element.title;
		},
		list: {
			maxNumberOfElements: 10,
			match: {
				enabled: true
			}
		},
		template: {
			type: "custom",
			method: function(value, item){
				return '<a href="#" onclick="checkAvailability(' + item.id + ')">' + item.title + '</a>';
			}
		}
	};*/
	// end

	// Added by Armen
	var options = {
		url: function(phrase) {
			//console.log(phrase);
			return "/api/info";
		},
		getValue: function(element){
			return element.title;
		},
		requestDelay: 800,
		highlightPhrase: true,
		ajaxSettings: {
			dataType: "json",
			method: "GET",
			data: {
				dataType: "json",
			}
		},
		preparePostData: function(data) {
			data.phrase = $("#basics").val();
			return data;
		},
		list: {
			maxNumberOfElements: 30,
			match: {
				enabled: true
			},
			showAnimation: {
				type: "fade", //normal|slide|fade
				time: 200,
			},

			hideAnimation: {
				type: "slide", //normal|slide|fade
				time: 200,
			}
		},
		template: {
			type: "custom",
			method: function(value, item){
				return '<a href="#" onclick="checkAvailability(' + item.id + ')">' + item.title + '</a>';
			}
		}
	};
	// end

	$("#basics").easyAutocomplete(options);

	function checkAvailability(id){
		$.ajax({
			url: '/api/availability/' + id,
			success: function(result){
				if(result.message == 'yes'){
					$("#showForm").removeClass('invisible');
					$("#idProduct").val(id);
				}else{
					alert('this product has a SVG file');
				}
			}
		});
	}
</script>
@endsection