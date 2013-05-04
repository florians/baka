<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   14.03.2013  create of the file
 *
 * This Class is responsible for the User and stores all the
 * Attributes and makes them aviable for the rest of the Program 
 */
class User extends Model {

  // this constant defines the table name of the MySQL Database
  const TABLENAME = 'user';
  // this constant defines the name of this class it is used while fetching the object 
  const CLASSNAME = 'User';

  // id of the user
  private $uId;
  // firstname of the user
  private $uFirstname;
  // lastname of the user
  private $uLastname;
  // username of the user
  private $uUsername;
  // password of the user
  private $uPassword;
  // email adress of the user
  private $uEmail;
  // last activity of the user
  private $uLastActivity;
  // online status of the user
  private $uOnline;
  // status of the activation of the account
  private $uActive;
  // if the user is listet on the dashboard
  private $uListOnDashboard;

  // this function selects the user that fulfill the given clause
  public static function select($clause = "") {
    $objs = array();
    $query = Database::getInstance() -> select(self::TABLENAME, $clause);
    while ($row = $query -> fetch_object(self::CLASSNAME)) {
      $obj = new User();
      $obj = $row;
      $objs[] = $obj;
    }
    return $objs;
  }
  // this inserts the current object into the Database
  protected function insert() {
    Database::getInstance() -> insert(self::TABLENAME, "(uFirstname,uLastname,uUsername,uPassword,uEmail,uLastActivity,uOnline,uActive,uListOnDashboard) VALUES('" . 
      encode($this -> uFirstname) . "','" . 
      encode($this -> uLastname) . "','" . 
      encode($this -> uUsername) . "','" . 
      encode($this -> uPassword) . "','" . 
      encode($this -> uEmail) . "','" . 
      encode($this -> uLastActivity) . "','" . 
      encode($this -> uOnline) . "','" . 
      encode($this -> uActive) . "','" . 
      encode($this -> uListOnDashboard) . "');");
      $this->uId = Database::getInstance()->insertId();
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this updates all attacks that fulfill the given clause
  public static function updates($clause = "") {
    return Database::getInstance() -> update(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this updates the attack of this object in the database
  protected function update() {
    return self::updates("
	    uFirstname='" . encode($this -> uFirstname) . "',
  	  uLastname='" . encode($this -> uLastname) . "',
  	  uUsername='" . encode($this -> uUsername) . "',
  	  uPassword='" . encode($this -> uPassword) . "',
  	  uEmail='" . encode($this -> uEmail) . "',
  	  uLastActivity='" . encode($this -> uLastActivity) . "',
  	  uOnline='" . encode($this -> uOnline) . "', 
  	  uListOnDashboard='" . encode($this -> uListOnDashboard) . "', 
  	  uActive='" . encode($this -> uActive) . "' WHERE uId='" . encode($this -> uId) . "';");
  }
  // this deletes the attaks that fulfill the given clause
  public static function deletes($clause = "") {
    Database::getInstance() -> delete(self::TABLENAME, $clause);
    return (Database::getInstance()->affectedRows() > 0);
  }
  // this deletes the attack of this object in the database
  public function delete() {
    return self::deletes(" WHERE uId='" . encode($this -> uId) . "';");
  }
  // this function saves the current object either by insert it into the Database or updating the entry
  public function save() {
    if (is_null($this -> uId)) {
      return self::insert();
    } else {
      return self::update();
    }
  }
  // this function gets the record with the coresponding ID
  public static function byId($id = 0) {
    if (is_numeric($id)) {
      $users = self::select(" WHERE uId = '" . $id . "';");
      if (count($users) == 1) {
        return $users[0];
      }
    }
    return null;
  }
  /*
   * This function is triggered if the user creates a new useraccount.
   * It validates the sent post record and generates the error messages
   */
  public function registration($passwordb) {
    //pre($firstname . ' ' . $lastname . ' ' . $username . ' ' . $passworda . ' ' . $passwordb . ' ' . $email);
    $userwrite = false;
    $errorUsername = false;
    $errorEmail = false;
    $label = array('Firstname', 'Lastname', 'Username', 'Password', 'Retype', 'E-Mail');
    $checkarray = array($this->uFirstname, $this->uLastname, $this->uUsername, $this->uPassword, $passwordb, $this->uEmail);

    // chek if username or email adress already exists
    if ($userwrite == false) {
      if (self::selectByUsername($this->uUsername) == true) {
        $errorUsername = true;
      }
      if (self::selectByEmail($this->uEmail) == true) {
        $errorEmail = true;
      }
      if ($errorUsername == false && $errorEmail == false) {
        $userwrite = true;
      }
    }

    if ($userwrite == true) {
      /* Checks:  firstname, lastname, username has to be longer than 2
       *          passwords has to be longer than 4 and must be the same string
       *          email has to be valid
       */
      if (strlen($this->uFirstname) > 2 && strlen($this->uLastname) > 2 && strlen($this->uUsername) > 2 && $this->uPassword == $passwordb && strlen($this->uPassword) > 4 && filter_var($this->uEmail, FILTER_VALIDATE_EMAIL) != false) {
        $this->uPassword = pwCrypt($this->uPassword);
        $this -> save();
        $this -> sendActivateMail();
        return true;
      } else {
        // message if it isnt set
        for ($i = 0; $i < count($checkarray); $i++) {
          if ($checkarray[$i] == null) {
            Message::getInstance() -> addError($label[$i] . ' not set');
          }
        }
        // message if Firstname, Lastname and Username are to short
        for ($i = 0; $i < 3; $i++) {
          if (strlen($checkarray[$i]) < 3) {
            Message::getInstance() -> addError($label[$i] . ' to short. Has to be longer than 3 characters');
          }
        }
        // message if password is to short
        if (strlen($this->uPassword) < 5) {
          Message::getInstance() -> addError('Password to short. Has to be longer than 5 characters');
        }

        // message if passwords aren't equal
        if ($this->uPassword != $passwordb) {
          Message::getInstance() -> addError('Passwords aren\'t equal');
        }

        // message if email isn't valid
        if (filter_var($checkarray[5], FILTER_VALIDATE_EMAIL) == false) {
          Message::getInstance() -> addError($label[5] . ' is not Valid');
        }
        return false;

      }
    } else {
      if ($errorUsername == true) {
        Message::getInstance() -> addError('Username already in use!');
      }
      if ($errorEmail == true) {
        Message::getInstance() -> addError('E-Mail address already in use!');
      }
    }
  }
  // this function selects the whole user which has the specifig username
  public static function selectByUsername($username) {
    // select from db with that usernam in the where
    if (isset($username)) {
      $user = self::select("WHERE uUsername like '" . $username . "'");
      if(count($user)){
        return $user[0];
      }
    }
    return false;
  }
  // this function selects the whole user which has the specifig email adress
  public static function selectByEmail($email) {
    // select from db with that email in the where
    if (isset($email)) {
      $user = self::select("WHERE uEmail = '" . $email . "'");
      if(count($user)){
        return $user[0];
      }
    }
    return false;
  }
  /* 
   * This function is triggered if the user wants to login.
   * It validates the sent post record and sets Session variables
   */
  public static function login($username, $password) {
    $user = self::selectByUsername($username);
    //pre($user);
    if ($user == false) {
      Message::getInstance() -> addError('User doesn\'t exist!');  
    } 
    else {
      if ($user->getUsername() == $username && $user->getPassword() == $password && $user->getActive() == '1') {
        //session_start();
        //$_SESSION['user'] = serialize($user);
        $_SESSION['id'] = $user->getId();
        $_SESSION['login'] = true;
        Message::getInstance() -> addSuccess('Successfully logged in');
        return $user;
      } else {
        if ($user->getUsername() == $username && $user->getPassword() != $password && $password != '') {
          Message::getInstance() -> addError('Wrong Password!');
        }
        if ($user->getActive() == '0') {
          Message::getInstance() -> addError('Account not activated');
        }
        if ($username == '') {
          Message::getInstance() -> addError('Username has to be set!');
        }
        if ($password == '') {
          Message::getInstance() -> addError('Password has to be set!');
        }
      }
    }
    
    return false;
  }
  /*
   * this function is triggered when the user creates an account.
   * It creates a simple php mail and sends it to the registered email adress.  
   */
  public function sendActivateMail(){
    $link = crypt($this->getUsername().$this->getEmail(),'iuqfapuiasdjb');
    $empfaenger = $this->getEmail();
    $absendername = "Baka";
    $absendermail = "stettler.florian@gmail.com";
    $betreff = "Password Activation";
    $text = "Here is your Password Activation Link \n
    Firstname: ".$this->uFirstname."\n
    Lastname: ".$this->uLastname."\n
    Username: ".$this->uUsername."\n
    Email: ".$this->uEmail."\n
    http://".$_SERVER['HTTP_HOST']."/index.php?page=Registration&activate=".$link;
    mail($empfaenger, $betreff, $text, "From:".$absendername." <".$absendermail.">"."Reply-To: ".$absendername." <".$absendermail.">");
  }
  // this function is triggered when the user clicks on the activations link in the mail. It sets the user Accoutn activation to true
  public function checkActivateMail($hash){;
    $users = $this->select();
    foreach($users as $user){
      $userhash = crypt($user->getUsername().$user->getEmail(),'iuqfapuiasdjb');
      if($userhash == $hash){
        $user->setActive('1');
        $user->save(); 
      }
    }
  }
  public function getId() {
    return $this -> uId;
  }
  
  public function setListOnDashboard($setVal) {
    $this -> uListOnDashboard = $setVal;
  }

  public function getListOnDashboard() {
    return (boolean) $this -> uListOnDashboard;
  }
  
  public function setFirstname($setVal) {
    $this -> uFirstname = $setVal;
  }

  public function getFirstname() {
    return $this -> uFirstname;
  }

  public function setLastname($setVal) {
    $this -> uLastname = $setVal;
  }

  public function getLastname() {
    return $this -> uLastname;
  }

  public function setUsername($setVal) {
    $this -> uUsername = $setVal;
  }

  public function getUsername() {
    return $this -> uUsername;
  }

  public function setPassword($setVal) {
    $this -> uPassword = $setVal;
  }

  public function getPassword() {
    return $this -> uPassword;
  }

  public function setEmail($setVal) {
    $this -> uEmail = $setVal;
  }

  public function getEmail() {
    return $this -> uEmail;
  }

  public function setLastActivity($setVal) {
    $this -> uLastActivity = $setVal;
  }

  public function getLastActivity() {
    return $this -> uLastActivity;
  }

  public function setOnline($setVal) {
    $this -> uOnline = $setVal;
  }

  public function getOnline() {
    return $this -> uOnline;
  }

  public function setActive($setVal) {
    $this -> uActive = $setVal;
  }

  public function getActive() {
    return $this -> uActive;
  }
  
  // this function gets the char of the User back
  public function getChar(){
    return Character::byUserId($this->uId);
  }
  // this function get the wins of the character of the user
  public function getWins(){
    $char = $this->getChar();
  	if($char != null && is_object($char)){
      	return $char->getWins();
  	} else {
  		return 0;
  	}
  }
  // this function get the loses of the character of the user
  public function getLoses(){
    $char = $this->getChar();
  	if($char != null && is_object($char)){
      	return $char->getLoses();
  	} else {
  		return 0;
  	}
  }
  // this function get the total amount of fight the character had
  public function getBattleTotal(){
    $char = $this->getChar();
  	if($char != null && is_object($char)){
      	return $char->getLoses()+$char->getWins();
  	} else {
  		return 0;
  	}
  }
}
?>