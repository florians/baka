<?php

/*
 * @Author Florian Stettler
 * Create Date:   14.03.2013  create of the file
 */

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 1);

session_start();

// Include all the files
include_once 'classes/include.php';

if (post('event')) {
  Events::event(post('event'), post());
}
if (session('id')) {
  $allowed = array('Dashboard', 'Battle', 'Character', 'Profile', 'Logout');
} else {
  $allowed = array('Home', 'Login', 'Registration');
}
$viewId = null;
if (get('page') != '' && in_array(get('page'), $allowed)) {
  $viewId = 'views_' . strtolower(get('page'));
} elseif (session('id')) {
  $viewId = 'views_dashboard';
} elseif (get('page') == '') {
  $viewId = 'views_home';
}
if (!class_exists($viewId)){
  //die('Class ' . $viewId . ' not found!');
  header('Location:index.php');
}

$viewObject = new $viewId;

$viewObject -> init();

$viewObject -> processAction();
$viewObject -> hasMessagse();
?>

<!doctype html>
<html>
  <head>
    <?php
    include_once 'pages/head.php';
    echo $viewObject -> additionalHeaders();
    if (session('id') != '') {
      $header = '
<script>
onlineCheck(1);
setInterval("onlineCheck(1)", 3000);
</script>';
      echo $header;
    }
    ?>
    <LINK REL="SHORTCUT ICON" HREF="img/design/favicon.png" />
  </head>
  <body>
    <div class="asd"></div>
    <div id='wrapper'>
      <div id='bannermargin'>
        <div id='banner'>
          <p>
            <b>B.A.K.A</b>
          </p>
          <p>
            <span>B</span>attle <span>a</span>nd <span>K</span>nokout <span>A</span>rena
          </p>
        </div>
      </div>
      <div id='navimargin'>
        <div id='navi'>
          <div id='navipoints'>
            <?php

            include 'pages/navi.php';
            ?>
          </div>
        </div>
      </div>
      <div id='contentmargin'>
        <div id='content'>
          <div id="<?= $viewObject -> view_id ?>">
            <?php
            if ($viewObject -> getMessages()) {
              echo $viewObject -> getMessages();
            }
            echo $viewObject -> getContent();
            ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
