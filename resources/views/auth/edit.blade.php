@extends('layouts.external',['title' => 'Edit User'])
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">
				<div class="card-header bg-dark">
					<span class="text-light">
						Edit User
					</span>
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
					<form action="{{ route('update.user',$user->id) }}" method="POST">
						@csrf
						<input name="_method" type="hidden" value="{{ (Auth::user()->id == $user->id)?'PUT':'PATCH' }}">
						<input name="type_form" type="hidden" value="only_data">
						<div class="form-group row">
							<label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
							<div class="col-md-6">
								<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}" required autofocus>
								@if ($errors->has('name'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('name') }}</strong>
								</span>
								@endif
							</div>
						</div>
						@if(Auth::user()->id != $user->id)
						<div class="form-group row">
							<label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
							<div class="col-md-6">
								<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}" required>
								@if ($errors->has('email'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('email') }}</strong>
								</span>
								@endif
							</div>
						</div>
						@endif
						<input type="hidden" name="type_form" value="{{ (Auth::user()->id != $user->id)?'general_user':'my_user' }}">
						<div class="form-group row">
							<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
							<div class="col-md-6">
								<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
								@if ($errors->has('password'))
								<span class="invalid-feedback" role="alert">
									<strong>{{ $errors->first('password') }}</strong>
								</span>
								@endif
							</div>
						</div>
						<div class="form-group row">
							<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
							<div class="col-md-6">
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
							</div>
						</div>
						<div class="form-group row mb-0">
							<div class="col-md-6 offset-md-4">
								<button type="submit" class="btn btn-primary">
								{{ __('Update') }}
								</button>
							</div>
						</div>
					</form>
					<!--<ul class="nav justify-content-center nav-tabs" role="tablist">
							<li class="nav-item">
									<a class="nav-link active" href="#profile" role="tab" data-toggle="tab">Edit Personal Info</a>
							</li>
							<li class="nav-item">
									<a class="nav-link" href="#buzz" role="tab" data-toggle="tab">Edit Password</a>
							</li>
					</ul>
					<div class="tab-content">
							<div role="tabpanel" class="tab-pane fade show active" id="profile">
										
										
							</div>
							<div role="tabpanel" class="tab-pane fade" id="buzz">
										<form action="{{ route('update.user',$user->id) }}" method="POST">
													@csrf
													<input name="_method" type="hidden" value="PATCH">
											<input name="type_form" type="hidden" value="only_password">
											<div class="form-group row">
													<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
													<div class="col-md-6">
															<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">
															@if ($errors->has('password'))
															<span class="invalid-feedback" role="alert">
																	<strong>{{ $errors->first('password') }}</strong>
															</span>
															@endif
													</div>
											</div>
											<div class="form-group row">
													<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
													<div class="col-md-6">
															<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
													</div>
											</div>
											<div class="form-group row mb-0">
													<div class="col-md-6 offset-md-4">
															<button type="submit" class="btn btn-primary">
															{{ __('Update') }}
															</button>
													</div>
											</div>
										</form>
							</div>
					</div>-->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('styles')
<style>
.tab-pane form{
margin-top:15px;
}
</style>
@endsection