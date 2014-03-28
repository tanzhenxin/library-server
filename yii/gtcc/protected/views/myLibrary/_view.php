<div class="view">
<?php 
$bookInfo = $data->book;
$book = Book::model()->findByPk($bookInfo['$id']);

?>
<div id="no1">
    <div id="no2">
        <div id="no2-1"><img src='<?php echo '../../gtcclibrary/images/'.$book->ISBN.'.jpg'; ?>'/>
        </div>
        <div id="no2-2">
             <ul>
                 <li><b><?php echo CHtml::encode($data->getAttributeLabel('bookName')); ?>:</b>
           <?php echo CHtml::encode($book->title); ?>
                     
           <br /></li>
                 <li><b><?php echo CHtml::encode($data->getAttributeLabel('bookTag')); ?>:</b>
           <?php echo CHtml::encode($book->BianHao); ?>      
           <br /></li>
                 <li> <b><?php echo CHtml::encode($data->getAttributeLabel('startBorrowDate')); ?>:</b>
           <?php echo CHtml::encode($data->startBorrowDate); ?>
           <br /></li>
                 <?php 
                 if($data->realReturnDate == -1)
                 echo '<li><b>'.CHtml::encode($data->getAttributeLabel('planReturnDate')).' :</b> '
                                .CHtml::encode($data->planReturnDate)
                      .'<br/></li>';
                
                 if($data->realReturnDate != -1)
                 {
                    echo '<li><b>'.CHtml::encode($data->getAttributeLabel('realReturnDate'))
                    .' : </b>'.CHtml::encode($data->realReturnDate).'<br />   </li>';
                 }
                 ?>
           
                 <li><b>
                         <?php 
                         
                         $buttonName = uniqid();
                         $errormessageId = uniqid();
                            if($data->realReturnDate == -1)
                            {
                                echo CHtml::ajaxButton('Return', Yii::app()->createUrl('borrowHistory/ajaxReturn'),
                                    array(
                                            'type' => 'POST',
                                            //'dataType' => 'JSON',
                                            'data' => array('bookTag' => $book->BianHao, 
                                                            'username' => Yii::app()->user->name
                                                      ),
                                            'success' => "js:function(data){
                                               //alert(data);
                                                
                                                var json = JSON.parse(data);
                                                if(json._returnCode == 0)
                                                {
                                                    document.getElementById(\"$errormessageId\").innerHTML=\"Return Successfully!\";
                                                }else
                                                {
                                                    document.getElementById(\"$errormessageId\").innerHTML=\"Return Failed!\";
                                                }
                                                document.getElementById(\"$buttonName\").style.visibility='hidden';
                                             }"
                                      ),
                                      array('id'=>"$buttonName")
                               );
                                   
                            }
                            ?>
                         <br> <label id="<?php echo $errormessageId?>" style="color: red"></label>
                    <br />   </li>
             </ul>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
    
    
</div>