<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   14.03.2013  create of the file
 * 
 * contains all the Events wich the Character uses 
 */
 
// this Class is for the Events involving the Character
class CharacterEvents {
   /*
    * this function is triggered when someone creates a new Character.
    * It passes on the Post Value from the From sent 
    */
  public static function newCharacter($post) {
    $charactername = encode($post['charactername']);
    $uId = encode($post['uId']);
    $image = files('characterimage');

    $character = new Character();
    $character -> setName($charactername);
    $character -> setUserId($uId);
    if ($character -> newCharacter($image) != false) {
      header('Location:index.php?page=Character&success=Character creation successful');
    }
  }

}
?>