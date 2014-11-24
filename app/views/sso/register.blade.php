{{Form::open()}}

	{{Form::label('username', '用户名')}}
	{{Form::text('username')}}
	{{$errors->first('username', '<strong>:message</strong>')}}
	<br>
	{{Form::label('email', 'email')}}
	{{Form::text('email')}}
	{{$errors->first('email', '<strong>:message</strong>')}}
<br>
	{{Form::label('password', '密码')}}
	{{Form::password('password')}}
	{{$errors->first('password', '<strong>:message</strong>')}}
<br>
	{{Form::label('确认密码：')}}
	{{Form::password('password_confirmation')}}
<br>
	{{Form::token()}}
	{{Form::hidden('appid', $clienInfo['id'])}}

	{{Form::submit('注册')}}

{{Form::close()}}

{{HTML::link('/login?appid='.$clienInfo['id'], '登陆')}}