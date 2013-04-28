<?php

/* getDirContent - reads all the JS files from the folder in the parameter
 * get - with the parameter you get the checked value back
 * post - see get
 * files - see get
 * session - seet get
 * encode - checks the parameter with htmlentities and mysql_real_escape_string
 * pre - plus parameter gives you a nice output for varables or arrays
 */

function getDirContent($dir) {
  $content = null;
  $allfiles = scandir($dir);
  foreach ($allfiles as $file) {
    if (strlen($file) > 3) {
      $filend = explode('.', $file);
      if (end($filend) == 'js') {
        $content .= '<script src="' . $dir . '/' . $file . '" type="text/javascript"></script>' . "\n";
      }
    }
  }
  echo $content;
}

// function to get the GET with the parameter
function get($parameter = null) {
  if (isset($_GET[$parameter])) {
    $get = $_GET[$parameter];
  } else if ($parameter == null) {
    $get = $_GET;
  } else {
    $get = null;
  }
  return $get;
}

// function to get the POST with the parameter
function post($parameter = null) {
  if (isset($_POST[$parameter])) {
    $post = $_POST[$parameter];
  } else if ($parameter == null) {
    $post = $_POST;
  } else {
    $post = null;
  }
  return $post;
}

// function to get the FILE with the parameter
function files($parameter = null) {
  if (isset($_FILES[$parameter])) {
    $file = $_FILES[$parameter];
  } else if ($parameter == null) {
    $file = $_FILES;
  } else {
    $file = null;
  }
  return $file;
}

// function to get the SESSION with the parameter
function session($parameter = null) {

  if (isset($_SESSION[$parameter])) {
    $session = $_SESSION[$parameter];
  } else if ($parameter == null) {
    $session = $_SESSION;
  } else {
    $session = null;
  }
  return $session;
}

// injections encode
function encode($entry) {
  $encoded = htmlspecialchars($entry);
  $encoded = Database::getInstance() -> mysqlRealEscapeStringCheck($encoded);
  return $encoded;
}

function decode($encoded) {
  $decoded = htmlspecialchars_decode($encoded);
  return $decoded;
}

// formated output
function pre($string) {
  print '<pre>';
  print_r($string);
  print '</pre>';
}

// password crypt
function pwCrypt($password) {
  if (CRYPT_SHA512 == 1) {
    $pw = crypt($password, '$6$rounds=4000$zasduqwclbvwjkqowie$');
    $pw = explode('$', $pw);
    return $pw[4];
  }
}

// generates the Whole Navigation
function getNavigation($navigation) {
  $mainlink = 'index.php?page=';
  foreach ($navigation as $navipoint) {
    if (get('page') == "" && $navipoint == $navigation[0] || get('page') == $navipoint) {
      echo "<a class='active' href='" . $mainlink . $navipoint . "'>" . $navipoint . "</a>";
    } else {
      echo "<a href='" . $mainlink . $navipoint . "'>" . $navipoint . "</a>";
    }
  }
}

// gets the ViweId of the actual page if exists
function getViewId($value) {
  if ($value != '' && in_array($value, getConfig('allowed'))) {
    $viewId = 'views_' . strtolower($value);
  } elseif ($value == '') {
    $viewId = 'views_' . strtolower(getConfig('redirect'));
  }
  if (!class_exists($viewId)) {
    header('Location:index.php');
  } else {
    return $viewId;
  }
}

// get configs which you have written in config.php
function getConfig($val = null) {
  $config = new Config();
  if (!$val) {
    $returnval = $config;
  } else {
    if (session('id')) {
      $value = $val . 'Log';
      $returnval = $config -> $value;
    } else {
      $returnval = $config -> $val;
    }
    if (empty($returnval) == 1) {
      $returnval = $config -> $val;
    }
  }
  return $returnval;
}

function bLogReplace($val) {
  $val = str_replace('/evenfront', '<span class="even">', $val);
  $val = str_replace('/evenback', '</span>', $val);
  $val = str_replace('/oddfront', '<span class="odd">', $val);
  $val = str_replace('/oddback', '</span>', $val);
  return $val;
}
?>