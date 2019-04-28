<?php
namespace App\Api;
use PhalApi\Api;

class Hello extends Api
{
	public function world(){
		return array('title' => 'hello world!');
	}
}