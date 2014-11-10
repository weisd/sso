<?php
namespace Helper;
/**
 * 自定义工具库
 */
class Comm {
	/**
	 * 取邮箱的登陆域名
	 * @param  string $email
	 * @return string
	 */
	static public function mail2Domain($email) {
		return 'http://mail.' . substr($email, intval(strpos($email, '@')) + 1);
	}

}
?>