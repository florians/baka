<?php

/*
 * @Author Florian Stettler
 * Create Date:   14.03.2013  create of the file
 */

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 1);

session_start();

// Include all the files
include_once 'classes/include.php';;
if (post('event')) {
  Events::event(post('event'), post());
}

$viewId = getViewId(get('page'));

$viewObject = new $viewId;
$viewObject -> init();
$viewObject -> processAction();
$viewObject -> hasMessages();
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
  </head>
  <body>
    <div id='wrapper'>
      <div id='bannermargin'>
        <div id='banner'>
          <p>
            <b>B.A.K.A</b>
          </p>
          <p>
            <span>B</span>attle <span>a</span>nd <span>K</span>nockout <span>A</span>rena
          </p>
        </div>
      </div>
      <div id='navimargin'>
        <div id='navi'>
          <div id='navipoints'>
            <?php
            getNavigation(getConfig('navi'));
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
