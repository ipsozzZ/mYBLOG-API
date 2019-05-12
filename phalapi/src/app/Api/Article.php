<?php
namespace App\Api;

use PhalApi\Api;

class Article extends Api{
	public function getRules(){
		return array(
			'add' => array(
				'Account' => array('name' => 'Account', 'require' => true, 'min' => 4,'desc' => '用户名'),
				'Pass' => array('name' => 'Pass', 'require' => true, 'min' => 4,'desc' => '用户密码'),
				'CurrId' => array('name' => 'CurrId', 'require' => true, 'desc' => '当前操作的管理员Id')
			),
			'delete' => array(
				'Id' => array('name' => 'Id', 'require' => true, 'desc' => '需要删除的管理员Id'),
				'CurrId' => array('name' => 'CurrId', 'require' => true, 'desc' => '当前操作的管理员Id')
			),
			'update' => array(
				'Account' => array('name' => 'Account', 'require' => true, 'min' => 4,'desc' => '用户名'),
				'Pass' => array('name' => 'Pass', 'require' => true, 'min' => 4,'desc' => '用户密码'),
				'CurrId' => array('name' => 'CurrId', 'require' => true, 'desc' => '当前操作的管理员Id')
			),
		);
	}

	
}