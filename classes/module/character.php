<?php
class Character extends Model {

  const TABLENAME = 'character';
  const CLASSNAME = 'Character';

  private $cId;
  private $cUserId;
  private $cName;
  private $cLvlExp;
  private $cNextLvlExp;
  private $cMagAtk;
  private $cPhyAtk;
  private $cMagDef;
  private $cPhyDef;
  private $cHp;
  private $cImage;

  public static function select($clause = "") {
    $objs = array();
    $query = Database::getInstance() -> select('`'.self::TABLENAME.'`', $clause);
    while ($row = $query -> fetch_object(self::CLASSNAME)) {
      $obj = new Character();
      $obj = $row;
      $objs[] = $obj;
    }
    return $objs;
  }

  protected function insert() {
    Database::getInstance() -> insert('`'.self::TABLENAME.'`', "(cUserId,cName,cLvlExp,cNextLvlExp,cMagAtk,cMagDef,cPhyAtk,cPhyDef,cHp,cImage) VALUES('" . 
      encode($this -> cUserId) . "','" . 
      encode($this -> cName) . "','" . 
      encode($this -> cLvlExp) . "','" . 
      encode($this -> cNextLvlExp) . "','" . 
      encode($this -> cMagAtk) . "','" . 
      encode($this -> cMagDef) . "','" . 
      encode($this -> cPhyAtk) . "','" . 
      encode($this -> cPhyDef) . "','" . 
      encode($this -> cHp) . "','" . 
      encode($this -> cImage) . "');");
      $this->cId = Database::getInstance()->insertId();
    return (is_numeric($this -> cId) && $this -> cId > 0);
  }

  public static function updates($clause = "") {
    Database::getInstance() -> update('`'.self::TABLENAME.'`', $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }

  protected function update() {
    return self::updates("
  	  cUserId='" . encode($this -> cUserId) . "',
  	  cName='" . encode($this -> cName) . "',
  	  cLvlExp='" . encode($this -> cLvlExp) . "',
  	  cNextLvlExp='" . encode($this -> cNextLvlExp) . "',
  	  cMagAtk='" . encode($this -> cMagAtk) . "',
  	  cPhyAtk='" . encode($this -> cPhyAtk) . "',
  	  cMagDef='" . encode($this -> cMagDef) . "',
  	  cPhyDef='" . encode($this -> cPhyDef) . "',
  	  cHp='" . encode($this -> cHp) . "', 
  	  cImage" . encode($this -> cImage) . "' WHERE cId='" . encode($this -> cId) . "';");
  }

  public static function deletes($clause = "") {
    Database::getInstance() -> delete('`'.self::TABLENAME.'`', $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }

  public function delete() {
    return self::deletes(" WHERE cId='" . encode($this -> cId) . "';");
  }

  public function save() {
    if (is_null($this -> cId)) {
      return self::insert();
    } else {
      return self::update();
    }
  }

  public static function byId($id = 0) {
    if (is_numeric($id)) {
      $character = self::select(" WHERE cId = '" . encode($id) . "';");
      if (count($character) == 1) {
        return $character[0];
      }
    }
    return null;
  }

  public function newCharacter($image){
    $caracterwrite = false;
    $byCharacterName = false;
    $errorName = false;
    
    $this->setLvlExp('0');
    $this->setNextLvlExp('100');
    $this->setMagAtk('10');
    $this->setPhyAtk('10');
    $this->setMagDef('10');
    $this->setPhyDef('10');
    $this->setMagAtk('10');
    $this->setHp('100');
    
    $filetype = $image['type'];
    $filesize = $image['size'];
    $charactername = strtolower($this->cName);
    $extension = end(explode('.', $image['name']));
    $errorUserId = false;
    $errorName = false;
     
    if ($caracterwrite == false) {
      if (self::byUserId($this->cUserId) == true) {
        $errorUserId = true;
      }
      if (self::byCharacterName($this->cName) == true) {
        $errorName = true;
      }
      if ($errorUserId == false && $errorName == false) {
        $caracterwrite = true;
      }
    }
    
    
    if($caracterwrite == true){
      if ((($filetype == 'image/gif') || ($filetype == 'image/jpeg') || ($filetype == 'image/jpg') || ($filetype == 'image/png')) && ($filesize < 1000000)){
        if (file_exists('img/avatar/' . $charactername.'.'.$extension)){
          Message::getInstance() -> addError('File already exists');
        }else{
          move_uploaded_file($image['tmp_name'],'img/avatar/' . $charactername.'.'.$extension);
          $this->cImage = 'img/avatar/' . $charactername.'.'.$extension;
        //  $im = new imagick($this->cImage);
       //   $im->cropThumbnailImage(190, 190);
       //   $im->writeImage($this->cImage);
          $this->save();
          return true;
        }
      }else{
        Message::getInstance() -> addError('File extension not accepted');
      }
    }else{
      if ($byCharacterName == true) {
        Message::getInstance() -> addError('User has already a Character');
      }
      if ($errorName == true) {
        Message::getInstance() -> addError('Character Name already in use');
      }
    }
    return false;
  }

  static function byUserId($uId){
    if (isset($uId)) {
      $character = self::select("WHERE cUserId = '" . $uId . "'");
      if(count($character)){
        return $character[0];
      }
    }
    return false;
  }
  function byCharacterName($charactername){
    if (isset($charactername)) {
      $character = self::select("WHERE cName = '" . $charactername . "'");
      if(count($character)){
        return $character[0];
      }
    }
    return false;
  }
  public function getId() {
    return $this -> cId;
  }
  
  public function getUser() {
    return User::byId($this -> cUserId);
  }

  public function setUserId($setVal) {
    $this -> cUserId = $setVal;
  }

  public function getUserId() {
    return $this -> cUserId;
  }

  public function setName($setVal) {
    $this -> cName = $setVal;
  }

  public function getName() {
    return $this -> cName;
  }

  public function setLvlExp($setVal) {
    $this -> cLvlExp = $setVal;
  }

  public function getLvlExp() {
    return $this -> cLvlExp;
  }

  public function setNextLvlExp($setVal) {
    $this -> cNextLvlExp = $setVal;
  }

  public function getNextLvlExp() {
    return $this -> cNextLvlExp;
  }

  public function setMagAtk($setVal) {
    $this -> cMagAtk = $setVal;
  }

  public function getMagAtk() {
    return $this -> cMagAtk;
  }

  public function setPhyAtk($setVal) {
    $this -> cPhyAtk = $setVal;
  }

  public function getPhyAtk() {
    return $this -> cPhyAtk;
  }

  public function setMagDef($setVal) {
    $this -> cMagDef = $setVal;
  }

  public function getMagDef() {
    return $this -> cMagDef;
  }

  public function setPhyDef($setVal) {
    $this -> cPhyDef = $setVal;
  }

  public function getPhyDef() {
    return $this -> cPhyDef;
  }

  public function setHp($setVal) {
    $this -> cHp = $setVal;
  }

  public function getHp() {
    return $this -> cHp;
  }

  public function setImage($setVal) {
    $this -> cImage = $setVal;
  }

  public function getImage() {
    return $this -> cImage;
  }

  public function getLevel(){
    return Exp::getCharLvl($this->cLvlExp);
  }
  
  public function getCharAtks(){
    return CharAtk::select(" WHERE caCharId = '".encode($this->cId)."' ");
  }
  
  public function getAttaks(){
    $attacks = array();
    foreach($this->getCharAtks() as $charAtk){
      $attacks = $charAtk->getAtk();
    }
    return $attacks;
  }
}
?>