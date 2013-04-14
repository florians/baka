<?php

function characterProfile($char, $button = null) {
   $content = '
    <div class="chartop">
      <div class="charimg"><img style="height:190px" src="' . $char -> getImage() . '" /></div>
      <div class="charattr">
        <h2>' . $char -> getName() . '</h2>
        <h3>Level ' . $char->getLevel() . '</h3>
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

function characterLife($maxLife, $liveLeft) {
  $content = '
    <div class="charmiddle">
      <progress value="' . $liveLeft . '" max="' . $maxLife . '"></progress>
      <span class="progresstext">' . $liveLeft . ' / ' . $maxLife . '</span>
    </div>';
  echo $content;
}

function characterAtk($atks = null) {
  if (count($atks) > 0) {
    $content = '
      <div class="charbottom">';
    $countatk = 1;
    foreach ($atks as $atk) {
      ($countatk % 5 == 0) ? $addclass = 'skillright' : $addclass = '';
      $content .= '<a href="#" class="skill ' . $addclass . '" title="' . $atk -> getName() . '">' . $atk -> getName() . '</a>';
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
    $content .= '<a href="#" class="delAtk">Delete Attaks</a>';
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

// will may get uneccessary
function getMessages($action) {
  if ($action) {
    $content = '<div class="messages">';
    switch($action) {
      case 'newUser' :
        $content .= 'New User has been added!';
        break;
      case 'noUser' :
        $content .= 'User already exists!';
        break;
      case 'noAnswer' :
        $content .= 'Answer could not be set!';
        break;
      case 'Answeradd' :
        $content .= 'Answer added!';
        break;
      case 'loggedin' :
        $content .= 'Successfully logged in!';
        break;
      case 'loggedout' :
        $content .= 'Successfully logged out!';
        break;
      case 'noUserFound' :
        $content .= 'User not found!';
        break;
    }
    $content .= '</div>';
    echo $content;
  }
}
?>
