<?php 
/**
 * @Author Florian Stettler
 * @Version 5
 * Create Date:   03.04.2013  creation of the file
 * 
 * This Class represent the Exp (experience points) Table. 
 * It has all the functions and attributes that store or retrieve the data of the Exp table.
 *
 */
class Exp extends Model {
 
  // this constant defines the table name of the MySQL Database table
  const TABLENAME = 'exp';
  // this constant defines the name of this class it is used while fetching the object 
  const CLASSNAME = 'Exp';
  
  // id of the Exp record
  private $eId;
  // the level that corespones with this record
  private $eLvl;
  // the exp value
  private $eExp;
  /*
   * The type of this record
   * 'n' = what's needed to reach the level
   * 'g' = what you gain after winning a fight
   * 'l' = what you gain after losing a fight
   */
  private $eTyp;

  // this function selects the Exps that fulfill the given clause
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
    Database::getInstance()->insert(self::$TABLENAME, "(eLvl,eExp,eTyp) VALUES('".
    encode($this->eLvl)."','".
    encode($this->eExp)."','".
    encode($this->eTyp)."');");
    $this->eId = Database::getInstance()->insertId();
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  
  // this updates all exps that fulfill the given clausse
  public static function updates($clause = ""){
    Database::getInstance()->update(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  // this updates the record of this exp in the database
  protected function update(){
    return self::updates("
     eLvl='".encode($this->eLvl)."',
     eExp='".encode($this->eExp)."',
     eTyp='".encode($this->eTyp)."' WHERE eId='".encode($this->eId)."';");
  }

  // this detelets all records that match the clause
  public static function deletes($clause = ""){
    Database::getInstance()->delete(self::TABLENAME,$clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  
  // this deletes the record for the current Exp object.
  public function delete(){
    return self::deletes(" WHERE eId='".encode($this->eId)."';");
  }
  
  // htis saves the current object by either inserting it or updating in the Database.
  public function save(){
    if(is_null($this->bId)){
      return self::insert();
    } else {
      return self::update();
    }
  }
  
  // gets the Exp by it's Id
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
  
  // gets the level of the character based on the given amount of Exp
  public static function getCharLvl($lvlExp){
    $exp = self::select('WHERE eExp <= ' . encode($lvlExp) . ' AND eTyp = "n"  ORDER BY eExp DESC LIMIT 1');
    if(isset($exp[0]) && is_numeric($exp[0]->getLvl())){
      return $exp[0]->getLvl();
    } else {
      return 0;
    }
  }
  
  // gets the Exp needed for the characters current level, this is mainly used to establish the diffrence to the next Level
  public static function getCharLvlExp($lvlExp){
    //pre($lvlExp.'^--');
    $exp = self::select('WHERE eExp <= ' . encode($lvlExp) . ' AND eTyp = "n"  ORDER BY eExp DESC LIMIT 1');
    //pre($exp);
    if(isset($exp[0]) && is_numeric($exp[0]->getLvl())){
      return $exp[0]->getExp();
    } else {
      return 0;
    }
  }
  
  // Gets the amount of Exp the winner of a battle recieves
  public static function getWinLvlUp($lvl){
    $exp = self::select('WHERE eLvl = ' . encode($lvl) . ' AND eTyp = "g"  ORDER BY eExp DESC LIMIT 1');
    if(isset($exp[0]) && is_numeric($exp[0]->getLvl())){
      return $exp[0]->getExp();
    } else {
      return 0;
    }
  }
  
  // Gets the amount of Exp the loser of a battle recieves
  public static function getLoseLvlUp($lvl){
    $exp = self::select('WHERE eLvl = ' . encode($lvl) . ' AND eTyp = "l"  ORDER BY eExp DESC LIMIT 1');
    if(isset($exp[0]) && is_numeric($exp[0]->getLvl())){
      return $exp[0]->getExp();
    } else {
      return 0;
    }
  }
  
  // gets the amount of Exp needed to reach the next level
  public static function getExpToNext($lvlExp){
    $exp = self::select('WHERE eExp > ' . encode($lvlExp) . ' AND eTyp = "n"  ORDER BY eExp ASC LIMIT 1');
    if(isset($exp[0]) && is_numeric($exp[0]->getLvl())){
      return $exp[0]->getExp();
    } else {
      return 0;
    }
  }
}
?>