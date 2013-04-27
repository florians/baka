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