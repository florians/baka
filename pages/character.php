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
  <div class="parts">
    <div class="partnavi">
      <div rel="leftdiv" class="partnavileft partnavipoint active">
        Attacks
      </div>
      <?php if($this -> attrpoints){ ?>
      <div rel="rightdiv" class="partnaviright partnavipoint">
        Attributpoints
      </div>
      <?php } ?>
    </div>
    <div class="leftdiv innerdiv">
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
    <?php if($this -> attrpoints){ ?>
    <div class="rightdiv hidden innerdiv">
      <div class="charap">
        <div class="apheader">
          <div class="headerleft">
            Attribut
          </div>
          <div class="headerright">
            Points
          </div>
        </div>
        <div class="apcontent">
          <div class="apcontentleft">
            <div rel="PhyAtk" class="att">
              PhyAtk
            </div>
            <div rel="MagAtk" class="att">
              MagAtk
            </div>
            <div rel="PhyDef" class="att">
              PhyDef
            </div>
            <div rel="MagDef" class="att">
              MagDef
            </div>
            <div rel="Durability" class="att">
              Durability
            </div>
          </div>
          <div class="apcontentright">
            <?
            for ($i = 0; $i < $this -> attrpoints; $i++) {
              echo '<div class="skillpoint">+</div>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
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