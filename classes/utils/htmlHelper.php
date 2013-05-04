<?php
/**
 * @Author Florian Stettler 
 * @Version 1
 * Create Date:   19.03.2013  create of the file
 * 
 * This file contains html Functions for bigger generated parts 
 */
 
 /*
  * this function generates the html of the Character Profile
  * It shows the Avatar of the Character and it's Attribut Points.
  * Also the EXP Progressbar will get generated.
  */
function characterProfile($char, $button = null) {
  $characterExp = Exp::getCharLvlExp($char -> getLvlExp());
  $expProcent = (100 / (($char -> getLvlExp() + $char -> getNextLvlExp())-$characterExp)) * ($char->getLvlExp() - $characterExp);  
  $content = '
    <div class="chartop">
      <div class="charimg"><img style="height:190px" src="' . $char -> getImage() . '" /></div>
      <div class="charattr">
        <h2>' . $char -> getName() . '</h2>
        <h3>Level ' . $char -> getLevel() . '</h3>
        <table class="charattrtable">
          <tr>
            <td>PhyAtk</td>
            <td class="PhyAtk">' . $char -> getPhyAtk() . '</td>
          </tr>
          <tr>
            <td>MagAtk</td>
            <td class="MagAtk">' . $char -> getMagAtk() . '</td>
          </tr>
          <tr>
            <td>PhyDef</td>
            <td class="PhyDef">' . $char -> getPhyDef() . '</td>
          </tr>
          <tr>
            <td>MagDef</td>
            <td class="MagDef">' . $char -> getMagDef() . '</td>
          </tr>
        </table>
        <input class="Durability" type="hidden" value="' . $char -> getDurability() . '" />
      </div>
    </div>
    <div class="exp">
      <div style="width:' . $expProcent . '%"class="exp_top"></div>
      <div class="exp_bottom"></div>
      <span class="exptext">EXP: ' .
      //pre($char->getLvlExp().'__'.$charcterExp);
      //pre($char -> getNextLvlExp());
      
      
      ($char->getLvlExp() - $characterExp) . ' / ' . (($char -> getLvlExp() + $char -> getNextLvlExp())-$characterExp) . '</span>
    </div>';

  echo $content;
}

// this function generates the html of the Character Life bar
function characterLifeRaw($maxLife, $liveLeft) {
  $class = '';
  $liveLeftProzent = (100 / $maxLife) * $liveLeft;
  if ($liveLeftProzent >= 50) {
    $class = 'full';
  } elseif ($liveLeftProzent < 50 && $liveLeftProzent > 25) {
    $class = 'half';
  }
  if ($liveLeftProzent <= 25) {
    $class = 'danger';
  }
  $content = '
    <div class="charmiddle">
      <div style="width:' . $liveLeftProzent . '%"class="progressbar_top ' . $class . '"></div>
      <div class="progressbar_bottom"></div>
      <span class="progresstext">HP: ' . $liveLeft . ' / ' . $maxLife . '</span>
    </div>';
  return $content;
}
// this function calls the Character Lifebar
function characterLife($maxLife, $liveLeft) {
  echo characterLifeRaw($maxLife, $liveLeft);
}
// this function generates the html of Character Attacks container
function characterAtk($atks = null) {
  if (count($atks) > 0) {

    $content = '
      <div class="charbottom">';
    $countatk = 1;
    foreach ($atks as $atk) {
      if ($atk -> getTyp() == 'p') {
        $atkTyp = 'Physical';
      } elseif ($atk -> getTyp() == 'm') {
        $atkTyp = 'Magical';
      } elseif ($atk -> getTyp() == 'a') {
        $atkTyp = 'Special';
      }
      ($countatk % 5 == 0) ? $addclass = 'skillright' : $addclass = '';
      $content .= '<a href="#" rel="' . $atk -> getId() . '" class="skill ' . $addclass . '" title="' . $atk -> getName() . '"><img src="' . getAtkImag($atk -> getId()) . '" /></a>
                    <span class="skilltext"><b>' . $atk -> getName() . '</b><br />Damage ' . $atk -> getDmgPt() . '<br />' . $atkTyp . '</span>';
      $countatk++;
    }
    $content .= '</div>';
  } else {
    $content = '
      <div class="charbottom"><p style="height:50px">There aren\'t any Skills learned!</p></div>';
  }
  echo $content;
}

// this function generates the html of the different buttons wich are needed in a fight or on the Character Page
function characterButton($button) {
  $content = "";
  if ($button == 'retreat') {
    $content .= '<a href="#" class="retreat">Retreat</a>';
  } elseif ($button == 'del') {
    $content .= '<a href="#" class="delAtk">Delete Attacks</a>';
  }
  echo $content;
}
// this function generates the html of battlelog
function battlelog() {
  $content = null;
  $content .= '
  <div class="battlelog">
    <div class="battlelogvs">VS</div>
    <div class="battlelogtext">
      <h2>Battlelog</h2>
      Here is the Battlelog text!!
    </div>
  </div>';
  echo $content;
}
// this function generates the html of the Skill Table 
function getSkillTable($val, $typ) {
  $content = '';
  $content .= '<div class="' . $typ . ' selectable">';
  foreach ($val as $atk) {
    if ($atk -> getTyp() == 'p') {
      $atkTyp = 'Physical';
    } elseif ($atk -> getTyp() == 'm') {
      $atkTyp = 'Magical';
    } elseif ($atk -> getTyp() == 'a') {
      $atkTyp = 'Special';
    }
    $content .= '
      <div class="skillcontainer">
        <a href="#" rel="' . $atk -> getId() . '" class="skill" title="' . $atk -> getName() . '"><img src="' . getAtkImag($atk -> getId()) . '" /></a>
        <span class="skilltext"><b>' . $atk -> getName() . '</b><br />Damage ' . $atk -> getDmgPt() . '<br />Typ ' . $atkTyp . '</span>
      </div>';
  }
  $content .= '</div>';
  echo $content;
}
// this function generates the Attack picture if it is set. Otherwise it will show a default image
function getAtkImag($atk) {
  $atkimgpath = 'img/atkimg/' . $atk . '.jpg';
  if (file_exists($atkimgpath)) {
    $atkimg = $atkimgpath;
  } else {
    $atkimg = 'img/atkimg/def.jpg';
  }
  return $atkimg;
}
?>
