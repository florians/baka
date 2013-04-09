<?php
class views_dashboard extends views {

  public $view_id = 'dashboard';

  public function init() {
    $this -> user = User::byId(session('id'));
    $character = new Character();
    $this -> character = $character -> byUserId(session('id'));

    $attack = new Attack();
    $this -> attack = $attack -> select();
  }

  public function processAction() {
  }

  public function additionalHeaders() {
    $header = '
      <script type="text/javascript">
        dashboard();
        setInterval("dashboard()", 3000); 
      </script>';
    echo $header;
  }

}
?>