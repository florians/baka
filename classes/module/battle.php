<?php 
/**
 * ChallangeStatus:
 *    'a' = accepted
 *    'r' = rejected
 *    'p' = pending
 *    'u' = unavailable
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
    Database::getInstance()->insert(self::TABLENAME, "(bTimeOfChallenge,bRound,bWinner,bChallengeStatus,bOver,bWhosTurn) VALUES('".
    encode($this->bTimeOfChallenge)."','".
    encode($this->bRound)."','".
    encode($this->bWinner)."','".
    encode($this->bChallengeStatus)."','".
    encode($this->bOver)."','".
    encode($this->bWhosTurn)."');");
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
     bWhosTurn='".encode($this->bWhosTurn)."' WHERE bId='".encode($this->bId)."';");
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
    $objs = self::select(" WHERE bId = '".encode($this->bId)."';");
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
  
  public function getPlayer($num){
    return BattleChar::select(" WHERE bcBattleId = '".$this->bId."' AND bcPlayer = '".encode($num)."' LIMIT 1;")[0];
  }
  
  public static function challanges($charId){
    return self::select(" JOIN battleChar ON bId = bcBattleId WHERE bChallengeStatus = 'p' AND bcCharId = '".$charId."' AND bcPlayer = '2' ORDER BY bTimeOfChallenge, bId ASC;");
  }
  
  public static function wins($charId){
    return count(self::select(" JOIN battleChar ON bId = bcBattleId WHERE bChallengeStatus = 'a' AND bcCharId = '".$charId."' bcPlayer = bWinner AND bOver = true;"));
  }
  
  public static function loses($charId){
    return count(self::select(" JOIN battleChar ON bId = bcBattleId WHERE bChallengeStatus = 'a' AND bcCharId = '".$charId."' bcPlayer <> bWinner AND bOver = true;"));
  }
  
  public function attack($myBattleChar,$oBattleChar,$attack){
    $oChar = $oBattleChar->getChar();
    $myChar = $myBattleChar->getChar();
    $hitInfo = $myBattleChar->hit($attack,$oChar);
    $this->bLog .= "<p>".$oChar->getName()." attacked ".$myChar->getName()." with ".$attack->getName().".</p>"; 
    $this->bLog .= "<p>".$hitInfo['status']." ".$myChar->getName()." took ".$hitInfo['dmg']." damage</p>"; 
    if($myBattleChar->getHp() <= 0){
      $this->bOver = 1;
      $this->bWinner = $myBattleChar->getPlayer();
    } else {
      $this->bWhosTurn = $myBattleChar->getPlayer();
    }
  }
}
?>