<div class="view">

<div id="no1">
    <div id="no2">
        <div id="no2-1"><img src='<?php echo './images/'.$data->ISBN.'.jpg'; ?>'/>
        </div>
        <div id="no2-2">
             <ul>
                 <li><b><?php echo CHtml::encode($data->getAttributeLabel('bookName')); ?>:</b>
           <?php echo CHtml::encode($data->bookName); ?>
                     
           <br /></li>
                 <li><b><?php echo CHtml::encode($data->getAttributeLabel('bookTag')); ?>:</b>
           <?php echo CHtml::encode($data->bookTag); ?>      
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
                         
                         $buttonName = 'button'.time();
                         
                            if($data->realReturnDate == -1)
                            {
                                echo CHtml::ajaxButton('Return', Yii::app()->createUrl('borrowHistory/ajaxReturn'),
                                array(
                                    'type' => 'POST',
                                    //'dataType' => 'JSON',
                                    'data' => array('borrowHistoryId' => (string)$data->_id,                                              
                                    'success' => "js:function(data){
                           document.getElementById(\"successMsg\").innerHTML=\"Return Successfully!\";
                           document.getElementById(\"$buttonName\").hidden=true;
                           }"
                                
                            )),
                         array('id'=>"$buttonName"));
                                   
                            }
                            ?>
                         <label id="successMsg" style="color: red"></label>
                    <br />   </li>
             </ul>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
    
    
</div>