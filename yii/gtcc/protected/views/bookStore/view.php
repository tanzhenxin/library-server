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
            <?php echo '../../gtcclibrary/images/'.$model->ISBN.'.jpg'; ?>"/>
        </div>
        <div id="no2-2">
             <ul>
                 <li><b><?php echo $model->title; ?></b></li>
                 <li><b>
                     <?php 
                           
                            $bookTag = $model->BianHao;
                            $result = CommonMethod::sendRequest('BorrowService', 'checkWhetherBookInBorrow', array($bookTag));  
                            if($result->_returnCode == YiiErrorCode::OK)
                            {
                                echo "Borrowed by : ".$result->borrowHistory->username;
                                echo "<br>Plan Return Date: ".$result->borrowHistory->planReturnDate;  
                            }
                            else
                            {
                                //echo CHtml::link('Borrow', Yii::app()->createUrl('borrowHistory/Create&userName='.Yii::app()->user->name."&bookTag=".$model->BianHao)); 
                                echo CHtml::ajaxButton('Borrow', Yii::app()->createUrl('borrowHistory/ajaxCreate'),
                                array(
                                    'type' => 'POST',
                                    //'dataType' => 'JSON',
                                    'data' => array('userName' => Yii::app()->user->name,
                                                    //'bookName' => $model->title,
                                                    //'bookId'=> (string)$model->_id,
                                                    'bookTag'=> $model->BianHao),
                                                    //'userId'=> (string)$currentUser->_id,
                                    'success' => 'js:function(data) { 
                                        //alert(data);
                                        var json = JSON.parse(data);
                                        if(json._returnCode == 0)
                                        {
                                            document.getElementById("successMsg").innerHTML="Borrow Successfully!";
                                        }else if(json._returnCode == -303)
                                        {
                                            document.getElementById("successMsg").innerHTML="You can only borrow Max 3 books!";
                                        }else
                                        {
                                            document.getElementById("successMsg").innerHTML="Borrow Failed!";
                                        }

                                        document.getElementById("button1").style.visibility=\'hidden\';
                                        
                                   }'

                                    ),
                                 array('id'=>'button1'));
                            }
                        
                        ?>
                         <br> <label id="successMsg" style="color: red"></label>
                 </li>
             </ul>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>




<br>

<?php

$this->widget('zii.widgets.CDetailView', array(
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
                array('name' => 'Category',
                      'value' => CommonMethod::GetCategory($model->BianHao),
                ),
		//'_id',
	),
        
)); 
?>
