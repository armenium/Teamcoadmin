@extends('layouts.app', ['title' => 'Manage Jersey Builder'])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-dark">
                    <div class="row">
                        <div class="col-md-4"><a class="text-light" href="{{route('builder.index')}}">Manage Jersey Builder</a></div>
                        <div class="col-md-4"><a class="text-light" href="{{route('builder.create')}}">Add New Builder </a></div>
                        <div class="col-md-4"><a class="text-light" href="{{route('color.index')}}">Manage Colours</a></div>
                    </div>
                </div>
                <hr>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
