<div class="left">
  <div class="usercontainer">
    <div class="userimage"></div>
    <div class="userinfo">
      <h3>Userinformation</h3>
      <table>
        <tr>
          <td style="width:100px;">Username:</td>
          <td><?= $this -> user -> getUsername(); ?></td>
        </tr>
        <tr>
          <td>Firstname:</td>
          <td><?= $this -> user -> getFirstname(); ?></td>
        <tr>
          <td>Lastname:</td>
          <td><?= $this -> user -> getLastname(); ?></td>
        <tr>
          <td>Email:</td>
          <td><?= $this -> user -> getEmail(); ?></td>
        </tr>
      </table>
    </div>
  </div>

<?php
if ($this -> character) {
  characterProfile($this -> character, $this -> attack);
} else {
  echo '
  <div class="usercontainer">
    <div class="userinfo">
      There is no Character yet
    </div>
  </div>';
}
?>

</div>
<div class="right"><?= pre('Playerlist ist loading'); ?></div>
