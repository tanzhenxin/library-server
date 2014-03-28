<div class="view">

<!--	<b><?php //echo CHtml::encode($data->getAttributeLabel('_id')); ?>:</b>
	<?php //echo CHtml::link(CHtml::encode($data->_id), array('view', 'id'=>$data->_id)); ?>
	<br />-->

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

<!--	<b><?php //echo CHtml::encode($data->getAttributeLabel('pwd')); ?>:</b>
	<?php //echo CHtml::encode($data->pwd); ?>
	<br />-->

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />


</div>