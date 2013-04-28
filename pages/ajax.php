<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 1);
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
include_once '../classes/utils/htmlHelper.php';
include_once '../classes/utils/messages.php';
include_once '../classes/utils/database.php';

$character = new Character();
$user = new User();
$charAtk = new CharAtk();

$content = null;
switch(post('event')) {
  case 'dashboard' :
    //$opponents = Character::select('AS c INNER JOIN user AS u ON u.uId = c.cUserId WHERE c.cUserId <>' . session('id') . ' AND u.uOnline = "1" AND uListOnDashboard = "1" ORDER BY c.cLvlExp,c.cName ASC');
    $opponents = Character::select('AS c INNER JOIN user AS u ON u.uId = c.cUserId WHERE c.cUserId <> ' . session('id') . ' AND u.uOnline = "1" AND u.uListOnDashboard = "1" AND (SELECT count(caCharId) as countatk FROM charatk WHERE caCharId = c.cId )  <> "0" ORDER BY c.cLvlExp,c.cName ASC');

    if (isset($opponents[0])) {
      foreach ($opponents as $opponent) {
        $content .= '
        <div class="challangecontainer">
          <div class="charimg"><img style="height:100px;"src="' . $opponent -> getImage() . '" /></div>
          <div class="charinfo"><h1>' . $opponent -> getName() . '</h1><h2>Level ' . $opponent -> getLevel() . '</h2></div>
          <div class="charbutton"><button id="' . $opponent -> getId() . '" class="challenge">Fight!</button></div>
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
    $user -> setListOnDashboard(post('dashboard')?1:0);
    $user -> save();
    break;
  case 'setCharAtk' :
    $char = $character -> byUserId(session('id'));
    $charAtk -> setAtkId(post('value'));
    $charAtk -> setCharId($char -> getId());

    $charAtk -> save();
    break;
  case 'delCharAtk' :
    $char = $character -> byUserId(session('id'));
    $charAtk -> setCharId($char -> getId());
    $charAtk -> delete();
    $content = '<script> location.reload();</script>';
    break;
  case 'setAttribute' :
    $char = $character -> byUserId(session('id'));
    switch (post('value')) {
      case 'MagAtk' :
        $char -> setMagAtk($char -> getMagAtk() + 1);
        break;
      case 'PhyAtk' :
        $char -> setPhyAtk($char -> getPhyAtk() + 1);
        break;
      case 'MagDef' :
        $char -> setMagDef($char -> getMagDef() + 1);
        break;
      case 'PhyDef' :
        $char -> setPhyDef($char -> getPhyDef() + 1);
        break;
      case 'Durability' :
        $char -> setDurability($char -> getDurability() + 1);
        break;
    }
    $char -> setAp($char->getAp()-1);
    $char -> save();
    break;
  case 'makeRequest' :
    $response = array();
    if (!is_null(post("challenger")) && is_numeric(post("challenger")) && post("challenger") > 0 && !is_null(post("challengee")) && is_numeric(post("challengee")) && post("challenger") > 0) {
      $chars = array();
      $chars[] = Character::byId(post("challenger"));
      $chars[] = Character::byId(post("challengee"));
      $i = 1;

      $battle = new Battle();
      $battle -> setTimeOfChallange(time());
      $battle -> setChallengeStatus("p");
      $battle -> setWhosTurn(rand(1, 2));
      $battle -> setRound(0);
      $battle -> setOver(FALSE);

      if ($battle -> save()) {

        foreach ($chars as $char) {
          $battleChar = new BattleChar();
          $battleChar -> setBattleId($battle -> getId());
          $battleChar -> setCharId($char -> getId());
          $battleChar -> setHp($char -> getHp());
          $battleChar -> setPlayer($i);
          if (!$battleChar -> save()) {
            $response['success'] = false;
            $response['message'] = "There was a mistake battle could not be started. Player " . $i;
            $battle -> delete();
            break;
          }
          $i++;
        }

        if (!isset($response['success'])) {
          $response['success'] = true;
          $response['battleId'] = $battle -> getId();
        }

      } else {
        $response['success'] = false;
        $response['message'] = "There was a mistake battle could not be started. battle could not be saved";
      }

    }

    if (!isset($response['success'])) {
      $response['success'] = false;
      $response['message'] = "Ids have an mistake";
    }
    $content = json_encode($response);
    break;
  case 'hasChallange' :
    $oldTime = strtotime('-1 Minute');
    Battle::updates('bChallengeStatus = "u" WHERE bTimeOfChallenge <= ' . $oldTime . ' AND bChallengeStatus = "p"');
    $response = array();
    $challenges = Battle::challanges(post('charId'));
    if (count($challenges) > 0) {
      $challenge = $challenges[0];
      $oppenent = $challenge -> getPlayer(1) -> getChar();
      $response['has'] = TRUE;
      $response['message'] = $oppenent -> getName() . " has challenged you to a fight";
      $response['battle'] = $challenge -> getId();
    } else {
      $response['has'] = FALSE;
      $response['message'] = "No challenge";
    }
    $content = json_encode($response);
    break;
  case 'requestCheck' :
    $oldTime = strtotime('-1 Minute');
    Battle::updates('bChallengeStatus = "u" WHERE bTimeOfChallenge <= ' . $oldTime . ' AND bChallengeStatus = "p"');
    $response = array();
    $challenge = Battle::byId(post("battleId"));
    if (isset($challenge)) {
      switch ($challenge->getChallengeStatus()) {
        case 'a' :
          $response['accepted'] = TRUE;
          $response['rejected'] = FALSE;
          break;
        case 'r' :
          $response['accepted'] = FALSE;
          $response['rejected'] = TRUE;
          $response['message'] = "Your request was rejected.";
          break;
        case 'u' :
          $response['accepted'] = FALSE;
          $response['rejected'] = TRUE;
          $response['message'] = "Your opponent isn't available";
          break;
        default :
          $response['accepted'] = FALSE;
          $response['rejected'] = FALSE;
          break;
      }
    } else {
      $response['accepted'] = FALSE;
      $response['rejected'] = FALSE;
    }
    $content = json_encode($response);
    break;
  case 'requestResponse' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    $battle -> setChallengeStatus(post("status"));
    $battle -> save();
    if (post("status") == "a") {
      $challenges = Battle::challanges(post('charId'));
      foreach ($challenges as $challenge) {
        $challenge -> setChallengeStatus("u");
        $challenge -> save();
      }
    }
    $content = "yay";
    break;
  case 'waiting' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    $myChar = Character::byId(post("charId"));
    $oChar = $battle -> getOpponent(post("charId"));

    $response['fled'] = ($battle -> getChallengeStatus() == "f" || $oChar -> isOnline() == false) ? true : false;
    if ($response['fled']) {
      $battle -> setOver(true);
      $battle -> setWinner($myChar -> getBattleChar($battle -> getId()) -> getPlayer());
      $battle -> save();
      $myChar -> winGrow($oChar);
    }
    $myBattleChar = BattleChar::byId(post("battleId"), post("charId"));
    $response['attack'] = ($battle -> getWhosTurn() == $myBattleChar -> getPlayer());
    $response['over'] = (boolean)$battle -> getOver();
    if($response['over'] == true){
      //error_log("Got here\n",3,"C:/xampp/apache/logs/baka.log");  
      $response['overmessage'] = "You ".($battle->getWinner() == $myChar->getBattleChar(post("battleId"))->getPlayer()?"Won":"Lost");
      //error_log($response['overmessage']."\n",3,"C:/xampp/apache/logs/baka.log");  
    }
    $response['oHp'] = characterLifeRaw($oChar -> getHp(), $oChar -> getHpLeft(post("battleId")));
    $response['myHp'] = characterLifeRaw($myChar -> getHp(), $myBattleChar -> getHp());
    $response['bLog'] = bLogReplace($battle -> getLog());
    //error_log("is leaving\n",3,"C:/xampp/apache/logs/baka.log");  
    $content = json_encode($response);
    break;
  case 'livepoints' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    $agrChar = Character::byId(post("charId"));
    $defChar = $battle -> getOpponent(post("charId"));
    $response['oHp'] = characterLifeRaw($defChar -> getHp(), $defChar -> getHpLeft(post("battleId")));
    $response['myHp'] = characterLifeRaw($agrChar -> getHp(), $agrChar -> getHpLeft(post("battleId")));
    $response['bLog'] = bLogReplace($battle -> getLog());
    $content = json_encode($response);
    break;
  case 'hasLevelUp' :
    $response = array();
    $agrChar = Character::byId(post("charId"));
    $response['levelup'] = $agrChar -> getLevelUp();
    $agrChar -> setLevelUp(FALSE);
    $agrChar -> save();
    if($response['levelup'] == true){
      $response['message'] = "You have reached Level " . $agrChar -> getLevel()."\n To reach the next Level you need " . $agrChar -> getNextLvlExp()." more EXP.";
    } else {
      $response['message'] = "To reach the next Level you need " . $agrChar -> getNextLvlExp()." more EXP.";
    }
    $content = json_encode($response);
    break;
  case 'attack' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    $agrChar = Character::byId(post("charId"));
    $attack = $agrChar -> getAttack(post("atkId"));

    if ($agrChar != null && $battle != null && $attack != null) {
      $response['valid'] = true;
      $defChar = $battle -> getOpponent(post("charId"));
      //error_log("isNotOnline = ".($defChar->isOnline() ==  false?"true":"false")."\n",0);
      $response['fled'] = (count(Battle::select(" WHERE bId = '" . encode($battle -> getId()) . "' AND bChallengeStatus = 'f';")) > 0 || $defChar -> isOnline() == false) ? true : false;
      //error_log("countBattle = ".(count( Battle::select(" WHERE bId = '".encode($battle->getId())."' AND bChallengeStatus = 'f';")) > 0 || $defChar->isOnline() == false)) ==  false?"0":"1")."\n",0);

      if ($response['fled'] == true) {
        $battle -> setChallengeStatus("f");
        $battle -> setOver(true);
        $battle -> setWinner($agrChar -> getBattleChar($battle -> getId()) -> getPlayer());
        $battle -> save();
        $agrChar -> winGrow($defChar);
      } else {
        $battle -> attack($agrChar, $defChar, $attack);
      }
      $response['oHp'] = characterLifeRaw($defChar -> getHp(), $defChar -> getHpLeft(post("battleId")));
      $response['myHp'] = characterLifeRaw($agrChar -> getHp(), $agrChar -> getHpLeft(post("battleId")));
      $response['bLog'] = bLogReplace($battle -> getLog());
      $response['over'] = (boolean)$battle -> getOver();
      if($response['over'] == true){  
        $response['overmessage'] = "You ".($battle->getWinner() == $agrChar->getBattleChar(post("battleId"))->getPlayer()?"Won":"Lost");
      }
    } else {
      $response['valid'] = false;
    }
    $content = json_encode($response);
    break;
  case 'getRole' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    $myBattleChar = BattleChar::byId(post("battleId"), post("charId"));
    $response['attack'] = ($battle -> getWhosTurn() == $myBattleChar -> getPlayer());
    $response['waiting'] = ($battle -> getWhosTurn() != $myBattleChar -> getPlayer());
    break;
  case 'flee' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    if ($battle -> getOver() == false) {
      $battle -> setChallengeStatus("f");
      $battle -> setOver(true);
      $battle -> save();
    }
    break;
}
echo $content;
