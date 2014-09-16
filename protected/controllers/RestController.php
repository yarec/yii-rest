<?php

class RestController extends Controller
{
    use PUtil\YiiUtils;

    public $layout = 'main';

	public function actions() {
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

    public function filters() {  
        return array(  
            'init',  
        );  
    }  

    public $id= null;
    public $input = null;
    public $data = null;
    public $aid= null;
    public $ret = array('code'=>0);

    public function filterInit($filterChain) {  
        $this->data['_uptm'] = date('Y-m-d H:i:s');
        $filterChain->run();  
    }  

    /* GET All */
    public function actionIndex() {
        self::succ();
    }

    /* GET Item /{id} */
    public function actionShow($id) {
        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);

        #$applist= $this->loadItem($id);
        #self::succ($applist);
    }

    /* Post */
    public function actionCreate() {
        #$GLOBALS['HTTP_RAW_POST_DATA'];

        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);

        #$this->saveItem();
    }


    /* Put Item /{id} */
    public function actionUpdate($id) {
        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);

        #$this->saveItem($id);
    }

    /* Delete /{id} */
    public function actionDelete($id) {
        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);

        #Catagory::model()->deleteByPk($id); 
        #self::code();
    }

/*
    public function getItems() {
        $type = isset($_GET['type'])?$_GET['type']:1;

        $conds = '(:type=0 or type=:type)';
        $params = array(':type'=>$type);

        $items= Yii::app()->db->createCommand()  
            ->from('catagory')  
            ->where($conds, $params)  
            ->limit($this->pagesize, $this->offset);

        $items = $items->queryAll();
        if(!empty($items)){
            foreach($items as $k=>$item){
                $items[$k]['imgurl'] = 'http://admin.xyingyong.com/imgs/'.$item['img_name'];
            }
        }
        $ret['data'] = $items;

        $count = Catagory::model()->count($conds, $params);
        $ret = array_merge($ret, $this->getCountArray($count));

        self::code($ret);
    }

    public function saveItem($id=null) {
        $errs = Yii::app()->params['errors'];
        $item= $this->loadItem($id);
        if(!$item){
            $item= new Catagory;
            $this->data['_intm'] = date('Y-m-d H:i:s');
        }

        $item->attributes = $this->data;
        try{
            if($item->save()){ 
                self::info("Catagory save 成功: ". json_encode($item->attributes));
                self::code();
            }else{ 
                $err = $item->getErrors();
                $this->error(json_encode($err));
                self::code($err, $errs['PARAM_ERR']);
            } 
        }catch(Exception $e) {
            $msg = $e->getMessage();
            Yii::log("Catagory save 失败: ".$msg, 'error');
            self::code(array(), $errs['SQL_ERR']);
        }
    }

    public function loadItem($id) {
        return Catagory::model()->findByPk($id);
    }
*/
}
