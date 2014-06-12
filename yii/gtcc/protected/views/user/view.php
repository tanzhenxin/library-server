<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->_id,
);

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>View User</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'username',
		'pwd',
		'email',
		'_id',
	),
)); ?>