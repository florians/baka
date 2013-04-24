<div class="charcontainer myChar">
  <?php
  characterProfile($this -> character);
  characterLife($this -> character ->  getHp(), $this -> character ->  getHp());
  characterAtk($this -> character -> getAttaks());
  characterButton('retreat');
  ?>
</div>
<?php
battlelog()
?>
<div class="charcontainer otherChar">
  <?php
  characterProfile($this->opponent);
  characterLife($this->opponent ->  getHp(), $this -> opponent ->  getHp());
  characterAtk($this -> opponent -> getAttaks());
  ?>
</div>