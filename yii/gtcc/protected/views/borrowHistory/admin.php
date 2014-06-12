<?php
/* @var $this BorrowHistoryController */
/* @var $model BorrowHistory */

$this->breadcrumbs=array(
	'Manage Records',
	'Manage',
);

//$this->menu=array(
//	array('label'=>'List BorrowHistory', 'url'=>array('index')),
//	array('label'=>'Create BorrowHistory', 'url'=>array('create')),
//);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('borrowHistory-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<h1>Manage Records</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php 
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'borrowHistory-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
                array(            
                'name'=>'bookName',
                'header'=>'Title',
                'value'=>array($this,'getBookTitle'), 
                 ),
                array(            
                'name'=>'bookTag',
                'header'=>'Tag',
                'value'=>array($this,'getBookTag'), 
                 ),
                array(            
                'name'=>'userName',
                'header'=>'User Name',
                'value'=>array($this,'getUserName'), 
                 ),
                array(            
                'name'=>'startBorrowDate',
                'header'=>'Borrowed Date',
                 ),
                array(            
                'name'=>'planReturnDate',
                'header'=>'Due Date',
                 ),
                array(            
                'name'=>'realReturnDate',
                'header'=>'Returned Date',
                'value'=>array($this,'gridDataColumn'), 
                 ),       
		//'_id',
		
//		array(
//			'class'=>'CButtonColumn',
//                        'header'=>'Operatoin',
//		),
	),
)); ?>