<?php
$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->title,
);

?>

<h1>Book Detail :</h1>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bookDisplay.css" />
<div id="no1">
    <div id="no2">
        <div id="no2-1"><img src="
            <?php echo './images/'.$model->ISBN.'.jpg'; ?>"/>
        </div>
        <div id="no2-2">
             <ul>
                 <li><b><?php echo $model->title; ?></b></li>
                 <li><b>
                     <?php 
                        // check whether the book has been borrowed by someone
                        $criteria = new EMongoCriteria;
                        $criteria->realReturnDate('==','-1');
                        $criteria->bookTag('==',$model->BianHao);
                        $result = BorrowHistory::model()->find($criteria);
                        if(!isset($result))
                        {
                            $condition = new EMongoCriteria;
                            $condition->username('==', Yii::app()->user->name);
                            $currentUser = User::model()->find($condition);
                            //print_r($currentUser);

                            if(isset($currentUser))
                            {
                            echo CHtml::ajaxButton('Borrow', Yii::app()->createUrl('borrowHistory/ajaxCreate'),
                            array(
                                'type' => 'POST',
                                //'dataType' => 'JSON',
                                'data' => array('userName' => Yii::app()->user->name,
                                                'bookName' => $model->title,
                                                'bookId'=> (string)$model->_id,
                                                'ISBN'=> $model->ISBN,
                                                'bookTag'=> $model->BianHao,
                                                'userId'=> (string)$currentUser->_id,
                                'success' => 'js:function(data){
                               document.getElementById("successMsg").innerHTML="Borrow Successfully!";
                               document.getElementById("button1").hidden=true;
                               }'

                                )),
                             array('id'=>'button1'));
                            }
                        }
                        ?>
                         <label id="successMsg" style="color: red"></label>
                 </li>
             </ul>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>




<br>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ISBN',
		'title',
		'price',
		'BianHao',
		'description',
		'author',
		'publishedDate',
		'publisher',
		'language',
		'printLength',
		//'_id',
	),
)); ?>