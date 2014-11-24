{{$errors->first('error', '<strong>:message</strong>')}}
{{Form::open()}}
{{Form::label('username', '用户名')}}
{{Form::text('username')}}

{{Form::label('password', '密码')}}
{{Form::password('password')}}
{{Form::token()}}

{{Form::label('remember', '记住我')}}
{{Form::checkbox('remember', '1')}}

{{Form::hidden('appid', $clienInfo['id'])}}

{{Form::submit('登陆')}}
{{Form::close()}}


{{HTML::link('/register?appid='.$clienInfo['id'], '注册')}}