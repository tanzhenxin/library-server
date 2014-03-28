<?php
$this->breadcrumbs=array(
	'My Library',
);

?>

<h1>My Library</h1>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bookDisplay.css" />
<div id="mainmenu">
<?php

$url = '/myLibrary/index';
$this->widget('zii.widgets.CMenu',array(
        'items'=>array(
                array('label'=>'Book in Borrowed', 'url'=>array($url), 'active'=> !isset($_GET['category'])?true:false),
                array('label'=>'Book Returned', 'url'=>array($url,'category'=>0)), 
        ),
)); ?>
</div>
    <?php 
    $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>