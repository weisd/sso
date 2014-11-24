<?php
/**
 * sso models
 */
class Rsso {
	private static $SALT = "test";

	/**
	 * 生成tikect
	 * @param  [type]  $appid   [description]
	 * @param  integer $timeout [description]
	 * @return [type]           [description]
	 */
	public static function generate_ticket($appid, $timeout = 60) {
		$uuid = Rhumsaa\Uuid\Uuid::uuid4();
		$ticket = md5($uuid . self::$SALT);
		$session_id = Session::getId();

		$data = ['sid' => $session_id, 'appid' => $appid];

		Cache::put($ticket, json_encode($data), $timeout);
		// $redis = Rredis::connection();
		// $redis->set($ticket, json_encode($data));

		return $ticket;
	}

}