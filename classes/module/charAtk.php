<?php
class CharAtk extends Model {

  const TABLENAME = 'charatk';
  const CLASSNAME = 'CharAtk';

  private $caCharId;
  private $caAtkId;

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

  protected function insert() {
    Database::getInstance() -> insert(self::TABLENAME, "(caCharId,caAtkId) VALUES('" . encode($this -> caCharId) . "','" . encode($this -> caAtkId) . "');");
    return (is_numeric($this -> caCharId) && $this -> caCharId > 0);
  }

  public static function updates($clause = "") {
    Database::getInstance() -> update(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }

  protected function update() {
    return self::updates("caAtkId='" . encode($this -> caAtkId) . "' WHERE caCharId='" . encode($this -> caCharId) . "';");
  }

  public static function deletes($clause = "") {
    Database::getInstance() -> delete(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }

  public function delete() {
    return self::deletes(" WHERE caCharId='" . encode($this -> caCharId) . "';");
  }

  public function save() {
    return self::insert();
  }

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

  public function getAtk() {
    $atks = Attack::select(" WHERE aID = '" . encode($this -> caAtkId) . "'");
    return (is_array($atks))?$atks[0]:null;
  }

}
?>