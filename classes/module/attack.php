<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   19.03.2013  create of the file
 * 
 * This Class is responsible for the Attacks and stores all the
 * Attributes and makes them aviable for the rest of the Program 
 */
class Attack extends Model {

  // this constant defines the table name of the MySQL Database
  const TABLENAME = 'attack';
  // this constant defines the name of this class it is used while fetching the object 
  const CLASSNAME = 'Attack';

  // id of the attack
  private $aId;
  // name of the attack
  private $aName;
  // damage points of the attack
  private $aDmgPt;
  // learn lvl of the attack
  private $aLearnLvl;
  // typ of the attack
  private $aTyp;

  // this function selects the attacks that fulfill the given clause
  public static function select($clause = "") {
    $objs = array();
    $query = Database::getInstance() -> select(self::TABLENAME, $clause);
    while ($row = $query -> fetch_object(self::CLASSNAME)) {
      $obj = new Attack();
      $obj = $row;
      $objs[] = $obj;
    }
    return $objs;
  }
  // this inserts the current object into the Database
  protected function insert() {
    Database::getInstance() -> insert(self::$TABLENAME, "(aName,aDmgPt,aLearnLvl,aTyp) VALUES('" 
    . encode($this -> aName) . "','" 
    . encode($this -> aDmgPt) . "','" 
    . encode($this -> aLearnLvl) . "','" 
    . encode($this -> aTyp) . "');");
    $this->aId = Database::getInstance()->insertId();
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this updates all attacks that fulfill the given clausse
  public static function updates($clause = "") {
    Database::getInstance() -> update(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this updates the attack of this object in the database
  protected function update() {
    return self::updates("
    aName='" . encode($this -> aName) . "',
    aDmgPt='" . encode($this -> aDmgPt) . "',
    aLearnLvl='" . encode($this -> aLearnLvl) . "',
    aTyp='" . encode($this -> aTyp). "' WHERE aId='" . encode($this -> aId) . "';");
  }
  // this deletes the attaks that fulfill theh given clause
  public static function deletes($clause = "") {
    Database::getInstance() -> delete(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this deletes the attack of this object in the database
  public function delete() {
    return self::deletes(" WHERE aId='" . encode($this -> aId) . "';");
  }
  // this function saves the current object either by insert it into the Database or updating the record
  public function save() {
    if (is_null($this -> aId)) {
      return self::insert();
    } else {
      return self::update();
    }
  }
  // this function gets the record with the coresponding ID
  public static function byId($id = 0) {
    if (is_numeric($id)) {
      $attack = self::select(" WHERE aId = '" . encode($this -> aId) . "';");
      if (count($attack) == 1) {
        return $attack[0];
      }
    }
    return null;
  }
  public function getId(){
    return $this->aId;
  }
  public function setName($setVal){
    $this -> aName = $setVal;
  }
  public function getName(){
    return $this->aName;
  }
  public function setDmgPt($setVal){
    $this -> aDmgPt = $setVal;
  }
  public function getDmgPt(){
    return $this->aDmgPt;
  }
  public function setLearnLvl($setVal){
    $this -> aLearnLvl = $setVal;
  }
  public function getLearnLvl(){
    return $this->aLearnLvl;
  }
  public function setTyp($setVal){
    $this -> aTyp = $setVal;
  }
  public function getTyp(){
    return $this->aTyp;
  }

}
?>