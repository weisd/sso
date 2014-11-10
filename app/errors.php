<?php
/**
 * app 中的异常在这处理
 */

// csrf token失败 
App::error(function(Illuminate\Session\TokenMismatchException $exception){
	Log::error($exception);
	// return Redirect::back()->with('');
});
?>