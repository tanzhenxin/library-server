<div class="view">
  
<div id="no1">
    <div id="no2">
        <div id="no2-1"><img src="
            <?php echo '../../gtcclibrary/images/'.$data->ISBN.'.jpg'; ?>"/>
        </div>
        <div id="no2-2">
             <ul>
                 <li><b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
           <?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->_id)); ?>
                     
           <br /></li>
                 <li> <b><?php echo CHtml::encode($data->getAttributeLabel('BianHao')); ?>:</b>
           <?php echo CHtml::encode($data->BianHao); ?>
                 <li> <b><?php echo CHtml::encode($data->getAttributeLabel('author')); ?>:</b>
           <?php echo CHtml::encode($data->author); ?>
           <br /></li>
                 <li><b><?php echo CHtml::encode($data->getAttributeLabel('publisher')); ?>:</b>
           <?php echo CHtml::encode($data->publisher); ?>
           <br />   </li>
                 <li><b><?php echo CHtml::encode($data->getAttributeLabel('Description')); ?>:</b>
           <?php echo CHtml::encode($data->description); ?></li>
             </ul>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
    
    
</div>