<?php 
/**
 * @Author Adrian Locher
 * @Version 5
 * Create Date:   03.04.2013  creation of the file
 * 
 * This class represents the Character in a battle.
 */
class BattleChar extends Model {
 
  // this constant defines the table name of the MySQL Database table
  const TABLENAME = 'battlechar';
  // this constant defines the name of this class it is used while fetching the object 
  const CLASSNAME = 'BattleChar';
  
  // Battle Id
  private $bcBattleId;
  // Character Id 
  private $bcCharId;
  // current HP that left during a fight
  private $bcHp;
  // the number of the fight 1 or 2
  private $bcPlayer;

  // this function selects the battle characters (BattleChar) that fulfill the given clause
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
    Database::getInstance()->insert(self::TABLENAME, "(bcBattleId,bcCharId,bcHp,bcPlayer) VALUES('".
    encode($this->bcBattleId)."','".
    encode($this->bcCharId)."','".
    encode($this->bcHp)."','".
    encode($this->bcPlayer)."');");
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  // this updates all battle characters (BattleChar) that fulfill the given clausse
  public static function updates($clause = ""){
    Database::getInstance()->update(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  // this updates the battle character of this object in the database
  protected function update(){
   return self::updates("
   bcHp='".encode($this->bcHp)."', 
   bcPlayer='".encode($this->bcPlayer)."' 
   WHERE bcCharId='".encode($this->bcCharId)."' and bcBattleId='".encode($this->bcBattleId)."';");
  
  }

  // this deletes the battle characters that fulfill theh given clause
  public static function deletes($clause = ""){
    Database::getInstance()->delete(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  // this deletes the battle character record of this object in the database
  public function delete(){
    return self::deletes(" WHERE bcCharId='".encode($this->bcCharId)."' and bcBattleId='".encode($this->bcBattleId)."';");
  }
  
  // this function saves the current object either by inserting it into the database or updating the corresponding record
  public function save(){
     if(self::byId($this->bcBattleId, $this->bcCharId)){
       return $this->update();
     } else {
       return $this->insert();
     }     
  }
  
  // reduces the HP of the battle character by the given lose
  public function reduceHP($lose){
    $this->bcHp -= $lose;
    $this->update();
  }
  
  // gets the record with the corresponding Id
  public static function byId($bcBattleId = 0, $bcCharId = 0){
  if(is_numeric($bcBattleId) && is_numeric($bcCharId)){
    $objs = self::select(" WHERE bcCharId='".encode($bcCharId)."' and bcBattleId='".encode($bcBattleId)."';");
    if(count($objs) == 1){
      return $objs[0];
    }     
  }
  return null;
  }
  
  // gets the battle object with the corresponding Id
  public function getBattle(){
    return Battle::byId($this->bcBattleId);
  }
  
  public function getBattleId(){
    return $this->bcBattleId;
  }

  public function setBattleId($setVal){
    $this->bcBattleId = $setVal;
  }
  
  public function getCharId(){
    return $this->bcCharId;
  }
  
  // gets the character with the corresponding Id
  public function getChar(){
    return Character::byId($this->bcCharId);
  }

  public function setCharId($setVal){
    $this->bcCharId = $setVal;
  }
  
  public function getHp(){
    return $this->bcHp;
  }

  public function setHp($setVal){
    $this->bcHp = $setVal;
  }
  
  public function getPlayer(){
    return $this->bcPlayer;
  }

  public function setPlayer($setVal){
    $this->bcPlayer = $setVal;
  }
  
  // determinse how critical the hit was and how much damage is done to the character
  private static function hitCrit($damage){
    $returnValue = array();
    $hitCrit = rand(0, 1000);
  switch (TRUE) {
    case 0 <= $hitCrit && $hitCrit <= 30:
      $returnValue['status'] = "Missed";
      $returnValue['dmg'] = 0 * $damage;
      return $returnValue;
      break;
    
    case 31 <= $hitCrit && $hitCrit <= 200:
      $returnValue['status'] = "Bad hit";
      $returnValue['dmg'] = 0.5 * $damage;
      return $returnValue;
      break;
    
    case 201 <= $hitCrit && $hitCrit <= 800:
      $returnValue['status'] = "Normal hit";
      $returnValue['dmg'] = 1 * $damage;
      return $returnValue;
      break;
    
    case 801 <= $hitCrit && $hitCrit <= 950:
      $returnValue['status'] = "Good hit";
      $returnValue['dmg'] = 1.5 * $damage;
      return $returnValue;
      break;
    
    case 951 <= $hitCrit && $hitCrit <= 999:
      $returnValue['status'] = "Excellent hit";
      $returnValue['dmg'] = 2 * $damage;
      return $returnValue;
      break;
    
    case $hitCrit == 1000:
      $returnValue['status'] = "Incredible hit";
      $returnValue['dmg'] = 4 * $damage;
      return $returnValue;
      break;
      
    default:
      return 1 * $damage;
      break;
  }
  }
  
  // determines how much damage is done by the attack to the character
  public function hit($attack, $agrChar){
    $defChar = $this->getChar();
    $atk = 0;
    $def = 0;
  switch ($attack->getTyp()) {
    case 'p':
      $def = ($defChar->getPhyDef())/3;
      $atk = ($agrChar->getPhyAtk())/2;
      break;
    case 'm':
      $def = ($defChar->getMagDef())/3;
      $atk = ($agrChar->getMagAtk())/2;
      break;
    case 'a':
      $def = (($defChar->getMagDef()+$defChar->getPhyDef())/2)/3;
      $atk = (($agrChar->getMagAtk()+$agrChar->getMagAtk())/2)/2;
      break;
    default:
      $def = ($myChar->getPhyDef())/3;
      $atk = ($oChar->getPhyAtk())/2;
      break;
  }
  $hit = self::hitCrit( $attack->getDmgPt()+$atk-$def);
  $hit['dmg'] = round(($hit['dmg'] > 0 || $hit['status'] == "Missed")?$hit['dmg']:1);
  $this->bcHp -= $hit['dmg'];
  $this->save();
  return $hit;
  }
  
}
?>