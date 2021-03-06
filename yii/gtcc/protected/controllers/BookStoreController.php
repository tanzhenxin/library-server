<?php

class BookStoreController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column1';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','search'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{                     
            $model = new Book('search');
            $model->unsetAttributes();
            
            if(isset($_GET['Book']))
            { 
                foreach ($_GET['Book'] as $key => $value) {
                    $_GET['Book'][$key] = trim($value);
                }
                $model->setAttributes($_GET['Book']);
            }else if(isset($_GET['category']))
            {
                $model->setAttributes(array("BianHao"=>$_GET['category']));            
            }
            
            // sort by tag asc
            $criteria = new EMongoCriteria;
            $criteria->sort("BianHao", EMongoCriteria::SORT_ASC);
            $model->setDbCriteria($criteria);
            
            $this->render('index',array(
                           'model'=>$model
                   ));
	}

        /**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
        
        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Book::model()->findByPk(new MongoId($id));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='book-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        protected function getCategoryName($data)
        {
            die(var_dump($data));
        } 
}
