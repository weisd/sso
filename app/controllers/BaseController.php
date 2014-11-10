<?php

class BaseController extends Controller {

	public function success($data = "") {
		$res = ['code' => 200, 'data' => $data];
		return Response::json($res);
	}

	public function error($code, $msg = "") {
		$res = ['code' => $code, 'error' => $msg];
		return Response::json($res);
	}

}
