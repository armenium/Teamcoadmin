@section('content')
<div class="row mb-3 align-items-center d-none">
	<div class="col-12"><input type="text" class="form-control" name="country_code" placeholder="Country (code)" value="CA"></div>
</div>
<div class="row mb-3 align-items-center">
	<div class="col-12"><input type="text" class="form-control" name="state_province" placeholder="Province (Abbreviation)"></div>
</div>
<div class="row mb-3 align-items-center">
	<div class="col-12"><input type="text" class="form-control" name="postal_code" placeholder="Postal Code"></div>
</div>
@if(!empty($custom_fields))
	<div class="row mb-3 align-items-center">
		<div class="col-4">Jersey Type:</div>
		<div class="col-8">
			@foreach($custom_fields as $k => $v)
				@if($v['status'] == 1)
					<label class="custom-radio"><input type="radio" name="jersey_type" value="{{$k}}"> {{$v['title']}}</label>
				@endif
			@endforeach
		</div>
	</div>
@endif
<div class="row mb-3 align-items-center">
	<div class="col-12">
		<input type="number" class="form-control" name="units" min="1" max="60" value="" placeholder="Total # of Units in Order (max 60)">
		<div class="input-desc">(Please include the total number of jerseys, shorts, and/or socks that would be in the order. [E.g. an order for 20 soccer jerseys and 20 soccer shorts would have a total quantity of 40])</div>
	</div>
</div>
@endsection