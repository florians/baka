<?php

class Message {

  protected static $ins = null;

  protected $error = array();

  protected $notice = array();

  protected $success = array();

  protected function __construct() {

  }

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
