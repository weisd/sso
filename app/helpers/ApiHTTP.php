<?php
/**
 * api http sdk
 */
class ApiHTTP {
	public static function checkLogin($username, $password) {
		$url = self::makeUrl('/account/auth');

		try {
			$client = new GuzzleHttp\Client();
			$response = $client->post($url, ['body' => ['username' => $username, 'password' => $password, 'from' => 'ktkt']]);

			$code = $response->getStatusCode();
			if ($code != 200) {
				// 错误 处理
				return ['code' => '502', 'response not 200'];
			}

			return $response->json();
		} catch (Exception $e) {
			return ['code' => '502', 'response not 200'];
		}
	}

	/**
	 * 取用户信息
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function getUserInfo($id) {
		$url = self::makeUrl('/account/info');
		try {
			$client = new GuzzleHttp\Client();
			$response = $client->post($url, ['body' => ['uid' => $id]]);

			$code = $response->getStatusCode();
			if ($code != 200) {
				// 错误 处理
				return ['code' => '502', 'response not 200'];
			}

			return $response->json();

		} catch (Exception $e) {
			return ['code' => '502', 'response not 200'];
		}
	}

	/**
	 * 更新用户信息
	 * @param  [type] $uid  [description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public static function updateUserInfo($uid, $data) {
		$url = self::makeUrl('/account/update');

		$json_data = json_encode($data);
		try {
			$client = new GuzzleHttp\Client();
			$response = $client->post($url, ['body' => ['uid' => $id, 'data' => $json_data]]);

			$code = $response->getStatusCode();
			if ($code != 200) {
				// 错误 处理
				return ['code' => '502', 'response not 200'];
			}

			return $response->json();

		} catch (Exception $e) {
			return ['code' => '502', 'response not 200'];
		}
	}

	/**
	 * 生成请求url
	 * @param  [type] $uri [description]
	 * @return [type]      [description]
	 */
	public static function makeUrl($uri) {
		return 'http://localhost:4000' . $uri;
	}

	/**
	 * 验证sso 客户端
	 * @param  stirn $token [description]
	 * @return [type]        [description]
	 */
	public static function checkSSOClient($token) {
		$url = self::makeUrl('/sso/auth');
		try {
			$client = new GuzzleHttp\Client();
			$response = $client->post($url, ['body' => ['token' => $token]]);

			$code = $response->getStatusCode();
			if ($code != 200) {
				// 错误 处理
				return ['code' => '502', 'response not 200'];
			}

			return $response->json();
		} catch (Exception $e) {
			return ['code' => '502', 'response not 200'];
		}
	}

	/**
	 * 取sso 子系统列表
	 */
	public static function GetSSOClients() {
		try {
			$url = self::makeUrl('/sso/list');
			$client = new GuzzleHttp\Client();
			$response = $client->get($url);

			$code = $response->getStatusCode();
			if ($code != 200) {
				// 错误 处理
				return ['code' => '502', 'response not 200'];
			}

			return $response->json();
		} catch (Exception $e) {
			return ['code' => '502', 'response not 200'];
		}
	}

	/**
	 * [getSSOClient description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function GetSSOClient($id) {
		try {
			$url = self::makeUrl('/sso/client');
			$client = new GuzzleHttp\Client();
			$response = $client->get($url, ['query' => ['id' => $id]]);

			$code = $response->getStatusCode();
			if ($code != 200) {
				// 错误 处理
				return ['code' => '502', 'response not 200'];
			}

			return $response->json();
		} catch (Exception $e) {
			return ['code' => '502', 'response not 200'];
		}
	}
}