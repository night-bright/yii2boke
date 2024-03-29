<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>
<style>
    .border-bottom {
        border-bottom: 1px solid #ccc;
    }

    .panel-body .media-feed {
        font-size: 12px;
        line-height: 1.5em;
    }

    .panel-body .media-feed .media .media-object {
        width: 40px;
        height: 40px;
        padding: 1px;
        border: #ddd solid 1px;
    }

    .panel-body .media-feed .media-action {
        margin-top: 5px;
    }

    .panel-body {
        padding-left:0px;
        padding-right:0px;
    }

    .feed .panel-body {
        padding-bottom:0px;
    }
    .btn-feed {
        height:50px;
        border-radius:0px;
    }

    .form-group textarea {
        height: 50px;
        resize: none;
        font-size: 12px;
    }

    .panel-body .media-feed {
        height: 360px;
        position: relative;
        word-break: break-all;
        overflow:auto;
    }

    .panel-body ul {
        padding: 0;
        margin: 0;
    }
</style>
<div class="panel">
    <!-- 只言片语 -->
    <div class="panel-title box-title">
        <span><strong>只言片语</strong></span>
        <span class="pull-right"><a href="#" class="font-12">更多》</a></span>
    </div>

    <div class="panel-body">
        <form action="/" id="w0" method="post">
            <div class="form-group input-group field-feed-content required">
                <textarea id="feed-content" class="form-control" name="content" placeholder="我的留言" rows="" cols=""></textarea>
                <span class="input-group-btn">
            <button type="button" data-url="<?=Url::to(['site/add-feed'])?>" class='btn btn-success btn-feed j-feed'>发布</button>


            </span>
            </div>
        </form>
        <?php if (!empty($data['feed'])):?>
            <ul class="media-list media-feed feed-index ps-container ps-active-y">
                <?php foreach ($data['feed'] as $list):?>
                    <li class="media">
                        <div class="media-left"><a href="#" rel="author" data-original-title="" title="">
                                <img alt="" class="avatar-img" style="width:37px;height:37px;" src="<?php echo Yii::$app->params['avatar']['small'] ;  ?> "/></a></div>
                        <div class="media-body">
                            <div class="media-content">
                                <a href="#" ><?=$list['user']['username']?>: </a>
                                <span><?=$list['content']?></span>
                            </div>
                            <div class="media-action">
                                <?=date('Y-m-d h:i:s',$list['created_at'])?>
                            </div>
                        </div>

                    </li>
                <?php endforeach;?>
            </ul>
        <?php endif;?>
    </div>
</div>