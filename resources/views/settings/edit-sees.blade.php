@extends('layouts.app',['title' => 'Edit Size'])
@section('content')
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 text-left">
                <h4>Edit Setting Option:</h4>
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
        <form action="{{ route('settings.update',$settings->id) }}" method="POST">
            @csrf
            <input name="_method" type="hidden" value="PATCH">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" placeholder="Option name" value="{{$settings->name}}">
            </div>
            <div class="form-group">
                <label for="name">Value:</label>
                <table id="js_multi_data_table">
                    @foreach($json_data as $k => $v)
                        <tr>
                            <td><input type="text" class="form-control" name="value[{{$k}}][name]" placeholder="Service name" value="{{$v['name']}}"></td>
                            <td><input type="checkbox" class="form-control" name="value[{{$v}}][value]" value="{{$v['value']}}" {{$v['value'] ? 'checked' : ''}}></td>
                            <td><button class="js_remove_row">Remove</button></td>
                        </tr>
                    @endforeach
                </table>
                <button>Add new</button>
            </div>
            <button class="btn btn-primary" type="submit">Submit</button>
        </form>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function($){

            var BJS = {
                els: {
                    js_multi_data_table: $('#js_multi_data_table'),
                },
                Init: function(){
                    this.initEvents();
                },
                initEvents: function(){
                    $(document)
                        .on('click', '.js_remove_row', BJS.remove_row);
                        .on('click', '.js_add_row', BJS.add_row);
                },
                remove_row: function($btn){
                    $btn.parents('tr').remove();
                },
                add_row: function($btn){
                    var $tr = $('<tr>');
                    $tr.append('<td><input type="text" class="form-control" name="value[][name]" placeholder="Service name" value=""></td>');
                    $tr.append('<td><input type="checkbox" class="form-control" name="value[][value]" value="1"></td>');
                    $tr.append('<td><button class="js_remove_row">Remove</button></td>');
                    BJS.els.js_multi_data_table.append(tr);
                },
            };

            BJS.Init();

        });
    </script>
@endsection
