<?php

$this->breadcrumbs = array(
  Yii::t('app', 'Admin')=>array('/admin'),
	$model->label(2) => array('index'),
	GxHtml::valueEx($model),
);

$this->menu=array(
	array('label'=>Yii::t('app', 'Manage') . ' ' . $model->label(2), 'url'=>array('admin')),
	array('label'=>Yii::t('app', 'Create') . ' ' . $model->label(), 'url'=>array('create')),
	array('label'=>Yii::t('app', 'Update') . ' ' . $model->label(), 'url'=>array('update', 'id' => $model->id)),
	array('label'=>Yii::t('app', 'Delete') . ' ' . $model->label(), 
    'url'=>'#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm'=>'Are you sure you want to delete this item?'),
    'visible' => !($model->hasAttribute("locked") && $model->locked)),
);
?>

<h1><?php echo Yii::t('app', 'View') . ' ' . GxHtml::encode($model->label()) . ' ' . GxHtml::encode(GxHtml::valueEx($model)); ?></h1>

<?php 
$used_collection = Yii::t('t', 'none');
if (count($model->collections) > 0) {
  $is = array();
  
  foreach($model->collections as $collection) {
    $is[] = $collection->name;
  }
  
  $used_collection = implode(", ", $is);
}

$this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'cssFile' => Yii::app()->request->baseUrl . "/css/yii/detailview/styles.css",
  'attributes' => array(
  'id',
  'name',
  'description',
  array (
    'name' => Yii::t('app', 'Used by collection(s)'),
    'type' => 'html',
    'value' => $used_collection,
  ),
  'created',
  'modified',
	),
)); ?>
