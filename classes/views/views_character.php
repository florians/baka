<?php
class views_character extends views {

  public $view_id = 'character';

  public function init() {
    $user = new User();
    $user = $user -> byId(session('id'));
    $this -> uId = $user -> getId();
    $character = new Character();
    $this -> character = $character -> byUserId(session('id'));
    
    $charAtk = new CharAtk();
    $this->charAtks = $charAtk->byId($this -> character->getId());
    
    $attak = new Attack();
    $this->phyAtk = $attak::select('WHERE aTyp = "p"');
    $this->magAtk = $attak::select('WHERE aTyp = "m"');
    $this->specialAtk = $attak::select('WHERE aTyp = "a"');
  }

  public function processAction() {
  }

  public function additionalHeaders() {
  }

}
?>