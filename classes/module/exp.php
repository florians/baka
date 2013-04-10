<?php 
/**
 *
 */
class Exp extends Model {
 
  const TABLENAME = 'exp';
  const CLASSNAME = 'Exp';
  
  private $eId;
  private $eLvl;
  private $eExp;
  private $eTyp;

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
  SQL::getInstance()->insert(self::$TABLENAME, "(eLvl,eExp,eTyp) VALUES('".
  encode($this->eLvl)."','".
  encode($this->eExp)."','".
  encode($this->eTyp)."');");
  return (is_numeric($this->eId) && $this->eId > 0);
  }
  
  
  
  public static function updates($clause = ""){
  SQL::getInstance()->update(self::TABLENAME,$clause);
  }
  
  private function update(){
  self::updates("
   eLvl='".encode($this->eLvl)."',
   eExp='".encode($this->eExp)."',
   eTyp='".encode($this->eTyp)."' WHERE eId='".encode($this->eId)."';");
  }

  public static function deletes($clause = ""){
  SQL::getInstance()->delete(self::TABLENAME,$clause);
  }
  
  public function delete(){
  self::deletes(" WHERE eId='".encode($this->eId)."';");
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
    $objs = self::select(" WHERE eId = '".encode($this->eId)."';");
    if(count($objs) = 1){
      return $objs[0];
    }     
  }
  return null;
  }
  
  public function getId(){
    return $this->eId;
  }

  public function setLvl($setVal){
    $this->$eLvl = $setVal;
  }
  
  public function getLvl(){
    return $this->$eLvl;
  }

  public function setExp($setVal){
    $this->eExp = $setVal;
  }
  
  public function getExp(){
    return $this->eExp;
  }
  
  public function setType($setVal){
    $this->eTyp = $setVal;
  }
  
  public function getType(){
    return $this->eTyp;
  }
}
?>