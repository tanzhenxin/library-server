<?php
/* @var $this BookController */
/* @var $model Book */

$this->breadcrumbs=array(
	'Manage Books',
	'Manage',
);

$this->menu=array(	
	array('label'=>'Add a book', 'url'=>array('create')),
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

<h1>Manage Books</h1>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'book-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'ISBN',
                array(            
                'name'=>'title',
                'header'=>'Title',
                 ),
                array(            
                'name'=>'BianHao',
                'header'=>'Tag',
                 ),
                array(            
                'name'=>'author',
                'header'=>'Author',
                 ),
		array(            
                'name'=>'publishedDate',
                'header'=>'Publication Date',
                 ),
                array(            
                'name'=>'publisher',
                'header'=>'Publisher',
                 ),
		//'language',
		//'printLength',
                //'description',
		//'_id',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>