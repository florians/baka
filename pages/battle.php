<div class="charcontainer">
  <?php
  characterProfile($this -> player);
  characterLife($this -> player ->  getHp(), $this -> player ->  getHp());
  characterAtk($this -> player -> getAttaks());
  characterButton('retreat');
  ?>
</div>
<?php
battlelog()
?>