<?php
if (session('login')) {
  getNavigation(array('Dashboard', 'Battle', 'Character', 'Profile', 'Logout'));
} else {
  getNavigation(array('Home', 'Login', 'Registration'));
}
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
?>