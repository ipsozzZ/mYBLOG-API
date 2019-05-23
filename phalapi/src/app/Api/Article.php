<?php
namespace App\Api;
use App\Model\Article as Model;
use PhalApi\Api;
use App\Common\MyRules;
use App\Model\Admin;

/**
 * 文章类接口
 */
class Article extends Api{
	public function getRules(){
		return array(
			'add' => array(
				'title'    => array('name' => 'title', 'require' => true, 'desc' => '文章标题'),
				'author'   => array('name' => 'author', 'require' => true, 'desc' => '文章作者'),
				'keywords' => array('name' => 'keywords', 'require' => true, 'desc' => '文章关键字'),
				'desc'     => array('name' => 'desc', 'desc' => '文章描述'),
				'cate'     => array('name' => 'cate', 'desc' => '文章分类'),
				'istop'    => array('name' => 'istop', 'desc' => '文章是否置顶'),
				'like'     => array('name' => 'like', 'desc' => '文章点赞'),
				'comments' => array('name' => 'comments', 'desc' => '文章评论'),
				'face'     => array('name' => 'face', 'desc' => '文章封面'),
				'state'    => array('name' => 'state', 'desc' => '文章状态，发布/未发布'),
				'content'  => array('name' => 'content', 'desc' => '文章内容'),
			),
			'delete' => array(
				'id'     => array('name' => 'id', 'require' => true, 'desc' => '文章Id'),
				'currId' => array('name' => 'currId', 'require' => true, 'desc' => '当前操作的管理员Id')
			),
			'update' => array(
				'id'       => array('name' => 'id', 'desc' => '文章id'),
				'title'    => array('name' => 'title', 'desc' => '文章标题'),
				'author'   => array('name' => 'author', 'desc' => '文章作者'),
				'keywords' => array('name' => 'keywords', 'desc' => '文章关键字'),
				'desc'     => array('name' => 'desc', 'desc' => '文章描述'),
				'cate'     => array('name' => 'cate', 'desc' => '文章分类'),
				'istop'    => array('name' => 'istop', 'desc' => '文章是否置顶'),
				'like'     => array('name' => 'like', 'desc' => '文章点赞'),
				'comments' => array('name' => 'comments', 'desc' => '文章评论'),
				'face'     => array('name' => 'face', 'desc' => '文章封面'),
				'state'    => array('name' => 'state', 'desc' => '文章状态，发布/未发布'),
				'content'  => array('name' => 'content', 'desc' => '文章内容'),
			),
			'getCount' => array(),
			'getList' => array(
				'page' => array('name' => 'page', 'require' => true, 'desc' => '当前页'),
				'num'  => array('name' => 'num', 'require' => true, 'desc' => '每页数量')
			),
			'getById' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '文章id'),
			),
			'publish' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '文章id'),
			),
			'getAll' => array(
			),
		);
	}

	/**
	 * 添加文章
	 */
	public function add(){
		$model = new Model();
		$data = array(
			'title'    => $this -> title,
			'author'   => $this -> author,
			'keywords' => $this -> keywords,
			'desc'     => $this -> desc,
			'cate'     => $this -> cate,
			'istop'    => $this -> istop,
			'like'     => $this -> like,
			'comments' => $this -> comments,
			'face'     => $this -> face,
			'state'    => $this -> state,
			'content'  => $this -> content,
		);
		$data['ctime'] = time();
		$data['rtime'] = time();
		$data['face'] = '#';
		$res = $model -> insertOne($data);
		if(!$res){
			return MyRules::myRuturn(0, '添加失败，稍后重试！');
		}
		return MyRules::myRuturn(1, '添加成功！', $res);
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
			return MyRules::myRuturn(0, '获取失败!');
		}
		return MyRules::myRuturn(1, '获取成功!', $list);
	}

	/**
	 * 根据文章id更新文章
	 */
	public function update(){
		$model = new Model();
		$data = array(
			'title'    => $this -> title,
			'author'   => $this -> author,
			'keywords' => $this -> keywords,
			'desc'     => $this -> desc,
			'cate'     => $this -> cate,
			'istop'    => $this -> istop,
			'like'     => $this -> like,
			'comments' => $this -> comments,
			'face'     => $this -> face,
			'state'    => $this -> state,
			'content'  => $this -> content,
		);
		$data['rtime'] = time();
		$Id = $this -> id;
		$res = $model -> updateOne($Id, $data);
		if(!$res){
			return MyRules::myRuturn(0, '更新失败！');
		}
		return MyRules::myRuturn(1, '更新成功！');
	}

	/**
	 * 删除文章
	 */
	public function delete(){
		$model = new Model();
		$Id = $this -> id;
		$currAdminId = $this -> currId;
		$article = $model -> getById($Id);
		$admin = new Admin();
		$currAdmin = $admin -> getById($currAdminId);
		// 判断是否有权限删除文章
		if($article["author"] != $currAdmin["account"] && $currAdmin['limit'] != 1){
			return MyRules::myRuturn(0, '删除失败, 没有删除该文章的权限');
		}
		$sql = $model -> deleteOne($Id);
		if(!$sql){
			return MyRules::myRuturn(0, '删除失败,稍后重试!');
		}
		return MyRules::myRuturn(1, '删除成功!');
	}

	/**
	 * 通过文章id获取文章详情
	 */
	public function getById(){
		$Id = $this -> id;
		$model = new Model();
		$article = $model -> getById($Id);
		if(!$article){
			return MyRules::myRuturn(0, '数据获取失败!');
		}
		$article["ctime"] = date('Y/m/d H:i:s', $article["ctime"]);
		$article["rtime"] = date('Y/m/d H:i:s', $article["rtime"]);
		return MyRules::myRuturn(1, '数据获取成功', $article);
	}

	/**
	 * 发布一篇文章
	 */
	public function publish(){
		$Id = $this -> id;
		$model = new Model();
		$art = $model -> getById($Id);
		if(is_file($art['state']) == false){
			return MyRules::myRuturn(0, '请添加文章封面后再发布文章');
		}
		$art['state'] = 1;
		$res = $model -> updateOne($Id, $art);
		if(!$res){
			return MyRules::myRuturn(0, '文章发布失败');
		}
		return MyRules::myRuturn(1, '文章发布成功！');
	}

	/**
	 * 获取所有文章
	 */
	public function getAll(){
		$model = new Model();
		$list = $model -> getAll();
		if(!$list){
			return MyRules::myRuturn(0, '获取失败');
		}
		return MyRules::myRuturn(1, '获取成功!', $list);
	}
}