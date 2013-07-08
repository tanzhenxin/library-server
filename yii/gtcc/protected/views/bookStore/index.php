<?php
/* @var $this BookStoreController */
/* @var $model BookStore */

$this->breadcrumbs=array(
	'Book Library',
);

?>

<h1>Book Library</h1>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bookDisplay.css" />

<div class="form">
<?php echo CHtml::beginForm(); 
 
    echo '<Label>Book Search:</Label> ';
    echo CHtml::textField('searchCriteria').' ';
    echo CHtml::dropDownList('searchBy', array(), array('by book tag','by book name','by book description'));
    echo ' '.CHtml::submitButton('Search'); 
    echo CHtml::endForm(); 
?>
</div><!-- form -->

<?php

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>