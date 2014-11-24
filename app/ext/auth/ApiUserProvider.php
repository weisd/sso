<?php
use Illuminate\Auth\GenericUser;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserProviderInterface;
/**
 * api 验证用户
 */

class ApiUserProvider implements UserProviderInterface {
	/**
	 * Retrieve a user by their unique identifier.
	 * 通过id 找用户，登陆时用到
	 *
	 * @param  mixed  $identifier
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveById($identifier) {
		$res = ApiHTTP::GetUserInfo($identifier);
		if ($res['code'] == 200) {
			return new GenericUser((array) $res['data']);
		}

	}

	/**
	 * Retrieve a user by by their unique identifier and "remember me" token.
	 *
	 * @param  mixed  $identifier
	 * @param  string  $token
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByToken($identifier, $token) {
		$res = ApiHTTP::GetUserInfo($identifier);
		if ($res['code'] == 200 && $res['data']['remember_token'] == $token) {
			return new GenericUser((array) $res['data']);
		}
	}

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  string  $token
	 * @return void
	 */
	public function updateRememberToken(UserInterface $user, $token) {
		$res = ApiHTTP::updateUserInfo($user->getAuthIdentifier(), ['remember_token' => $token]);
		if ($res['code'] != 200) {
			Log::error('updateRememberToken failed : ');
		}
	}

	/**
	 * Retrieve a user by the given credentials.
	 * 条件查找
	 *
	 * @param  array  $credentials
	 * @return \Illuminate\Auth\UserInterface|null
	 */
	public function retrieveByCredentials(array $credentials) {

	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Auth\UserInterface  $user
	 * @param  array  $credentials
	 * @return bool
	 */
	public function validateCredentials(UserInterface $user, array $credentials) {

	}

}