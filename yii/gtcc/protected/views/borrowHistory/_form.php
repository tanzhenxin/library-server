<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'borrowHistory-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'planReturnDate'); ?>
		<?php echo $form->textField($model,'planReturnDate'); ?>
		<?php echo $form->error($model,'planReturnDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'realReturnDate'); ?>
		<?php echo $form->textField($model,'realReturnDate'); ?>
		<?php echo $form->error($model,'realReturnDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startBorrowDate'); ?>
		<?php echo $form->textField($model,'startBorrowDate'); ?>
		<?php echo $form->error($model,'startBorrowDate'); ?>
	</div>

        <div class="row">
		<?php echo $form->labelEx($model,'userId'); ?>
		<?php echo $form->textField($model,'userId'); ?>
		<?php echo $form->error($model,'userId'); ?>
	</div>
        
	<div class="row">
		<?php echo $form->labelEx($model,'userName'); ?>
		<?php echo $form->textField($model,'userName'); ?>
		<?php echo $form->error($model,'userName'); ?>
	</div>

        <div class="row">
		<?php echo $form->labelEx($model,'bookId'); ?>
		<?php echo $form->textField($model,'bookId'); ?>
		<?php echo $form->error($model,'bookId'); ?>
	</div>
        
	<div class="row">
		<?php echo $form->labelEx($model,'bookName'); ?>
		<?php echo $form->textField($model,'bookName'); ?>
		<?php echo $form->error($model,'bookName'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->