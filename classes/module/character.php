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
  private $cAp;
  private $cLevelUp;
  private $cDurability;

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
  	  cImage='" . encode($this -> cImage) . "', 
      cAp='" . encode($this -> cAp) . "', 
      cLevelUp='" . encode($this -> cLevelUp) . "', 
      cDurability='" . encode($this -> cDurability) . "' WHERE cId='" . encode($this -> cId) . "';");
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
  
  public function setDurability($setVal) {
    $this -> cDurability = $setVal;
    $this -> cHp = ($setVal * 10)+65;
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
  

  public function getLevel(){
    return Exp::getCharLvl($this->cLvlExp);
  }
  
  public function getCharAtks(){
    return CharAtk::select(" WHERE caCharId = '".encode($this->cId)."' ");
  }
  
  public function getCharAtk($atkId){
    $charAtks = CharAtk::select(" WHERE caCharId = '".encode($this->cId)."' AND caAtkId = '".encode($atkId)."'");
    if(is_array($charAtks)){
      return $charAtks[0];
    }
    return null;
  }
  
  public function getAttaks(){
    $attacks = array();
    foreach($this->getCharAtks() as $charAtk){
      $attacks[] = $charAtk->getAtk();
    }
    return $attacks;
  }
  
  public function getAttack($atkId){
    $charAtk = $this -> getCharAtk($atkId);
    if($charAtk != null){
      return $charAtk->getAtk();      
    } else {
      return NULL;
    }
  }
  
  public function getBattleChars(){
    return BattleChar::select(" WHERE bcCharId = '".encode($this->cId)."' ");
  }
  
  public function getBattleChar($battleId){
    $battleChars = BattleChar::select(" WHERE bcCharId = '".encode($this->cId)."' AND bcBattleId = '".encode($battleId)."' ;");
    if(is_array($battleChars)){
      return $battleChars[0];
    } else {
      return null;
    }
    
  }
  
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
  
  public function winGrow($otherChar){
   // error_log("got in winGrow\n",3,"C:/xampp/apache/logs/baka.log");
    $exp = Exp::getWinLvlUp($otherChar->getLevel());
    $this->growing($exp);
  }
  
  public function loseGrow($otherChar){
   // error_log("got in loseGrow"."\n",3,"C:/xampp/apache/logs/baka.log");
    $exp = Exp::getLoseLvlUp($otherChar->getLevel());
    $this->growing($exp);
  }
  public function getWins(){
    return Battle::wins($this -> cId);
  }
  
  public function getLoses(){
    return Battle::loses($this -> cId);
  }
  
  private function growing($exp){
   //  error_log("Exp = ".$exp."\n",3,"C:/xampp/apache/logs/baka.log");
    $this->cLvlExp += $exp;
  //  error_log("cLvlExp = ".$this->cLvlExp."\n",3,"C:/xampp/apache/logs/baka.log");
    $this->cNextLvlExp -= $exp;
  //  error_log("cNextLvlExp = ".$this->cNextLvlExp."\n",3,"C:/xampp/apache/logs/baka.log");
    if($this->cNextLvlExp <= 0 && $this->cLvlExp ){
  //    error_log("level up"."\n",3,"C:/xampp/apache/logs/baka.log");
      $this->cLevelUp = true;
      $this->cNextLvlExp = (Exp::getExpToNext($this->cLvlExp) - $this->cLvlExp);
      $this->grow();
    } else {
      $this->cLevelUp = false;
      if($this->cLvlExp > 1570909908495){
        $this->cLvlExp = 1570909908495;
      }
    }
    $this->save();
   // error_log("saved winGrow\n",3,"C:/xampp/apache/logs/baka.log");
  }
  private function grow(){
  //  error_log("growing",3,"C:/xampp/apache/logs/baka.log");
    $lvl = $this->getLevel();
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
    $this->cHp = $this->cDurability * 25;
    $this->cMagAtk += $growth;
    $this->cMagDef += $growth;
    $this->cPhyAtk += $growth;
    $this->cPhyDef += $growth;
  }

  public function isOnline(){
    return (boolean) $this->getUser()->getOnline();
  }
}
?>