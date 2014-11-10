<?php
/**
 * message
 */
class MessageController extends BaseController {

	public function push() {
		$key = Input::get('key', '');
		$token = Input::get('token', '');
		$content = Input::get('content', '');
		$code = Input::get('code', '');
		$from = Input::get('from', 'software');

		if (empty($key) || empty($code) || empty($content)) {
			return $this->error('400', 'params failt');
		}

		$user_info = Ruser::get_info_by_kt_token($token);
		if (empty($user_info)) {
			return $this->error('401', 'auth failt');
		}

		// @todo 权限验证
		// @todo 内容过虑

		$pushData = [];
		$pushData['uid'] = $user_info['id'];
		$pushData['username'] = $user_info['nick_name'];
		$pushData['content'] = $content;
		$pushData['code'] = $code;
		$pushData['created'] = time();

		$res = Gopush::pushMsg($key, json_encode($pushData));
		if ($res['ret'] != 0) {
			// 参数错误
			if ($res['ret'] == 65534) {
				return $this->error('400', 'get params error');
			}
			Log::warning('gopush error : ' . json_encode($res));
			return $this->error(500, 'server error');
		}

		// @todo 自已处理消息持久保存

		return $this->success('ok');
	}

	/**
	 * 历史消息
	 * @return [type] [description]
	 */
	public function history() {
		$key = Input::get('key', '');
		$mid = Input::get('mid', 0);
		$cb = Input::get('cb', '');

		if (empty($key)) {
			return $this->error('400', 'params failt');
		}

		$res = Gopush::historyMsg($key, $mid, $cb);
		dd($res);
		if ($res['ret'] != 0) {
			// 参数错误
			if ($res['ret'] == 65534) {
				return $this->error('400', 'get params error');
			}
			Log::warning('gopush error : ' . json_encode($res));
			return $this->error(500, 'server error');
		}

		return $this->success($res['data']);
	}

	/**
	 * 初始mid
	 * @return [type] [description]
	 */
	public function mid() {

	}
}
?>