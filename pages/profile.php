<?php  
 /**
 * @Author Florian Stettler, Adrian Locher
 * @Version 9
 * Create Date:   19.03.2013  creation of the file
 * 
 * This is the profile page it lists you userinformation as well as you battle statistics
 */
?>
<div class="left">
  <div class="userinfo">
    <h1>Profile</h1>
    <table>
      <tr>
        <td class="label">Firstname</td>
        <td><?= $this -> user -> getFirstname() ?></td>
      </tr>
      <tr>
        <td class="label">Lastname</td>
        <td><?= $this -> user -> getLastname() ?></td>
      </tr>
      <tr>
        <td class="label">Username</td>
        <td><?= $this -> user -> getUsername() ?></td>
      </tr>
      <tr>
        <td class="label">E-Mail</td>
        <td><?= $this -> user -> getEmail() ?></td>
      </tr>
    </table>
  </div>
</div>
<div class="right">
  <div class="statistic">
    <h1>Statistic</h1>
    <table>
      <tr>
        <td class="label">Fights fought</td>
        <td><?= $this -> user -> getBattleTotal() ?></td>
      </tr>
      <tr>
        <td class="label">Wins</td>
        <td><?= $this -> user -> getWins() ?></td>
      </tr>
      <tr>
        <td class="label">Loses</td>
        <td><?= $this -> user -> getLoses() ?></td>
      </tr>
    </table>
  </div>
</div>