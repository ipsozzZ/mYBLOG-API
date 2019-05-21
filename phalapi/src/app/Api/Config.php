<?php
namespace App\Api;
use PhalApi\Api;
use App\Model\Config as Model;

class Config extends Api{
	public function getRulers(){
		return array(
			'add' => array(
				'config' => array('name' => 'config', 'require' => true, 'desc' => '站点配置')
			),
		);
	}
}