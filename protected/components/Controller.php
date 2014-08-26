<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    public $id= null;
    public $aid= null;
    public $page= null;
    public $offset= null;
    public $pagesize= null;
    public $input = null;
    public $data = null;

    public function filterInitdata($filterChain) {  
        $baseurl = Yii::app()->getBaseUrl(true);
        $this->aid= $this->action->id;
        $this->page= isset($_GET['page'])?$_GET['page']:1;
        $this->pagesize= isset($_GET['pagesize'])?$_GET['pagesize']:10;
        $this->offset = ($this->page-1)* $this->pagesize;

        $this->input = file_get_contents("php://input");
        if($this->input){
            $this->data = json_decode($this->input, true);
        }

        $this->id = isset($_GET['id'])?$_GET['id']:null;
        if($this->id){
            $this->data['id'] = 0+$this->id;
        }
        if(!empty($_POST)){
            $this->data = $_POST;
        }

        $filterChain->run();  
    }  

    public function getCountArray($count){
        $ret['cur_page'] = $this->page;
        $ret['total_page'] = ceil($count/$this->pagesize);
        $ret['count'] = $count;
        return $ret;
    }
}
