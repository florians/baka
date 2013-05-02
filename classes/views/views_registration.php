<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   19.03.2013  create of the file
 *
 * This Class is responsible for the Registration page.
 */
class views_registration extends views {

  // this variable is responcible to get the right page
  public $view_id = 'registration';

  // this function contains initialized classes
  public function init() {
  }

  // this function contains some actions which are needed on the page
  public function processAction() {
    if (get('activate')) {
      $user = new User();
      $user -> checkActivateMail(get('activate'));
      Message::getInstance() -> addSucces('Account Activation successful!');
    }
  }

  // this function contains a additional Header scripts
  public function additionalHeaders() {
  }

}
?>