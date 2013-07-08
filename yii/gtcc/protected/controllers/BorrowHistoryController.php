<?php

class BorrowHistoryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','ajaxCreate','ajaxReturn'),
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
		$dataProvider=new EMongoDocumentDataProvider('BorrowHistory');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

        
        /**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
        
        /**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new BorrowHistory('search');
		$model->unsetAttributes();

		if(isset($_GET['BorrowHistory']))
			$model->setAttributes($_GET['BorrowHistory']);

		$this->render('admin', array(
			'model'=>$model
		));
	}
        
        public function actionCreate()
	{
		$model=new BorrowHistory;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BorrowHistory']))
		{
			$model->attributes=$_POST['BorrowHistory'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
        
        public function actionAjaxCreate()
	{
            //if(Yii::app()->request->isAjaxRequest){//是否ajax请求
                //print_r($_POST);
                $bookName =  Yii::app()->request->getParam('bookName');//getparam 会获得 get post 变量 ，原来也可以接收json处理后的变量
                $bookId =  Yii::app()->request->getParam('bookId');   
                $userId =  Yii::app()->request->getParam('userId');   
                $userName =  Yii::app()->request->getParam('userName');
                $bookTag = Yii::app()->request->getParam('bookTag');
                $ISBN = Yii::app()->request->getParam('ISBN');
                $dateFormat = "Y-m-d";
                $starBorrowDate = time();
	 	$planReturnDate = strtotime( '+1 month', $starBorrowDate );
                $realReturnDate = '-1';
                
                $model=new BorrowHistory;
                $model->attributes = array(
                    'bookName' => $bookName,
                    'bookId' => $bookId,
                    'userId' => $userId,
                    'userName' => $userName,
                    'bookTag' => $bookTag,
                    'ISBN' => $ISBN,
                    'planReturnDate' => Date($dateFormat,$planReturnDate),
                    'startBorrowDate' => Date($dateFormat,$starBorrowDate),
                    'realReturnDate' => $realReturnDate, 
                );
                if($model->save()){
                    echo CJSON::encode(array('id'=>$model->bookName));//Yii 的方法将数组处理成json数据
                }
	}
        
        public function actionAjaxReturn()
        {
            $borrowId =  Yii::app()->request->getParam('borrowHistoryId'); 
            
            $criteria = new EMongoCriteria;
            $criteria->_id = new MongoId($borrowId); 
            $record =BorrowHistory::model()->find($criteria);
            $record['realReturnDate'] = Date('Y-m-d',time());
            if($record->update())
                echo CJSON::encode(array('id'=>$record,'borrowid'=>$borrowId));//Yii 的方法将数组处理成json数据
        }
        
        public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['BorrowHistory']))
		{
			$model->attributes=$_POST['BorrowHistory'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->_id));
		}

		$this->render('update',array(
			'model'=>$model,
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
        
            //called on rendering the column for each row 
         protected function gridDataColumn($data,$row)
         {
              // ... generate the output for the column
              // print_r($data);die();
             if($data->realReturnDate == -1)
                   return 'Yet to Return';
                  
             return $data->realReturnDate;    
        }       
        
        
        /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=BorrowHistory::model()->findByPk(new MongoId($id));
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='borrowHistory-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
