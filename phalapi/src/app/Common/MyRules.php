<?php
namespace App\Common;

/* ------------  ipso  -------------- */

class MyRules {

	/**
	 * 定义全局接口相应规则
	 * 
	 * @param code  状态码
	 * @param msg   响应信息
	 * @param data  响应数据
	 * 
	 * @return array
	 */
	public static function myRuturn($code = 0, $msg = '', $data = ''){
		$rule = array(
			'code' => $code,
			'msg'  => $msg,
			'data' => $data
		);

		return $rule;
	}
}