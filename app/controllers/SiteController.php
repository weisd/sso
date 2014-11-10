<?php
/**
 * 主页
 */

class SiteController extends BaseController {

	// public function __construct()
	// {
	// 	$this->beforeFilter('csrf', ['on'=>'post']);
	// }

	/**
	 * 首页
	 */
	public function getIndex() {
		return View::make('hello');
	}

	/**
	 * 登陆页
	 */
	public function getSignin() {
		// @todo 已登陆，跳转
		return View::make('site.signin');
	}

	/**
	 * 登陆提交
	 */
	public function postSignin() {
		// 验证
		$credentials = ['email' => Input::get('email'), 'password' => Input::get('password')];
		$remember = Input::get('remember', 0);

		if (!Auth::attempt($credentials, $remember)) {
			return Redirect::back()->withInput()->withErrors(['error' => '邮箱或密码不正确']);
		}

		return Redirect::intended();
	}

	/**
	 * 注册页
	 * @return [type] [description]
	 */
	public function getSignup() {
		return View::make('site.signup');
	}

	/**
	 * 注册信息提交
	 */
	public function postSignup() {
		// @todo 添加配置开关，是否允许注册

		// @todo 超过三次出验证码！

		// 验证数据
		$data = Input::all();
		// 验证规则
		// @todo username:中文、英文、数字、下划线 , 用户名过滤
		$rules = ['username' => 'required|between:3,16|unique:users', 'email' => 'required|email|unique:users', 'password' => 'required|between:6,16|confirmed'];
		// 验证消息
		$messages = [
			'username.required' => '请输入用户名',
			'username.between' => '用户名长度请保持在:min到:max位之间',
			'email.required' => '请输入邮箱地址',
			'email.email' => '请输入正确的邮箱地址',
			'password.required' => '请输入密码',
			'password.confirmed' => '两次输入的密码不一致',
			'password.between' => '密码长度请保持在:min到:max位之间',
		];

		// 开始验证
		$validator = Validator::make($data, $rules, $messages);
		// 如果验证失败... 要验证成功用passes()方法
		if ($validator          ->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		}

		// 存用户信息
		$user = new User;
		$user->username = $data['username'];
		$user->email = $data['email'];
		$user->password = $data['password'];
		if (!$user              ->save()) {
			return Redirect::back()->withInput()->withErrors(['error' => '注册失败：数据库保存失败']);
		}

		return $this->sendActivateMail($user->email);
	}

	/**
	 * 激活
	 */
	public function getActivate($code = '') {
		// 如果 用户登陆，未激活，显示重发激活链接
		if (Auth::check()) {
			return View::make('site.activate', ['type' => 3, 'email' => Auth::user()->email]);
		}
		// 查找code 是否存在
		$activation = Activation::where('token', $code)->first();

		// 不存在，提交失效
		if (is_null($activation)) {
			return View::make('site.activate', ['type' => 0, 'email' => '']);
		}
		// 存在，激活
		$user = User::where('email', $activation->email)->first();
		$user->activated_at = new Carbon;
		$user->save();

		// 删除code 记录
		$activation->delete();

		// 提示激活成功
		return View::make('site.activate', ['type' => 2, 'email' => '']);
	}

	/**
	 * 重新发送激活邮件
	 */
	public function postActivate() {
		// 验证登陆
		// 没登陆 不重发激活邮件
		if (!Auth::check()) {
			return Redirect::to('/');
		}

		$email = Auth::user()->email;

		// 用户已激活
		if (Auth::user()->activated_at) {
			return View::make('site.activate', ['type' => 4, 'email' => $email]);
		}

		// 重新生成激活码
		Activation::where('email', $email)->delete();

		// 发送邮件
		return $this->sendActivateMail($email);
	}

	/**
	 * 发送激活邮件
	 * @param  [type] $email [description]
	 * @return [type]        [description]
	 */
	private function sendActivateMail($email) {
		// 生成验证连接 @todo 定时清理过期数据
		$activation = new Activation;
		$activation->email = $email;
		$activation->token = str_random(40);
		$activation->save();

		// 发送验证邮件
		// @todo 队列发送
		Mail::send('emails.site.activation', ['activationCode' => $activation->token], function ($message) use ($email) {
			$message->to($email)->subject('帐号激活邮件');
		});

		// 显示发送成功
		return View::make('site.activate', ['type' => 1, 'email' => $email]);
	}

	/**
	 * 找回密码页
	 */
	public function getForgetPassword() {
		return View::make('site.forgetPassword');
	}

	/**
	 * 找回密码，提交
	 */
	public function postForgetPassword() {
		$email = Input::only('email');
		// 调用系统提供的类
		$response = Password::remind($email, function ($m, $user, $token) {
			$m->subject('weisd/sns 密码重置邮件');// 标题
		});

		// 检测邮箱并发送密码重置邮件
		$message = Lang::get($response);
		switch ($response) {
			case Password::INVALID_USER:
				return Redirect::back()->with('error', $message)->withInput();
			case Password::REMINDER_SENT:
				// @todo
				return Redirect::back()->with('status', $message)->withInput();
		}
	}

	/**
	 * 重设密码页
	 * @param  [type] $code [description]
	 * @return [type]       [description]
	 */
	public function getResetPassword($token = '') {
		// 数据库中无令牌
		$reminder = PasswordReminder::where('token', $token)->first();
		if (is_null($reminder)) {
			$type = 0;
		} else {
			$type = 1;
		}

		return View::make('site.resetPassword', ['type' => $type, 'token' => $token]);
	}

	/**
	 * 重设密码，提交
	 * @return [type] [description]
	 */
	public function postResetPassword() {
		// 调用系统自带密码重置流程
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function ($user, $password) {
			// 保存新密码
			$user->password = $password;
			$user->save();
			// 登录用户
			Auth::login($user);
		});

		switch ($response) {
			case Password::INVALID_PASSWORD:
				// no break
			case Password::INVALID_TOKEN:
				// no break
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));
			case Password::PASSWORD_RESET:
				return Redirect::to('/');
		}
	}

	/**
	 * 动作：退出
	 * @return Response
	 */
	public function getSignout() {
		Auth::logout();
		return Redirect::to('/');
	}
}
?>