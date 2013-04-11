<?php
session_start();

// include module
include_once '../classes/module/model.php';
include_once '../classes/module/attack.php';
include_once '../classes/module/battle.php';
include_once '../classes/module/battleChar.php';
include_once '../classes/module/character.php';
include_once '../classes/module/charAtk.php';
include_once '../classes/module/exp.php';
include_once '../classes/module/user.php';

// include utils
include_once '../classes/utils/globalHelper.php';
//include_once '../classes/utils/htmlHelper.php';
include_once '../classes/utils/messages.php';
include_once '../classes/utils/database.php';

$character = new Character();
$user = new User();
$charAtk = new CharAtk();

$content = null;
switch(post('event')) {
  case 'dashboard' :
    $opponents = Character::select('AS c INNER JOIN user AS u ON u.uId = c.cUserId WHERE c.cUserId <>' . session('id') . ' AND u.uOnline = "1" ORDER BY c.cLvlExp,c.cName ASC');
    if (isset($opponents[0])) {
      foreach ($opponents as $opponent) {
        $content .= '
        <div class="challangecontainer">
          <div class="charimg"><img style="height:100px;width:100px;"src="' . $opponent -> getImage() . '" /></div>
          <div class="charinfo"><h1>' . $opponent -> getName() . '</h1><h2>Level 1</h2></div>
          <div class="charbutton"><a href="#">Fight!</a></div>
        </div>';
      }
    } else {
      pre('There is no Player online');
    }
    break;
  case 'onlineCheck' :
    $oldTime = strtotime('-1 Minute');
    User::updates('uOnline = "0" WHERE uLastActivity <= ' . $oldTime . ' AND uOnline = "1"');
    $user = User::byId(session('id'));
    $user -> setOnline(post('value'));
    $user -> setLastActivity(strtotime('now'));
    $user -> save();
    break;
  case 'setCharAtk' :
    $char = $character->byUserId(session('id'));
    $charAtk -> setAtkId(post('value'));
    $charAtk -> setCharId($char->getId());
    $charAtk -> save();
    break;
  case 'delCharAtk' :
    $char = $character->byUserId(session('id'));
    $charAtk -> setCharId($char->getId());
    $charAtk -> delete();
    $content = '<script> location.reload();</script>';   
    break;
  case 'hasChallange' :
    $response = array();
    if(post('charId') == 22){
     $response['has'] = "yes";
     $response['message'] = "You've got Mail! It's not spam!";
    } else {
     $response['has'] = "no";   
    }
    $content = json_encode($response);
    break;
}
echo $content;
