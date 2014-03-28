<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

    
        <div class="row">
		<?php echo $form->label($model,'bookName'); ?>
		<?php echo $form->textField($model,'bookName'); ?>
	</div>
    
        <div class="row">
		<?php echo $form->label($model,'bookTag'); ?>
		<?php echo $form->textField($model,'bookTag'); ?>
	</div>
    
        <div class="row">
		<?php echo $form->label($model,'userName'); ?>
		<?php echo $form->textField($model,'userName'); ?>
	</div>
    
        <div class="row">
		<?php echo $form->label($model,'startBorrowDate'); ?>
		<?php echo $form->textField($model,'startBorrowDate'); ?>
	</div>
    
	<div class="row">
		<?php echo $form->label($model,'planReturnDate'); ?>
		<?php echo $form->textField($model,'planReturnDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'realReturnDate'); ?>
		<?php echo $form->textField($model,'realReturnDate'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->