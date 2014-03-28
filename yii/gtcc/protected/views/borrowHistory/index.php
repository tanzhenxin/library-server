<?php
$this->breadcrumbs=array(
	'BorrowHistory',
);

$this->menu=array(
	array('label'=>'Create BorrowHistory', 'url'=>array('create')),
	array('label'=>'Manage BorrowHistory', 'url'=>array('admin')),
);

?>

<h1>Borrow History</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>