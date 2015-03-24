<?php

class SiteController extends Controller
{
    use PUtil\YiiUtils;
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

    public function actionCaptcha(){
        self::captcha();
    }

    public function actionUpload(){
        $upname = 'upload';
        if(isset($_FILES[$upname])){
            $imgname = self::saveimg('upload');
            $ret = array('name'=>$imgname);
            self::ret($ret);
        }
        else if(self::tst()){
            echo <<<EOF
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb2312"><title>只使用html   怎样实现 上传文件？_百度知道      </title><link rel="alternate" type="application/rss+xml" title="“只使用html   怎样实现 上传文件？”的最新回答（RSS 2.0）" href="http://zhidao.baidu.com/q?ct=20&qid=68168077&pn=65535&rn=25&tn=rssqb">
<link href="/ikqb.css" rel="stylesheet" type="text/css">
</head>
<body><form action="/site/upload" method="post" enctype ="multipart/form-data" runat="server"> 
<input id="File1" runat="server" name="upload" type="file" /> 
<input type="submit" name="Button1" value="Button" id="Button1" />
</form>
</body> 
</html>
EOF;
        }
        else{
            self::ret(1, "upload required");
        }
    }

    public function actionT(){
        $s = '';
        for($i=0; $i< 16; $i++){
            $s .= rand(0,9);
        }
        echo $s;
    }

}
