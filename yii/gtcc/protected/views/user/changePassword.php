<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Change Password';
$this->breadcrumbs=array(
	'Change Password',
);

?>

<h1>Change Password</h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'changePassword-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
        <div class="row">
		<?php echo $form->labelEx($model,'currentPassword'); ?>
		<?php echo $form->passwordField($model,'currentPassword'); ?>
		<?php echo $form->error($model,'currentPassword'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'newPassword'); ?>
		<?php echo $form->passwordField($model,'newPassword'); ?>
		<?php echo $form->error($model,'newPassword'); ?>
	</div>
        <div class="row">
		<?php echo $form->labelEx($model,'newPassword_repeat'); ?>
		<?php echo $form->passwordField($model,'newPassword_repeat'); ?>
		<?php echo $form->error($model,'newPassword_repeat'); ?>
	</div>

        <div class="row buttons">
		<?php echo CHtml::submitButton('Save'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php 
  $this->widget('bootstrap.widgets.TbAlert', array(
      'block'=>true, // display a larger alert block?
      'fade'=>true, // use transitions?
      'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
      'alerts'=>array( // configurations per alert type
        'success'=>array(
          'block'=>true,
          'fade'=>true,
          'closeText'=>'&times;',
        ), // success, info, warning, error or danger
      ),
    )
  );
?>
