<?php
class views_battle extends views {

  public $view_id = 'battle';

  public function init() {
    $character = new Character();
    $this -> character = $character -> byUserId(session('id'));

    $this -> character2 = $character -> select('WHERE cUserId <>' . session('id').' LIMIT 1');
    $this -> character2 = $this -> character2[0];
    
    $attack = new Attack();
    $this->attack = $attack->select();
  }

  public function processAction() {
  }

  public function additionalHeaders() {
  }

}
?>