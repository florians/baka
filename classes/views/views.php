<?php
abstract class views {

  public $view_id = '';

  protected $message;

  abstract public function init();
  abstract public function processAction();
  public function getContent() {
    include ('pages/' . $this -> view_id . '.php');
  }

  public function hasMessagse() {
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

  public function getMessages() {
    return Message::getInstance() -> getMessageHtml();
  }

}
?>