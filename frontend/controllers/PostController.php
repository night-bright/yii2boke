<?php 
namespace frontend\controllers;
/**
* 文章控制器
*/

use Yii;
use frontend\controllers\base\BaseController;
use frontend\models\PostsForm;
use common\models\CatsModel;
use common\models\PostsModel;
use common\models\PostExtendsModel;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use common\models\PostExtendModel;

class PostController extends BaseController
{
	/**
	* 行为过滤
	* @return [type] [description]
	*/
	public function behaviors()
	{
	return [
	    'access' => [
	        'class' => AccessControl::className(),
	        'only' => ['index', 'create','upload','ueditor'],
	        'rules' => [
	            [
	            	//都可以访问
	                'actions' => ['index'],
	                'allow' => true,
	                
	            ],
	            [
	            	// 登录之后能访问
	                'actions' => ['create','upload','ueditor'],
	                'allow' => true,
	                'roles' => ['@'],
	            ],
	        ],
	    ],
	    'verbs' => [
	        'class' => VerbFilter::className(),
	        'actions' => [
	              '*' =>['get','post'],
	            //'create' => ['post'],
	        ],
	    ],
	];
	}
	public function actions()
    {
        return [
            'upload'=>[
                'class' => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' => [
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            'ueditor'=>[
	            'class' => 'common\widgets\ueditor\UeditorAction',
	            'config'=>[
	                //上传图片配置
	                'imageUrlPrefix' => "", /* 图片访问路径前缀 */
	                'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
	            ]
	        ]
        ];
    }
	/**
	 * 文章列表
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}
	/**
	 * 创建文章
	 * @return [type] [description]
	 */
	public function actionCreate()
	{
		$model = new PostsForm();
		// 定义场景
		$model->setScenario(PostsForm::SCENARIOS_CREATE);
		if($model->load(Yii::$app->request->post()) && $model->validate()){
            if(!$model->create()){
                Yii::$app->session->setFlash('warning', $model -> _lastError);
            }else{
                return $this->redirect(['post/view', 'id' => $model->id]);
            }
        }
		// 获取所有分类
		$cat = CatsModel::getAllCats();
		return $this->render('create',['model' => $model,'cat' => $cat]);


	}
	/**
	* 文章详情页
	*/
	public function actionView($id)
	{
		$model =new PostsForm();
		$data = $model ->getViewById($id);

		//文章统计
		$model =new PostExtendsModel();

		$model->upCounter(['post_id'=>$id],'browser',1);
		return $this->render('view',['data'=>$data]);
	}
}