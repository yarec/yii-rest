<?php

class RestController extends Controller
{
    use PUtil\YiiUtils;

    public $layout = 'main';

	public function actions() {
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
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
        $baseurl = Yii::app()->getBaseUrl(true);
        $actionid = $this->action->id;
        $this->aid=$actionid;

        $this->input = file_get_contents("php://input");
        if($this->input){
            $this->data = json_decode($this->input, true);
        }

        $this->id = isset($_GET['id'])?$_GET['id']:null;
        if($this->id){
            $this->data['id'] = 0+$this->id;
        }
        if(isset($_POST)){
            $this->data = $_POST;
        }

        $filterChain->run();  
    }  

    /* GET All */
    public function actionIndex() {
        self::succ();
    }

    /* GET Item /{id} */
    public function actionShow() {
        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);
    }

    /* Post */
    public function actionCreate() {
        #$GLOBALS['HTTP_RAW_POST_DATA'];

        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);
    }


    /* Put Item /{id} */
    public function actionUpdate() {
        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);
    }

    /* Delete /{id} */
    public function actionDelete() {
        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);
    }

}
