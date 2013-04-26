<?php
class views_battle extends views {

  public $view_id = 'battle';

  public function init() {
    $this -> character = new Character();
    $this -> battle = new Battle();
    $this -> opponent = new Character();
    $this -> user = new User();
  }

  public function processAction() {
    // gets the userinformation of the logged in user
    $this -> user = User::byId(session('id'));
    // gets the character of the logged in user
    $this -> character = $this -> user -> getChar();
    if ($this -> character && get("fight")) {
      $battles = Battle::fightExists(get("fight"),$this -> character -> getId());
      if(count($battles) <= 0){
          header("location: index.php");
      } else {
        $this -> battle = $battles[0];
        $this -> opponent = $this -> battle -> getOpponent($this->character -> getId());
      }
    } else {
      header("location: index.php");
    }
  }

  public function additionalHeaders() {
    $header = '
      <script type="text/javascript">
      ';
      $header .= '
          var waitingTime;
          var theWait = true;
          var attacking = false;
        '; 
      if($this->character){
        
        $header .= ' 
           var thisCharId = '.$this -> character ->getId().';
         ';
         
        if($this -> battle != null){
          $header .= '
            battleId = '.$this -> battle -> getId().';
            attackingPlayer = '.$this->battle->getWhosTurn().'
          ';
          if($this->character -> getId() != $this -> battle -> getPlayer($this->battle->getWhosTurn()) -> getCharId()){
            $header .= '
              waiting();
              waitingTime = setInterval("waiting()",3000);
          ';
          } else {
            $header .= '
              attacking = true;
              theWait = false;
          ';
          }
        }
      } else {
        $header .= ' 
         var receiveChallengeTime = setInterval("hasChallange(thisCharId)", 3000);
        ';
      }
      $header .= '</script>';
    echo $header;
  }

}
?>