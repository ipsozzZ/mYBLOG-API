<?php
namespace App\Api;
use PhalApi\Api;
use App\Domain\User as Domain;
use App\Model\Admin as Model;
use App\Common\MyRules;
use App\Common\GD;

/**
 * 管理员类接口
 */
class Admin extends Api
{
	public function getRules(){
		return array(
			'Login' => array(
				'Manager' => array('name' => 'Manager', 'require' => true, 'min' => 4, 'desc' => '用户名'),
				'Pass' => array('name' => 'Pass', 'require' => true, 'min' => 4, 'desc' => '用户密码'),
			),
			'add' => array(
				'Account' => array('name' => 'Account', 'require' => true, 'min' => 4,'desc' => '用户名'),
				'Pass' => array('name' => 'Pass', 'require' => true, 'min' => 4,'desc' => '用户密码'),
				'CurrId' => array('name' => 'CurrId', 'require' => true, 'desc' => '当前操作的管理员Id')
			),
			'getAdmins' => array(),
			'getCode' => array(),
			'delete' => array(
				'Id' => array('name' => 'Id', 'require' => true, 'desc' => '需要删除的管理员Id'),
				'CurrId' => array('name' => 'CurrId', 'require' => true, 'desc' => '当前操作的管理员Id')
			),
			'giveLimit' => array(
				'Id' => array('name' => 'Id', 'require' => true, 'desc' => '需要授权的管理员Id'),
				'CurrId' => array('name' => 'CurrId', 'require' => true, 'desc' => '当前操作的管理员Id')
			),
			'update' => array(
				'Account' => array('name' => 'Account', 'require' => true, 'min' => 4,'desc' => '用户名'),
				'Pass' => array('name' => 'Pass', 'require' => true, 'min' => 4,'desc' => '用户密码'),
				'CurrId' => array('name' => 'CurrId', 'require' => true, 'desc' => '当前操作的管理员Id')
			),
		);
	}

	/**
	 * 管理员登录
	 */
	public function Login(){
		$user = $this -> Manager;
		$pass = $this -> Pass;
		$model = new Model();
		$manager = $model -> getByName($user);
		// 判断管理员是否存在
		if(!$manager){
			return MyRules::myRuturn(0, '登录失败，管理员不存在');
		}

		$logMsg = '';
		// 判断管理员密码是否正确
		if($pass != $manager['pass']){
			// 生成登录日志
			$logMsg = '登录失败，管理员密码不正确';
			$log = array(
				'msg'  => $logMsg,
			);
			return MyRules::myRuturn(0, '登录失败，管理员密码不正确');
		}

		// 生成登录日志
		$logMsg = '登录成功';
		$log = array(
			'msg'  => $logMsg,
		);

		// 登录成功 
		return MyRules::myRuturn(1, '登录成功', $manager);
	}

	/**
	 * 添加管理员
	 */
	public function add(){
		$model = new Model();
		$currId = $this -> CurrId;
		$currAdmin = $model -> getById($currId);
		if($currAdmin['limit'] != 1){
			return MyRules::myRuturn(0, '你没有操作权限!', '');
		}
		$isAdmin = $model -> getByName($this -> Account);
		if($isAdmin){
			return MyRules::myRuturn(0, '管理员已经存在!', '');
		}
		$data = array(
			'account' => $this -> Account,
			'pass'    => $this -> Pass,
			'limit'   => 0,
		);
		$sql = $model -> insertOne($data);
		if(!$sql){
			return MyRules::myRuturn(0, '添加失败', '');
		}
		return MyRules::myRuturn(1, '添加成功!', $sql);
	}

	/**
	 * 获取管理员信息
	 */
	public function GetMessage(){
		$user = $this -> username;
		$pass = $this -> password;
		$domain = new Domain();
	}

	/**
	 * 获取管理员列表
	 */
	public function getAdmins(){
		$model = new Model();
		$list = $model -> getAll();
		if(!$list){
			return array(
				'code' => 0,
				'msg'  => '获取失败',
				'data' => '',
			);
		}
		return array(
			'code' => 1,
			'msg'  => '获取成功',
			'data' => $list,
		);
	}

	/**
	 * 获取图片验证码信息
	 * @return res{
	 *  "code"  => 数字和字母组成的验证码,
	 * 	"pic"   => base64格式的图片
	 * }
	 */
	public function getCode(){
		$common = new GD();
		$res = $common -> getVerification(4);
		return MyRules::myRuturn(1, '获取成功', $res);
	}

	/**
	 * 超级管理员删除普通管理员
	 */
	public function delete(){
		$model = new Model();
		$Id = $this -> Id;
		$CurrId = $this -> CurrId;
		$currAdmin = $model -> getById($CurrId);
		if($currAdmin['limit'] == 0){
			return MyRules::myRuturn(0, '只有超级管理员才能删除管理员','');
		}
		$admin = $model -> getById($Id);
		if($admin['limit'] == 1){
			return MyRules::myRuturn(0, '不允许删除超级管理员','');
		}
		$sql = $model -> deleteOne($Id);
		if(!$sql){
			return MyRules::myRuturn(0, '操作异常，请重试!', '');
		}
		return MyRules::myRuturn(1, '删除成功!', '');
	}

	/**
	 * 将普通管理员授权为超级管理员，只有超级管理员才能进行次操作
	 */
	public function giveLimit(){
		$model = new Model();
		$currId = $this -> CurrId;
		$Id = $this -> Id;
		$currAdmin = $model -> getById($currId);
		$aimAdmin = $model -> getById($Id);
		if($currAdmin['limit'] != 1){
			return MyRules::myRuturn(0, '你没有权限进行此操作!','');
		}
		if($aimAdmin['limit'] == 1){
			// return MyRules::myRuturn(0, '对方已经是超级管理员!','');
			$aimAdmin['limit'] = 0;
		}else {
			$aimAdmin['limit'] = 1;
		}
		$sql = $model -> updateOne($Id, $aimAdmin);
		if(!$sql){
			return MyRules::myRuturn(0, '操作异常，请稍后尝试!', '');
		}
		return MyRules::myRuturn(1, '授权成功!', '');
	}

	/**
	 * 修改管理员信息
	 */
	public function update(){
		$model = new Model();
		$data = array();
		$admin = $model -> getById($this -> CurrId);
		if($admin['pass'] != $this -> Pass){
			return MyRules::myRuturn(0, '管理员密码不正确!', '');
		}
		$isAdmin = $model -> getByName($this -> Account);
		if($isAdmin){
			return MyRules::myRuturn(0, '管理员账号已存在!', '');
		}
		$data = array(
			"account" => $this -> Account,
			"pass"    => $this -> Pass,
		);
		$sql = $model -> updateOne($this -> CurrId, $data);
		if(!$sql){
			return MyRules::myRuturn(0, '修改失败，请稍后重试!', '');
		}
		return MyRules::myRuturn(1, '修改成功!', '');
	}
}