<?php
$this->breadcrumbs=array(
	'Books'=>array('index'),
	'Update',
);

$this->menu=array(
	array('label'=>'Create Book', 'url'=>array('create')),
	array('label'=>'Manage Book', 'url'=>array('admin')),
);
?>

<h1>Update Book</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>