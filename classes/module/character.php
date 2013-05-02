<?php
/**
 * @Author Florian Stettler & Adrian Locher
 * @Version 2
 * Create Date:   19.03.2013  create of the file
 *                ....        add funtions for lvl up
 * 
 * This Class is responsible for the Character and stores all the
 * Attributes and makes them aviable for the rest of the Program 
 */
class Character extends Model {
  
  // this constant defines the table name of the MySQL Database
  const TABLENAME = 'character';
  // this constant defines the name of this class it is used while fetching the object
  const CLASSNAME = 'Character';

  // id of the character
  private $cId;
  // userid of the character
  private $cUserId;
  // name of the character
  private $cName;
  // total exp of the character
  private $cLvlExp;
  // exp the character need for his next lvl up
  private $cNextLvlExp;
  // magical attack points of the character
  private $cMagAtk;
  // physical attack points of the character
  private $cPhyAtk;
  // magical defensive points of the character
  private $cMagDef;
  // physical defensive points of the character
  private $cPhyDef;
  // healthpoints of the character
  private $cHp;
  // image path of the character
  private $cImage;
  // attribute points of the character
  private $cAp;
  // 
  private $cLevelUp;
  // durability points of the character with it the character life is calculated
  private $cDurability;

  // this function selects the character that fulfill the given clause
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
  // this inserts the current object into the Database
  protected function insert() {
    Database::getInstance() -> insert('`'.self::TABLENAME.'`', "(cUserId,cName,cLvlExp,cNextLvlExp,cMagAtk,cMagDef,cPhyAtk,cPhyDef,cHp,cImage,cAp,cLevelUp,cDurability) VALUES('" . 
      encode($this -> cUserId) . "','" . 
      encode($this -> cName) . "','" . 
      encode($this -> cLvlExp) . "','" . 
      encode($this -> cNextLvlExp) . "','" . 
      encode($this -> cMagAtk) . "','" . 
      encode($this -> cMagDef) . "','" . 
      encode($this -> cPhyAtk) . "','" . 
      encode($this -> cPhyDef) . "','" . 
      encode($this -> cHp) . "','" .  
      encode($this -> cImage) . "','" .  
      encode($this -> cAp) . "','" . 
      encode($this -> cLevelUp) .  "','" . 
      encode($this -> cDurability) . "');");
      $this->cId = Database::getInstance()->insertId();
    return (is_numeric($this -> cId) && $this -> cId > 0);
  }
  // this updates all character that fulfill the given clause
  public static function updates($clause = "") {
    Database::getInstance() -> update('`'.self::TABLENAME.'`', $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this updates the character of this object in the database
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
  	  cImage='" . encode($this -> cImage) . "', 
      cAp='" . encode($this -> cAp) . "', 
      cLevelUp='" . encode($this -> cLevelUp) . "', 
      cDurability='" . encode($this -> cDurability) . "' WHERE cId='" . encode($this -> cId) . "';");
  }
  // this deletes the character that fulfill the given clause
  public static function deletes($clause = "") {
    Database::getInstance() -> delete('`'.self::TABLENAME.'`', $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this deletes the character of this object in the database
  public function delete() {
    return self::deletes(" WHERE cId='" . encode($this -> cId) . "';");
  }
  // this function saves the current object either by insert it into the Database or updating the entry
  public function save() {
    if (is_null($this -> cId)) {
      return self::insert();
    } else {
      return self::update();
    }
  }
  // this function gets the record with the coresponding ID
  public static function byId($id = 0) {
    if (is_numeric($id)) {
      $character = self::select(" WHERE cId = '" . encode($id) . "';");
      if (count($character) == 1) {
        return $character[0];
      }
    }
    return null;
  }
  
  /*
   * This funtion is triggered when a new character is created.
   * It sets the default attributes for the new Character.
   * It also uploads the Image to the server and makes a thumbmail out of it.
   */
  public function newCharacter($image){
    $caracterwrite = false;
    $byCharacterName = false;
    $errorName = false;
    
    $this->setLvlExp('0');
    $this->setNextLvlExp('100');
    $this->setMagAtk('5');
    $this->setPhyAtk('5');
    $this->setMagDef('5');
    $this->setPhyDef('5');
    $this->setMagAtk('5');
    $this->setDurability('5');
    
    $filetype = $image['type'];
    $filesize = $image['size'];
    $charactername = str_replace(' ','_', strtolower($this->cName));
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
          $im = new imagick($this->cImage);
          $im->cropThumbnailImage(190, 190);
          $im->writeImage($this->cImage);
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
  // this function selects the character which has a specific user ID
  static function byUserId($uId){
    if (isset($uId)) {
      $character = self::select("WHERE cUserId = '" . $uId . "'");
      if(count($character)){
        return $character[0];
      }
    }
    return false;
  }
  // this function selects the character which has a specific username
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
  
  public function setDurability($setVal) {
    $this -> cDurability = $setVal;
    $this -> cHp = ($setVal * 10)+60;
  }

  public function getDurability() {
    return $this -> cDurability;
  }
  
  public function setAp($setVal) {
    $this -> cAp = $setVal;
  }

  public function getAp() {
    return $this -> cAp;
  }

  public function setLevelUp($setVal) {
    $this -> cLevelUp = $setVal;
  }

  public function getLevelUp() {
    return (boolean) $this -> cLevelUp;
  }
  
  // this function gets the lvl of the Character back
  public function getLevel(){
    return Exp::getCharLvl($this->cLvlExp);
  }
  // this function gets all attacks of the Character back
  public function getCharAtks(){
    return CharAtk::select(" WHERE caCharId = '".encode($this->cId)."' ");
  }
  // this function gets a attack of the Character back
  public function getCharAtk($atkId){
    $charAtks = CharAtk::select(" WHERE caCharId = '".encode($this->cId)."' AND caAtkId = '".encode($atkId)."'");
    if(is_array($charAtks)){
      return $charAtks[0];
    }
    return null;
  }
  // this function gets all attack in the Database back
  public function getAttaks(){
    $attacks = array();
    foreach($this->getCharAtks() as $charAtk){
      $attacks[] = $charAtk->getAtk();
    }
    return $attacks;
  }
  // this function gets an attack of the Database back
  public function getAttack($atkId){
    $charAtk = $this -> getCharAtk($atkId);
    if($charAtk != null){
      return $charAtk->getAtk();      
    } else {
      return NULL;
    }
  }
  // this function gets the Character back which is in a fight
  public function getBattleChars(){
    return BattleChar::select(" WHERE bcCharId = '".encode($this->cId)."' ");
  }
  // this function gets the Character back which is in a specific fight
  public function getBattleChar($battleId){
    $battleChars = BattleChar::select(" WHERE bcCharId = '".encode($this->cId)."' AND bcBattleId = '".encode($battleId)."' ;");
    if(is_array($battleChars)){
      return $battleChars[0];
    } else {
      return null;
    }
    
  }
  // this function gets the HP which is left back
  public function getHpLeft($battleId = 0){
    if($battleId == 0){
      return $this->getHp();
    } else {
      $battleChar = $this->getBattleChar($battleId);
      if(is_object($battleChar)){
        return  $battleChar->getHp();
      } else {
        return $this->getHp();
      }
    }
  }
  // this function gets the EXP back how much it will be if the user wins
  public function winGrow($otherChar){
    // error_log("got in winGrow\n",3,"C:/xampp/apache/logs/baka.log");
    $exp = Exp::getWinLvlUp($otherChar->getLevel());
    $this->growing($exp);
  }
  // this function gets the EXP back how much it will be if the user loses
  public function loseGrow($otherChar){
    // error_log("got in loseGrow"."\n",3,"C:/xampp/apache/logs/baka.log");
    $exp = Exp::getLoseLvlUp($otherChar->getLevel());
    $this->growing($exp);
  }
  // this function gets the wins of the character back
  public function getWins(){
    return Battle::wins($this -> cId);
  }
  // this function gets the loses of the characte back
  public function getLoses(){
    return Battle::loses($this -> cId);
  }
  // this function sets the Character stats after a lvl up
  private function growing($exp){
   //  error_log("Exp = ".$exp."\n",3,"C:/xampp/apache/logs/baka.log");
    $oldLvl = $this->getLevel();
    
    $this->cLvlExp += $exp;
  //  error_log("cLvlExp = ".$this->cLvlExp."\n",3,"C:/xampp/apache/logs/baka.log");
    $this->cNextLvlExp -= $exp;
  //  error_log("cNextLvlExp = ".$this->cNextLvlExp."\n",3,"C:/xampp/apache/logs/baka.log");
    if($this->cNextLvlExp <= 0 && $this->cLvlExp ){
      $newLvel = $this->getLevel();
  //    error_log("level up"."\n",3,"C:/xampp/apache/logs/baka.log");
      $this->cLevelUp = true;
      $this->cNextLvlExp = (Exp::getExpToNext($this->cLvlExp) - $this->cLvlExp);
      for($i = $oldLvl; $i < $newLvel; $i++){
        $this->grow($i+1);
      }
    } else {
      $this->cLevelUp = false;
      if($this->cLvlExp > 1570909908495){
        $this->cLvlExp = 1570909908495;
      }
    }
    $this->save();
   // error_log("saved winGrow\n",3,"C:/xampp/apache/logs/baka.log");
  }
  // this function gets how much attribute points the character will rise after a lvl up.
  // It alos sets the amount of attribute points the character will gain
  private function grow($lvl){
  //  error_log("growing",3,"C:/xampp/apache/logs/baka.log");
    $growth = 1;
    $giveaway = 1;
    switch (true) {
      case $lvl == 100:
        $giveaway +=  2;
      case $lvl > 94 && $lvl < 100:
        $giveaway +=  1;
      case $lvl > 89 && $lvl < 95:
        $giveaway +=  2;
      case $lvl > 84 && $lvl < 90:
        $giveaway +=  1;
      case $lvl > 79 && $lvl < 85:
        $giveaway +=  2;
      case $lvl > 74 && $lvl < 80:
        $giveaway +=  1;
      case $lvl > 69 && $lvl < 75:
        $giveaway +=  2;
      case $lvl > 64 && $lvl < 70:
        $giveaway +=  1;
      case $lvl > 59 && $lvl < 65:
        $giveaway +=  2;
      case $lvl > 54 && $lvl < 60:
        $giveaway +=  1;
      case $lvl > 49 && $lvl < 55:
        $giveaway +=  2;
      case $lvl > 44 && $lvl < 50:
        $giveaway +=  1;
      case $lvl > 39 && $lvl < 45:
        $giveaway +=  2;
      case $lvl > 34 && $lvl < 40:
        $giveaway +=  1;
      case $lvl > 29 && $lvl < 35:
        $giveaway +=  2;
      case $lvl > 24 && $lvl < 30:
        $giveaway +=  1;
      case $lvl > 19 && $lvl < 25:
        $giveaway +=  2;
      case $lvl > 14 && $lvl < 20:
        $giveaway +=  1;
      case $lvl > 9 && $lvl < 15:
        $giveaway +=  2;
      case $lvl > 4 && $lvl < 10:
        $giveaway +=  1;
        break;
    }
    //error_log("giveaway =".$giveaway,3,"C:/xampp/apache/logs/baka.log");
    //error_log("growth =".$growth,3,"C:/xampp/apache/logs/baka.log");
    $this->cAp += $giveaway;
    $this->setDurability($this->cDurability + $growth);
    $this->cMagAtk += $growth;
    $this->cMagDef += $growth;
    $this->cPhyAtk += $growth;
    $this->cPhyDef += $growth;
  }
  // this function checks if the user is online
  public function isOnline(){
    return (boolean) $this->getUser()->getOnline();
  }
}
?>