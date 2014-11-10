<?php
/**
 * 初如化用户数据
 */
class UsersTableSeeder extends Seeder {

	public function run() {
		DB::table('users')->delete();
		// @todo new
		$datetime = new Carbon;
		User::create(['username' => 'dada', 'email' => 'weishidavip@163.com', 'password' => 'sdfsdf', 'activated_at' => $datetime]);
	}

}
?>