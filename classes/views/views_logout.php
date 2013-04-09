<?php
class views_logout extends views {

  public $view_id = 'logout';

  public function init() {
    $user = new User();
  }

  public function processAction() {
  }

  public function additionalHeaders() {
  }

}
?>