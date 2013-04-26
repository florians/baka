<?php

function characterProfile($char, $button = null) {
  $content = '
    <div class="chartop">
      <div class="charimg"><img style="height:190px" src="' . $char -> getImage() . '" /></div>
      <div class="charattr">
        <h2>' . $char -> getName() . '</h2>
        <h3>Level ' . $char -> getLevel() . '</h3>
        <table class="charattrtable">
          <tr>
            <td>PhyAtk</td>
            <td>' . $char -> getPhyAtk() . '</td>
          </tr>
          <tr>
            <td>MagAtk</td>
            <td>' . $char -> getMagAtk() . '</td>
          </tr>
          <tr>
            <td>PhyDef</td>
            <td>' . $char -> getPhyDef() . '</td>
          </tr>
          <tr>
            <td>MagDef</td>
            <td>' . $char -> getMagDef() . '</td>
          </tr>
        </table>
      </div>
    </div>';

  echo $content;
}

function characterLifeRaw($maxLife, $liveLeft) {
  /*$content = '
   <div class="charmiddle">
   <progress value="' . $liveLeft . '" max="' . $maxLife . '"></progress>
   <span class="progresstext">' . $liveLeft . ' / ' . $maxLife . '</span>
   </div>';*/
  $liveLeftProzent = (100 / $maxLife) * $liveLeft;
  $content = '
    <div class="charmiddle">
      <div style="width:' . $liveLeftProzent . '%"class="progressbar_top"></div>
      <div class="progressbar_bottom"></div>
      <span class="progresstext">' . $liveLeft . ' / ' . $maxLife . '</span>
    </div>';
  return $content;
}

function characterLife($maxLife, $liveLeft) {
  echo characterLifeRaw($maxLife, $liveLeft);
}

function characterAtk($atks = null) {
  if (count($atks) > 0) {
    $content = '
      <div class="charbottom">';
    $countatk = 1;
    foreach ($atks as $atk) {
      ($countatk % 5 == 0) ? $addclass = 'skillright' : $addclass = '';
      $content .= '<a href="#" rel="' . $atk -> getId() . '" class="skill ' . $addclass . '" title="' . $atk -> getName() . '"><img src="' . getAtkImag($atk -> getId()) . '" /></a>
                    <span class="skilltext"><b>' . $atk -> getName() . '</b><br />Damage ' . $atk -> getDmgPt() . '<br />Typ ' . $atk -> getTyp() . '</span>';
      $countatk++;
    }
    $content .= '</div>';
  } else {
    $content = '
      <div class="charbottom"><p style="height:50px">There aren\'t any Skills learned!</p></div>';
  }
  echo $content;
}

function characterButton($button) {
  $content = "";
  if ($button == 'retreat') {
    $content .= '<a href="#" class="retreat">Retreat</a>';
  } elseif ($button == 'del') {
    $content .= '<a href="#" class="delAtk">Delete Attacks</a>';
  }
  echo $content;
}

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

function getSkillTable($val, $typ) {
  $content = '';
  $content .= '<div class="' . $typ . ' selectable">';
  foreach ($val as $atk) {
    $content .= '
      <div class="skillcontainer">
        <a href="#" rel="' . $atk -> getId() . '" class="skill" title="' . $atk -> getName() . '"><img src="' . getAtkImag($atk -> getId()) . '" /></a>
        <span class="skilltext"><b>' . $atk -> getName() . '</b><br />Damage ' . $atk -> getDmgPt() . '<br />Typ ' . $atk -> getTyp() . '</span>
      </div>';
  }
  $content .= '</div>';
  echo $content;
}

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
