<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   19.03.2013  create of the file
 *
 * This Class is responsible for the Profile page.
 */
class views_profile extends views {
  
  // this variable is responcible to get the right page
  public $view_id = 'profile';
  
  // this function contains initialized classes
  public function init() {
    $this -> user = User::byId(session('id'));
  }

  // this function contains some actions which are needed on the page
  public function processAction() {
  }

  // this function contains a additional Header scripts
  public function additionalHeaders() {
  }

}
?>