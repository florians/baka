<?php 
/**
 * ChallangeStatus:
 *    'a' = accepted
 *    'r' = rejected
 *    'p' = pending
 *    'u' = unavailable
 *    'f' = fled
 */
class Battle extends Model {
 
  const TABLENAME = 'battle';
  const CLASSNAME = 'Battle';
  
  private $bId;
  private $bTimeOfChallenge;
  private $bRound;
  private $bWinner;
  private $bChallengeStatus;
  private $bOver;
  private $bWhosTurn;
  private $bLog;

  public static function select($clause = ""){
    $objs = array();
    $query=  Database::getInstance()->select(self::TABLENAME,$clause);
    while($row = $query->fetch_object(self::CLASSNAME)){
      $obj = new Battle();
      $obj = $row;
      $objs[] = $obj;
    }
    return $objs;
  }
  
  protected function insert(){
    Database::getInstance()->insert(self::TABLENAME, "(bTimeOfChallenge,bRound,bWinner,bChallengeStatus,bOver,bWhosTurn,bLog) VALUES('".
    encode($this->bTimeOfChallenge)."','".
    encode($this->bRound)."','".
    encode($this->bWinner)."','".
    encode($this->bChallengeStatus)."','".
    encode($this->bOver)."','".
    encode($this->bWhosTurn)."','".
    encode($this->bLog)."');");
    $this->bId = Database::getInstance()->insertId();
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  
  
  public static function updates($clause = ""){
    Database::getInstance()->update(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  protected  function update(){
    return self::updates("
     bTimeOfChallenge='".encode($this->bTimeOfChallenge)."',
     bRound='".encode($this->bRound)."',
     bWinner='".encode($this->bWinner)."',
     bChallengeStatus='".encode($this->bChallengeStatus)."',
     bOver='".encode($this->bOver)."',
     bWhosTurn='".encode($this->bWhosTurn)."',
     bLog='".encode($this->bLog)."' WHERE bId='".encode($this->bId)."';");
  }

  public static function deletes($clause = ""){
    Database::getInstance()->delete(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  public function delete(){
    foreach($this->getBattleChars() as $battleChar){
      $battleChar->delete();
    }
    return self::deletes(" WHERE bId='".encode($this->bId)."';");
  }
  
  public function save(){
    if(is_null($this->bId)){
      return self::insert();
    } else {
      return self::update();
    }
  }
  
  public static function byId($id = 0){
  if(is_numeric($id)){
    $objs = self::select(" WHERE bId = '".encode($id)."';");
    if(count($objs) == 1){
      return $objs[0];
    }     
  }
  return null;
  }
  
  public function getId(){
    return $this->bId;
  }

  public function setTimeOfChallange($setVal){
    $this->bTimeOfChallenge = $setVal;
  }
  
  public function getTimeOfChallange(){
    return $this->bTimeOfChallenge;
  }

  public function setRound($setVal){
    $this->bRound = $setVal;
  }
  
  public function getRound(){
    return $this->bRound;
  }

  public function setWinner($setVal){
    $this->bWinner = $setVal;
  }
  
  public function getWinner(){
    return $this->bWinner;
  }

  public function setChallengeStatus($setVal){
    $this->bChallengeStatus = $setVal;
  }
  
  public function getChallengeStatus(){
    return $this->bChallengeStatus;
  }

  public function setOver($setVal){
    $this->bOver = $setVal;
  }
  
  public function getOver(){
    return $this->bOver;
  }
  
  public function setWhosTurn($setVal){
    $this->bWhosTurn = $setVal;
  }
  
  public function getWhosTurn(){
    return $this->bWhosTurn;
  }
  
  public function getBattleChars(){
    return BattleChar::select(" WHERE bcBattleId = '".$this->bId."';");
  }
  
  public function getChars(){
    $chars = array();
    foreach($this->getBattleChars() as $battleChar){
      $chars[] = $battleChar-> getChar();
    }
    return $chars;
  }
  
  public function getPlayer($num){
    $getPlayer = BattleChar::select(" WHERE bcBattleId = '".$this->bId."' AND bcPlayer = '".encode($num)."' LIMIT 1;");
    return $getPlayer[0];
  }
  
  public function getLog(){
    return $this->bLog;
  }
  
  public static function challanges($charId){
    return self::select(" JOIN battlechar ON bId = bcBattleId WHERE bChallengeStatus = 'p' AND bcCharId = '".encode($charId)."' AND bcPlayer = '2' ORDER BY bTimeOfChallenge, bId ASC;");
  }
  
  public static function challengeings($charId){
    return self::select(" JOIN battlechar ON bId = bcBattleId WHERE bChallengeStatus = 'p' AND bcCharId = '".encode($charId)."' AND bcPlayer = '1' ORDER BY bTimeOfChallenge, bId ASC;");
  }
  
  public static function fightExists($battleId, $charId){
    return self::select(" JOIN battlechar ON bId = bcBattleId WHERE bId = ".encode($battleId)." AND bChallengeStatus = 'a' AND bOver = 0 AND bcCharId = '".encode($charId)."' ORDER BY bTimeOfChallenge, bId ASC;");
  }
  
  public function getOpponent($myCharId =''){
    $opponent = null;
    $chars = array();
    $chars = $this->getChars();
    foreach ($chars as $char) {
      if($myCharId != $char->getId()){
        $opponent = $char;
      }
    }
    return $opponent;
  }
  public static function wins($charId){
    return count(self::select(" JOIN battlechar ON bId = bcBattleId WHERE bChallengeStatus = 'a' AND bcCharId = '".encode($charId)."' bcPlayer = bWinner AND bOver = true;"));
  }
  
  public static function loses($charId){
    return count(self::select(" JOIN battlechar ON bId = bcBattleId WHERE bChallengeStatus = 'a' AND bcCharId = '".encode($charId)."' bcPlayer <> bWinner AND bOver = true;"));
  }
  
  public function attack($agrChar, $defChar, $attack){
    $this->bRound++;
    $defBattleChar = $defChar->getBattleChar($this->bId);
    $hitInfo = $defBattleChar->hit($attack,$agrChar);
    $this->bLog .= "".$agrChar->getName()." attacked ".$defChar->getName()." with ".$attack->getName().".\n"; 
    $this->bLog .= "".$hitInfo['status']." ".$defChar->getName()." took ".$hitInfo['dmg']." damage\n\n"; 
    if($defBattleChar->getHp() <= 0){
    error_log("battle is over",3,"C:/xampp/apache/logs/baka.log");
      $this->bOver = 1;
      $this->bWinner = $agrChar->getBattleChar($this->bId)->getPlayer();
      $agrChar->winGrow($defChar);
      $defChar->loseGrow($agrChar);
      $this->save();
    } else {
      $this->bWhosTurn = $defBattleChar->getPlayer();
      $this->save();
    }
  }
}
?>