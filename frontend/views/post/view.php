<?php
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = $data['title'];
$this ->params['breadcrumbs'][]=['label'=>'文章','url'=>['post/index']];
$this ->params['breadcrumbs'][]=$this->title;
?>
<style type="text/css">
	.page-title {
	    border-bottom: 1px solid #eee;
	    margin-bottom: 10px;
	    padding-bottom: 5px;
	}

	.page-title h1 {
	    font-size: 18px;
	    margin: 4px 0 10px;
	}

	.page-title span {
	    color: #999;
	    font-size: 12px;
	    margin-right: 5px;
	}

	.page-content {
	    border-bottom: 1px solid #eee;
	    margin-bottom: 10px;
	    min-height: 400px;
	}
</style>
<div class="row">
	<div class="col-lg-9">
		<div class="page-title">
			<h1><?=$data['title']?></h1>
			<span>作者:<?=$data['user_name']?></span>
			<span>发布:<?=date('Y-m-d',$data['created_at']);?></span>
			<span>浏览:<?=isset($data['extend']['browser'])?$data['extend']['browser']:0?></span>
		</div>

		<div class="page-content">
			<?=$data['content']?>
		</div>
		<div class="page-tag">
			标签:
			<?php foreach ($data['tags'] as $tag):?>
				<span><a href="#"><?=$tag ?></a></span>
			<?php endforeach; ?>
		</div>

	</div>   
	<div class="col-lg-3">
		<?php if(!\Yii::$app->user->isGuest):?>
			<a class="btn btn-success btn-block btn-post" href="<?=Url::to(['post/create'])?>">创建文章</a>

			<?php  if(\Yii::$app->user->identity->id == $data['user_id']): ?>
                <a class="btn btn-info btn-block btn-post" href="<?=Url::to(['post/update','id'=>$data['id']])?>">编辑文章</a>

                <a class="btn btn-danger btn-block btn-post" onClick="return confirm('确定删除?');" href="<?=Url::to(['post/delete','id'=>$data['id']])?>">删除文章</a>
            <?php endif;?>

        <?php endif;?>

	</div>

</div>