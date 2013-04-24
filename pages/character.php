<h1>Character</h1>
<?php
if($this->character){
?>
<div class="left">
  <div class="charcontainer">
    <?php
    characterProfile($this -> character);
    characterLife($this -> character -> getHp(), $this -> character -> getHp());
    characterAtk($this -> character -> getAttaks());
    characterButton('del');
    ?>
  </div>
</div>
<div class="right">
  <div class="variation">
    <a href="#" rel="physical" class="physical variation"> Physical </a>
    <a href="#" rel="magical" class="magical variation"> Magical </a>
    <a href="#" rel="special" class="special variation"> Special </a>
  </div>
  <div class="skills">
    <?php
    getSkillTable($this -> phyAtk, 'physical');
    getSkillTable($this -> magAtk, 'magical');
    getSkillTable($this -> specialAtk, 'special');
    ?>
  </div>
</div>
<?php
}else{
?>
<div class="newChar">
  <form enctype="multipart/form-data" method="post">
    <input type="hidden" value="newcharacter" name="event"/>
    <input type="hidden" value="<?= $this -> uId ?>" name="uId"/>
    <table>
      <tr>
        <td class="label">Character Name</td>
        <td>
        <input type="text" value="<?= decode(post('charactername')) ?>" name="charactername" />
        </td>
      </tr>
      <tr>
        <td class="label">Character Image</td>
        <td>
        <input type="file" value="" name="characterimage" />
        </td>
      </tr>
      <tr>
        <td class="label"></td>
        <td>
        <input type="submit" value=" Send " />
        </td>
      </tr>
    </table>
  </form>
</div>
<?php } ?>