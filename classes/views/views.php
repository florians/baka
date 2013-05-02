<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   19.03.2013  create of the file
 *
 * This class is the abstract class for every views page
 */
abstract class views {

  // this variable is responcible to get the right page
  public $view_id = '';

  // this function contains initialized classes
  abstract public function init();
  
  // this function contains some actions which are needed on the page
  abstract public function processAction();
  
  // this function reads the content out of the page which is in the view_id variable
  public function getContent() {
    include ('pages/' . $this -> view_id . '.php');
  }
  // this function get the messages out of the addressline
  public function hasMessages() {
    if (get('success')) {
      Message::getInstance() -> addSuccess(get('success'));
    }
    if (get('error')) {
      Message::getInstance() -> addError(get('error'));
    }
    if (get('notice')) {
      Message::getInstance() -> addNotice(get('notice'));
    }
  }
  // this function gets back the messages in the HTML
  public function getMessages() {
    return Message::getInstance() -> getMessageHtml();
  }

}
?>