<?php
/**
 * sso system
 */
class SSOController extends Controller {

	public function login() {
		//1.判断来源是不是所在子系统 appkey, appsecrit
		$appid = Input::get('appid');
		if (empty($appid)) {
			return App::abort(404);
		}

		// 验证
		$res = ApiHTTP::GetSSOClient($appid);
		if ($res['code'] !== 200) {
			return App::abort(404);
		}
		$clienInfo = $res['data'];

		Log::info('login clieninfo :' . json_encode($clienInfo));

		//2.判断是否登陆，已登陆，带上子系统id 跳转到login_api
		if (!empty(Session::get('uid'))) {
			Log::info('已登陆: ' . Session::get('uid'));
			$tikect = Rsso::generate_ticket($clienInfo['id']);
			$callback_url = 'http://' . $clienInfo['url'] . $clienInfo['login'] . '?tikect=' . $tikect;
			return Redirect::to($callback_url);
		}

		//3.显示登陆表单页
		return View::make('sso.login', compact('clienInfo'));
	}

	public function loginPost() {
		$username = Input::get('username');
		$password = Input::get('password');
		$remember_me = Input::get('remember', 0);
		$appid = Input::get('appid');

		// 验证
		if (empty($username) || empty($password)) {
			return Redirect::back()->withInput()->withErrors(['error' => '用户名、密码不能为空']);
		}

		// @todo 完全过滤

		// 登陆所有子系统
		$res = ApiHTTP::GetSSOClient($appid);
		if ($res['code'] != 200) {
			return Redirect::back()->withInput()->withErrors(['error' => '未找到登陆系统']);
		}

		$client = $res['data'];
		// appid nofound
		if (empty($client)) {
			return Redirect::back()->withInput()->withErrors(['error' => '登陆系统有误']);
		}

		// 验证登陆
		$res = ApiHTTP::checkLogin($username, $password);
		if ($res['code'] != 200) {
			return Redirect::back()->withInput()->withErrors(['error' => '用户名、密码错误']);
		}

		$user_info = $res['data'];
		if (empty($user_info)) {
			return Redirect::back()->withInput()->withErrors(['error' => '用户名、密码错误']);
		}
		// 登陆用户
		// $user = Auth::loginUsingId($user_info['id'], $remember_me);

		Session::regenerate();

		// $sessionId = Session::getId();

		// Log::info('send session id : ' . $sessionId);

		Session::put('uid', $user_info['id']);

		$tikect = Rsso::generate_ticket($client['id']);

		$callback_url = 'http://' . $client['url'] . $client['login'] . '?tikect=' . $tikect;
		return Redirect::to($callback_url);
	}

	/**
	 * 直接登陆
	 * @return string
	 */
	public function doLogin() {
		$token = Input::get('token');

		if (empty($token)) {
			return Response::json(['code' => 401, 'msg' => 'params error']);
		}

		// 解出appid & uid
		try {
			$tokenStr = Crypt::decrypt($token);
		} catch (Exception $e) {
			return Response::json(['code' => 401, 'msg' => 'token error']);
		}

		list($appid, $uid, $appkey) = explode('|', $tokenStr);

		// 登陆所有子系统
		$res = ApiHTTP::GetSSOClient($appid);
		if ($res['code'] != 200) {
			return Response::json(['code' => 404, 'msg' => 'app not found']);
		}

		$client = $res['data'];

		// 验证appkey是否正确

		// 用户是否存在
		$res = ApiHTTP::GetUserInfo($uid);
		if ($res['code'] != 200) {
			return Response::json(['code' => 404, 'msg' => 'user not found']);
		}

		Session::regenerate();

		Session::put('uid', $uid);

		$tikect = Rsso::generate_ticket($client['id']);

		return Response::json(['code' => 200, 'data' => $tikect]);

	}

	public function sync() {
		$tikect = Input::get('tikect');
		$sid = Input::get('sid');

		if (empty($tikect) || empty($sid)) {
			return ['code' => 401, 'msg' => 'params error'];
		}

		$return = ['code' => 200, 'msg' => ''];

		if (Cache::has($tikect)) {
			$data = Cache::get($tikect);
			$info = json_decode($data, true);

			Log::info('tikect info : ' . $data);
			Cache::forget($tikect);
			if (empty($info)) {
				$return['code'] = 404;
				$return['msg'] = 'tikect info no found';
				return Response::json($return);
			}

			// 更新session
			Session::setId($info['sid']);
			Session::start();

			Log::info('sync get session id : ' . Session::getId());

			// dd(Session::all());

			$return['uid'] = Session::Get('uid');
			$uuid = Rhumsaa\Uuid\Uuid::uuid4();
			$ptoken = md5($uuid . 'sdfsdf');
			$return['ptoken'] = $ptoken;

			$uuid = Rhumsaa\Uuid\Uuid::uuid4();
			$ptlogout = md5($uuid . 'sdfdsf');
			$return['ptlogout'] = $ptlogout;

			$ptInfo = ['ptlogout' => $ptlogout, 'ptoken' => $ptoken, 'sid' => $sid];

			$ptlogin = (array) Session::get('ptlogin');

			// Log::info('---- ---' . json_encode($ptlogin));

			$ptlogin[$info['appid']] = $ptInfo;

			Session::put('ptlogin', $ptlogin);

			// Log::info('retourn : ' . json_encode($return));
			return Response::json($return);
		} else {
			$return['code'] = 404;
			$return['msg'] = 'tikect no found';
			return Response::json($return);
		}
	}

	/**
	 * 同步退出
	 * @return [type] [description]
	 */
	public function logout() {
		//1.判断来源是不是所在子系统 appkey, appsecrit
		Log::info('get params : ' . json_encode(Input::all()));
		$appid = Input::get('appid');
		$ptoken = Input::get('ptoken');
		if (empty($appid) || empty($ptoken)) {
			return App::abort(404);
		}

		$ptlogin = (array) Session::get('ptlogin');

		$curr_ptoken = isset($ptlogin[$appid]) ? $ptlogin[$appid]['ptoken'] : '';
		if ($ptoken != $curr_ptoken) {
			return App::abort(404);
		}

		// 验证
		$res = ApiHTTP::GetSSOClient($appid);
		if ($res['code'] !== 200) {
			return 'checkSSOClient';
			return App::abort(404);
		}
		$clienInfo = $res['data'];

		$clients = ApiHTTP::GetSSOClients();
		if ($clients['code'] != 200) {
			return App::abort(500);
		}

		$client_infos = [];

		foreach ($clients['data'] as $key => $value) {
			$client_infos[$value['id']] = $value;
		}

		Log::info('clients : ' . json_encode($client_infos));

		Log::info('ptlogin' . json_encode($ptlogin));
		foreach ($ptlogin as $k => $v) {
			// if ($k == $clienInfo['id']) {
			// 	Log::info('自己退出 ！！');
			// 	continue;
			// }
			$ptlogout = $v['ptlogout'];
			$sid = $v['sid'];
			$url = 'http://' . $client_infos[$k]['url'] . '/sso/logout?' . http_build_query(['tikect' => $ptlogout, 'sid' => $sid]);
			Log::info('out url : ' . $k . ' : ' . $url);
			$ret = file_get_contents($url);

			Log::info('logout ' . $k . ': ' . $ret);
		}

		// Auth::logout();
		Session::flush();
		// Session::regenerate();

		//2.判断是否登陆，已登陆，带上子系统id 跳转到login_api

		$callback_url = 'http://' . $clienInfo['url'];
		return Redirect::to($callback_url);

	}

	// 激活 重发
	public function activate() {
		// 生成激活码，发送邮件
		// 回调
	}

	// 激活确认
	public function confirm() {

	}

	// 注册
	public function register() {
		//1.判断来源是不是所在子系统 appkey, appsecrit
		$appid = Input::get('appid');
		if (empty($appid)) {
			return App::abort(404);
		}

		// 验证
		$res = ApiHTTP::GetSSOClient($appid);
		if ($res['code'] !== 200) {
			return App::abort(404);
		}
		$clienInfo = $res['data'];

		return View::make('sso.register', compact('clienInfo'));
	}

	// 注册
	public function registerPost() {
		// 验证数据
		$data = Input::all();
		// 验证规则
		// @todo username:中文、英文、数字、下划线 , 用户名过滤
		$rules = ['username' => 'required|between:3,16', 'email' => 'required|email', 'password' => 'required|between:6,16|confirmed'];
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
		if ($validator->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		}

		// @todo 用户是否已存在
		// @todo email 是否已存在

		// 生成用户信息 入库
		//
		// 发送激活邮件
		// 提示激活

		dd($data);
	}

	// 找
	// 回密码
	public function forget() {
		# code...
	}

	// 重设密码
	public function reset() {
		# code...
	}

	// 修改头像
	public function avatar() {
		# code...
	}
}