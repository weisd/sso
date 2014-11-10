<?php
/**
 * user model use redis
 */
class Ruser {

	/**
	 * 通过token 取用户信息
	 * @param  string $token
	 * @return array
	 */
	public static function get_info_by_kt_token($token) {
		$info = [];
		$redis = Rredis::connection();
		$uid = self::get_uid_by_kt_token($token);
		if (empty($uid)) {
			return $info;
		}

		$info = self::get_user_info($uid);

		return $info;
	}

	/**
	 * 通过 token 取uid
	 * @param  [type] $token [description]
	 * @return [type]        [description]
	 */
	public static function get_uid_by_kt_token($token) {
		$redis = Rredis::connection();
		$id = $redis->get(self::get_kt_uid_by_token_key($token));
		return intval($id);
	}

	/**
	 * kt用户 uid 对应 token key
	 */
	public static function get_token_by_kt_uid_key($uid) {
		return 'user:' . $uid . ':kt.token';
	}

	/**
	 * token 对应kt用户 id key
	 */
	public static function get_kt_uid_by_token_key($token) {
		return 'user.kt.token:' . $token;
	}

	/**
	 *  取用户信息
	 *  @param int $id
	 *  @return array
	 */
	public static function get_user_info($id) {
		$redis = Rredis::connection();
		return (array) $redis->hGetAll(self::get_user_info_key($id));
	}

	/**
	 * redis 用户信息key
	 * @param int $id
	 * @return string
	 */
	public static function get_user_info_key($id) {
		return 'user:' . $id . ':info';
	}

}