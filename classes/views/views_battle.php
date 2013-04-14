<?php
class views_battle extends views {

  public $view_id = 'battle';

  public function init() {
    $this -> character = new Character();
    $this -> charAtks = new CharAtk();
    $this -> charExp = new Exp();
  }

  public function processAction() {
    // gets the userinformation of the logged in user
    $this -> user = User::byId(session('id'));
    // gets the character of the logged in user
    $this -> player = $this -> user -> getChar();
    if ($this -> player) {
    }
  }

  public function additionalHeaders() {
  }

}
?>