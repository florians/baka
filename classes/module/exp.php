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
    $query=  Database::getInstance()->select(self::TABLENAME,$clause);
    while($row = $query->fetch_object(self::CLASSNAME)){
      $obj = new Battle();
      $obj = $row;
      $objs[] = $obj;
    }
    return $objs;
  }
  
  protected function insert(){
    Database::getInstance()->insert(self::$TABLENAME, "(eLvl,eExp,eTyp) VALUES('".
    encode($this->eLvl)."','".
    encode($this->eExp)."','".
    encode($this->eTyp)."');");
    $this->eId = Database::getInstance()->insertId();
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  
  
  public static function updates($clause = ""){
    Database::getInstance()->update(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  protected function update(){
    return self::updates("
     eLvl='".encode($this->eLvl)."',
     eExp='".encode($this->eExp)."',
     eTyp='".encode($this->eTyp)."' WHERE eId='".encode($this->eId)."';");
  }

  public static function deletes($clause = ""){
    Database::getInstance()->delete(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  public function delete(){
    return self::deletes(" WHERE eId='".encode($this->eId)."';");
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
    $objs = self::select(" WHERE eId = '".encode($this->eId)."';");
    if(count($objs) == 1){
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
    return $this->eLvl;
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
  
  public static function getCharLvl($lvlExp){
    $exp = self::select('WHERE eExp <= ' . encode($lvlExp) . ' AND eTyp = "n"  ORDER BY eExp DESC LIMIT 1')[0];
    if(isset($exp) && is_numeric($exp->getLvl())){
      return $exp->getLvl();
    } else {
      return 0;
    }
  }
}
?>