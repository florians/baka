<?php
/**
 * @Author Florian Stettler
 * @Version 1
 * Create Date:   11.03.2013  create of the file
 *
 * This class is used to handle the UserEvents
 */
class UserEvents {
  /*
   * This function is used to encode the Post Values which are sent form the Formular
   * It also created a User Object and sets the Values entered in the Form
   * if successful the User will be redirected and recive a succes notification
   */
  public static function registration($post) {
    $firstname = encode($post['firstname']);
    $lastname = encode($post['lastname']);
    $username = encode($post['username']);
    $passworda = encode($post['passworda']);
    $passwordb = encode($post['passwordb']);
    $email = encode($post['email']);

    //pre(pwCrypt($passworda));
    $user = new User();
    $user -> setFirstname($firstname);
    $user -> setLastname($lastname);
    $user -> setUsername($username);
    $user -> setPassword(pwCrypt($passworda));
    $user -> setEmail($email);

    if ($user -> registration(pwCrypt($passwordb)) != false) {
      header('Location:index.php?success=Registration successful');
    }
  }

  /*
   * This function is triggered when Users tries to Login.
   * It checks if the user exists and active.
   * If all the information is correct the User will be logged in and redirected to the Dashboardpage
   * with a success notification.
   */
  public static function login($post) {
    $username = encode($post['username']);
    $password = encode($post['password']);

    if (User::login($username, pwCrypt($password)) != false) {
      header('Location:index.php?page=Dashboard&success=successfully logged in');
    }
  }

}
?>