<?php
class views_character extends views {

  public $view_id = 'character';

  public function init() {
    $user = new User();
    $user = $user -> byId(session('id'));
    $this -> uId = $user -> getId();
    $character = new Character();
    $this -> character = $character -> byUserId(session('id'));
    
    $attack = new Attack();
    $this->attack = $attack->select();
  }

  public function processAction() {
  }

  public function additionalHeaders() {
  }

}
?>