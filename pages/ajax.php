<?php
/**
 * @Author Florian Stettler, Adrian Locher
 * @Version 9
 * Create Date:   03.04.2013  creation of the file
 * 
 * This file is responsible for all of the Ajax request and caring out verious actions
 */
// These lines are needed for developing on a windows with Xampp
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
// This switch case is responsible for carring out code according to the even passed by the Ajax code.
switch(post('event')) {
  // this loads the content of the dashboard
  case 'dashboard' :
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
    /**
     * this checks if the users are online it sets the user as online and the users 
     * that haven't been on the site for a minute as offline
     */
  case 'onlineCheck' :
    $oldTime = strtotime('-1 Minute');
    User::updates('uOnline = "0" WHERE uLastActivity <= ' . $oldTime . ' AND uOnline = "1"');
    $user = User::byId(session('id'));
    $user -> setOnline(post('value'));
    $user -> setLastActivity(strtotime('now'));
    $user -> setListOnDashboard(post('dashboard')?1:0);
    $user -> save();
    break;
    /**
     * Gives a character an attack, which has it's value passed on by the ajax request
     */
  case 'setCharAtk' :
    $char = $character -> byUserId(session('id'));
    $charAtk -> setAtkId(post('value'));
    $charAtk -> setCharId($char -> getId());
    $atklvl = $charAtk->getAtk($charAtk->getAtkId);
    if($atklvl->getLearnLvl() <= $char->getLevel()){
      $charAtk -> save();
    }
    break;
    /**
     * Delletes all attacks from the character and then reloads the content.
     */
  case 'delCharAtk' :
    $char = $character -> byUserId(session('id'));
    $charAtk -> setCharId($char -> getId());
    $charAtk -> delete();
    $content = '<script> location.reload();</script>';
    break;
    /**
     * add an attribute point to a aharacter "attribute", but only if the character has enough attribute points
     */
  case 'setAttribute' :
    $char = $character -> byUserId(session('id'));
    if ($char -> getAp() > 0) {
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
      $char -> setAp($char -> getAp() - 1);
      $char -> save();
    }
    break;
    /**
     * create a battle record with the status pending  with two battle chars for the potential fighters
     */
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
    /**
     * Checks if the player has a challange, in other words if he's character is asigned to a pending battle
     * it also changes the status of battles that had the status pending for over a minute.
     * It return a JSON string that has verious response values for the ajax function to process.
     */
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
    /**
     * Checks if the sent Battle request has been accepted or rejected.
     * It also returns a JSON Object as a string with various information 
     * to be prossesed in the jquery script.
     */
  case 'requestCheck' :
    $oldTime = strtotime('-1 Minute');
    Battle::updates('bChallengeStatus = "u" bOver = "1" WHERE bTimeOfChallenge <= ' . $oldTime . ' AND bChallengeStatus = "p"');
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
    /**
     * This handles the response to the request, it either sets it a accepted or rejected
     */
  case 'requestResponse' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    if($battle->getChallengeStatus() == "p"){
      $battle -> setChallengeStatus(post("status"));
      $battle -> save();
    }
    if ( post("status") == "a" ) {
      Battle::updates(" bChallengeStatus = 'f' WHERE bId in (SELECT bcBattleId FROM battlechar WHERE bcBattleId <> ".encode($battle -> getId())
      ." bcCharId ='".encode($battle -> getPlayer(1)->getCharId())."' or bcCharId ='".encode($battle -> getPlayer(2)->getCharId())."' AND bChallengeStatus = 'a' AND bOver = 0 )");
      $challenges = Battle::challanges(post('charId'));
      foreach ($challenges as $challenge) {
        $challenge -> setChallengeStatus("u");
        $challenge -> setOver(TRUE);
        $challenge -> save();
      }
    }
    $content = "yay";
    break;
    /**
     * This handles the waiting part of the fight it looks if the attacking player as changed 
     * and return a Json Object string with the battle log and current HP of both players,
     * as well as other information.
     */
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
    $response['bLog'] = battleLogReplace($battle -> getLog());
    //error_log("is leaving\n",3,"C:/xampp/apache/logs/baka.log");  
    $content = json_encode($response);
    break;
    /**
     * gets the current amount of hp of both players and the battle log. 
     * It returns the values in a JSON object.
     */
  case 'livepoints' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    $agrChar = Character::byId(post("charId"));
    $defChar = $battle -> getOpponent(post("charId"));
    $response['oHp'] = characterLifeRaw($defChar -> getHp(), $defChar -> getHpLeft(post("battleId")));
    $response['myHp'] = characterLifeRaw($agrChar -> getHp(), $agrChar -> getHpLeft(post("battleId")));
    $response['bLog'] = battleLogReplace($battle -> getLog());
    $content = json_encode($response);
    break;
    /**
     * This checks if the character has gained a level 
     * and passes on the message to the JQuery funtion that made the Ajax request
     */
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
    /**
     * Sends the attack, changes the current player and returns various information to be processed.
     */
  case 'attack' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    $agrChar = Character::byId(post("charId"));
    $attack = $agrChar -> getAttack(post("atkId"));

    if ($agrChar != null && $battle != null && $attack != null && ($battle ->getPlayer(1) == $agrChar->getBattleChar($battle->getId()) || $battle ->getPlayer(2) == $agrChar->getBattleChar($battle->getId()))) {
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
        if($battle ->getPlayer($battle -> getWhosTurn()) == $agrChar->getBattleChar($battle->getId())){
          $battle -> attack($agrChar, $defChar, $attack);
        }
      }
      $response['oHp'] = characterLifeRaw($defChar -> getHp(), $defChar -> getHpLeft(post("battleId")));
      $response['myHp'] = characterLifeRaw($agrChar -> getHp(), $agrChar -> getHpLeft(post("battleId")));
      $response['bLog'] = battleLogReplace($battle -> getLog());
      $response['over'] = (boolean)$battle -> getOver();
      if($response['over'] == true){  
        $response['overmessage'] = "You ".($battle->getWinner() == $agrChar->getBattleChar(post("battleId"))->getPlayer()?"Won":"Lost");
      }
    } else {
      $response['valid'] = false;
    }
    $content = json_encode($response);
    break;
    /**
     * This would get the role of the player and return them as a JSON object but it's not being used.
     */
  case 'getRole' :
    $response = array();
    $battle = Battle::byId(post("battleId"));
    $myBattleChar = BattleChar::byId(post("battleId"), post("charId"));
    $response['attack'] = ($battle -> getWhosTurn() == $myBattleChar -> getPlayer());
    $response['waiting'] = ($battle -> getWhosTurn() != $myBattleChar -> getPlayer());
    break;
    /**
     * This sets the fight as over and the status as fled.
     */
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
