<?php
$this->breadcrumbs=array(
	'BorrowHistory'=>array('index'),
	$model->bookName=>array('view','id'=>$model->_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List BorrowHistory', 'url'=>array('index')),
	array('label'=>'View BorrowHistory', 'url'=>array('view', 'id'=>$model->_id)),
	array('label'=>'Manage BorrowHistory', 'url'=>array('admin')),
);
?>

<h1>Update BorrowHistory <?php echo $model->_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>