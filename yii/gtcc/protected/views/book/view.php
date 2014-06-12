<?php
$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Create Book', 'url'=>array('create')),
	array('label'=>'Manage Book', 'url'=>array('admin')),
);
?>

<h1>View Book</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ISBN',
		'title',
		'price',
		'BianHao',
		'description',
		'author',
		'publishedDate',
		'publisher',
		'language',
		'printLength',
		'_id',
	),
)); ?>