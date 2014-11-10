<?php
/**
 * gopush
 * @uses  guzzlehttp/guzzle [description]
 */
class Gopush {

	/**
	 * 取comet服务器地址
	 * @param  string  $key   [description]
	 * @param  integer $proto [description]
	 * @param  string  $cb    [description]
	 * @return json         [description]
	 */
	public static function getComets($key, $proto = 2, $cb = '') {

		$server_addr = Config::get('gopush.server_addr', 'localhost');
		$web_port = Config::get('gopush.web_port', '8090');

		$url = 'http://' . $server_addr . ':' . $web_port . '/2/server/get';
		$client = new GuzzleHttp\Client();

		$response = $client->get($url, ['query' => ['k' => $key, 'p' => $proto, 'cb' => $cb]]);
		$code = $response->getStatusCode();
		if ($code != 200) {
			Log::error("gopush getComets http client failt " . $code);
			return ['ret' => 65544];
		}

		return $response->json();
	}

	/**
	 * 发送消息
	 * @param  [type] $key     [description]
	 * @param  string $content [description]
	 * @return [type]          [description]
	 */
	public static function pushMsg($key, $content = '') {
		$server_addr = Config::get('gopush.server_addr', 'localhost');
		$admin_port = Config::get('gopush.admin_port', '8091');

		$url = 'http://' . $server_addr . ':' . $admin_port . '/1/admin/push/private?key=' . $key . '&expire=0';

		$client = new GuzzleHttp\Client();

		$response = $client->post($url, ['body' => $content]);
		$code = $response->getStatusCode();
		if ($code != 200) {
			Log::error("gopush pushMsg http client failt " . $code);
			return ['ret' => 65544];
		}

		return $response->json();
	}

	/**
	 * 离线消息
	 * @param  [type]  $key [description]
	 * @param  integer $m   [description]
	 * @param  string  $cb  [description]
	 * @return [type]       [description]
	 */
	public static function historyMsg($key, $m = 0, $cb = '') {

		$server_addr = Config::get('gopush.server_addr', 'localhost');
		$web_port = Config::get('gopush.web_port', '8090');

		$url = 'http://' . $server_addr . ':' . $web_port . '/1/msg/get';
		$client = new GuzzleHttp\Client();

		$response = $client->get($url, ['query' => ['k' => $key, 'm' => $m, 'cb' => $cb]]);
		$code = $response->getStatusCode();
		if ($code != 200) {
			Log::error("gopush historyMsg http client failt " . $code);
			return ['ret' => 65544];
		}

		return $response->json();
	}

}