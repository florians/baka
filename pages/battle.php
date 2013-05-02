<?php
/**
 * @Author Florian Stettler, Adrian Locher
 * @Version 9
 * Create Date:   19.03.2013  creation of the file
 * 
 * This is the battle page this is where the fight happens
 */
?>
<div class="charcontainer myChar">
  <div><p class="status">Waiting</p></div>
  <?php
  characterProfile($this -> character);
  characterLife($this -> character ->  getHp(), $this -> character ->  getHpLeft($this -> battle->getId()));
  characterAtk($this -> character -> getAttaks());
  characterButton('retreat');
  ?>
</div>
<?php
battlelog()
?>
<div class="charcontainer otherChar">
  <div><p class="status">Waiting</p></div>
  <?php
  characterProfile($this->opponent);
  characterLife($this->opponent ->  getHp(), $this -> opponent ->  getHpLeft($this -> battle->getId()));
  characterAtk($this -> opponent -> getAttaks());
  ?>
</div>