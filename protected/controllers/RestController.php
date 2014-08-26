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
    }

    /* Post */
    public function actionCreate() {
        #$GLOBALS['HTTP_RAW_POST_DATA'];

        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);
    }


    /* Put Item /{id} */
    public function actionUpdate($id) {
        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);
    }

    /* Delete /{id} */
    public function actionDelete($id) {
        $this->ret['data'] = $this->data;
        $this->sendJSON($this->ret);
    }

}
