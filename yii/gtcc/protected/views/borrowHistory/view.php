<?php
$this->breadcrumbs=array(
	'BorrowHistory'=>array('index'),
	$model->_id,
);

$this->menu=array(
	array('label'=>'List BorrowHistory', 'url'=>array('index')),
	array('label'=>'Create BorrowHistory', 'url'=>array('create')),
	array('label'=>'Update BorrowHistory', 'url'=>array('update', 'id'=>$model->_id)),
	array('label'=>'Delete BorrowHistory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BorrowHistory', 'url'=>array('admin')),
);

$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'planReturnDate',
                'realReturnDate',
                'startBorrowDate',
                'userId',
                'userName',
                'bookId',
                'bookName',
		//'_id',
	),
)); ?>