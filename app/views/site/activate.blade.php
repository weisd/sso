@extends('layouts.master')


@section('content')

<?php switch($type):?>
<?php case 0:?>
链接失效

<?php break; case 1:?>
已经发送邮件到{{$email}}请注意查收<br>
{{HTML::link(mail2Domain($email), 'go to your mail')}}

<?php break; case 2:?>
激活成功！
{{HTML::link(route('login'), '跳到登陆页')}}

<?php break; case 3:?>
你的邮箱未激活，点击
{{Form::open()}}
{{form::token()}}
{{Form::submit('重新发送激活邮件')}}
{{Form::close()}}

<?php break; case 4:?>
你的帐号已经激活，不用重复激活

<?php endswitch;?>


@stop