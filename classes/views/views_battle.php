<?php
/**
 * @Author Florian Stettler, Adrian Locher
 * @Version 9
 * Create Date:   19.03.2013  creation of the file
 * 
 * This class is responsible for loading the Battle page and it's content
 */
class views_battle extends views {

  // this is responsible for loading the right page
  public $view_id = 'battle';

  // this initilizes the variables of the classes used on the page
  public function init() {
    $this -> character = new Character();
    $this -> battle = new Battle();
    $this -> opponent = new Character();
    $this -> user = new User();
  }

  // this processes the infrmation goten from the getter and defines the initialized variables
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
            waiting();
            battle = true;
          ';
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