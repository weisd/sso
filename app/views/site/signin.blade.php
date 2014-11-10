@extends('layouts.master')

@section('content')

{{$errors->first('error', '<strong>:message</strong>')}}

{{Form::open()}}
{{Form::label('email', '邮箱地址')}}
{{Form::text('email')}}

{{Form::label('password', '密码')}}
{{Form::password('password')}}

{{Form::label('remember', '记住我')}}
{{Form::checkbox('remember')}}

{{Form::submit('登陆')}}
{{Form::close()}}

<a href="/forget-password">找回密码</a>

@stop