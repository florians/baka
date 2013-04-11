<?php 
/**
 *
 */
class Battle extends Model {
 
  const TABLENAME = 'battle';
  const CLASSNAME = 'Battle';
  
  private $bId;
  private $bTimeOfChallange;
  private $bRound;
  private $bWinner;
  private $bExepted;
  private $bOver;
  private $bWhosTurn;

  public static function select($clause = ""){
    $objs = array();
    $query=  SQL::getInstance()->select(self::TABLENAME,$clause);
    while($row = $query->fetch_object(self::CLASSNAME)){
      $obj = new Battle();
      $obj = $row;
      $objs[] = $obj;
    }
    return $objs;
  }
  
  protected function insert(){
  SQL::getInstance()->insert(self::$TABLENAME, "(bTimeOfChallange,bRounds,bWinner,bExepted,bOver,bWhosTurn) VALUES('".
  encode($this->bTimeOfChallange)."','".
  encode($this->bRounds)."','".
  encode($this->bWinner)."','".
  encode($this->bExepted)."','".
  encode($this->bOver)."','".
  encode($this->bWhosTurn)."');");
  return (is_numeric($this->bId) && $this->bId > 0);
  }
  
  
  
  public static function updates($clause = ""){
  SQL::getInstance()->update(self::TABLENAME,$clause);
  }
  
  protected  function update(){
  self::updates("
   bTimeOfChallange='".encode($this->bTimeOfChallange)."',
   bRounds='".encode($this->bRounds)."',
   bWinner='".encode($this->bWinner)."',
   bExepted='".encode($this->bExepted)."',
   bOver='".encode($this->bOver)."',
   bWhosTurn='".encode($this->bWhosTurn)."' WHERE bId='".encode($this->bId)."';");
  }

  public static function deletes($clause = ""){
  SQL::getInstance()->delete(self::TABLENAME,$clause);
  }
  
  public function delete(){
  self::deletes(" WHERE bId='".encode($this->bId)."';");
  }
  
  public function save(){
    if(is_null($this->bId)){
      self::insert();
    } else {
      self::update();
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
    $this->bTimeOfChallange = $setVal;
  }
  
  public function getTimeOfChallange(){
    return $this->bTimeOfChallange;
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

  public function setExepted($setVal){
    $this->bExepted = $setVal;
  }
  
  public function getExepted(){
    return $this->bExepted;
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