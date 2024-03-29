<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<style>
    .panel-body h1 {
        font-size: 18px;
        height: 30px;
        line-height: 30px;
        margin: 0;
        overflow: hidden;
    }

    .post-label {
        background-color: #fff;
        border: 1px solid #ddd;
        display: block;
        line-height: 1.42857;
        padding: 4px;
        transition: border 0.2s ease-in-out 0s;
        border-radius: 0;
        margin-bottom: 5px;
        position: relative;
    }

    .post-label > img {
        margin-left: auto;
        margin-right: auto;
        display: block;
        height: 150px;
        width: 100%;
    }

    .post-tags {
        color: #999;
        font-size: 12px;
    }

    .post-tags span {
        margin-right: 4px;
    }

    .post-tags a {
        color: #999;
    }

    .tags {
        font-size: 12px;
        margin-left: 2px;
    }

    .font-12 {
        font-size: 12px;
    }
</style>
<div class="panel">
    <div class="panel-title box-title">
        <span><?=$data['title']?></span>
        <?php if($this->context->more):?>
        <span class="pull-right"><a href="<?=$data['more']?>" class="font-12">更多»</a></span>
        <?php endif;?>
    </div>
    <div class="new-list">
    <?php foreach ($data['body'] as $list):?>
        <div class="panel-body border-bottom">      
            <div class="row">
                <div class="col-lg-4 label-img-size">
                    <a href="#" class="post-label">
                        <img src="<?=($list['label_img']?\Yii::$app->params['upload_url'].$list['label_img']:\Yii::$app->params['default_label_img'])?>" alt="<?=$list['title']?>">
                    </a>
                </div>
                <div class="col-lg-8 btn-group">
                    <h1><a href="<?=Url::to(['post/view','id'=>$list['id']])?>"><?=$list['title']?></a></h1>
                    <span class="post-tags">
                        <span class="glyphicon glyphicon-user"></span><a href="<?=Url::to(['member/index','id'=>$list['user_id']])?>"><?=$list['user_name']?></a>&nbsp;
                        <span class="glyphicon glyphicon-time"></span><?=date('Y-m-d',$list['created_at'])?>&nbsp;
                        <span class="glyphicon glyphicon-eye-open"></span><?=isset($list['extend']['browser'])?$list['extend']['browser']:0?>&nbsp;
                        <span class="glyphicon glyphicon-comment"></span><a href="<?=Url::to(['post/view','id'=>$list['id']])?>"><?=isset($list['extend']['comment'])?$list['extend']['comment']:0?></a>
                    </span>
                    <p class="post-content"><?=$list['summary']?></p>
                    <a href="<?=Url::to(['post/view','id'=>$list['id']])?>"><button class="btn btn-warning no-radius btn-sm pull-right">阅读全文</button></a>
                </div>
            </div>
            <div class="tags">
                <?php if(!empty($list['tags'])):?>
                <span class="fa fa-tags"></span>
                    <?php foreach ($list['tags'] as $lt):?>
                    <a href="#"><?=$lt?></a>，
                    <?php endforeach;?>
                <?php endif;?>
            </div>
        </div>
        <?php endforeach;?>            
    </div>
    <?php if($this->context->page):?>
    <div class="page"><?=LinkPager::widget(['pagination' => $data['page']]);?></div>
    <?php endif;?>
</div>