<?php 
/**
 * @Author Florian Stettler
 * @Version 5
 * Create Date:   03.04.2013  creation of the file
 * 
 * This Class represents the Battles and Requests for a fight.
 * It stores all Properties that have to do with the fight exepts for
 * the Ids of the Characters and their current HP. 
 */

class Battle extends Model {
 
  // this constant defines the table name of the MySQL Database table
  const TABLENAME = 'battle';
  // this constant defines the name of this class it is used while fetching the object 
  const CLASSNAME = 'Battle';
  
  // id of the Battle record
  private $bId;
  // time the Battle was created aka when player 1 challanges player 2
  private $bTimeOfChallenge;
  // current number of rounds in the fight
  private $bRound;
  // the plaer number of the winner can be 1 or 2
  private $bWinner;
  /*
   * Status of the challange or fight
   * Possible values:
   *    'a' = accepted
   *    'r' = rejected
   *    'p' = pending
   *    'u' = unavailable
   *    'f' = fled
   */
  private $bChallengeStatus;
  // wether the fights over (1) or not (0) 
  private $bOver;
  // number of the player who's turn it is can e 1 or 2
  private $bWhosTurn;
  // the log of the battle
  private $bLog;

  // this function selects the battles that fulfill the given clause
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
  
  // this inserts the current object into the Database
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
  
  // this updates all battles that fulfill the given clausse
  public static function updates($clause = ""){
    Database::getInstance()->update(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  // this updates the record of this battle of this object in the database
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

  // this deletes the battles that fulfill theh given clause
  public static function deletes($clause = ""){
    Database::getInstance()->delete(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  // this deletes the battle record of this object in the database
  public function delete(){
    foreach($this->getBattleChars() as $battleChar){
      $battleChar->delete();
    }
    return self::deletes(" WHERE bId='".encode($this->bId)."';");
  }
  
  // this function saves the current object either by insert it into the Database or updating the record
  public function save(){
    if(is_null($this->bId)){
      return self::insert();
    } else {
      return self::update();
    }
  }
  
  // this function gets the record with the coresponding ID
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
  
  // get the Character objects that are in the fight
  public function getChars(){
    $chars = array();
    foreach($this->getBattleChars() as $battleChar){
      $chars[] = $battleChar-> getChar();
    }
    return $chars;
  }
  
  // get the player of with the coresponding player number
  public function getPlayer($num){
    $getPlayer = BattleChar::select(" WHERE bcBattleId = '".$this->bId."' AND bcPlayer = '".encode($num)."' LIMIT 1;");
    return $getPlayer[0];
  }
  
  public function getLog(){
    return $this->bLog;
  }
  
  // get challanges for the character with the Id 
  public static function challanges($charId){
    return self::select(" JOIN battlechar ON bId = bcBattleId WHERE bChallengeStatus = 'p' AND bcCharId = '".encode($charId)."' AND bcPlayer = '2' ORDER BY bTimeOfChallenge, bId ASC;");
  }
  
  // get challangings for the character with the Id
  public static function challengeings($charId){
    return self::select(" JOIN battlechar ON bId = bcBattleId WHERE bChallengeStatus = 'p' AND bcCharId = '".encode($charId)."' AND bcPlayer = '1' ORDER BY bTimeOfChallenge, bId ASC;");
  }
  
  // Check if the fight exists
  public static function fightExists($battleId, $charId){
    return self::select(" JOIN battlechar ON bId = bcBattleId WHERE bId = ".encode($battleId)." AND bChallengeStatus = 'a' AND bOver = 0 AND bcCharId = '".encode($charId)."' ORDER BY bTimeOfChallenge, bId ASC;");
  }
  
  // gets the Opponent for the character for the given Id
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
  
  // gets the amount of wins for the characters with the given Id
  public static function wins($charId){
    return count(self::select(" JOIN battlechar ON bId = bcBattleId WHERE (bChallengeStatus = 'a' OR bChallengeStatus = 'f') AND bcCharId = '".encode($charId)."' AND bcPlayer = bWinner AND bOver = true;"));
  }
  
  // gets the amount of loses for the character with the given Id
  public static function loses($charId){
    return count(self::select(" JOIN battlechar ON bId = bcBattleId WHERE (bChallengeStatus = 'a' OR bChallengeStatus = 'f') AND bcCharId = '".encode($charId)."' AND bcPlayer <> bWinner AND bOver = true;"));
  }
  
  // carries out the attack and the cauculations that come with it
  public function attack($agrChar, $defChar, $attack){
    $this->bRound++;
    $defBattleChar = $defChar->getBattleChar($this->bId);
    $hitInfo = $defBattleChar->hit($attack,$agrChar);
    //$this->bLog .= "".$agrChar->getName()." attacked ".$defChar->getName()." with ".$attack->getName().".\n"; 
    //$this->bLog .= "".$hitInfo['status']." ".$defChar->getName()." took ".$hitInfo['dmg']." damage\n\n"; 
    $class = '';
    ($this->bRound % 2 == 0)?$class = '/odd':$class = '/even';
    $this->bLog = $class.'front'.$agrChar->getName()." attacked ".$defChar->getName()." with ".$attack->getName().".\n".$hitInfo['status']." ".$defChar->getName()." took ".$hitInfo['dmg']." damage".$class.'back'.$this->bLog;
    //$this->bLog = $agrChar->getName()." attacked ".$defChar->getName()." with ".$attack->getName().".\n".$hitInfo['status']." ".$defChar->getName()." took ".$hitInfo['dmg']." damage\n\n".$this->bLog;
    
    if($defBattleChar->getHp() <= 0){
    //error_log("battle is over",3,"C:/xampp/apache/logs/baka.log");
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