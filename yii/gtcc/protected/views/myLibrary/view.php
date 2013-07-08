<?php
$this->breadcrumbs=array(
	'Books'=>array('index'),
	$model->bookName,
);

?>

<h1>Book Detail :<?php echo $model->bookName; ?></h1>

<div><img src="<?php echo 'images\\'.$model->ISBN.'.jpg'; ?>"/></div>


<br>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ISBN',
		
		//'_id',
	),
)); ?>