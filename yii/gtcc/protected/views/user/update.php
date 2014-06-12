<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h1>Update User</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>