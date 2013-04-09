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
    // timestamp
    // check timestamp older than 1 min ago
    // UPDATE user SET uOnline = 0 WHERE uLastActivity <= oldTime AND uOnline = '1'
    //User::select();
    $user = User::byId(session('id'));
    $user -> setOnline(post('value'));
    //$user -> setLastActivity();
    $user -> save();
    break;
}
echo $content;
