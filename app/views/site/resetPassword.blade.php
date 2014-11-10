@extends('layouts.master')


@section('content')

@if($type == 0)
链接已失效！

@else

@if( Session::get('error') )
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <strong>{{ Session::get('error') }}</strong>
        </div>
@endif

{{Form::open()}}
{{Form::label('email', '注册时的邮箱')}}
{{Form::email('email', Input::old('email'))}}
{{ $errors->first('email', '<strong class="error">:message</strong>') }}

{{Form::label('password', '新密码：')}}
{{Form::password('password')}}
{{ $errors->first('password', '<strong class="error">:message</strong>') }}

{{Form::label('password_confirmation', '确认密码：')}}
{{Form::password('password_confirmation')}}

{{Form::hidden('token', $token)}}

{{Form::submit('提交')}}

{{Form::close()}}
@endif


@stop

