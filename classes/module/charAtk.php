<?php
class CharAtk extends Model {

  const TABLENAME = 'charAtk';
  const CLASSNAME = 'CharAtk';

  private $caCharId;
  private $caAtkId;

  public static function select($clause = "") {
    $obj = array();
    $query = SQL::getInstance() -> select(self::TABLENAME, $clause);
    while ($row = $query -> fetch_object(self::CLASSNAME)) {
      $obj = new CharAtk();
      $obj = $row;
      $objs[] = $obj;
    }
    return $objs;
  }

  protected function insert() {
    SQL::getInstance() -> insert(self::$TABLENAME, "(caCharId,caAtkId) VALUES('" 
    . encode($this -> caCharId) . "','" 
    . encode($this -> caAtkId).";");
    return (is_numeric($this -> caCharId) && $this -> caCharId > 0);
  }

  public static function updates($clause = "") {
    SQL::getInstance() -> update(self::TABLENAME, $clause);
  }

  protected function update() {
    self::updates("
    caAtkId='" . encode($this -> caAtkId). "' WHERE caCharId='" . encode($this -> caCharId) . "';");
  }

  public static function deletes($clause = "") {
    SQL::getInstance() -> delete(self::TABLENAME, $clause);
  }

  public function delete() {
    self::deletes(" WHERE caCharId='" . encode($this -> caCharId) . "';");
  }

  public function save() {
    if (is_null($this -> caCharId)) {
      self::insert();
    } else {
      self::update();
    }
  }

  public static function byId($id = 0) {
    if (is_numeric($id)) {
      $charAtk = self::select(" WHERE caCharId = '" . encode($this -> caCharId) . "';");
      if (count($charAtk) == 1) {
        return $charAtk[0];
      }
    }
    return null;
  }
  
  public function setCharId($setVal){
    $this->caCharId = $setVal;
  }
  public function getCharId(){
    return $this->caCharId;
  }
  public function setAtkId($setVal){
    $this->caCharId = $setVal;
  }
  public function getAtkId(){
    return $this->caCharId;
  }
  
}
?>