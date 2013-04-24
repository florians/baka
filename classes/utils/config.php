<?php
class Config {
  /**
   * Configs if Log after varable name its for the
   * use if the Site is difrent when the User is logged in
   */

  // set Navigation
  public $navi = array('Home', 'Login', 'Registration');
  public $naviLog = array('Dashboard', 'Character', 'Profile', 'Logout');

  // set redirect
  public $redirect = 'Home';
  public $redirectLog = 'Dashboard';

  // allowed pages
  public $allowed = array('Home', 'Login', 'Registration');
  public $allowedLog = array('Dashboard', 'Battle', 'Character', 'Profile', 'Logout');
}
?>