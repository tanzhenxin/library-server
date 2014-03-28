<?php
/* @var $this BorrowHistoryController */
/* @var $model BorrowHistory */

$this->breadcrumbs=array(
	'BorrowHistory'=>array('index'),
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
	$.fn.yiiGridView.update('book-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<h1>Manage BorrowHistory</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

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
                'header'=>'Book Name',
                'value'=>array($this,'getBookTitle'), 
                 ),
                array(            
                'name'=>'bookTag',
                'header'=>'Book Tag',
                'value'=>array($this,'getBookTag'), 
                 ),
                array(            
                'name'=>'userName',
                'header'=>'User Name',
                'value'=>array($this,'getUserName'), 
                 ),
                array(            
                'name'=>'startBorrowDate',
                'header'=>'Start Borrow Date',
                 ),
                array(            
                'name'=>'planReturnDate',
                'header'=>'Plan Return Date',
                 ),
                array(            
                'name'=>'realReturnDate',
                'header'=>'Real Return Date',
                'value'=>array($this,'gridDataColumn'), 
                 ),       
		//'_id',
		
//		array(
//			'class'=>'CButtonColumn',
//                        'header'=>'Operatoin',
//		),
	),
)); ?>