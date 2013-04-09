<?php

/**
 *
 */
class CharacterEvents {
  public static function newCharacter($post) {
    $charactername = encode($post['charactername']);
    $uId = encode($post['uId']);
    $image = files('characterimage');

    $character = new Character();
    $character -> setName($charactername);
    $character -> setUserId($uId);
    if ($character -> newCharacter($image) != false) {
      header('Location:index.php?page=Dashboard&success=Character creation successful');
    }
  }

}
?>