<?php
/**
 * @Author Florian Stettler & Adrian Locher
 * @Version 2
 * Create Date:   19.03.2013  create of the file
 *                ....        add funtions getAtk
 * 
 * This Class is responsible for the Character Attacks and stores all the
 * Attributes and makes them aviable for the rest of the Program 
 */
class CharAtk extends Model {

  // this constant defines the table name of the MySQL Database
  const TABLENAME = 'charatk';
  // this constant defines the name of this class it is used while fetching the object
  const CLASSNAME = 'CharAtk';

  // id of the Chracter
  private $caCharId;
  // id of the Attack
  private $caAtkId;

  // this function selects the Character Attacks that fulfill the given clause
  public static function select($clause = "") {
    $objs = array();
    $query = Database::getInstance() -> select(self::TABLENAME, $clause);
    while ($row = $query -> fetch_object(self::CLASSNAME)) {
      $obj = new CharAtk();
      $obj = $row;
      $objs[] = $obj;
    }
    return $objs;
  }
  // this inserts the current object into the Database
  protected function insert() {
    Database::getInstance() -> insert(self::TABLENAME, "(caCharId,caAtkId) VALUES('" . encode($this -> caCharId) . "','" . encode($this -> caAtkId) . "');");
    return (is_numeric($this -> caCharId) && $this -> caCharId > 0);
  }
  // this updates all Character Attacks that fulfill the given clause
  public static function updates($clause = "") {
    Database::getInstance() -> update(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this updates the Character Attacks of this object in the database
  protected function update() {
    return self::updates("caAtkId='" . encode($this -> caAtkId) . "' WHERE caCharId='" . encode($this -> caCharId) . "';");
  }
  // this deletes the Character Attack that fulfill the given clause
  public static function deletes($clause = "") {
    Database::getInstance() -> delete(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this deletes the Character Attacks of this object in the database
  public function delete() {
    return self::deletes(" WHERE caCharId='" . encode($this -> caCharId) . "';");
  }
  // this function saves the current object
  public function save() {
    return self::insert();
  }
  // this function gets the record with the coresponding ID
  public static function byId($id = 0) {
    if (is_numeric($id)) {
      $charAtk = self::select("AS ca INNER JOIN attack AS a ON ca.caAtkId = a.aId WHERE caCharId = '" . $id . "';");
      if (count($charAtk) >= 1) {
        return $charAtk;
      }
    }
    return null;
  }

  public function setCharId($setVal) {
    $this -> caCharId = $setVal;
  }

  public function getCharId() {
    return $this -> caCharId;
  }

  public function setAtkId($setVal) {
    $this -> caAtkId = $setVal;
  }

  public function getAtkId() {
    return $this -> caAtkId;
  }
  // this function gets the Character Attack back
  public function getAtk() {
    $atks = Attack::select(" WHERE aID = '" . encode($this -> caAtkId) . "'");
    return (is_array($atks))?$atks[0]:null;
  }

}
?>