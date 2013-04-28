<?php
class views_character extends views {

  public $view_id = 'character';

  public function init() {
    $this -> attak = new Attack();
    $this -> charAtk = new CharAtk();
  }

  public function processAction() {
    // gets the userinformation of the logged in user
    $this -> user = User::byId(session('id'));
    // gets tht user ID to write it in the character db
    $this -> uId = $this -> user -> getId();
    // gets the character of the logged in user
    $this -> character = $this -> user -> getChar();
    // gets the character lvl out of the exp db
    if ($this -> character) {
      // gets all attacks
      $attak = new Attack();
      $this -> phyAtk = Attack::select('WHERE aTyp = "p" AND aLearnLvl <=' . $this -> character -> getLevel());
      $this -> magAtk = Attack::select('WHERE aTyp = "m" AND aLearnLvl <=' . $this -> character -> getLevel());
      $this -> specialAtk = Attack::select('WHERE aTyp = "a" AND aLearnLvl <=' . $this -> character -> getLevel());

      $this -> attrpoints = $this -> character -> getAp();
    }
  }

  public function additionalHeaders() {
    $header = '
      <script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery(".partnaviright").click();
        });
      </script>';
    echo $header;
  }

}
?>