<?php

class TagController extends GxController {

  public function filters() {
  	return array(
  			'accessControl', 
  			);
  }
  
  public function accessRules() {
  	return array(
  			array('allow',
  				'actions'=>array('view'),
  				'roles'=>array('*'),
  				),
  			array('allow', 
  				'actions'=>array('index','view', 'batch', 'create','update', 'admin', 'delete'),
  				'roles'=>array('editor', 'dbmanager', 'admin', 'xxx'), // ammend after creation
  				),
  			array('deny', 
  				'users'=>array('*'),
  				),
  			);
  }

	public function actionView($id) {
		$this->render('view', array(
			'model' => $this->loadModel($id, 'Tag'),
		));
	}

	public function actionCreate() {
		$model = new Tag;
		$model->created = date('Y-m-d H:i:s'); 
    $model->modified = date('Y-m-d H:i:s'); 
    
		$this->performAjaxValidation($model, 'tag-form');

		if (isset($_POST['Tag'])) {
			$model->setAttributes($_POST['Tag']);

			if ($model->save()) {
        MGHelper::log('create', 'Created Tag with ID(' . $model->id . ')');
				Flash::add('success', Yii::t('app', "Tag created"));
        if (Yii::app()->getRequest()->getIsAjaxRequest())
					Yii::app()->end();
				else 
				  $this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render('create', array( 'model' => $model));
	}

	public function actionUpdate($id) {
		$model = $this->loadModel($id, 'Tag');
    $model->modified = date('Y-m-d H:i:s');
		$this->performAjaxValidation($model, 'tag-form');

		if (isset($_POST['Tag'])) {
			$model->setAttributes($_POST['Tag']);

			if ($model->save()) {
        MGHelper::log('update', 'Updated Tag with ID(' . $id . ')');
        Flash::add('success', Yii::t('app', "Tag updated"));
				$this->redirect(array('view', 'id' => $model->id));
			}
		}

		$this->render('update', array(
				'model' => $model,
				));
	}

	public function actionDelete($id) {
		if (Yii::app()->getRequest()->getIsPostRequest()) {
			$model = $this->loadModel($id, 'Tag');
			if ($model->hasAttribute("locked") && $model->locked) {
			  throw new CHttpException(400, Yii::t('app', 'Your request is invalid.'));
			} else {
			  $model->delete();
			  MGHelper::log('delete', 'Deleted Tag with ID(' . $id . ')');
        
        Flash::add('success', Yii::t('app', "Tag deleted"));

			  if (!Yii::app()->getRequest()->getIsAjaxRequest())
				  $this->redirect(array('admin'));
		  }
		} else
			throw new CHttpException(400, Yii::t('app', 'Your request is invalid.'));
	}

	public function actionIndex() {
		$model = new Tag('search');
    $model->unsetAttributes();

    if (isset($_GET['Tag']))
      $model->setAttributes($_GET['Tag']);

    $this->render('admin', array(
      'model' => $model,
    ));
	}

	public function actionAdmin() {
		$model = new Tag('search');
		$model->unsetAttributes();

		if (isset($_GET['Tag']))
			$model->setAttributes($_GET['Tag']);

		$this->render('admin', array(
			'model' => $model,
		));
	}
  
  
  public function actionBatch($op) {
    if (Yii::app()->getRequest()->getIsPostRequest()) {
      switch ($op) {
        case "delete":
          $this->_batchDelete();
          break;
      }
      if (!Yii::app()->getRequest()->getIsAjaxRequest())
        $this->redirect(array('admin'));
    } else
      throw new CHttpException(400, Yii::t('app', 'Your request is invalid.'));  
    
  }

  private function _batchDelete() {
    if (isset($_POST['tag-ids'])) {
      $criteria=new CDbCriteria;
      $criteria->addInCondition("id", $_POST['tag-ids']);
            
      MGHelper::log('batch-delete', 'Batch deleted Tag with IDs(' . implode(',', $_POST['tag-ids']) . ')');
        
      $model = new Tag;
      $model->deleteAll($criteria);
        
    } 
  }
}