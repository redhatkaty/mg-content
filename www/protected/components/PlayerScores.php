<?php
/**
 * PlayerScores class file
 *
 * @author Vincent Van Uffelen <novazembla@gmail.com>
 * @link http://www.metadatagames.com/
 * @copyright Copyright &copy; 2008-2011 Tiltfactor
 * @license http://www.metadatagames.com/license/
 */

/**
 * PlayerScores provides a small widget that lists the scores of the current player for each active game
 *
 * @author Vincent Van Uffelen <novazembla@gmail.com>
 * @since 1.0
 */
Yii::import('zii.widgets.CPortlet');
 
class PlayerScores extends CPortlet
{
  public function init() {
    $this->title=Yii::t('app', "Your Scores");
      parent::init();
  }
 
  protected function renderContent() {
    if ($user_id = Yii::app()->user->id) {
      $games = GamesModule::getPlayerScores($user_id);
      
      if (is_null($games))
        $games = array();
      
      $this->render('playerScores', array(
        'games' => $games
      ));  
    }
  }
}