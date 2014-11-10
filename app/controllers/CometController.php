<?php
/**
 * im comet 接口
 */
class CometController extends BaseController {

	/**
	 * 取comet服务地址
	 */
	public function servers() {
		$key = Input::get('key', '');
		$from = Input::get('from', 'software');

		$proto = 1;
		if ($from == 'software') {
			$proto = 2;
		}

		if (empty($key)) {
			return $this->error('400', 'params failt');
		}

		// helpers/gopush  取comet
		$res = Gopush::getComets($key, $proto);
		if (empty($res)) {
			return $this->error(500, 'parse json error');
		}

		if ($res['ret'] != 0) {
			// 参数错误
			if ($res['ret'] == 65534) {
				return $this->error('400', 'get params error');
			}
			Log::debug('gopush error : ', json_encode($res));
			return $this->error(500, 'server error');
		}

		//@todo 记录key 后台统计

		$servers = $res['data']['server'];
		// @todo 处理 内网与外网映射

		$serv = $servers[mt_rand(0, 1)];

		$server = ['server' => $serv];

		return $this->success($server);
	}

}
?>