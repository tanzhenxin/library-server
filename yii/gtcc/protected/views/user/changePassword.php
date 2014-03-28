<?php
/* @var $this UserController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Change Password';
?>
<style type="text/css">
.clearfix {zoom:1;}
.clearfix:after {content:"."; display:block; height:0; visibility:hidden; clear:both;}
label {display:block; float:left; width:200px; text-align:left;}
input {display:block; float:left;}
</style>
<div id='box-logo'>
  <div id='logo-sw-270x60'></div>
</div>
<h2>Change Password</h2>
<?php /** @var BootActiveForm $form */
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'changePassword-form',
    'inlineErrors'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
      'validateOnSubmit'=>true,
    ),
    'htmlOptions'=>array('class'=>'well'),
)); ?>


<div class="clearfix" style="padding-bottom: 10px"><?php echo $form->passwordFieldRow($model, 'currentPassword', array('class'=>'span3','placeholder'=>'Current Password...')); ?></div>
<div class="clearfix" style="padding-bottom: 10px"><?php echo $form->passwordFieldRow($model, 'newPassword', array('class'=>'span3','placeholder'=>'New Password...')); ?></div>
<div class="clearfix" style="padding-bottom: 10px"><?php echo $form->passwordFieldRow($model, 'newPassword_repeat', array('class'=>'span3','placeholder'=>'New Password (repeat)...')); ?></div>
<div class="clearfix" >
<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Save', 'type'=>'primary')); ?></div>
<?php $this->endWidget(); ?>
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