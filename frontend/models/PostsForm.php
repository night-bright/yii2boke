<?php
namespace frontend\models;
use Yii;
use yii\base\Model;
use common\models\PostsModel;
use common\models\RelationPostTagsModel;
use frontend\models\TagForm;
use yii\db\Query;
/**
 * 文章表单模型
 */
class PostsForm extends Model
{
	public $id;
	public $title;
	public $content;
	public $label_img;
	public $cat_id;
	public $tags;
	public $_lastError = "";

	/*
	*定义场景
	* const SCENARIOS_CREATE='create'; 创建场景
	* const SCENARIOS_UPDATE='update'; 更新场景
	*/
	const SCENARIOS_CREATE='create';
	const SCENARIOS_UPDATE='update';

	/**
	* 定义事件
	* const EVENT_AFTER_CREATE="eventAfterCreate";创建之后的事件
	* const EVENT_AFTER_UPDATE="eventAfterUpdate"; 更新之后的事件
	*/
	const EVENT_AFTER_CREATE="eventAfterCreate";
	const EVENT_AFTER_UPDATE="eventAfterUpdate";
	/*
	场景设置
	*/
	public function scenarios()
	{
		$scenarios=[
			self::SCENARIOS_CREATE=>['title','content','label_img','cat_id','tags'],
			self::SCENARIOS_UPDATE=>['title','content','label_img','cat_id','tags'],
		];
		return array_merge(parent::scenarios(),$scenarios);
	}

	public function rules()
	{
		return[
			[['id','title','content','cat_id'],'required'],
			[['id','cat_id'],'integer'],
			['title','string','min'=>4,'max'=>50],

		];
	}
	public function attributeLabels(){
		return[
			'id' => '编码',
			'title' => '标题',
			'content' => '内容',
			'label_img' => '标签图',
			'tags' => '标签',
			'cat_id' => '分类',
		];
	}

	public static  function getList($cond,$curPage=1,$pageSize =5 ,$orderBy=['id'=>SORT_DESC])
	{
		$model = new PostsModel();
		$select =['id','title','summary','label_img','cat_id','user_id','user_name','is_valid','created_at','updated_at'];
		$query=$model->find()
		->select($select)
		->where($cond)
		->with('relate.tag','extend')
		->orderBy($orderBy);
		/**
		* 获取分页数据
		* @var [type]
		*/
		$res = $model->getPages($query,$curPage,$pageSize);
		//格式化
		$res['data'] = self::_formatList($res['data']);
		return $res;
	}
	/**
	* 数据格式化
	* @return [type] [description]
	*/
    public static function _formatList($data)
    {
		foreach ($data as &$list) {
			$list['tags']=[];
			if(isset($list['relate'])&& !empty($list['relate'])){
				foreach($list['relate'] as $lt){
					$list['tags'][]=$lt['tag']['tag_name'];
				}
			}
			unset($list['relate']);
		}

		return $data;
    }

	/*
	文章创建
	*/
	public function create()
	{
		//事物
		$transation = Yii::$app->db->beginTransaction();
		try{
			$model =new PostsModel();
			$model->setAttributes($this->attributes);
			$model->summary = $this->_getSummary();
			$model->user_id=Yii::$app->user->identity->id;
			$model->user_name=Yii::$app->user->identity->username;
			$model->is_valid =PostsModel::IS_VALID;
			$model->created_at=time();
			$model->updated_at=time();
			if(!$model->save())
				throw new \Exception('文章保存失败');
			$this->id =$model->id;
			//调用事件
			$data =array_merge($this->getAttributes(),$model->getAttributes());
			$this->_eventAfterCreate($data);
		    // 执行事务
			$transation->commit();
			return true;
		}catch(\Exception $e){
			// 回滚
			$transation->rollBack();
			// 记录错误
			$this->_lastError = $e->getMessage();
			return false;
		}
	}

	public function getViewById($id)
	{
		$res = PostsModel::find()->with('relate.tag','extend')->where(['id'=>$id])->asArray()->one();
		if(!$res){
			throw new NotFoundHttpException("文章不存在！");
		}
		//处理标签格式
		$res['tags'] =[];
		if(isset($res['relate'])&&!empty($res['relate'])){
			foreach ($res['relate'] as $list) {
				$res['tags'][] = $list['tag']['tag_name'];
			}
		}
		unset($res['relate']);

		return $res;
	}
	/*
	*截取文章摘要
	*/
	private function _getSummary($s=0,$e=90,$char='utf-8')
	{
		if(empty($this->content))
			return null;

		return(mb_substr(str_replace('&nbsp;','',strip_tags($this->content)), $s,$e,$char));
	}
	/**
	 * 创建完成后调用事件方法
	 * @return [type] [description]
	 */
	private function _eventAfterCreate($data)
	{
		//添加事件  将_eventAddTage方法注册到EVENT_AFTER_CREATE事件里
		$this->on(self::EVENT_AFTER_CREATE,[$this,'_eventAddTage'],$data);
		//触发事件
		$this->trigger(self::EVENT_AFTER_CREATE);

	}
    private function _eventAfterUpdate($data)
    {
        //添加事件  将_eventAddTage方法注册到EVENT_AFTER_CREATE事件里
        $this->on(self::EVENT_AFTER_UPDATE,[$this,'_eventAddTage'],$data);
        //触发事件
        $this->trigger(self::EVENT_AFTER_UPDATE);

    }
	/**
	* [_eventAddTage description] 添加标签
	* @return [type] [description]
	*/
	public function _eventAddTage($event)
	{     
	    //保存标签
		$tag =new TagForm();
		$tag->tags=$event->data['tags'];
		$tagids = $tag->saveTags();
		//删除文章原先的关联关系
		RelationPostTagsModel::deleteAll(['post_id' => $event->data['id']]);
		//批量保存文章标签的关联关系
		if(!empty($tagids)){
			foreach($tagids as $k=>$id){
				$row[$k]['post_id'] = $this ->id;
				$row[$k]['tag_id'] = $id;
			}
			//批量插入
			$res =(new Query())->createCommand()
			->batchInsert(RelationPostTagsModel::tableName(),['post_id','tag_id'],$row)
			->execute();
			//返回结果
			if(!$res)
				throw new Exception("关联关系保存失败！");

		}
	      
	}


    public function getupdate($id)
    {
        $data = PostsModel::find()->with('relate.tag')->where(['id'=>$id])->asArray()->one();
        $data = self::_formatList2($data);
        $this->setAttributes($data);
    }
    private function _formatList2($data)
    {
        foreach ($data['relate'] as $list){
            $data['tags'][]= $list['tag']['tag_name'];
        }
        unset($data['relate']);
        return $data;
    }
    public function update($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $postmodel = PostsModel::find()->with('relate.tag')->where(['id'=>$id])->one();
            $postmodel->setAttributes($this->attributes);
            $postmodel->summary = $this->_getSummary(); //生成摘要
            $postmodel->user_id = Yii::$app->user->identity->id;
            $postmodel->user_name = Yii::$app->user->identity->username;
            $postmodel->is_valid = PostsModel::IS_VALID;
            $postmodel->updated_at = time();
            if (!$postmodel->save()){
                throw new \yii\base\Exception('文章保存失败!');
            }
            $this->id = $postmodel->id;
            //调用事件
            $data = array_merge($this->getAttributes(),$postmodel->getAttributes());
            $this->_eventAfterUpdate($data);
            $transaction->commit();
            return true;
        }catch ( \yii\base\Exception $e)
        {
            $transaction->rollBack();
            $this->_lastError = $e->getMessage();
            return false;
        }
    }





	
}