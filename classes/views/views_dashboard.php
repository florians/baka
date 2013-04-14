<?php
class views_dashboard extends views {

  public $view_id = 'dashboard';

  public function init() {
  }

  public function processAction() {
    // gets the userinformation of the logged in user
    $this -> user = User::byId(session('id'));
    // gets the character of the logged in user
    $this -> character = $this -> user -> getChar();
  }

  public function additionalHeaders() {
    $header = '
      <script type="text/javascript">
      ';
      $header .= '
      var renewDash = true;
      var challangeable = true;
      var dashboardTime = setInterval("dashboard()", 3000);
      var checkRequestTime;
      var checkRequest = true;
        '; 
      if($this->character){
        $header .= '
         var receiveChallengeTime = setInterval("hasChallange('.$this -> character ->getId().')", 3000);
        ';
      }
      $header .= '</script>';
    echo $header;
  }

}
?>