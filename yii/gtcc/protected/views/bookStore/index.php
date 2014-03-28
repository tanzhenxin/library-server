<?php
/* @var $this BookStoreController */
/* @var $model BookStore */

$this->breadcrumbs=array(
	'Book Library',
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

<h1>Book Library</h1>
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

$url = '/bookStore/index';
$this->widget('zii.widgets.CMenu',array(
        'items'=>array(
                array('label'=>'All', 'url'=>array($url), 'active'=> !isset($_GET['category'])?true:false),
                array('label'=>'Self-Improvement', 'url'=>array($url,'category'=>'A')), 
                array('label'=>'English Learning', 'url'=>array($url,'category'=>'B')),
                array('label'=>'Miscellaneous', 'url'=>array($url,'category'=>'C')),
                array('label'=>'Technology', 'url'=>array($url,'category'=>'D')),
        ),
)); ?>
</div>

<?php

$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$model->search(),
	'itemView'=>'_view',
)); ?>