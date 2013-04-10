<?php
class views_battle extends views {

  public $view_id = 'battle';

  public function init() {
    $character = new Character();
    $this -> character = $character -> byUserId(session('id'));

    $this -> character2 = $character -> select('WHERE cUserId <>' . session('id').' LIMIT 1');
    $this -> character2 = $this -> character2[0];
    
    $charAtk = new CharAtk();
    $this->charAtks = $charAtk->byId($this -> character->getId());
    $this->charAtks2 = $charAtk->byId($this -> character2->getId());
  }

  public function processAction() {
  }

  public function additionalHeaders() {
  }

}
?>