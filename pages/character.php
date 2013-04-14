<h1>Character</h1>
<?php
if($this->character){
  ?>
  <div class="left">
    <div class="charcontainer">
      <?php
        characterProfile($this -> character);
        characterLife($this -> character ->  getHp(), $this -> character ->  getHp());
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
      <div class="physical selectable">
        <?php
        $content = null;
        foreach ($this->phyAtk as $pAtk) {
          echo '<a href="#" rel="' . $pAtk -> getId() . '" class="skill" title="' . $pAtk -> getName() . '">' . $pAtk -> getName() . '</a>';
        }
        ?>
      </div>
      <div class="magical selectable">
        <?php
        foreach ($this->magAtk as $mAtk) {
          echo '<a href="#" rel="' . $mAtk -> getId() . '" class="skill" title="' . $mAtk -> getName() . '">' . $mAtk -> getName() . '</a>';
        }
        ?>
      </div>
      <div class="special selectable">
        <?php
        foreach ($this->specialAtk as $sAtk) {
          echo '<a href="#" rel="' . $sAtk -> getId() . '" class="skill" title="' . $sAtk -> getName() . '">' . $sAtk -> getName() . '</a>';
        }
        ?>
      </div>
      <?php
      echo $content;
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