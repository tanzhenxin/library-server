<?php
/* @var $this BookStoreController */
/* @var $model BookStore */

$this->breadcrumbs=array(
	'Library',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('book-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Library</h1>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bookDisplay.css" />

<?php echo CHtml::link('Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->
<br><br>
<div id="mainmenu">
<?php

// Available book option
$checkedValue = isset($_GET['available']) ? $_GET['available']: 0 ;

$url = '/bookStore/index';

// Get All menu
$categoryArray = array(
    array('label'=>'All', 'url'=>array($url, 'available'=>$checkedValue), 'active'=> !isset($_GET['category'])?true:false)
);

// Get other menu
foreach (CommonMethod::GetCategoryArray() as $key => $value) {
    array_push($categoryArray, array('label'=>$value, 'url'=>array($url,'category'=>$key,'available'=>$checkedValue)));
}

$this->widget('zii.widgets.CMenu',array(
        'items'=>$categoryArray,
)); 

?>
</div>

<?php
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$model->search(),
	'itemView'=>'_view',
)); ?>