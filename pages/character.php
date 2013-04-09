<h1>Character</h1>
<!--<div class="char">
<div class="charimg"></div>
<div class="charinfo">
<h2>Charactername</h2>
<h3>Level 12</h3>
</div>
<div class="select"><a href="#">Select</a></div>
</div>
<div class="char">
<div class="newchar">
<a href="#" title="New Character"><img src="img/design/plus.png" /></a>
</div>
</div>-->
<?php
if($this->character){
  characterProfile($this->character,$this->attack);
}else{
?>
<div class="char">
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