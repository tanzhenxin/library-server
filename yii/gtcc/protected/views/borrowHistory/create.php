<?php
$this->breadcrumbs=array(
	'BorrowHistory'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BorrowHistory', 'url'=>array('index')),
	array('label'=>'Manage BorrowHistory', 'url'=>array('admin')),
);
?>

<h1>Create BorrowHistory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>