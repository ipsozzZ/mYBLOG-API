<?php
namespace App\Api;
use PhalApi\Api;
use App\Domain\User as Domain;
use App\Common\GD as GD;
use App\Model\User as Model;
use App\Common\MyRules;

/**
 * 用户模块接口服务
 * @author ipso
 */
class User extends Api {
    public function getRules() {	
      return array(
				'Login' => array(
					'name' => array('name' => 'name', 'require' => true, 'min' => 4, 'desc' => '用户名'),
					'pass' => array('name' => 'pass', 'require' => true, 'min' => 8, 'desc' => '用户密码'),
				),
				'add' => array(
					'name' => array('name' => 'name', 'require' => true, 'min' => 4, 'max' => 50, 'desc' => '用户名'),
					'pass' => array('name' => 'pass', 'require' => true, 'min' => 6, 'max' => 50, 'desc' => '用户密码'),
					'about'      => array('name' => 'about', 'desc' => '一句话介绍自己'),
				),
				'update' => array(
					'id' => array('name' => 'id', 'require' => true, 'desc' => '用户编号'),
					'name' => array('name' => 'name', 'min' => 4, 'max' => 50, 'desc' => '用户名'),
					'about'      => array('name' => 'about', 'desc' => '一句话介绍自己'),
				),
				'addLike' => array(
					'id' => array('name' => 'id', 'require' => true, 'desc' => '用户id'),
					'aid' => array('name' => 'aid', 'require' => true, 'desc' => '文章id'),
				),
				'getList' => array(
					'page' => array('name' => 'page', 'desc' => '当前页码'),
					'num' => array('name' => 'num', 'desc' => '每页数量'),
				),
				'getCount' => array(),
				'getById' => array(
					'id' => array('name' => 'id', 'require' => true, 'desc' => '用户id'),
				),
				'delete' => array(
					'id' => array('name' => 'id', 'require' => true, 'desc' => '用户id'),
				),
			);
		}
		
    /**
     * 登录接
     */
    public function Login() {
      $user = $this->name;
			$pass = $this->pass;
			$GD = new GD();
			$codeimg = $GD -> getVerification(4);
			$domain = new Domain();
			$login = $domain -> Login($user, $pass);
			if($login['status'] == 0){
				return $this -> getReturn(0, $login['msg']);
			}
			return $this -> getReturn(1,$login['msg'], $codeimg['pic']);
		}

		/**
     * 注册接口
     */
    public function add() {
			$data = array(
				'name'  => $this->name,
				'pass'  => $this->pass,
				'about' => $this->about,
				'like'  => '',
				'pic'   => '',
			);
			$domain = new Domain();
			$register = $domain -> Register($data);
			if($register['status'] == 0){
				return $this -> getReturn(0, $register['msg']);
			}
			return $this -> getReturn(1, $register['msg'], $register['status']);
		}


		/**
	 * 获取文章数量
	 */
	public function getCount(){
		$model = new Model();
		$count = $model -> getCount();
		return MyRules::myRuturn(1, '获取成功!', $count);
	}

	/**
	 * 获取文章列表，分页
	 */
	public function getList(){
		$model = new Model();
		$begin = ($this -> page - 1) * $this -> num;
		$list = $model -> getList($begin, $this -> num);
		if(!$list){
			return MyRules::myRuturn(0, '无数据!');
		}
		return MyRules::myRuturn(1, '获取成功!', $list);
	}

	/**
	 * 删除一条用户信息
	 */
	public function delete(){
		$model = new Model();
		$Id = $this -> id;
		$sql = $model -> deleteOne($Id);
		if(!$sql){
			return MyRules::myRuturn(0, '删除失败!');
		}
		return MyRules::myRuturn(1, '删除成功!');
	}

	/**
	 * 管理员修改用户信息
	 */
	public function update(){
		$model = new Model();
		$data = array(
			'name'  => $this -> name,
			'about' => $this -> about,
		);
		$Id = $this -> id;
		$sql = $model -> updateOne($Id, $data);
		if(!$sql){
			return MyRules::myRuturn(0, '修改失败！');
		}
		return MyRules::myRuturn(1, '修改成功!');
	}

	/**
	 * 通过Id获取用户信息
	 */
	public function getById(){
		$model = new Model();
		$Id = $this -> id;
		$user = $model -> getById($Id);
		if(!$user){
			return MyRules::myRuturn(0, '获取失败');
		}
		return MyRules::myRuturn(1, '获取成功！', $user);
	}
		
	/**
	 * 组织返回信息的格式，将返回信息放入数组
	 * @param state 状态码
	 * @param msg   返回信息,
	 * @param data  返回的数据
	 */
	protected function getReturn($state = 0, $msg = '', $data = null){
		return array(
			'code' => $state,
			'msg'   => $msg,
			'data'  => $data,
		);
	}
} 
