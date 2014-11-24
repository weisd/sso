<?php
/**
 * 自定义
 */
class ApiGuard extends Illuminate\Auth\Guard {
	//重写更新方法， 不更新session id
	// protected function updateSession($id) {
	// 	$this->session->put($this->getName(), $id);

	// 	// $this->session->migrate(true);
	// }
}