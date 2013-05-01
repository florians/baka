<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   11.03.2013  create of the file
 *
 * in this Class is used to handle Events and triggers the function the event of our event classes
 */
class Events {
  /*
   * This fucntion is triggered when a post has an key with the name event
   * Then according to the eventkey triggers the coresponding function througt a switch case
   */
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
    }
  }

}
?>