<?php

/**
 *
 */
class UserEvents {
  public static function registration($post) {
    $firstname = encode($post['firstname']);
    $lastname = encode($post['lastname']);
    $username = encode($post['username']);
    $passworda = encode($post['passworda']);
    $passwordb = encode($post['passwordb']);
    $email = encode($post['email']);


    pre(pwCrypt($passworda));
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

  public static function login($post) {
    $username = encode($post['username']);
    $password = encode($post['password']);
  
    if(User::login($username, pwCrypt($password)) != false){
      header('Location:index.php?page=Dashboard&success=successfully logged in');
    }
  }

}
?>