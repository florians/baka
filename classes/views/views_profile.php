<?php
class views_profile extends views {

  public $view_id = 'profile';

  public function init() {
    $this -> user = User::byId(session('id'));
  }

  public function processAction() {
  }

  public function additionalHeaders() {
  }

}
?>