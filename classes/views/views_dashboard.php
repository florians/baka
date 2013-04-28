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
    if ($this -> character) {
      $this -> battle = Battle::challengeings($this -> character -> getId());
    }
  }

  public function additionalHeaders() {
    $header = '
      <script type="text/javascript">
      ';
    $header .= '
      dashboard();
     
      var renewDash = true;
      var challangeable = true;
      var dashboardTime = setInterval("dashboard()", 3000);
      var checkRequestTime;
      var checkRequest = false;
        ';
    if ($this -> character) {

      $header .= ' 
           var thisCharId = ' . $this -> character -> getId() . ';
         ';

      if (count($this -> battle) > 0) {
        $battle = $this -> battle[0];
        if ($battle != null) {
          $header .= '
              renewDash = false;
              challangeable = true;
              clearInterval(dashboardTime);
              checkRequest = true;
              requestCheck(' . $battle -> getId() . ');
              checkRequestTime = setInterval("requestCheck(' . $battle -> getId() . ')", 3000);
              jQuery(document).ready(function() {
                jQuery(".right").html("<pre>You have challanged an opponent</pre>");
              });
            ';
        }
      } else {
        $header .= ' 
           var receiveChallengeTime = setInterval("hasChallange(thisCharId)", 3000);
          ';
      }
    }
    $header .= '</script>';
    echo $header;
  }

}
?>