<?php
class views_registration extends views {

  public $view_id = 'registration';

  public function init() {

  }

  public function processAction() {

    if (get('activate')) {
      $user = new User();
      $user -> checkActivateMail(get('activate'));
    }
  }

  public function additionalHeaders() {
  }

}
?>