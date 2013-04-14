<?php
class Attack extends Model {

  const TABLENAME = 'attack';
  const CLASSNAME = 'Attack';

  private $aId;
  private $aName;
  private $aDmgPt;
  private $aLearnLvl;
  private $aTyp;

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

  protected function insert() {
    Database::getInstance() -> insert(self::$TABLENAME, "(aName,aDmgPt,aLearnLvl,aTyp) VALUES('" 
    . encode($this -> aName) . "','" 
    . encode($this -> aDmgPt) . "','" 
    . encode($this -> aLearnLvl) . "','" 
    . encode($this -> aTyp) . "');");
    $this->aId = Database::getInstance()->insertId();
    return (Database::getInstance()->affectedRows() > 0);
  }

  public static function updates($clause = "") {
    Database::getInstance() -> update(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }

  protected function update() {
    return self::updates("
    aName='" . encode($this -> aName) . "',
    aDmgPt='" . encode($this -> aDmgPt) . "',
    aLearnLvl='" . encode($this -> aLearnLvl) . "',
    aTyp='" . encode($this -> aTyp). "' WHERE aId='" . encode($this -> aId) . "';");
  }

  public static function deletes($clause = "") {
    Database::getInstance() -> delete(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }

  public function delete() {
    return self::deletes(" WHERE aId='" . encode($this -> aId) . "';");
  }

  public function save() {
    if (is_null($this -> aId)) {
      return self::insert();
    } else {
      return self::update();
    }
  }

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