@extends('layouts.master')


@section('content')

{{Form::open()}}

	{{Form::label('username')}}
	{{Form::text('username')}}
	{{$errors->first('username', '<strong>:message</strong>')}}

	{{Form::label('email')}}
	{{Form::email('email')}}
	{{$errors->first('email', '<strong>:message</strong>')}}

	{{Form::label('密码：')}}
	{{Form::password('password')}}
	{{$errors->first('password', '<strong>:message</strong>')}}

	{{Form::label('确认密码：')}}
	{{Form::password('password_confirmation')}}

	{{Form::submit('注册')}}

{{Form::close()}}


<a href="/signin">登陆</a>

@stop