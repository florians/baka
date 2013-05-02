<?php
/**
 * @Author Florian Stettler, Adrian Locher
 * @Version 3
 * Create Date:   19.03.2013  creation of the file
 * 
 * This class is responsible for loading the Dashboard page and it's content
 */
class views_dashboard extends views {

  // this is responsible for loading the right page
  public $view_id = 'dashboard';

  // this initilizes the variables of the classes used on the page
  public function init() {
    $this -> user = new User();
    $this -> character = new Character();
    $this -> battle = new Battle();
  }

  // this processes the infrmation goten from the getter and defines the initialized variables
  public function processAction() {
    // gets the userinformation of the logged in user
    $this -> user = User::byId(session('id'));
    // gets the character of the logged in user
    $this -> character = $this -> user -> getChar();
    if ($this -> character) {
      $this -> battle = Battle::challengeings($this -> character -> getId());
    }
  }

  // Adds new tags to the header with Javascript for this page that is determined by the variables.
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
      onDashboard = true;
      console.debug(onDashboard);
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