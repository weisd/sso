@extends('layouts.master')


@section('content')

@if( Session::get('error') )
<div class="alert alert-warning alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>{{ Session::get('error') }}</strong>
</div>

@elseif( Session::get('status') )
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>{{ Session::get('status') }}</strong>
</div>
@endif

{{Form::open()}}
{{Form::label('email', 'Email：')}}
{{Form::email('email', Input::old('email'))}}
{{Form::submit('提交')}}
{{Form::close()}}


@stop