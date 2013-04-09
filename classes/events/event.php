<?php
/*
 * @Author Florian Stettler
 * Create Date:   11.03.2013  create of the file
 */
class Events {
  public static function event($event, $post) {
    switch (strtolower($event)) {
      case 'registration' :
        UserEvents::registration($post);
        break;
      case 'login' :
        UserEvents::login($post);
        break;
      case 'newcharacter' :
        CharacterEvents::newCharacter($post);
        break;
      default :
        echo 'asd';
        break;
    }
  }

}
?>