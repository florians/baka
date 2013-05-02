<?php

/*
 * @Author Florian Stettler
 * Create Date:   14.03.2013  create of the file
 * 
 * This is the Main file wich the User can see. All of the content it can be here is generatet
 * 
 */

// this code is for working with the software Xampp
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 1);

// This line starts a Session for the Login
session_start();

// Include all the files which are needed to make the site run
include_once 'classes/include.php';;

// checks if there is an event and if so it triggers the right event in the Event files
if (post('event')) {
  Events::event(post('event'), post());
}

// gets the page and validates if it is allowed
$viewId = getViewId(get('page'));

// loads the view class with the name of the validated Page and makes an instance of it
$viewObject = new $viewId; 
$viewObject -> init();
$viewObject -> processAction();
$viewObject -> hasMessages();
?>

<!doctype html>
<html>
  <head>
    <?php
    // includes the headprt of the Site 
    include_once 'pages/head.php';
    // includes additional Headers if this is needed and set
    echo $viewObject -> additionalHeaders();
    // loads a little Script which does the Online Check of the User but only if the Session is set
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
            // get the navigation with the getConfig which gets his content out of the config.php
            getNavigation(getConfig('navi'));
            ?>
          </div>
        </div>
      </div>
      <div id='contentmargin'>
        <div id='content'>
          <!-- sets the id of the inner part of the Content to the same Name as the Page which is loaded -->
          <div id="<?= $viewObject -> view_id ?>">
            <?php
            // gets the messages if there are any set
            if ($viewObject -> getMessages()) {
              echo $viewObject -> getMessages();
            }
            // gets all the content a site includes
            echo $viewObject -> getContent();
            ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>