<?php 
/**
 *
 */
class BattleChar extends Model {
 
  const TABLENAME = 'battleChar';
  const CLASSNAME = 'BattleChar';
  
  private $bcBattleId;
  private $bcCharId;
  private $bcHp;

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
  
  private function insert(){
  SQL::getInstance()->insert(self::$TABLENAME, "(bcBattleId,bcCharId,bcHp) VALUES('".
  encode($this->bcBattleId)."','".
  encode($this->bcCharId)."','".
  encode($this->bcHp)."');");
  return (is_numeric($this->bcBattleId) && $this->bcBattleId > 0 && is_numeric($this->bcCharId) && $this->bcCharId > 0);
  }
  
  
  
  public static function updates($clause = ""){
  SQL::getInstance()->update(self::TABLENAME,$clause);
  }
  
  private function update(){
  self::updates("
   bcHp='".encode($this->bcHp)."' WHERE bcCharId='".encode($this->bcCharId)."' and bcBattleId='".encode($this->bcBattleId)."';");
  }

  public static function deletes($clause = ""){
  SQL::getInstance()->delete(self::TABLENAME,$clause);
  }
  
  public function delete(){
  self::deletes(" WHERE bcCharId='".encode($this->bcCharId)."' and bcBattleId='".encode($this->bcBattleId)."';");
  }
  
  public function save(){
    if(is_null($this->uId)){
      self::insert();
    } else {
      self::update();
    }
  }
  
  public static function byId($bcBattleId = 0, $bcCharId = 0){
  if(is_numeric($bcBattleId) && is_numeric($bcCharId)){
    $objs = self::select(" WHERE bcCharId='".encode($this->bcCharId)."' and bcBattleId='".encode($this->bcBattleId)."';");
    if(count($objs) = 1){
      return $objs[0];
    }     
  }
  return null;
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

  public function setCharId($setVal){
    $this->bcCharId = $setVal;
  }
  
  public function getHp(){
    return $this->bcHp;
  }

  public function setHp($setVal){
    $this->bcHp = $setVal;
  }
  
}
?>