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
  <div class="charcontainer">
    <?php
    if ($this -> character) {
      ?><input type="hidden" id="myCharId" value="<?= $this -> character -> getId() ?>"><?php
      characterProfile($this -> character);
      characterLife($this -> character -> getHp(), $this -> character -> getHp());
      characterAtk($this -> character -> getAttaks());
    } else {
      echo '
        <div class="userinfo">
          There is no Character yet
        </div>';
    }
    ?>
  </div>
</div>
<div class="right"><?= pre('Playerlist ist loading'); ?></div>
