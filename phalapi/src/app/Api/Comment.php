<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Comment as Model;
use App\Common\MyRules;
use App\Domain\Affair;
use App\Model\User;
use App\Model\Article;

/**
 * 友情链接类接口
 */
class Comment extends Api{
	public function getRules(){
		return array(
			'add' => array(
				'uid'     => array('name' => 'uid', 'require' => true, 'desc' => '用户id'),
				'like'    => array('name' => 'like', 'desc' => '点赞'),
				'pid'     => array('name' => 'parentid', 'desc' => '父评论id'),
				'aid'     => array('name' => 'aid', 'desc' => '文章id(pid和aid必须有一个不为空)'),
				'comment' => array('name' => 'comment', 'desc' => '文章评论数'),
				'content' => array('name' => 'content', 'desc' => '评论内容'),
			),
			'update' => array(
				'id'      => array('name' => 'id', 'require' => true, 'desc' => '配置id'),
				'uid'     => array('name' => 'uid', 'require' => true, 'desc' => '用户id'),
				'like'    => array('name' => 'like', 'desc' => '点赞'),
				'pid'     => array('name' => 'parentid', 'desc' => '父评论id'),
				'comment' => array('name' => 'comment', 'desc' => '文章评论数'),
				'aid'     => array('name' => 'aid', 'desc' => '文章id(pid和aid必须有一个不为空)'),
				'content' => array('name' => 'content', 'desc' => '评论内容'),
			),
			'getById' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '评论id')
			),
			'getList' => array(
				'page' => array('name' => 'page', 'desc' => '当前页'),
				'num'  => array('name' => 'num', 'desc' => '每页数量')
			),
			'getCount' => array(
			),
			'delete' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '评论id')
			),
			'getByUser' => array(
				'uid' => array('name' => 'uid', 'require' => true, 'desc' => '用户id')
			),
			'getCountByArticle' => array(
				'aid' => array('name' => 'aid', 'require' => true, 'desc' => '文章id')
			),
			'getListByArticle' => array(
				'aid'  => array('name' => 'aid', 'require' => true, 'desc' => '文章id'),
				'page' => array('name' => 'page', 'desc' => '当前页'),
				'num'  => array('name' => 'num', 'desc' => '每页数量')
			),
			'getByComment' => array(
				'cid' => array('name' => 'cid', 'require' => true, 'desc' => '评论id，获取评论的子评论')
			),
			'getDefaultByArt' => array(
				'aid' => array('name' => 'aid', 'require' => true, 'desc' => '通过文章id，获取评论')
			),
			'getAllByArt' => array(
				'aid' => array('name' => 'aid', 'require' => true, 'desc' => '通过文章id，获取评论')
			),
		);
	}

	/**
	 * 添加一条友情链接
	 */
	public function add(){
		$model = new Model();
		$data = array(
			'uid'      => $this -> uid,
			'like'     => $this -> like,
			'parentid' => $this -> pid,
			'aid'      => $this -> aid,
			'content'  => $this -> content
		);
		$res = $model -> insertOne($data);
		if(!$res){
			return MyRules::myRuturn(0, '抱歉！添加失败！');
		}
		if($data['aid']){
			$artModel = new Article();
			$art = $artModel -> getById((int)$data['aid']);
			$art['comments'] += 1;
			$sql = $artModel -> updateOne($data['aid'], $art);
			if(!$sql){
				return MyRules::myRuturn(0, '抱歉！添加失败！');
			}
		}
		return MyRules::myRuturn(1, '添加成功！', $res);
	}

	/**
	 * 根据id更新一条友情链接
	 */
	public function update(){
		$model = new Model();
		$Id = $this -> id;
		$friend = $model -> getById($Id);
		if(!$friend){
			return MyRules::myRuturn(0, '链友不存在');
		}
		$data = array(
			'uid'      => $this -> uid,
			// 'ip'       => $_SERVER['REMOTE_ADDR'],
			'like'     => $this -> like,
			'parentid' => $this -> pid,
			'aid'      => $this -> aid,
			'content'  => $this -> content
		);
		$sql = $model -> updateOne($Id, $data);
		if(!$sql){
			return MyRules::myRuturn(0, '更新失败');
		}
		return MyRules::myRuturn(1, '更新成功!');
	}

	/**
	 * 通过id获取一条记录
	 */
	public function getById(){
		$model = new Model();
		$Id = $this -> id;
		$fr = $model -> getById($Id);
		if(!$fr){
			return MyRules::myRuturn(1, '失败！');
		}
		return MyRules::myRuturn(1, '成功！', $fr);
	}

	/**
	 * 获取所有友链
	 */
	public function getList(){
		$model = new Model();
		$begin = ($this -> page - 1) * $this -> num;
		$list = $model -> getList($begin, $this -> num);
		if(!$list){
			return MyRules::myRuturn(0, '获取失败');
		}
		return MyRules::myRuturn(1, '获取成功', $list);
	}

	/**
	 * 获取所有记录数
	 */
	public function getCount(){
		$model = new Model();
		$count = $model -> getCount();
		if(!$count){
			return MyRules::myRuturn(0, '获取失败');
		}
		return MyRules::myRuturn(1, '获取成功', $count);
	}

	/**
	 * 获取所有记录数
	 */
	public function getCountByArticle(){
		$model = new Model();
		$aid = $this -> aid;
		$count = $model -> getCountByAid($aid);
		if(!$count){
			return MyRules::myRuturn(0, '获取失败');
		}
		return MyRules::myRuturn(1, '获取成功', $count);
	}

	/**
	 * 获取所有记录数
	 */
	public function getListByArticle(){
		$model = new Model();
		$aid = $this -> aid;
		$begin = ($this -> page - 1) * $this -> num;
		$list = $model -> getListByAid($aid, $begin, $this -> num);
		if(!$list){
			return MyRules::myRuturn(0, '暂无数据');
		}
		return MyRules::myRuturn(1, '获取成功', $list);
	}

	/**
	 * 根据id删除一条记录
	 */
	public function delete(){
		$model = new Model();
		$Id = $this -> id;
		$sql = $model -> deleteOne($Id);
		if(!$sql){
			return MyRules::myRuturn(0, '删除失败');
		}
		// 删除评论下的所有评论
		$domain = new Affair();
		$domain -> deleteComment($Id);
		return MyRules::myRuturn(1, '删除成功！');
	}

	/**
	 * 获取所有评论通过文章id
	 */
	public function getAllByArt(){
		$aid = $this -> aid;
		$model = new Model();
		$list = $model -> getByArt($aid);
		if(!$list){
			return MyRules::myRuturn(0, '无数据', '');
		}
		$newlist = $this -> getUserName($list);
		return MyRules::myRuturn(1, '成功', $newlist);
	}

	/**
	 * 获取默认显示的10条评论通过文章id
	 */
	public function getDefaultByArt(){
		$aid = $this -> aid;
		$model = new Model();
		$list = $model -> getDefault($aid);
		if(!$list){
			return MyRules::myRuturn(0, '无数据', '');
		}
		$newlist = $this -> getUserName($list);
		return MyRules::myRuturn(1, '成功', $newlist);
	}

	/**
	 * 通过data中的uid获取用户name
	 */
	private function getUserName($data){
		$userModel = new User();
		$count = @count($data);
		for($i = 0; $i < $count; $i++){
			$user = $userModel -> getById($data[$i]['uid']);
			$data[$i]['user'] = $user['name'];
			$user = null;
		}
		return $data;
	}
}