<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   19.03.2013  create of the file
 *
 * This class is responsible for the Messages
 */
class Message {

  // this variable is responsible for the Object instance
  protected static $ins = null;
  // this variable contains the errors of the Object
  protected $error = array();
  // this variable contains the notices of the Object
  protected $notice = array();
  // this variable contains the success of the Object
  protected $success = array();
  /*
   *  this function creates a new instance of the Object of not already set.
   *  If set the Object will get returned
   */
  public static function getInstance() {

    if (self::$ins == null) {
      self::$ins = new Message();
    }

    return self::$ins;

  }

  public function addError($msg) {
    $this -> error[] = $msg;
  }

  public function addNotice($msg) {
    $this -> notice[] = $msg;
  }

  public function addSuccess($msg) {
    $this -> success[] = $msg;
  }

  public function getSuccess() {
    return $this -> success;
  }

  public function getNotice() {
    return $this -> notice;
  }

  public function getError() {
    return $this -> error;
  }

  public function getMessage() {
    $arr = $this -> error + $this -> notice + $this -> success;
    return $arr;
  }

  // this function generates the html with the messages
  public function getMessageHtml() {
    $message = '';

    if ($this -> getSuccess()) {
      $message .= '<div class="messages success">';
      foreach ($this->getSuccess() as $msg) {
        $message .= '<p>' . $msg . '</p>';
      }
      $message .= '</div>';
    }
    if ($this -> getNotice()) {
      $message .= '<div class="messages notice">';
      foreach ($this->getNotice() as $msg) {
        $message .= '<p>' . $msg . '</p>';
      }
      $message .= '</div>';
    }
    if ($this -> getError()) {
      $message .= '<div class="messages error">';
      foreach ($this->getError() as $msg) {
        $message .= '<p>' . $msg . '</p>';
      }
      $message .= '</div>';
    }

    return $message;

  }

}
