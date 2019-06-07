<?php
namespace App\Domain;
use App\Model\Article;
use App\Model\Comment;
use App\Model\Cate;

/**
 * 文章、评论、栏目三表数据变动触发器
 * @author ipso
 */
class Affair {

	/**
	 * 删除文章，同时删除文章下的所有评论
	 * @param Id
	 */
	public function deleteArt($Id = 0){
		if($Id == 0) return 0;
		$model = new Article();
		$comment = new Comment();
		$isArt = $model -> getById($Id);
		if($isArt){
			$model -> deleteOne($Id);
		}
		$comment -> deleteAllByArt($Id);
		return 1;
	}

	/**
	 * 删除栏目，同时删除栏目下的所有文章 getArtsByCate
	 * @param Id
	 */
	public function deleteCate($Id = 0){
		if($Id == 0) return 0;
		$model = new Cate();
		$art = new Article();
		$comment = new Comment();
		$isCate = $model -> getById($Id);
		if($isCate){
			$model -> deleteOne($Id);
		}
		// 删除栏目下的所有文章
		$arts = $art -> getArtsByCate($Id);
		$count = count($arts);
		for($i = 0; $i < $count; $i++){
			$this -> deleteArt($arts[$i]['id']);
		}
		return 1;
	}

	/**
	 * 删除一条评论，同时删除该评论下的所有评论
	 * @param Id
	 */
	public function deleteComment($Id = 0){
		if($Id == 0) return 0;
		$model = new Comment();
		$isComment = $model -> getById($Id);
		if($isComment){
			$model -> deleteOne($Id);
		}
		$model -> deleteAllByParent($Id);
		return 1;
	}
}